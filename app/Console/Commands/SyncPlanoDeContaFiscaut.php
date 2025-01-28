<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Empresa;
use App\Models\PlanoDeConta;
use App\Services\FiscautService;
use Illuminate\Console\Command;

class SyncPlanoDeContaFiscaut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-plano-de-conta-fiscaut';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(FiscautService::class);

        $empresas = Empresa::where('sync', true)->get();

        foreach ($empresas as $empresa) {
            $this->info('Sincronizando plano de contas da empresa: '.$empresa->nome_emp);

            PlanoDeConta::where('codi_emp', $empresa->codi_emp)
                ->where('fiscaut_sync', false)
                ->chunk(500, function ($planos) use ($service, $empresa) {
                    foreach ($planos as $plano) {
                        $params = [
                            'cnpj_empresa' => $empresa->cgce_emp,
                            'codigo' => $plano->codi_cta,
                            'classificacao' => $plano->clas_cta,
                            'nome' => $plano->nome_cta,
                            'tipo' => $plano->tipo_cta,
                        ];

                        $response = $service->plano_de_conta()->create($params);

                        if (isset($response['status']) && $response['status'] == true) {
                            $plano->fiscaut_sync = true;
                            $plano->saveQuietly();
                        }

                        dump($params);
                    }
                });
        }
    }
}
