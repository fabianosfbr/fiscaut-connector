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

        foreach ($empresas as $empresa) {
            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->get();

            foreach ($rows as $key => $row) {
                $row->nome_cta = removeCaracteresEspeciais($row->nome_cta);

                $this->info('Empresa: '.$row->nome_cta);

                PlanoDeConta::updateOrCreate(
                    [
                        'codi_emp' => $row->codi_emp,
                        'clas_cta' => $row->clas_cta,
                    ],
                    [
                        'codi_cta' => $row->codi_cta,
                        'clas_cta' => $row->clas_cta,
                        'nome_cta' => $row->nome_cta,
                        'tipo_cta' => $row->tipo_cta,
                    ]
                );
            }
        }
    }
}
