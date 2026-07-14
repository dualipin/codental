<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Usuario extends Model
{
    //
    protected $table = 'usuarios';
    protected $primaryKey = 'user';
    public $incrementing = false; // Indica que la clave primaria no es auto-incrementable
    protected $keyType = 'string'; // Especifica que la clave primaria es de tipo string
    public $timestamps = false; // Si no tienes campos de timestamps en tu tabla


    protected $fillable = [
        'user', 'nom', 'app', 'apm', 'sex', 'ced', 'esp', 'nac', 'civ', 'dic', 'est', 'mun', 'tel', 'cor', 'rol', 'img', 'fec'
    ];  

    protected static function boot()
    {
        // Llama al método boot del padre para asegurarse de que los eventos se registren correctamente
        parent::boot();
        static::creating(function ($usuario) {
            //Genera un UUID estandar (v4) si el campo 'user' está vacío
            if(empty($usuario->user)) {
                $usuario->user = (string) Str::uuid();
            }
        });
    }
}
