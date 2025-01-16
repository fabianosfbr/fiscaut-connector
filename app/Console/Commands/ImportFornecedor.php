<?php

namespace App\Console\Commands;


use App\Models\Empresa;
use App\Models\Fornecedor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ImportFornecedor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:fornecedor';
    protected $description = 'Import fornecedores from Dominio ODBC to Fiscaut Connector';
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tableName = 'bethadba.effornece';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();


        foreach ($empresas as $empresa) {

            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->get();


            foreach ($rows as $key => $row) {

                $row->nome_for = removeCaracteresEspeciais($row->nome_for);

                $this->info('Empresa: ' . $row->nome_for . ' CNPJ/CPF: ' . $row->cgce_for);


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
                    ]
                );
            }
        }


    }
}
