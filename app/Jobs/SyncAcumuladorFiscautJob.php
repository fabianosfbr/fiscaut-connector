<?php

namespace App\Jobs;

use App\Models\Acumulador;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Fornecedor;
use App\Models\PlanoDeConta;
use App\Services\FiscautService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncAcumuladorFiscautJob implements ShouldQueue
{
    use Queueable;

    public $failOnTimeout = false;

    public $timeout = 120000;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Empresa $empresa
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = app(FiscautService::class);

        Acumulador::where('codi_emp', $this->empresa->codi_emp)
            ->where('fiscaut_sync', false)
            ->chunk(500, function ($acumuladores) use ($service) {
                foreach ($acumuladores as $acumulador) {
                    $params = [
                        'cnpj_empresa' => $this->empresa->cgce_emp,
                        'codi_acu' => $acumulador->codi_acu,
                        'nome_acu' => $acumulador->nome_acu,
                    ];

                    $response = $service->acumulador()->create($params);

                    if (isset($response['status']) && $response['status'] == true) {
                        $acumulador->fiscaut_sync = true;
                        $acumulador->saveQuietly();
                    }

                    $params['cnpj_nome'] = $this->empresa->razao_emp;

                    dump($params);
                }
            });
    }
}
