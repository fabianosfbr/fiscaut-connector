<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Fornecedor;
use App\Models\PlanoDeConta;
use App\Services\FiscautService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncPlanoDeContaFiscautJob implements ShouldQueue
{
    use Queueable;

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

        PlanoDeConta::where('codi_emp', $this->empresa->codi_emp)
            ->where('fiscaut_sync', false)
            ->chunk(500, function ($planos) use ($service) {
                foreach ($planos as $plano) {
                    $params = [
                        'cnpj_empresa' => $this->empresa->cgce_emp,
                        'codigo' => $plano->codi_cta,
                        'classificacao' => $plano->clas_cta,
                        'nome' => $plano->nome_cta,
                        'tipo' => $plano->tipo_cta,
                        'status' =>  true,
                    ];

                    $response = $service->plano_de_conta()->create($params);

                    if (isset($response['status']) && $response['status'] == true) {
                        $plano->fiscaut_sync = true;
                        $plano->saveQuietly();
                    }

                    $params = ['cnpj_nome' => $this->empresa->razao_emp,];

                    dump($params);
                }
            });
    }
}
