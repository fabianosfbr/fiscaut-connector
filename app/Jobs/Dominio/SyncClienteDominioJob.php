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

class SyncClienteDominioJob implements ShouldQueue
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
        $tableName = 'bethadba.efclientes';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        foreach ($empresas as $empresa) {
            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->orderBy('codi_cli', 'desc')
                ->get();

            foreach ($rows as $key => $row) {
                $row->nome_cli = removeCaracteresEspeciais($row->nome_cli);


                Cliente::updateOrCreate(
                    [
                        'codi_emp' => $row->codi_emp,
                        'codi_cli' => $row->codi_cli,
                    ],
                    [
                        'codi_emp' => $row->codi_emp,
                        'codi_cli' => $row->codi_cli,
                        'nome_cli' => $row->nome_cli,
                        'cgce_cli' => $row->cgce_cli,
                        'codi_cta' => $row->codi_cta,
                    ]
                );
            }
        }
    }
}
