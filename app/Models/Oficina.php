<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'oficinas';
    
    protected $primaryKey='codigo_oficina';

    protected $fillable = ['codigo_oficina','nombre','ugel'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registros()
    {
        return $this->hasMany('App\Models\Registro', 'codigo_oficina');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\Models\User', 'codigo_oficina');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usuarios()
    {
        return $this->hasMany('App\Models\Usuario', 'codigo_oficina');
    }
    
}
