<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Oficina;


class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $DNI, $Nombres_y_Apellidos, $email, $Tipo, $codigo_of, $password;
    public $NuevaContraseña = false;

    protected $messages = [
        'DNI.required' => 'El campo DNI es requerido.',
        'DNI.numeric' => 'El campo DNI debe ser un valor numérico.',
        'DNI.digits' => 'El campo DNI debe tener exactamente 8 dígitos.',
        'Nombres_y_Apellidos.required' => 'El campo de Nombres y Apellidos es requerido',
        'email.required' => 'El campo de correo es requerido',
        'email.email' => 'El campo debe ser de tipo correo',
        'Activado.required' => 'Es requerido que este Activado',
        'password.required' => 'El campo de contraseña es requerido',
        'password.min' => 'El campo de contraseña debe ser de minimo 8 caracteres',
        'Tipo.required' => 'El campo de Tipo es requerido',
    ];

    public function render()
    {
        $oficinas = Oficina::all();
        $keyWord = '%' . $this->keyWord . '%';
        $usuarios = User::latest()
            ->leftjoin('oficinas', 'users.codigo_of', '=', 'oficinas.codigo_oficina')
            ->select('users.*', 'oficinas.Nombre')
            ->orWhere('DNI', 'LIKE', $keyWord)
            ->orWhere('Nombres_y_Apellidos', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord)
            ->orWhere('Tipo', 'LIKE', $keyWord)
            ->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
            // ->orWhere('Activado', 'LIKE', $keyWord)
            ->paginate(13);
        return view('livewire.users.view', [
            'users' => $usuarios,
            'oficinas' => $oficinas,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->resetValidation();
    }

    private function resetInput()
    {
        $this->DNI = null;
        $this->Nombres_y_Apellidos = null;
        $this->email = null;
        $this->Tipo = null;
        $this->codigo_of = null;
        // $this->Activado = null;
        $this->password = null;
    }

    public function store()
    {
        $this->validate([
            'DNI' => 'required|numeric|digits:8',
            'Nombres_y_Apellidos' => 'required',
            'email' => 'required|email',
            'codigo_of' => 'required_if:Tipo,Personal_Geredu',
            'password' => 'required|min:8'
        ]);
        if ($this->Tipo === null) {
            $this->Tipo = 'Personal_Geredu';
        }
        User::create([
            'DNI' => $this->DNI,
            'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
            'email' => $this->email,
            'Tipo' => $this->Tipo,
            'codigo_of' => $this->codigo_of,
            'password' => Hash::make($this->password)
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
        session()->flash('message', 'User Successfully created.');
    }

    public function edit($DNI)
    {
        $record = User::findOrFail($DNI);
        $this->DNI = $DNI;
        $this->Nombres_y_Apellidos = $record->Nombres_y_Apellidos;
        $this->email = $record->email;
        $this->Tipo = $record->Tipo;
        $this->codigo_of = $record->codigo_of;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'DNI' => 'required|numeric|digits:8',
            'email' => 'required',
            'codigo_of' => 'required_if:Tipo,Personal_Geredu',
            'Tipo' => 'required',
            'password' => 'required_if:NuevaContraseña,true',
        ]);
        if ($this->codigo_of === '') {
            $this->codigo_of = null;
        };

        if ($this->DNI && $this->password) {
            $record = User::find($this->DNI);
            $record->update([
                'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
                'email' => $this->email,
                'Tipo' => $this->Tipo,
                'codigo_of' => $this->codigo_ofi,
                'password' => Hash::make($this->password)
            ]);

            $this->resetInput();
            $this->dispatchBrowserEvent('closeModal');
            session()->flash('message', 'User Successfully updated.');
        } else {
            if ($this->DNI) {
                $record = User::find($this->DNI);
                $record->update([
                    'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
                    'email' => $this->email,
                    'Tipo' => $this->Tipo,
                    'codigo_of' => $this->codigo_of,
                ]);

                $this->resetInput();
                $this->dispatchBrowserEvent('closeModal');
                session()->flash('message', 'User Successfully updated.');
            }
        }
    }

    public function destroy($DNI)
    {
        if ($DNI) {
            User::where('DNI', $DNI)->delete();
        }
    }
}
