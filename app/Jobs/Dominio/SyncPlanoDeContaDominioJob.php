<?php

namespace App\Jobs\Dominio;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Acumulador;
use App\Models\Fornecedor;
use App\Models\PlanoDeConta;
use App\Services\FiscautService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncPlanoDeContaDominioJob implements ShouldQueue
{
    use Queueable;

    public $failOnTimeout = false;

    public $timeout = 120000;

    public function __construct()
    {
        //
    }

    public function handle(): void
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
