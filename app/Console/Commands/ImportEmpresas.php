<?php

namespace App\Console\Commands;

use PDO;
use Exception;
use PDOException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class ImportEmpresas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:empresas';
    protected $description = 'Import empresas from Dominio ODBC to Fiscaut Connector';
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tableName = 'bethadba.geempre';

        $config = Config::get('database.connections.odbc');

        try {
            $pdo = new PDO("odbc:{$config['dsn']}", $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            // Consulta para obter os dados da tabela específica
            $query = $pdo->query("SELECT TOP 5 * FROM $tableName");
            if ($query) {
                echo "Consulta da tabela $tableName executada com sucesso!\n";
                echo "Dados:\n";
                foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    print_r($row);
                    echo "\n";
                }
            } else {
                echo "Erro ao executar consulta na tabela $tableName: " . $pdo->errorInfo()[2] . "\n";
            }

            // Fecha a conexão
            $pdo = null;
        } catch (PDOException $e) {
            echo "Erro ao conectar via ODBC: " . $e->getMessage();
        }
    }
}
