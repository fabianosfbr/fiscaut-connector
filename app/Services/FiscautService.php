<?php

namespace App\Services;

use App\Services\Fiscaut\Cliente;
use App\Services\Fiscaut\Fornecedor;
use App\Services\Fiscaut\PlanoDeConta;


class FiscautService
{


    function cliente(): Cliente
    {
        return new Cliente();
    }

    function fornecedor(): Fornecedor
    {
        return new Fornecedor();
    }

    function plano_de_conta(): PlanoDeConta
    {
        return new PlanoDeConta();
    }

}
