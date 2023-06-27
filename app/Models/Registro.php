<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'registros';

    protected $fillable = ['Fecha','N°_Voucher','N°_Cheque','C_P','DNI','Nombres_y_Apellidos','Detalle','Entrada','Salida','Saldo','codigo_oficina_Oficina','Activado'];
	
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
        return $this->hasOne('App\Models\User', 'DNI', 'DNI');
    }
    
}
