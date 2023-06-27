<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Oficina;

class Oficinas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selected_id, $keyWord, $nombre, $unidad;
    public $selected_oficina = null;

    //los mensajes de error segun el validate
    protected $messages = [
        'codigo.required' => 'El campo codigo es requerido.',
        'codigo.numeric' => 'El campo codigo debe ser un valor numérico.',
        'codigo.digits' => 'El campo codigo debe tener exactamente 11 dígitos.',
        'nombre.required' => 'El campo de Nombres y Apellidos es requerido',
        'unidad.required' => 'El campo de Cuenta Corriente es requerido',
        'unidad.numeric' => 'El campo de Cuenta Corriente debe ser un valor numérico',
    ];
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $oficinas = Oficina::latest()
            ->orWhere('codigo_oficina', 'LIKE', $keyWord)
            ->orWhere('nombre', 'LIKE', $keyWord)
            ->orWhere('unidad', 'LIKE', $keyWord)
            ->paginate(13);
        return view('livewire.oficinas.view', [
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
        $this->codigo_oficina = null;
        $this->nombre = null;
        $this->unidad = null;
    }

    public function store()
    {
        $this->validate([
            'codigo_oficina' => 'required|numeric|digits:11',
            'nombre' => 'required',
            'unidad' => 'required|numeric',
        ]);

        Oficina::create([
            'codigo_oficina' => $this->codigo_oficina,
            'nombre' => $this->nombre,
            'unidad' => $this->unidad
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
        session()->flash('message', 'Oficina Successfully created.');
    }

    public function edit($codigo_oficina)
    {
        $record = Oficina::findOrFail($codigo_oficina);
        $this->codigo_oficina = $codigo_oficina;
        $this->nombre = $record->nombre;
        $this->unidad = $record->unidad;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'codigo_oficina' => 'required|numeric|digits:11',
            'nombre' => 'required',
            'unidad' => 'required|numeric',
        ]);

        if ($this->codigo_oficina) {
            $record = Oficina::find($this->codigo_oficina);
            $record->update([
                'nombre' => $this->nombre,
                'unidad' => $this->unidad
            ]);

            $this->resetInput();
            $this->dispatchBrowserEvent('closeModal');
            session()->flash('message', 'Oficina Successfully updated.');
        }
    }

    public function destroy($codigo_oficina)
    {
        if ($codigo_oficina) {
            Oficina::where('codigo_oficina', $codigo_oficina)->delete();
        }
    }
}
