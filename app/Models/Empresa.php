<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $guarded = ['id'];


    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'codi_emp', 'codi_emp');
    }
}
