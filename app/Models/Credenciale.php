<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Credenciale extends Model
{
    //
    protected $table = 'credenciales';
    protected $primaryKey = 'idc';
    public $incrementing = false; // Indica que la clave primaria no es auto-incrementable
    protected $keyType = 'string'; // Especifica que la clave primaria es de tipo string
    public $timestamps = false; // Si no tienes campos de timestamps en tu tabla
    
    
    protected $fillable = [
        'idc',
        'user',
        'name',
        'pass',
        'tok',
        'tim'
    ];

    protected $hidden = [
        'pass', // Oculta la contraseña al convertir el modelo a JSON
        'tok', // Oculta el token al convertir el modelo a JSON
    ];

    protected static function boot()
    {
        // Llama al método boot del padre para asegurarse de que los eventos se registren correctamente
        parent::boot();
        static::creating(function ($credenciale) {
            // Genera un UUID estandar (v4) si el campo 'idc' está vacío
            if(empty($credenciale->idc)) {
                $credenciale->idc = (string) Str::uuid();
            }
        });
    }

    // Relación con el modelo Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user', 'user');
    }
}
