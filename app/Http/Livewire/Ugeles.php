<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ugel;
use App\Models\Oficina;

class Ugeles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selected_id, $keyWord, $ug, $nombre_ugel, $nombre_oficina;
    public $selected_ugel = null;

    //los mensajes de error segun el validate
    protected $messages = [
        'codigo_ug.required' => 'El campo codigo es requerido.',
        'codigo_ug.numeric' => 'El campo codigo debe ser un valor numérico.',
        'codigo_ug.digits' => 'El campo codigo debe tener exactamente 11 dígitos.',
        'nombre_ugel.required' => 'El campo de nombre_ugels y Apellidos es requerido',
    ];
    public function render()
    {
        $oficinas = Oficina::all();
        $keyWord = '%' . $this->keyWord . '%';
        $ugeles = Ugel::latest()
            ->leftjoin('oficinas', 'ugeles.nombre_oficina', '=', 'oficinas.codigo_oficina')
            ->select('ugeles.*', 'oficinas.nombre')
            ->orWhere('ug', 'LIKE', $keyWord)
            ->orWhere('nombre_ugel', 'LIKE', $keyWord)
            ->orWhere('oficinas.nombre', 'LIKE', $keyWord)
            ->paginate(13);
        return view('livewire.ugeles.view', [
            'ugeles' => $ugeles,
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
        $this->ug = null;
        $this->nombre_ugel = null;
    }

    public function store()
    {
        $this->validate([
            'ug' => 'required',
            'nombre_ugel' => 'required',
        ]);

        ugel::create([
            'ug' => $this->ug,
            'nombre_ugel' => $this->nombre_ugel,
        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
        session()->flash('message', 'ugel Successfully created.');
    }

    public function edit($ug)
    {
        $record = ugel::findOrFail($ug);
        $this->ug = $ug;
        $this->nombre_ugel = $record->nombre_ugel;
        $this->resetValidation();
    }

    public function update()
    {
        $this->validate([
            'ug' => 'required',
            'nombre_ugel' => 'required',
        ]);

        if ($this->ug) {
            $record = ugel::find($this->ug);
            $record->update([
                'nombre_ugel' => $this->nombre_ugel,
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