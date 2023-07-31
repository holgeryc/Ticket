<?php

namespace App\Http\Livewire;

use Dompdf\Dompdf;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Registro;
use App\Models\User;
use App\Models\Oficina;
use App\Models\Ugel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\ExclusiveOr;



class Tickets extends Component
{
	use WithPagination;

	protected $paginationTheme = 'bootstrap';
	public $selected_id, $keyWord, $Cod_registro, $Ticket, $Fecha_Inicio, $Fecha_Final, $cod_ugel, $Oficina, $Usuario, $Solucion, $Descripcion_problema, $Ruta_imagen, $Estado, $Encargado, $ugel_, $mesSeleccionado, $anioSeleccionado, $logueado, $ugelSeleccionado;

	protected $messages = [
		'Cod_registro.required' => 'El campo Cod_registro es requerido.',
		'Cod_registro.date' => 'El campo Cod_registro es de tipo Cod_registro.',
		// 'Nombres_y_Apellidos.required' => 'El campo Nombres y Apellidos es requerido.',
		// 'Nombres_y_Apellidos.strtoupper' => 'El campo Nombres y Apellidos es con Mayusculas',
		'Descripcion_problema.required' => 'El campo Descripcion_problema es requerido.',
		'Descripcion_problema.strtoupper' => 'El campo Deatlle es con Mayusculas.',
		'Ticket.required_with' => 'El campo Ticket es requerido si el campo ruta_imagen esta siendo usado.',
		'usuario.required_with' => 'El campo Usuario es requerido si el campo Asignado esta siendo usado.',
		'ruta_imagen.required_with' => 'El campo ruta_imagen es requerido si el campo Ticket esta siendo usado.',
		'ruta_imagen.numeric' => 'El campo ruta_imagen es de tipo numerico.',
		'ruta_imagen.min' => 'El campo ruta_imagen tiene que ser un numero positivo.',
		'codigo_oficina' => 'El campo de Oficina es requerido',
	];

	public function render()
	{
		$usuarios = User::all();
		$oficinas = Oficina::all();
		$ugeles = Ugel::all();
		$mesSeleccionado = $this->mesSeleccionado;
		$anioSeleccionado = $this->anioSeleccionado;
		$ugelSeleccionado = $this->ugelSeleccionado;

		$logueado = Auth::user()->codigo_ug;
		$keyWord = '%' . $this->keyWord . '%';
		if (Auth::user()->Tipo === "Personal_Geredu") {
			$registros = Registro::leftJoin('ugeles', 'registros.cod_ugel', '=', 'ugeles.ug')
				->leftJoin('users', 'users.DNI', '=', 'registros.Usuario')
				->selectRaw('registros.*, ugeles.nombre_ugel, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->selectRaw('registros.*, users.Nombres_y_Apellidos, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($logueado, $mesSeleccionado, $anioSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.cod_ugel LIKE ?', [$logueado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Cod_registro) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Cod_registro) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->Where('Ticket', 'LIKE', $keyWord)
						->orWhere('cod_ugel', 'LIKE', $keyWord)
						->orWhere('Oficina', 'LIKE', $keyWord)
						->orWhere('Usuario', 'LIKE', $keyWord)
						->orWhere('users.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('Encargado', 'LIKE', $keyWord)
						->orWhere('Estado', 'LIKE', $keyWord)
						->orWhere('Solucion', 'LIKE', $keyWord)
						->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
						->orWhere('Fecha_Inicio', 'LIKE', $keyWord)
						->orWhere('Fecha_Final', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc')
				->paginate(13);
		}
		if (Auth::user()->Tipo === "Centro_computo") {
			$registros = Registro::leftJoin('ugeles', 'registros.cod_ugel', '=', 'ugeles.ug')
				->leftJoin('users', 'users.DNI', '=', 'registros.Usuario')
				->selectRaw('registros.*, ugeles.nombre_ugel, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->selectRaw('registros.*, users.Nombres_y_Apellidos, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.cod_ugel LIKE ?', [$logueado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Cod_registro) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Cod_registro) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('Ticket', 'LIKE', $keyWord)
						->orWhere('cod_ugel', 'LIKE', $keyWord)
						->orWhere('Oficina', 'LIKE', $keyWord)
						->orWhere('Usuario', 'LIKE', $keyWord)
						->orWhere('users.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('Encargado', 'LIKE', $keyWord)
						->orWhere('Estado', 'LIKE', $keyWord)
						->orWhere('Solucion', 'LIKE', $keyWord)
						->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
						->orWhere('Fecha_Inicio', 'LIKE', $keyWord)
						->orWhere('Fecha_Final', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc')
				->paginate(13);
		}
		if (Auth::user()->Tipo === "Administrador") {
			$registros = Registro::leftJoin('ugeles', 'registros.cod_ugel', '=', 'ugeles.ug')
				->leftJoin('users', 'users.DNI', '=', 'registros.Usuario')
				->selectRaw('registros.*, ugeles.nombre_ugel, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->selectRaw('registros.*, users.Nombres_y_Apellidos, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $ugelSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $ugelSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $ugelSeleccionado) {
						if ($ugelSeleccionado) {
							$query->whereRaw('registros.Oficina LIKE ?', [$ugelSeleccionado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Cod_registro) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Cod_registro) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('Ticket', 'LIKE', $keyWord)
						->orWhere('cod_ugel', 'LIKE', $keyWord)
						->orWhere('Oficina', 'LIKE', $keyWord)
						->orWhere('Usuario', 'LIKE', $keyWord)
						->orWhere('users.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('Encargado', 'LIKE', $keyWord)
						->orWhere('Estado', 'LIKE', $keyWord)
						->orWhere('Solucion', 'LIKE', $keyWord)
						->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
						->orWhere('Fecha_Inicio', 'LIKE', $keyWord)
						->orWhere('Fecha_Final', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord);
				})
				->orderBy('Cod_registro', 'asc')
				->paginate(13);
		}
		
		return view('livewire.tickets.view', [
			'registros' => $registros,
			'usuarios' => $usuarios,
			'oficinas' => $oficinas,
		]);
	}
	public function generarPDF($ugelSeleccionado = null, $keyWord = null)
	{
		if (Auth::user()->Tipo === "Personal_Geredu") {
			$ugelSeleccionado = Auth::user()->codigo_oficina;
		}
		if (Auth::user()->Tipo === "Personal_Geredu") {
			if (empty($mesSeleccionado) || empty($anioSeleccionado)) {
				// Mostrar aviso de campos faltantes o redireccionar a una página de error
				return redirect()->back()->with('error', 'Debe elegir un mes y un año');
			}
		} else{
			$ugelSeleccionado = Auth::user()->codigo_oficina;
		}
		$usuarios = User::all();
		$oficinas = Oficina::all();
		$logueado = Auth::user()->codigo_oficina;
		$logueado_tipo = Auth::user()->Tipo;
		$keyWord = '%' . $keyWord . '%';

		if (Auth::user()->Tipo === "Personal_Geredu") {
			$registros = Registro::leftJoin('oficinas', 'registros.Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, ugeles.nombre_ugel, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($logueado, $mesSeleccionado, $anioSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.Oficina LIKE ?', [$logueado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Cod_registro) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Cod_registro) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->Where('Ticket', 'LIKE', $keyWord)
						->orWhere('cod_ugel', 'LIKE', $keyWord)
						->orWhere('Oficina', 'LIKE', $keyWord)
						->orWhere('Usuario', 'LIKE', $keyWord)
						->orWhere('users.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('Encargado', 'LIKE', $keyWord)
						->orWhere('Estado', 'LIKE', $keyWord)
						->orWhere('Solucion', 'LIKE', $keyWord)
						->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
						->orWhere('Fecha_Inicio', 'LIKE', $keyWord)
						->orWhere('Fecha_Final', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc')
				->paginate(31);
		}
		if (Auth::user()->Tipo === "Centro_computo") {
			$registros = Registro::leftJoin('oficinas', 'registros.Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, ugeles.nombre_ugel, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $ugelSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $ugelSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $ugelSeleccionado) {
						if ($ugelSeleccionado) {
							$query->whereRaw('registros.Oficina LIKE ?', [$ugelSeleccionado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Cod_registro) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Cod_registro) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('Ticket', 'LIKE', $keyWord)
						->orWhere('cod_ugel', 'LIKE', $keyWord)
						->orWhere('Oficina', 'LIKE', $keyWord)
						->orWhere('Usuario', 'LIKE', $keyWord)
						->orWhere('users.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('Encargado', 'LIKE', $keyWord)
						->orWhere('Estado', 'LIKE', $keyWord)
						->orWhere('Solucion', 'LIKE', $keyWord)
						->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
						->orWhere('Fecha_Inicio', 'LIKE', $keyWord)
						->orWhere('Fecha_Final', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc');
		}
		if (Auth::user()->Tipo === "Administrador") {
			$registros = Registro::leftJoin('oficinas', 'registros.Oficina', '=', 'oficinas.codigo_oficina')
				->selectRaw('registros.*, ugeles.nombre_ugel, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($ugelSeleccionado, function ($query)  {
					return $query->where(function ($query)  {
						if ($ugelSeleccionado) {
							$query->whereRaw('registros.Oficina LIKE ?', [$ugelSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('Ticket', 'LIKE', $keyWord)
						->orWhere('cod_ugel', 'LIKE', $keyWord)
						->orWhere('Oficina', 'LIKE', $keyWord)
						->orWhere('Usuario', 'LIKE', $keyWord)
						->orWhere('users.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('Encargado', 'LIKE', $keyWord)
						->orWhere('Estado', 'LIKE', $keyWord)
						->orWhere('Solucion', 'LIKE', $keyWord)
						->orWhere('ugeles.nombre_ugel', 'LIKE', $keyWord)
						->orWhere('Fecha_Inicio', 'LIKE', $keyWord)
						->orWhere('Fecha_Final', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord);
				})
				->orderBy('Cod_registro', 'asc')
				->paginate(31);
		}
		
		//Messeleeccioado mapeado para su nombr correspondiente
		$meses = [
			'01' => 'ENERO',
			'02' => 'FEBRERO',
			'03' => 'MARZO',
			'04' => 'ABRIL',
			'05' => 'MAYO',
			'06' => 'JUNIO',
			'07' => 'JULIO',
			'08' => 'AGOSTO',
			'09' => 'SEPTIEMBRE',
			'10' => 'OCTUBRE',
			'11' => 'NOVIEMBRE',
			'12' => 'DICIEMBRE',
		];
		// if (isset($meses[$mesSeleccionado])) {
		// 	$mesSeleccionado = $meses[$mesSeleccionado];
		// } else {
		// 	$mesSeleccionado = 'Mes inválido';
		// }
		//Nombre del oficinasleeccionado
		$oficina = Oficina::where('codigo_oficina', $ugelSeleccionado)->first();
		if ($oficina) {
			$ugelSeleccionado = $oficina->Nombre;
		}
		//cuenta bancaria del oficina sleccioando
		// if ($oficina) {
		// 	$oficinaCuentaCorriente = $oficina->ugel;
		// }
		//saldo anterior
		$primerRegistro = $registros->first();

		// if ($primerRegistro) {
		// 	$registroAnterior = Registro::where('Cod_registro', '<=', $primerRegistro->Cod_registro)
		// 		->where('id', '<', $primerRegistro->id)
		// 		->orderBy('Cod_registro', 'desc')
		// 		->first();
		// 	if ($registroAnterior) {
		// 		// El registro anterior está disponible
		// 		$saldoAnterior = $registroAnterior->Saldo;
		// 	} else {
		// 		// No se encontró ningún registro anterior al primer registro
		// 		// Realiza la lógica adecuada en este caso
		// 		$saldoAnterior = 0;
		// 	}
		// } else {
		// 	// No se encontró el primer registro para el mes y año especificados
		// 	// Realiza la lógica adecuada en este caso
		// 	$saldoAnterior = 0;
		// }
		// $saldoAnterior = 'S/. ' . number_format($saldoAnterior, 2);
		// Establecer la zona horaria
		date_default_timezone_set('America/Lima'); // Reemplaza 'America/Lima' con la zona horaria correspondiente a tu ubicación
		// Obtener la Cod_registro y hora actual
		$currentDateTime = date('d/m/Y h:i:s A');
		//sacamos la url para mostrarla en el reporte
		$currentURL = \URL::current();

		// Crear una instancia de Dompdf
		$dompdf = new Dompdf();

		// Generar el contenido HTML del PDF
		$html = view('livewire.tickets.pdf', compact('tickets', 'ugelSeleccionado',   'currentDateTime', 'currentURL'))->render();
		// Agregar título personalizado al HTML
		$title = $ugelSeleccionado ;
		$html = '<html><head><title>' . $title . '</title></head><body>' . $html . '</body></html>';

		// Cargar el contenido HTML en Dompdf
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->set_option('isRemoteEnabled', true);
		// Renderizar el PDF
		$dompdf->render();

		// Establecer el nombre del archivo PDF
		$filename = $ugelSeleccionado.'.pdf';

		// Descargar el PDF generado
		$dompdf->stream($filename, ['Attachment' => false]);
	}

	public function cancel()
	{
		$this->resetInput();
		$this->resetValidation();
	}

	private function resetInput()
	{
		$this->Cod_registro = null;
		$this->Ticket = null;
		$this->cod_ugel = null;
		$this->Of = null;
		$this->Usuario = null;
		$this->Descripcion_problema = null;
		$this->Ruta_imagen = null;
		$this->Fecha_Inicio = null;
	}

	public function store()
	{
		if (Auth::user()->Tipo === 'Personal_Geredu') {
			$this->validate([
				'Descripcion_problema' => 'required',
    			'Usuario' => 'required',
				'Fecha_Inicio' => 'required',
			]);
		}
		if (Auth::user()->Tipo === 'Administrador') {
			$this->validate([
				'Descripcion_problema' => 'required',
    			'Usuario' => 'required',
				'Fecha_Inicio' => 'required',
			]);
		}
		
		if ($this->Oficina === '') {
			$this->Oficina = null;
		};
		if ($this->cod_ugel === '') {
			$this->cod_ugel = null;
		};
		if ($this->Ruta_imagen === '' || $this->Ruta_imagen === 0) {
			$this->Ruta_imagen = null;
		};
		if (Auth::user()->Tipo === "Personal_Geredu") {
			$this->Oficina = Auth::user()->codigo_of;
		}
		if (Auth::user()->Tipo == "Personal_Geredu") {
			$totalRegistros = Registro::count();
    		$Cod_registro = $totalRegistros + 1;
			$Ticket = $totalRegistros + 1;

			Registro::create([
				'Cod_registro' => $Cod_registro,
				'Ticket' => $Ticket,
				'cod_ugel' => Auth::user()->codigo_ug,
				'Oficina' => 1,
				'Usuario' => Auth::user()->DNI,
				'Descripcion_problema' => $this->Descripcion_problema,
				'Ruta_imagen' => $this->Ruta_imagen,
				'Fecha_Inicio' => $this->Fecha_Inicio,
				'Activado' => true,
			]);
		} else {
			$totalRegistros = Registro::count();
    		$Cod_registro = $totalRegistros + 1;
			$Ticket = $totalRegistros + 1;

			Registro::create([
				'Cod_registro' => $Cod_registro,
				'Ticket' => $Ticket,
				'cod_ugel' => Auth::user()->codigo_ug,
				'Oficina' => 1,
				'Usuario' => Auth::user()->DNI,
				'Descripcion_problema' => $this->Descripcion_problema,
				'Ruta_imagen' => $this->Ruta_imagen,
				'Fecha_Inicio' => $this->Fecha_Inicio,
				'Activado' => true,
			]);
		
		$this->resetInput();
		$this->dispatchBrowserEvent('closeModal');
		session()->flash('message', 'Registro Successfully created.');
	}
}

	public function edit($id)
	{
		$record = Registro::findOrFail($id);
		$this->selected_id = $id;
		$this->Cod_registro = $record->Cod_registro;
		$this->Ticket = $record->Ticket;
		$this->Usuario = $record->Usuario;
		$this->Descripcion_problema = $record->Descripcion_problema;
		$this->Ruta_imagen = $record->Ruta_imagen;
		if (Auth::user()->Tipo === 'Administrador') {
			$this->Activado = $record->Activado;
		}
		$this->Oficina = $record->Oficina;
		$this->resetValidation();
	}

	public function update()
	{
		if (Auth::user()->Tipo === 'Personal_Geredu') {
			$this->validate([
				'Cod_registro' => 'required',
				'Descripcion_problema' => 'required',
				'Ticket' => 'required',
    			'Usuario' => 'required',
				'Ruta_imagen' => 'required',
			]);
		}
		if (Auth::user()->Tipo === 'Administrador') {
			$this->validate([
				'Cod_registro' => 'required',
				'Descripcion_problema' => 'required',
				'Ticket' => 'required',
    			'Usuario' => 'required',
				'Ruta_imagen' => 'required',
			]);
		// }
		// if ($this->ruta_imagen === '' || $this->ruta_imagen === 0) {
		// 	$this->ruta_imagen = null;
		// };
		// if ($this->Asignado === ''  || $this->Asignado === 0) {
		// 	$this->Asignado = null;
		// };
		// // $registroAnterior = Registro::where('codigo_oficina', $this->codigo_oficina)
		// // 	->where(function ($query) {
		// // 		$query->where('Cod_registro', '<', $this->Cod_registro)
		// // 			->orWhere(function ($query) {
		// // 				$query->where('Cod_registro', $this->Cod_registro)
		// // 					->where('id', '<', $this->selected_id);
		// // 			});
		// // 	})
		// // 	->orderByDesc('Cod_registro')
		// // 	->orderByDesc('id')
		// // 	->first();

		// // if (!$registroAnterior) {
		// // 	$saldoAnterior = 0;
		// // } else {
		// // 	$saldoAnterior = $registroAnterior->Saldo;
		// // }
		// // $Saldo = $saldoAnterior + doubleval($this->ruta_imagen) - doubleval($this->Asignado);


		// // if ($this->selected_id) {
		// // 	$record = Registro::find($this->selected_id);
		// // 	if (Auth::user()->Tipo === 'Administrador') {
		// // 		$record->update([
		// // 			'Cod_registro' => $this->Cod_registro,
		// // 			'Ticket' => $this->Ticket,
		// // 			'usuario' => $this->usuario,
		// // 			'C_P' => $this->C_P,
		// // 			'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
		// // 			'Descripcion_problema' => $this->Descripcion_problema,
		// // 			'ruta_imagen' => $this->ruta_imagen,
		// // 			'Asignado' => $this->Asignado,
		// // 			'Saldo' => $Saldo,
		// // 			'codigo_oficina' => $this->codigo_oficina,
		// // 			'Activado' => $this->Activado
		// // 		]);
		// // 	} else {
		// // 		$record->update([
		// // 			'Cod_registro' => $this->Cod_registro,
		// // 			'Ticket' => $this->Ticket,
		// // 			'usuario' => $this->usuario,
		// // 			'C_P' => $this->C_P,
		// // 			'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
		// // 			'Descripcion_problema' => $this->Descripcion_problema,
		// // 			'ruta_imagen' => $this->ruta_imagen,
		// // 			'Asignado' => $this->Asignado,
		// // 			'Saldo' => $Saldo,
		// // 			'codigo_oficina' => $this->codigo_oficina,
		// // 		]);
		// // 	};
		// 	// $this->updateSaldosPosteriores($record, $Saldo);

		// 	$this->resetInput();
		// 	$this->dispatchBrowserEvent('closeModal');
		// 	session()->flash('message', 'Registro Successfully updated.');
		}
	}
}
// 	private function updateSaldosPosteriores($registro, $saldoAnterior)
// {
//     $registrosPosteriores = Registro::where('codigo_oficina', $registro->codigo_oficina)
//         ->where(function ($query) use ($registro) {
//             $query->where('Cod_registro', '>', $registro->Cod_registro)
//                 ->orWhere(function ($query) use ($registro) {
//                     $query->where('Cod_registro', $registro->Cod_registro)
//                         ->where('id', '>', $registro->id);
//                 });
//         })
//         ->orderBy('Cod_registro')
//         ->orderBy('id')
//         ->get();

//     foreach ($registrosPosteriores as $registroPosterior) {
//         $saldo = $saldoAnterior + doubleval($registroPosterior->ruta_imagen) - doubleval($registroPosterior->Asignado);

//         $registroPosterior->update([
//             'Saldo' => $saldo,
//         ]);

//         $saldoAnterior = $saldo;
//     }
// }
	// public function destroy($id)
	// {
	// 	if ($id) {
	// 		//buscamos el registro respectivo
	// 		$Registro = Registro::where('id', $id)
	// 			->first();
	// 		//si el registro exite
	// 		if ($Registro) {
	// 			//sacamos el registro anterior
	// 			$registroAnterior = Registro::where('Cod_registro', '<=', $Registro->Cod_registro)
	// 				->where('id', '<', $Registro->id)
	// 				->orderBy('Cod_registro', 'desc')
	// 				->first();
	// 			//borramos el registro seleccionado
	// 			Registro::where('id', $id)->delete();
	// 			//sie l registro anteriro eciste
	// 			if ($registroAnterior) {
	// 				// El registro anterior está disponible
	// 				$saldoAnterior = $registroAnterior->Saldo;
	// 				//usamos el proceso de actualizacion de losm registro posteriores al anterior del borrado
	// 				$this->updateSaldosPosteriores($registroAnterior, $saldoAnterior);
	// 			}
	// 		}

