<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $guarded = ['id'];

    public function plano_de_conta()
    {
        return $this->belongsTo(PlanoDeConta::class, 'codi_emp', 'codi_emp');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'codi_emp', 'codi_emp');
    }

}
