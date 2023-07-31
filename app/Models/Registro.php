<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'registros';

    protected $fillable = ['Cod_registro','Ticket', 'cod_ugel', 'Oficina','Usuario','Descripcion_problema','Encargado','Estado','Solucion','Ruta_imagen','Fecha_Inicio','Fecha_Final','codigo_oficina'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oficina()
    {
        return $this->hasOne('App\Models\Oficina', 'codigo_oficina', 'Oficina');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'DNI', 'Usuario');
    }

    public function ugel()
    {
        return $this->hasOne('App\Models\Ugel', 'ug', 'cod_ugel');
    }
    
}
