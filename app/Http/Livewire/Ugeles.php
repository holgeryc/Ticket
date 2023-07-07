<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ugel;

class Ugeles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selected_id, $keyWord, $ug, $nombre;
    public $selected_ugel = null;

    //los mensajes de error segun el validate
    protected $messages = [
        'codigo_ug.required' => 'El campo codigo es requerido.',
        'codigo_ug.numeric' => 'El campo codigo debe ser un valor numérico.',
        'codigo_ug.digits' => 'El campo codigo debe tener exactamente 11 dígitos.',
        'nombre.required' => 'El campo de Nombres y Apellidos es requerido',
    ];
    public function render()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $ugeles = ugel::latest()
            ->orWhere('ug', 'LIKE', $keyWord)
            ->orWhere('nombre', 'LIKE', $keyWord)
            ->paginate(13);
        return view('livewire.ugeles.view', [
            'ugeles' => $ugeles,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->resetValidation();
    }

    private function resetInput()
    {
        $this->ug = null;
        $this->nombre = null;
    }

    public function store()
    {
        $this->validate([
            'ug' => 'required|numeric|digits:11',
            'nombre' => 'required',
        ]);

        ugel::create([
            'ug' => $this->ug,
            'nombre' => $this->nombre,
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
        session()->flash('message', 'ugel Successfully created.');
    }

    public function edit($ug)
    {
        $record = ugel::findOrFail($ug);
        $this->ug = $ug;
        $this->nombre = $record->nombre;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'ug' => 'required|numeric|digits:11',
            'nombre' => 'required',
        ]);

        if ($this->ug) {
            $record = ugel::find($this->ug);
            $record->update([
                'nombre' => $this->nombre,
            ]);

            $this->resetInput();
            $this->dispatchBrowserEvent('closeModal');
            session()->flash('message', 'ugel Successfully updated.');
        }
    }

    public function destroy($ug)
    {
        if ($ug) {
            ugel::where('ug', $ug)->delete();
        }
    }
}