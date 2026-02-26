<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cliente';

    protected $description = 'Import clientes from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = 'bethadba.efclientes';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        $totalNewClientes = 0;

        foreach ($empresas as $empresa) {
            // Get all existing client codes for this empresa
            $existingClientCodes = Cliente::where('codi_emp', $empresa->codi_emp)
                ->pluck('codi_cli')
                ->toArray();

            // Fetch only clients that don't exist for this empresa
            $query = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp);

            // If there are existing clients for this empresa, exclude them from the query
            if (!empty($existingClientCodes)) {
                $query = $query->whereNotIn('codi_cli', $existingClientCodes);
            }

            $rows = $query->orderBy('codi_cli', 'desc')->get();

            foreach ($rows as $key => $row) {
                $row->nome_cli = removeCaracteresEspeciais($row->nome_cli);

                $this->info('Cliente: '.$row->nome_cli.' CNPJ/CPF: '.$row->cgce_cli);

                Cliente::create([
                    'codi_emp' => $row->codi_emp,
                    'codi_cli' => $row->codi_cli,
                    'nome_cli' => $row->nome_cli,
                    'cgce_cli' => $row->cgce_cli,
                    'codi_cta' => $row->codi_cta,
                ]);
            }
            
            $totalNewClientes += $rows->count();
        }
        
        $this->info('Import completed. '.$totalNewClientes.' new clientes were added.');
    }
}
