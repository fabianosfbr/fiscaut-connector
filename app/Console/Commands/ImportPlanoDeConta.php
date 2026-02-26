<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Models\PlanoDeConta;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportPlanoDeConta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:plano-de-conta';

    protected $description = 'Import plano de contas from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = 'bethadba.ctcontas';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        $totalNewPlanosDeConta = 0;

        foreach ($empresas as $empresa) {
            // Get all existing plano de contas for this empresa (using clas_cta as the unique identifier)
            $existingPlanoDeContas = PlanoDeConta::where('codi_emp', $empresa->codi_emp)
                ->pluck('clas_cta')
                ->toArray();

            // Fetch only plano de contas that don't exist for this empresa
            $query = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp);

            // If there are existing plano de contas for this empresa, exclude them from the query
            if (!empty($existingPlanoDeContas)) {
                $query = $query->whereNotIn('clas_cta', $existingPlanoDeContas);
            }

            $rows = $query->get();

            foreach ($rows as $key => $row) {
                $row->nome_cta = removeCaracteresEspeciais($row->nome_cta);

                $this->info('Plano de Conta: '.$row->nome_cta);

                PlanoDeConta::create([
                    'codi_cta' => $row->codi_cta,
                    'clas_cta' => $row->clas_cta,
                    'nome_cta' => $row->nome_cta,
                    'tipo_cta' => $row->tipo_cta,
                    'codi_emp' => $empresa->codi_emp,
                ]);
            }
            
            $totalNewPlanosDeConta += $rows->count();
        }
        
        $this->info('Import completed. '.$totalNewPlanosDeConta.' new plano de contas were added.');
    }
}
