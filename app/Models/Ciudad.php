<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
   
    protected $table = 'gen_ciudad';
    protected $primaryKey = 'gen_ci_id';
    protected $fillable = [ 
        'gen_ci_nombre'	
    ];
}
