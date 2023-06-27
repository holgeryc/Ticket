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
	public $selected_id, $keyWord, $Fecha, $N°_Voucher, $N°_Cheque, $C_P, $DNI, $Nombres_y_Apellidos, $Detalle, $Entrada, $Salida, $Saldo, $codigo_oficina_Oficina, $Activado, $mesSeleccionado, $anioSeleccionado, $logueado, $oficinaSeleccionado;

	protected $messages = [
		'Fecha.required' => 'El campo Fecha es requerido.',
		'Fecha.date' => 'El campo Fecha es de tipo Fecha.',
		'Nombres_y_Apellidos.required' => 'El campo Nombres y Apellidos es requerido.',
		'Nombres_y_Apellidos.strtoupper' => 'El campo Nombres y Apellidos es con Mayusculas',
		'Detalle.required' => 'El campo Detalle es requerido.',
		'Detalle.strtoupper' => 'El campo Deatlle es con Mayusculas.',
		'N°_Voucher.required_with' => 'El campo N° Voucher es requerido si el campo Entrada esta siendo usado.',
		'N°_Cheque.required_with' => 'El campo N° Cheque es requerido si el campo Salida esta siendo usado.',
		'Entrada.required_with' => 'El campo Entrada es requerido si el campo N° Voucher esta siendo usado.',
		'Entrada.numeric' => 'El campo Entrada es de tipo numerico.',
		'Entrada.min' => 'El campo Entrada tiene que ser un numero positivo.',
		'Salida.required_with' => 'El campo Salida es requerido si el campo N° Cheque esta siendo usado.',
		'Salida.numeric' => 'El campo Salida es de tipo numerico.',
		'Salida.min' => 'El campo Salida tiene que ser un numero positivo.',
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
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Fecha, "%d/%m/%Y") AS FechaFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($logueado, $mesSeleccionado, $anioSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$logueado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Fecha) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Fecha) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->Where('N°_Voucher', 'LIKE', $keyWord)
						->orWhere('N°_Cheque', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
						->orWhere('Entrada', 'LIKE', $keyWord)
						->orWhere('Salida', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Fecha', 'asc')
				->paginate(13);
		}
		if (Auth::user()->Tipo === "Controlador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Fecha, "%d/%m/%Y") AS FechaFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Fecha) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Fecha) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('N°_Voucher', 'LIKE', $keyWord)
						->orWhere('N°_Cheque', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
						->orWhere('Entrada', 'LIKE', $keyWord)
						->orWhere('Salida', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Fecha', 'asc')
				->paginate(13);
		}
		if (Auth::user()->Tipo === "Administrador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Fecha, "%d/%m/%Y") AS FechaFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Fecha) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Fecha) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('N°_Voucher', 'LIKE', $keyWord)
						->orWhere('N°_Cheque', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
						->orWhere('Entrada', 'LIKE', $keyWord)
						->orWhere('Salida', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->orderBy('Fecha', 'asc')
				->paginate(13);
		}
		// Formatear los campos de entrada, salida y saldo en formato de moneda peruana
		$registros->getCollection()->transform(function ($registro) {
			if ($registro->Entrada != 0) {
				$registro->Entrada = 'S/. ' . number_format($registro->Entrada, 2);
			}
			if ($registro->Salida != 0) {
				$registro->Salida = 'S/. ' . number_format($registro->Salida, 2);
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
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Fecha, "%d/%m/%Y") AS FechaFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $logueado, function ($query) use ($logueado, $mesSeleccionado, $anioSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $logueado) {
						if ($logueado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$logueado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Fecha) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Fecha) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->Where('N°_Voucher', 'LIKE', $keyWord)
						->orWhere('N°_Cheque', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
						->orWhere('Entrada', 'LIKE', $keyWord)
						->orWhere('Salida', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Fecha', 'asc')
				->paginate(31);
		}
		if (Auth::user()->Tipo === "Controlador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->leftJoin('users', 'users.DNI', '=', 'registros.DNI')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Fecha, "%d/%m/%Y") AS FechaFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Fecha) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Fecha) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('N°_Voucher', 'LIKE', $keyWord)
						->orWhere('N°_Cheque', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
						->orWhere('Entrada', 'LIKE', $keyWord)
						->orWhere('Salida', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->whereRaw('users.Tipo != "Administrador"')
				->orderBy('Fecha', 'asc');
		}
		if (Auth::user()->Tipo === "Administrador") {
			$registros = Registro::leftJoin('oficinas', 'registros.codigo_oficina_Oficina', '=', 'oficinas.codigo_oficina')
				->selectRaw('registros.*, oficinas.Nombre, DATE_FORMAT(registros.Fecha, "%d/%m/%Y") AS FechaFormateada')
				->when($mesSeleccionado || $anioSeleccionado || $oficinaSeleccionado, function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
					return $query->where(function ($query) use ($mesSeleccionado, $anioSeleccionado, $oficinaSeleccionado) {
						if ($oficinaSeleccionado) {
							$query->whereRaw('registros.codigo_oficina_Oficina LIKE ?', [$oficinaSeleccionado]);
						}
						if ($mesSeleccionado) {
							$query->whereRaw('MONTH(Fecha) = ?', [str_pad($mesSeleccionado, 2, '0', STR_PAD_LEFT)]);
						}
						if ($anioSeleccionado) {
							$query->whereRaw('YEAR(Fecha) = ?', [$anioSeleccionado]);
						}
					});
				})
				->where(function ($query) use ($keyWord) {
					$query->where('N°_Voucher', 'LIKE', $keyWord)
						->orWhere('N°_Cheque', 'LIKE', $keyWord)
						->orWhere('C_P', 'LIKE', $keyWord)
						->orWhere('registros.Nombres_y_Apellidos', 'LIKE', $keyWord)
						->orWhere('Detalle', 'LIKE', $keyWord)
						->orWhere('Entrada', 'LIKE', $keyWord)
						->orWhere('Salida', 'LIKE', $keyWord)
						->orWhere('Saldo', 'LIKE', $keyWord)
						->orWhere('oficinas.Nombre', 'LIKE', $keyWord)
						->orWhere('registros.Activado', 'LIKE', $keyWord);
				})
				->orderBy('Fecha', 'asc')
				->paginate(31);
		}
		// Formatear los campos de entrada, salida y saldo en formato de moneda peruana
		$registros->getCollection()->transform(function ($registro) {
			if ($registro->Entrada != 0) {
				$registro->Entrada = 'S/. ' . number_format($registro->Entrada, 2);
			}
			if ($registro->Salida != 0) {
				$registro->Salida = 'S/. ' . number_format($registro->Salida, 2);
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
			$registroAnterior = Registro::where('Fecha', '<=', $primerRegistro->Fecha)
				->where('id', '<', $primerRegistro->id)
				->orderBy('Fecha', 'desc')
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
		// Obtener la fecha y hora actual
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
		$this->Fecha = null;
		$this->N°_Voucher = null;
		$this->N°_Cheque = null;
		$this->C_P = null;
		$this->DNI = null;
		$this->Nombres_y_Apellidos = null;
		$this->Detalle = null;
		$this->Entrada = null;
		$this->Salida = null;
		$this->Saldo = null;
		$this->codigo_oficina_Oficina = null;
		$this->Activado = null;
	}

	public function store()
	{
		if (Auth::user()->Tipo === 'Contador') {
			$this->validate([
				'Fecha' => 'required|date',
				'Nombres_y_Apellidos' => 'required|strtoupper',
				'Detalle' => 'required|strtoupper',
				'N°_Voucher' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Entrada'],
    			'N°_Cheque' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Salida'],
				'Entrada' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Voucher',
				'numeric',
				'min:0',],
				'Salida' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Cheque',
				'numeric',
				'min:0',],
			]);
		}
		if (Auth::user()->Tipo === 'Administrador') {
			$this->validate([
				'Fecha' => 'required|date',
				'codigo_oficina_Oficina' => 'required',
				'N°_Voucher' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Entrada','nullable'],
    			'N°_Cheque' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Salida', 'nullable'],
				'Entrada' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Voucher','nullable',
				'numeric',
				'min:0',],
				'Salida' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Cheque', 'nullable',
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
		if ($this->Entrada === '' || $this->Entrada === 0) {
			$this->Entrada = null;
		};
		if ($this->Salida === ''  || $this->Salida === 0) {
			$this->Salida = null;
		};
		if (Auth::user()->Tipo === "Contador") {
			$this->codigo_oficina_Oficina = Auth::user()->codigo_oficina_Oficina;
		}
		$registroAnterior = Registro::where('codigo_oficina_Oficina', $this->codigo_oficina_Oficina)
			->where('Fecha', '<=', $this->Fecha)
			->orderByDOficina
			->first();

		if (!$registroAnterior) {
			$saldoAnterior = 0;
		} else {
			$saldoAnterior = $registroAnterior->Saldo;
		}
		$Saldo = $saldoAnterior + doubleval($this->Entrada) - doubleval($this->Salida);
		if (Auth::user()->Tipo == "Contador") {
			Registro::create([
				'Fecha' => $this->Fecha,
				'N°_Voucher' => $this->N°_Voucher,
				'N°_Cheque' => $this->N°_Cheque,
				'C_P' => $this->C_P,
				'DNI' => Auth::user()->DNI,
				'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
				'Detalle' => $this->Detalle,
				'Entrada' => $this->Entrada,
				'Salida' => $this->Salida,
				'Saldo' => $Saldo,
				'codigo_oficina_Oficina' => $this->codigo_oficina_Oficina,
				'Activado' => true,
			]);
		} else {
			Registro::create([
				'Fecha' => $this->Fecha,
				'N°_Voucher' => $this->N°_Voucher,
				'N°_Cheque' => $this->N°_Cheque,
				'C_P' => $this->C_P,
				'DNI' => $this->DNI,
				'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
				'Detalle' => $this->Detalle,
				'Entrada' => $this->Entrada,
				'Salida' => $this->Salida,
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
		$this->Fecha = $record->Fecha;
		$this->N°_Voucher = $record->N°_Voucher;
		$this->N°_Cheque = $record->N°_Cheque;
		$this->C_P = $record->C_P;
		$this->Nombres_y_Apellidos = $record->Nombres_y_Apellidos;
		$this->Detalle = $record->Detalle;
		$this->Entrada = $record->Entrada;
		$this->Salida = $record->Salida;
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
				'Fecha' => 'required|date',
				'Nombres_y_Apellidos' => 'required',
				'N°_Voucher' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Entrada'],
    			'N°_Cheque' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Salida'],
				'Entrada' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Voucher',
				'numeric',
				'min:0',],
				'Salida' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Cheque',
				'numeric',
				'min:0',],
			]);
		}
		if (Auth::user()->Tipo === 'Administrador') {
			$this->validate([
				'Fecha' => 'required|date',
				'codigo_oficina_Oficina' => 'required',
				'N°_Voucher' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Entrada','nullable'],
    			'N°_Cheque' => [new ExclusiveOr('N°_Voucher', 'N°_Cheque'),'required_with:Salida', 'nullable'],
				'Entrada' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Voucher','nullable',
				'numeric',
				'min:0',],
				'Salida' => [new ExclusiveOr('Entrada', 'Salida'),'required_with:N°_Cheque', 'nullable',
				'numeric',
				'min:0',],
			]);
		}
		if ($this->Entrada === '' || $this->Entrada === 0) {
			$this->Entrada = null;
		};
		if ($this->Salida === ''  || $this->Salida === 0) {
			$this->Salida = null;
		};
		if($this->C_P===''){
			$this->C_P=null;
		}
		$registroAnterior = Registro::where('codigo_oficina_Oficina', $this->codigo_oficina_Oficina)
			->where(function ($query) {
				$query->where('Fecha', '<', $this->Fecha)
					->orWhere(function ($query) {
						$query->where('Fecha', $this->Fecha)
							->where('id', '<', $this->selected_id);
					});
			})
			->orderByDesc('Fecha')
			->orderByDesc('id')
			->first();

		if (!$registroAnterior) {
			$saldoAnterior = 0;
		} else {
			$saldoAnterior = $registroAnterior->Saldo;
		}
		$Saldo = $saldoAnterior + doubleval($this->Entrada) - doubleval($this->Salida);


		if ($this->selected_id) {
			$record = Registro::find($this->selected_id);
			if (Auth::user()->Tipo === 'Administrador') {
				$record->update([
					'Fecha' => $this->Fecha,
					'N°_Voucher' => $this->N°_Voucher,
					'N°_Cheque' => $this->N°_Cheque,
					'C_P' => $this->C_P,
					'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
					'Detalle' => $this->Detalle,
					'Entrada' => $this->Entrada,
					'Salida' => $this->Salida,
					'Saldo' => $Saldo,
					'codigo_oficina_Oficina' => $this->codigo_oficina_Oficina,
					'Activado' => $this->Activado
				]);
			} else {
				$record->update([
					'Fecha' => $this->Fecha,
					'N°_Voucher' => $this->N°_Voucher,
					'N°_Cheque' => $this->N°_Cheque,
					'C_P' => $this->C_P,
					'Nombres_y_Apellidos' => $this->Nombres_y_Apellidos,
					'Detalle' => $this->Detalle,
					'Entrada' => $this->Entrada,
					'Salida' => $this->Salida,
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
            $query->where('Fecha', '>', $registro->Fecha)
                ->orWhere(function ($query) use ($registro) {
                    $query->where('Fecha', $registro->Fecha)
                        ->where('id', '>', $registro->id);
                });
        })
        ->orderBy('Fecha')
        ->orderBy('id')
        ->get();

    foreach ($registrosPosteriores as $registroPosterior) {
        $saldo = $saldoAnterior + doubleval($registroPosterior->Entrada) - doubleval($registroPosterior->Salida);

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
				$registroAnterior = Registro::where('Fecha', '<=', $Registro->Fecha)
					->where('id', '<', $Registro->id)
					->orderBy('Fecha', 'desc')
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
