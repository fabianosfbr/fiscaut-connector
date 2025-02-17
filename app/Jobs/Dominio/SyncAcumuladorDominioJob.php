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

class SyncAcumuladorDominioJob implements ShouldQueue
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
        $tableName = 'bethadba.efacumuladores';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        foreach ($empresas as $empresa) {
            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->get();

            foreach ($rows as $key => $row) {

                $row->NOME_ACU = removeCaracteresEspeciais($row->NOME_ACU);
                $row->DESCRICAO_ACU = removeCaracteresEspeciais($row->DESCRICAO_ACU);

                Acumulador::updateOrCreate(
                    [
                        'codi_acu' => $row->CODI_ACU,
                        'codi_emp' => $empresa->codi_emp,
                    ],
                    [
                        'codi_acu' => $row->CODI_ACU,
                        'nome_acu' => $row->NOME_ACU,
                        'descricao_acu' => $row->DESCRICAO_ACU,
                        'codi_emp' => $empresa->codi_emp,
                    ]
                );
            }
        }
    }
}
