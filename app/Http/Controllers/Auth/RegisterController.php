<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Oficina;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __constcodigo_oficinat()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    
    protected function validator(array $data)
    {
        $messages = [
            'DNI.required' => 'El campo DNI es requerido.',
            'DNI.numeric' => 'El campo DNI debe ser un valor numérico.',
            'DNI.digits' => 'El campo DNI debe tener exactamente 8 dígitos.',
            'Nombres_y_Apellidos.required' => 'El campo de Nombres y Apellidos es requerido',
            'email.required' => 'El campo de Correo es requerido',
            'email.email' => 'El campo debe ser de tipo correo',
            'codigo_oficina_Oficina.required' => 'El campo de codigo_oficina Instito es requerido',
            'password.required' => 'El campo de Contraseña es requerido',
            'password.min'=>'El campo de Contraseña debe ser de minimo 8 caracteres',
            'password.confirmed' => 'El campo de Contraseña no coincide con el campo de Confirmar Contraseña',
            'Tipo.required' => 'El campo de Tipo es requerido',
        ];
        return Validator::make($data, [
                'DNI' => 'required|numeric|digits:8',
                'Nombres_y_Apellidos' => 'required',
                'email' => 'required|email',
                'codigo_oficina_Oficina' => 'required',
                'password' => 'required|min:8|confirmed',
        ],$messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        //los que se registren estaran como desactivados para eviatr filtracion de informacion
        return User::create([ 
			'DNI' => $data['DNI'],
			'Nombres_y_Apellidos' => $data['Nombres_y_Apellidos'],
			'email' => $data['email'],
			'codigo_of' => $data['codigo_of'],
            'password' =>Hash::make($data['password'])
        ]);
    }
    public function showRegistrationForm()
    {
        $oficinas = Oficina::all();
        return view('auth.register', compact('oficinas'));
    }
}
