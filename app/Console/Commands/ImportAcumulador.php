<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Acumulador;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAcumulador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:acumulador';

    protected $description = 'Import acumuladores from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = 'bethadba.efacumuladores';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        $totalNewAcumuladores = 0;

        foreach ($empresas as $empresa) {
            // Get all existing acumulador codes for this empresa
            $existingAcumuladorCodes = Acumulador::where('codi_emp', $empresa->codi_emp)
                ->pluck('codi_acu')
                ->toArray();

            // Fetch only acumuladores that don't exist for this empresa
            $query = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp);

            // If there are existing acumuladores for this empresa, exclude them from the query
            if (!empty($existingAcumuladorCodes)) {
                $query = $query->whereNotIn('CODI_ACU', $existingAcumuladorCodes);
            }

            $rows = $query->get();

            foreach ($rows as $key => $row) {
                $row->NOME_ACU = removeCaracteresEspeciais($row->NOME_ACU);
                $row->DESCRICAO_ACU = removeCaracteresEspeciais($row->DESCRICAO_ACU);

                $this->info('Acumulador: '.$row->NOME_ACU);

                Acumulador::create([
                    'codi_acu' => $row->CODI_ACU,
                    'nome_acu' => $row->NOME_ACU,
                    'descricao_acu' => $row->DESCRICAO_ACU,
                    'codi_emp' => $empresa->codi_emp,
                ]);
            }
            
            $totalNewAcumuladores += $rows->count();
        }
        
        $this->info('Import completed. '.$totalNewAcumuladores.' new acumuladores were added.');
    }
}
