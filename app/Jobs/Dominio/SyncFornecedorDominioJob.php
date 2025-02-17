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

class SyncFornecedorDominioJob implements ShouldQueue
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
        $tableName = 'bethadba.effornece';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        foreach ($empresas as $empresa) {
            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->orderBy('codi_for', 'desc')
                ->get();

            foreach ($rows as $key => $row) {
                $row->nome_for = removeCaracteresEspeciais($row->nome_for);

                Fornecedor::updateOrCreate(
                    [
                        'codi_emp' => $row->codi_emp,
                        'codi_for' => $row->codi_for,
                    ],
                    [
                        'codi_emp' => $row->codi_emp,
                        'codi_for' => $row->codi_for,
                        'nome_for' => $row->nome_for,
                        'cgce_for' => $row->cgce_for,
                        'codi_cta' => $row->codi_cta,
                    ]
                );
            }
        }
    }
}
