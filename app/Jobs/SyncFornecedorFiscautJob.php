<?php

namespace App\Jobs;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Fornecedor;
use App\Services\FiscautService;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncFornecedorFiscautJob implements ShouldQueue
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

        Fornecedor::where('codi_emp', $this->empresa->codi_emp)
            ->where('fiscaut_sync', false)
            ->chunk(500, function ($fornecedores) use ($service) {
                foreach ($fornecedores as $fornecedor) {
                    $params = [
                        'cnpj_empresa' => $this->empresa->cgce_emp,
                        'nome_fornecedor' => $fornecedor->nome_for,
                        'cnpj_fornecedor' => $fornecedor->cgce_for,
                        'conta_contabil_fornecedor' => $fornecedor->codi_cta,
                    ];

                    $response = $service->fornecedor()->create($params);

                    if (isset($response['status']) && $response['status'] == true) {
                        $fornecedor->fiscaut_sync = true;
                        $fornecedor->saveQuietly();
                    }
                    $params = ['cnpj_nome' => $this->empresa->razao_emp,];
                    dump($params);
                }
            });
    }
}
