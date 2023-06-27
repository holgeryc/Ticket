<?php 

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
	use HasFactory;
    use \Illuminate\Auth\Authenticatable;
	
    public $timestamps = true;

    protected $table = 'users';
    protected $primaryKey= 'DNI';

    protected $fillable = ['DNI','Nombres_y_Apellidos','email','Tipo','codigo_of','password'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function oficina()
    {
        return $this->hasOne('App\Models\Oficina', 'codigo_of');
    }
    
}
