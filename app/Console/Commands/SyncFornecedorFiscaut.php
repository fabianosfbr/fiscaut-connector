<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Fornecedor;
use Illuminate\Console\Command;
use App\Services\FiscautService;

class SyncFornecedorFiscaut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-fornecedor-fiscaut';

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

            $this->info('Sincronizando fornecedores da empresa: ' . $empresa->nome_emp);

            Fornecedor::where('codi_emp', $empresa->codi_emp)
                ->chunk(500, function ($clientes) use ($service, $empresa) {
                    foreach ($clientes as $cliente) {

                        $params = [
                            'cnpj_empresa' => $empresa->cgce_emp,
                            'nome_fornecedor' => $cliente->nome_for,
                            'cnpj_fornecedor' => $cliente->cgce_for,
                            'conta_contabil_fornecedor' => $cliente->codi_cta,
                        ];


                        $response = $service->fornecedor()->create($params);

                        dump($params);

                    }
                });
        }
    }
}
