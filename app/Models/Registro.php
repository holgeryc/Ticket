<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'registros';

    protected $fillable = ['Cod_registro','Ticket','Oficina','Usuario','Descripcion_problema','Ruta_imagen','codigo_oficina','DNI'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oficina()
    {
        return $this->hasOne('App\Models\Oficina', 'codigo_oficina', 'codigo_of');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'DNI', 'Usuario');
    }
    
}
