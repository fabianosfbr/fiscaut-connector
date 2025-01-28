<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Services\FiscautService;
use Illuminate\Console\Command;

class SyncClienteFiscaut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-cliente-fiscaut';

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
            $this->info('Sincronizando clientes da empresa: '.$empresa->nome_emp);

            Cliente::where('codi_emp', $empresa->codi_emp)
                ->where('fiscaut_sync', false)
                ->chunk(500, function ($clientes) use ($service, $empresa) {
                    foreach ($clientes as $cliente) {
                        $params = [
                            'cnpj_empresa' => $empresa->cgce_emp,
                            'nome_cliente' => $cliente->nome_cli,
                            'cnpj_cliente' => $cliente->cgce_cli,
                            'conta_contabil_cliente' => $cliente->codi_cta,
                        ];

                        $response = $service->cliente()->create($params);

                        if (isset($response['status']) && $response['status'] == true) {
                            $cliente->fiscaut_sync = true;
                            $cliente->saveQuietly();
                        }

                        dump($params);
                    }
                });
        }
    }
}
