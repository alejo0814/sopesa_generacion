<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Generador extends Model
{
      use HasFactory;
  protected $table = 'gen_maquina';





    protected $fillable = [
        'gen_ma_nombre',
        'gen_planta_gen_pl_id',
    ];

    public function planta() {
        return $this->belongsTo(Planta::class, 'gen_planta_gen_pl_id');
    }
}
