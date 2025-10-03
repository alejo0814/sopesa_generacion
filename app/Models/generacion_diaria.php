<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class generacion_diaria extends Model
{
    use HasFactory;
    protected $table = 'generacion_diaria';
    // protected $primaryKey = 'gen_repd_id ';
    //public $timestamps = false;
    protected $fillable = [
        'fecha',
        'horas_trabajada',
        'horas_disponible',
        'lectura_kw',
        'lectura_combustible',
        'lectura_aceite',
    ];
}
