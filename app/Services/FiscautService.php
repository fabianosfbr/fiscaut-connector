<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Fiscaut\Cliente;
use App\Services\Fiscaut\Fornecedor;
use App\Services\Fiscaut\PlanoDeConta;
use App\Services\Fiscaut\Acumulador;

class FiscautService
{
    public function cliente(): Cliente
    {
        return new Cliente();
    }

    public function fornecedor(): Fornecedor
    {
        return new Fornecedor();
    }

    public function plano_de_conta(): PlanoDeConta
    {
        return new PlanoDeConta();
    }

    public function acumulador(): Acumulador
    {
        return new Acumulador();
    }
}
