<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Models\Fornecedor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportFornecedor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:fornecedor';

    protected $description = 'Import fornecedores from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = 'bethadba.effornece';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        $totalNewFornecedores = 0;

        foreach ($empresas as $empresa) {
            // Get all existing fornecedor codes for this empresa
            $existingFornecedorCodes = Fornecedor::where('codi_emp', $empresa->codi_emp)
                ->pluck('codi_for')
                ->toArray();

            // Fetch only fornecedores that don't exist for this empresa
            $query = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp);

            // If there are existing fornecedores for this empresa, exclude them from the query
            if (!empty($existingFornecedorCodes)) {
                $query = $query->whereNotIn('codi_for', $existingFornecedorCodes);
            }

            $rows = $query->orderBy('codi_for', 'desc')->get();

            foreach ($rows as $key => $row) {
                $row->nome_for = removeCaracteresEspeciais($row->nome_for);

                $this->info('Fornecedor: '.$row->nome_for.' CNPJ/CPF: '.$row->cgce_for);

                Fornecedor::create([
                    'codi_emp' => $row->codi_emp,
                    'codi_for' => $row->codi_for,
                    'nome_for' => $row->nome_for,
                    'cgce_for' => $row->cgce_for,
                    'codi_cta' => $row->codi_cta,
                ]);
            }
            
            $totalNewFornecedores += $rows->count();
        }
        
        $this->info('Import completed. '.$totalNewFornecedores.' new fornecedores were added.');
    }
}
