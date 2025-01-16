<?php

namespace App\Console\Commands;

use App\Models\PlanoDeConta;
use App\Models\Empresa;
use Illuminate\Console\Command;
use App\Services\FiscautService;

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

            $this->info('Sincronizando clientes da empresa: ' . $empresa->nome_emp);

            PlanoDeConta::where('codi_emp', $empresa->codi_emp)
                ->chunk(500, function ($planos) use ($service, $empresa) {
                    foreach ($planos as $plano) {

                        $params = [
                            'cnpj_empresa' => $empresa->cgce_emp,
                            'codigo' => $plano->codi_cta,
                            'classificacao' => $plano->clas_cta,
                            'nome' => $plano->nome_cta,
                            'tipo' => $plano->tipo_cta,
                        ];


                        $response = $service->cliente()->create($params);

                        dump($params);

                    }
                });
        }
    }
}
