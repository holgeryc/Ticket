<?php

namespace App\Http\Livewire;

use Dompdf\Dompdf;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Registro;
use App\Models\User;
use App\Models\Oficina;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\ExclusiveOr;



class Registros extends Component
{
	use WithPagination;

	protected $paginationTheme = 'bootstrap';
	public $selected_id, $keyWord, $Cod_registro, $Nro_ticket, $usuario, $C_P, $DNI, $Nombres_y_Apellidos, $Descripcion_problema, $ruta_imagen, $Asignado, $Saldo, $codigo_oficina_Oficina, $Activado, $mesSeleccionado, $anioSeleccionado, $logueado, $oficinaSeleccionado;

	protected $messages = [
		'Cod_registro.required' => 'El campo Cod_registro es requerido.',
		'Cod_registro.date' => 'El campo Cod_registro es de tipo Cod_registro.',
		'Nombres_y_Apellidos.required' => 'El campo Nombres y Apellidos es requerido.',
		'Nombres_y_Apellidos.strtoupper' => 'El campo Nombres y Apellidos es con Mayusculas',
		'Descripcion_problema.required' => 'El campo Descripcion_problema es requerido.',
		'Descripcion_problema.strtoupper' => 'El campo Deatlle es con Mayusculas.',
		'Nro_ticket.required_with' => 'El campo Nro ticket es requerido si el campo ruta_imagen esta siendo usado.',
		'usuario.required_with' => 'El campo Usuario es requerido si el campo Asignado esta siendo usado.',
		'ruta_imagen.required_with' => 'El campo ruta_imagen es requerido si el campo Nro ticket esta siendo usado.',
		'ruta_imagen.numeric' => 'El campo ruta_imagen es de tipo numerico.',
		'ruta_imagen.min' => 'El campo ruta_imagen tiene que ser un numero positivo.',
		'Asignado.required_with' => 'El campo Asignado es requerido si el campo Usuario esta siendo usado.',
		'Asignado.numeric' => 'El campo Asignado es de tipo numerico.',
		'Asignado.min' => 'El campo Asignado tiene que ser un numero positivo.',
		'codigo_oficina_Oficina' => 'El campo de Oficina es requerido',
	];

	public function render()
	{
		$usuarios = User::all();
		$oficinas = Oficina::all();
		$mesSeleccionado = $this->mesSeleccionado;
		$anioSeleccionado = $this->anioSeleccionado;
		$oficinaSeleccionado = $this->oficinaSeleccionado;

		$logueado = Auth::user()->codigo_oficina_Oficina;
		$keyWord = '%' . $this->keyWord . '%';
		if (Auth::user()->Tipo === "Contador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($logueado, $mesSeleccionado, $anioSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$logueado]);
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
					$query->Where('Nro_ticket', 'LIKE', $keyWord)
						->orWhere('usuario', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord)
						->orWhere('Asignado', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc')
				->paginate(13);
		}
		if (Auth::user()->Tipo === "Controlador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
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
					$query->where('Nro_ticket', 'LIKE', $keyWord)
						->orWhere('usuario', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord)
						->orWhere('Asignado', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc')
				->paginate(13);
		}
		if (Auth::user()->Tipo === "Administrador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
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
					$query->where('Nro_ticket', 'LIKE', $keyWord)
						->orWhere('usuario', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord)
						->orWhere('Asignado', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->orderBy('Cod_registro', 'asc')
				->paginate(13);
		}
		// Formatear los campos de ruta_imagen, Asignado y saldo en formato de moneda peruana
		$registros->getCollection()->transform(function ($registro) {
			if ($registro->ruta_imagen != 0) {
				$registro->ruta_imagen = 'S/. ' . number_format($registro->ruta_imagen, 2);
			}
			if ($registro->Asignado != 0) {
				$registro->Asignado = 'S/. ' . number_format($registro->Asignado, 2);
			}
			if ($registro->Saldo != 0) {
				$registro->Saldo = 'S/. ' . number_format($registro->Saldo, 2);
			}
			return $registro;
		});
		return view('livewire.registros.view', [
			'registros' => $registros,
			'usuarios' => $usuarios,
			'oficinas' => $oficinas,
		]);
	}
	public function generarPDF($mesSeleccionado = null, $anioSeleccionado = null, $oficinaSeleccionado = null, $keyWord = null)
	{
		if (Auth::user()->Tipo === "Contador") {
			$oficinaSeleccionado = Auth::user()->codigo_oficina_Oficina;
		}
		if (Auth::user()->Tipo === "Contador") {
			if (empty($mesSeleccionado) || empty($anioSeleccionado)) {
				// Mostrar aviso de campos faltantes o redireccionar a una página de error
				return redirect()->back()->with('error', 'Debe elegir un mes y un año');
			}
		} else {
			if (empty($mesSeleccionado) || empty($anioSeleccionado) || empty($oficinaSeleccionado)) {
				// Mostrar aviso de campos faltantes o redireccionar a una página de error
				return redirect()->back()->with('error', 'Debe elegir un mes, un año y un oficina');
			}
		}
		$usuarios = User::all();
		$oficinas = Oficina::all();
		$logueado = Auth::user()->codigo_oficina_Oficina;
		$logueado_tipo = Auth::user()->Tipo;
		$keyWord = '%' . $keyWord . '%';

		if (Auth::user()->Tipo === "Contador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($logueado, $mesSeleccionado, $anioSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$logueado]);
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
					$query->Where('Nro_ticket', 'LIKE', $keyWord)
						->orWhere('usuario', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord)
						->orWhere('Asignado', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc')
				->paginate(31);
		}
		if (Auth::user()->Tipo === "Controlador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
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
					$query->where('Nro_ticket', 'LIKE', $keyWord)
						->orWhere('usuario', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord)
						->orWhere('Asignado', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Cod_registro', 'asc');
		}
		if (Auth::user()->Tipo === "Administrador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Cod_registro, "%d/%m/%Y") AS Cod_registroFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
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
					$query->where('Nro_ticket', 'LIKE', $keyWord)
						->orWhere('usuario', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Descripcion_problema', 'LIKE', $keyWord)
						->orWhere('ruta_imagen', 'LIKE', $keyWord)
						->orWhere('Asignado', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->orderBy('Cod_registro', 'asc')
				->paginate(31);
		}
		// Formatear los campos de ruta_imagen, Asignado y saldo en formato de moneda peruana
		$registros->getCollection()->transform(function ($registro) {
			if ($registro->ruta_imagen != 0) {
				$registro->ruta_imagen = 'S/. ' . number_format($registro->ruta_imagen, 2);
			}
			if ($registro->Asignado != 0) {
				$registro->Asignado = 'S/. ' . number_format($registro->Asignado, 2);
			}
			if ($registro->Saldo != 0) {
				$registro->Saldo = 'S/. ' . number_format($registro->Saldo, 2);
			}
			return $registro;
		});
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
		if (isset($meses[$mesSeleccionado])) {
			$mesSeleccionado = $meses[$mesSeleccionado];
		} else {
			$mesSeleccionado = 'Mes inválido';
		}
		//Nombre del oficinasleeccionado
		$oficina = Oficina::where('codigo_oficina', $oficinaSeleccionado)->first();
		if ($oficina) {
			$oficinaSeleccionado = $oficina->Nombre;
		}
		//cuenta bancaria del oficina sleccioando
		if ($oficina) {
			$oficinaCuentaCorriente = $oficina->unidad;
		}
		//saldo anterior
		$primerRegistro = $registros->first();

		if ($primerRegistro) {
			$registroAnterior = Registro::where('Cod_registro', '<=', $primerRegistro->Cod_registro)
				->where('id', '<', $primerRegistro->id)
				->orderBy('Cod_registro', 'desc')
				->first();
			if ($registroAnterior) {
				// El registro anterior está disponible
				$saldoAnterior = $registroAnterior->Saldo;
			} else {
				// No se encontró ningún registro anterior al primer registro
				// Realiza la lógica adecuada en este caso
				$saldoAnterior = 0;
			}
		} else {
			// No se encontró el primer registro para el mes y año especificados
			// Realiza la lógica adecuada en este caso
			$saldoAnterior = 0;
		}
		$saldoAnterior = 'S/. ' . number_format($saldoAnterior, 2);
		// Establecer la zona horaria
		date_default_timezone_set('America/Lima'); // Reemplaza 'America/Lima' con la zona horaria correspondiente a tu ubicación
		// Obtener la Cod_registro y hora actual
		$currentDateTime = date('d/m/Y h:i:s A');
		//sacamos la url para mostrarla en el reporte
		$currentURL = \URL::current();

		// Crear una instancia de Dompdf
		$dompdf = new Dompdf();

		// Generar el contenido HTML del PDF
		$html = view('livewire.registros.pdf', compact('registros', 'oficinaSeleccionado', 'mesSeleccionado', 'anioSeleccionado', 'oficinaCuentaCorriente', 'saldoAnterior', 'currentDateTime', 'currentURL'))->render();
		// Agregar título personalizado al HTML
		$title = $oficinaSeleccionado . '_' . $anioSeleccionado . '_' . $mesSeleccionado;
		$html = '<html><head><title>' . $title . '</title></head><body>' . $html . '</body></html>';

		// Cargar el contenido HTML en Dompdf
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->set_option('isRemoteEnabled', true);
		// Renderizar el PDF
		$dompdf->render();

		// Establecer el nombre del archivo PDF
		$filename = $oficinaSeleccionado.'_'.$anioSeleccionado.'_'.$mesSeleccionado.'.pdf';

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
		$this->Nro_ticket = null;
		$this->usuario = null;
		$this->C_P = null;
		$this->DNI = null;
		$this->Nombres_y_Apellidos = null;
		$this->Descripcion_problema = null;
		$this->ruta_imagen = null;
		$this->Asignado = null;
		$this->Saldo = null;
		$this->codigo_oficina_Oficina = null;
		$this->Activado = null;
	}

	public function store()
	{
		if (Auth::user()->Tipo === 'Contador') {
			$this->validate([
				'Cod_registro' => 'required|date',
				'Nombres_y_Apellidos' => 'required|strtoupper',
				'Descripcion_problema' => 'required|strtoupper',
				'Nro_ticket' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:ruta_imagen'],
    			'usuario' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:Asignado'],
				'ruta_imagen' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:Nro_ticket',
				'numeric',
				'min:0',],
				'Asignado' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:usuario',
				'numeric',
				'min:0',],
			]);
		}
		if (Auth::user()->Tipo === 'Administrador') {
			$this->validate([
				'Cod_registro' => 'required|date',
				'codigo_oficina_Oficina' => 'required',
				'Nro_ticket' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:ruta_imagen','nullable'],
    			'usuario' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:Asignado', 'nullable'],
				'ruta_imagen' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:Nro_ticket','nullable',
				'numeric',
				'min:0',],
				'Asignado' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:usuario', 'nullable',
				'numeric',
				'min:0',],
			]);
		}
		if($this->C_P===''){
			$this->C_P=null;
		}
		if ($this->codigo_oficina_Oficina === '') {
			$this->codigo_oficina_Oficina = null;
		};
		if ($this->ruta_imagen === '' || $this->ruta_imagen === 0) {
			$this->ruta_imagen = null;
		};
		if ($this->Asignado === ''  || $this->Asignado === 0) {
			$this->Asignado = null;
		};
		if (Auth::user()->Tipo === "Contador") {
			$this->codigo_oficina_Oficina = Auth::user()->codigo_oficina_Oficina;
		}
		$registroAnterior = Registro::where('codigo_oficina_Oficina', $this->codigo_oficina_Oficina)
			->where('Cod_registro', '<=', $this->Cod_registro)
			->orderByDOficina
			->first();

		if (!$registroAnterior) {
			$saldoAnterior = 0;
		} else {
			$saldoAnterior = $registroAnterior->Saldo;
		}
		$Saldo = $saldoAnterior + doubleval($this->ruta_imagen) - doubleval($this->Asignado);
		if (Auth::user()->Tipo == "Contador") {
			Registro::create([
				'Cod_registro' => $this->Cod_registro,
				'Nro_ticket' => $this->Nro_ticket,
				'usuario' => $this->usuario,
				'C_P' => $this->C_P,
				'DNI' => Auth::user()->DNI,
				'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
				'Descripcion_problema' => $this->Descripcion_problema,
				'ruta_imagen' => $this->ruta_imagen,
				'Asignado' => $this->Asignado,
				'Saldo' => $Saldo,
				'codigo_oficina_Oficina' => $this->codigo_oficina_Oficina,
				'Activado' => true,
			]);
		} else {
			Registro::create([
				'Cod_registro' => $this->Cod_registro,
				'Nro_ticket' => $this->Nro_ticket,
				'usuario' => $this->usuario,
				'C_P' => $this->C_P,
				'DNI' => $this->DNI,
				'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
				'Descripcion_problema' => $this->Descripcion_problema,
				'ruta_imagen' => $this->ruta_imagen,
				'Asignado' => $this->Asignado,
				'Saldo' => $Saldo,
				'codigo_oficina_Oficina' => $this->codigo_oficina_Oficina,
				'Activado' => true,
			]);
		}

		

		$this->resetInput();
		$this->dispatchBrowserEvent('closeModal');
		session()->flash('message', 'Registro Successfully created.');
	}

	public function edit($id)
	{
		$record = Registro::findOrFail($id);
		$this->selected_id = $id;
		$this->Cod_registro = $record->Cod_registro;
		$this->Nro_ticket = $record->Nro_ticket;
		$this->usuario = $record->usuario;
		$this->C_P = $record->C_P;
		$this->Nombres_y_Apellidos = $record->Nombres_y_Apellidos;
		$this->Descripcion_problema = $record->Descripcion_problema;
		$this->ruta_imagen = $record->ruta_imagen;
		$this->Asignado = $record->Asignado;
		$this->Saldo = $record->Saldo;
		if (Auth::user()->Tipo === 'Administrador') {
			$this->Activado = $record->Activado;
		}
		$this->codigo_oficina_Oficina = $record->codigo_oficina_Oficina;
		$this->resetValidation();
	}

	public function update()
	{
		if (Auth::user()->Tipo === 'Contador') {
			$this->validate([
				'Cod_registro' => 'required|date',
				'Nombres_y_Apellidos' => 'required',
				'Nro_ticket' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:ruta_imagen'],
    			'usuario' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:Asignado'],
				'ruta_imagen' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:Nro_ticket',
				'numeric',
				'min:0',],
				'Asignado' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:usuario',
				'numeric',
				'min:0',],
			]);
		}
		if (Auth::user()->Tipo === 'Administrador') {
			$this->validate([
				'Cod_registro' => 'required|date',
				'codigo_oficina_Oficina' => 'required',
				'Nro_ticket' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:ruta_imagen','nullable'],
    			'usuario' => [new ExclusiveOr('Nro_ticket', 'usuario'),'required_with:Asignado', 'nullable'],
				'ruta_imagen' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:Nro_ticket','nullable',
				'numeric',
				'min:0',],
				'Asignado' => [new ExclusiveOr('ruta_imagen', 'Asignado'),'required_with:usuario', 'nullable',
				'numeric',
				'min:0',],
			]);
		}
		if ($this->ruta_imagen === '' || $this->ruta_imagen === 0) {
			$this->ruta_imagen = null;
		};
		if ($this->Asignado === ''  || $this->Asignado === 0) {
			$this->Asignado = null;
		};
		if($this->C_P===''){
			$this->C_P=null;
		}
		$registroAnterior = Registro::where('codigo_oficina_Oficina', $this->codigo_oficina_Oficina)
			->where(function ($query) {
				$query->where('Cod_registro', '<', $this->Cod_registro)
					->orWhere(function ($query) {
						$query->where('Cod_registro', $this->Cod_registro)
							->where('id', '<', $this->selected_id);
					});
			})
			->orderByDesc('Cod_registro')
			->orderByDesc('id')
			->first();

		if (!$registroAnterior) {
			$saldoAnterior = 0;
		} else {
			$saldoAnterior = $registroAnterior->Saldo;
		}
		$Saldo = $saldoAnterior + doubleval($this->ruta_imagen) - doubleval($this->Asignado);


		if ($this->selected_id) {
			$record = Registro::find($this->selected_id);
			if (Auth::user()->Tipo === 'Administrador') {
				$record->update([
					'Cod_registro' => $this->Cod_registro,
					'Nro_ticket' => $this->Nro_ticket,
					'usuario' => $this->usuario,
					'C_P' => $this->C_P,
					'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
					'Descripcion_problema' => $this->Descripcion_problema,
					'ruta_imagen' => $this->ruta_imagen,
					'Asignado' => $this->Asignado,
					'Saldo' => $Saldo,
					'codigo_oficina_Oficina' => $this->codigo_oficina_Oficina,
					'Activado' => $this->Activado
				]);
			} else {
				$record->update([
					'Cod_registro' => $this->Cod_registro,
					'Nro_ticket' => $this->Nro_ticket,
					'usuario' => $this->usuario,
					'C_P' => $this->C_P,
					'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
					'Descripcion_problema' => $this->Descripcion_problema,
					'ruta_imagen' => $this->ruta_imagen,
					'Asignado' => $this->Asignado,
					'Saldo' => $Saldo,
					'codigo_oficina_Oficina' => $this->codigo_oficina_Oficina,
				]);
			};
			$this->updateSaldosPosteriores($record, $Saldo);

			$this->resetInput();
			$this->dispatchBrowserEvent('closeModal');
			session()->flash('message', 'Registro Successfully updated.');
		}
	}
	private function updateSaldosPosteriores($registro, $saldoAnterior)
{
    $registrosPosteriores = Registro::where('codigo_oficina_Oficina', $registro->codigo_oficina_Oficina)
        ->where(function ($query) use ($registro) {
            $query->where('Cod_registro', '>', $registro->Cod_registro)
                ->orWhere(function ($query) use ($registro) {
                    $query->where('Cod_registro', $registro->Cod_registro)
                        ->where('id', '>', $registro->id);
                });
        })
        ->orderBy('Cod_registro')
        ->orderBy('id')
        ->get();

    foreach ($registrosPosteriores as $registroPosterior) {
        $saldo = $saldoAnterior + doubleval($registroPosterior->ruta_imagen) - doubleval($registroPosterior->Asignado);

        $registroPosterior->update([
            'Saldo' => $saldo,
        ]);

        $saldoAnterior = $saldo;
    }
}
	public function destroy($id)
	{
		if ($id) {
			//buscamos el registro respectivo
			$Registro = Registro::where('id', $id)
				->first();
			//si el registro exite
			if ($Registro) {
				//sacamos el registro anterior
				$registroAnterior = Registro::where('Cod_registro', '<=', $Registro->Cod_registro)
					->where('id', '<', $Registro->id)
					->orderBy('Cod_registro', 'desc')
					->first();
				//borramos el registro seleccionado
				Registro::where('id', $id)->delete();
				//sie l registro anteriro eciste
				if ($registroAnterior) {
					// El registro anterior está disponible
					$saldoAnterior = $registroAnterior->Saldo;
					//usamos el proceso de actualizacion de losm registro posteriores al anterior del borrado
					$this->updateSaldosPosteriores($registroAnterior, $saldoAnterior);
				}
			}
		}
	}

}
