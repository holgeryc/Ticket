<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Oficina;
use App\Models\Ugel;

class Oficinas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selected_id, $keyWord, $codigo_oficina, $nombre;
    public $selected_oficina = null;

    //los mensajes de error segun el validate
    protected $messages = [
        'codigo_oficina.required' => 'El campo codigo es requerido.',
        'codigo_oficina.numeric' => 'El campo codigo debe ser un valor numérico.',
        'codigo_oficina.digits' => 'El campo codigo debe tener exactamente 11 dígitos.',
        'nombre.required' => 'El campo de Nombres y Apellidos es requerido',
        'ugel.required' => 'El campo de Cuenta Corriente es requerido',
        'ugel.numeric' => 'El campo de Cuenta Corriente debe ser un valor numérico',
    ];
    public function render()
    {
        $ugeles = Ugel::all();
        $keyWord = '%' . $this->keyWord . '%';
        $oficinas = Oficina::latest()
            // ->leftjoin('ugeles', 'oficinas.ugel', '=', 'ugeles.ug')
            // ->select('oficinas.*', 'ugeles.nombre_ugel')
            ->orWhere('codigo_oficina', 'LIKE', $keyWord)
            ->orWhere('nombre', 'LIKE', $keyWord)
            // ->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
            ->paginate(13);
        return view('livewire.oficinas.view', [
            'oficinas' => $oficinas,
            // 'ugeles' => $ugeles,
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
        $this->ugel = null;
    }

    public function store()
    {
        $this->validate([
            'codigo_oficina' => 'required',
            'nombre' => 'required',
            // 'ugel' => 'required',
        ]);

        Oficina::create([
            'codigo_oficina' => $this->codigo_oficina,
            'nombre' => $this->nombre,
            // 'ugel' => $this->ugel
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
        // $this->ugel = $record->ugel;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'codigo_oficina' => 'required|numeric|digits:11',
            'nombre' => 'required',
        ]);

        if ($this->codigo_oficina) {
            $record = Oficina::find($this->codigo_oficina);
            $record->update([
                'nombre' => $this->nombre,
                // 'ugel' => $this->ugel
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
