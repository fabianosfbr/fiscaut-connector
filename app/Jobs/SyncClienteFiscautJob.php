<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Services\FiscautService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncClienteFiscautJob implements ShouldQueue
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

        Cliente::where('codi_emp', $this->empresa->codi_emp)
            ->where('fiscaut_sync', false)
            ->chunk(500, function ($clientes) use ($service) {
                foreach ($clientes as $cliente) {
                    $params = [
                        'cnpj_empresa' => $this->empresa->cgce_emp,
                        'nome_cliente' => $cliente->nome_cli,
                        'cnpj_cliente' => $cliente->cgce_cli,
                        'conta_contabil_cliente' => $cliente->codi_cta,
                    ];

                    $response = $service->cliente()->create($params);

                    if (isset($response['status']) && $response['status'] == true) {
                        $cliente->fiscaut_sync = true;
                        $cliente->saveQuietly();
                    }

                    $params = ['cnpj_nome' => $this->empresa->razao_emp,];
                    dump($params);
                }
            });
    }
}
