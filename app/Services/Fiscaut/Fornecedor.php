<?php

namespace App\Services\Fiscaut;

class Fornecedor
{
    use FiscautConfig;


    public function create(array $data)
    {
        return $this->post('contabil/fornecedores', $data);
    }

}
