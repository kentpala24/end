<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Module;
use App\Models\Junta;
use App\Models\Cat;
use App\Models\Permit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Form;

class ServicioController extends Controller
{
    protected $model;
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $validationRulesUpProfile;
    protected $attributeNamesUpServicio;
    protected $errorMessagesUpProfile;
    protected $validationRulesUpPassword;
    protected $attributeNamesUpPassword;
    protected $errorMessagesUpPassword;
    protected $validationRulesAddUser;
    protected $attributeNamesAddServicio;

    public function __construct(Servicio $model)
    {
        $this->validationRulesUpProfile = [
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|max:255',
        ];
        $this->attributeNamesUpServicio = [
            'proyecto' => 'proyecto',
            'id_cliente' => 'id_cliente',
            'proyecto' => 'proyecto',
            'inspeccion' => 'inspeccion',
            'desc_cant_elem' => 'desc_cant_elem',
            'fecha_inicio' => 'fecha_inicio',
            'horario' => 'horario',
            'observacion' => 'observacion',
        ];
        $this->attributeNamesUpJunta = [
            'name' => 'required|string|max:300',
            'email' => 'required|email|max:100|unique:users',
            'cta_id' => 'required|integer',
            'tipo_emp' => 'required|integer',
            'lvl_id' => 'required|integer',
        ];
        $this->errorMessagesUpProfile = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->validationRulesUpPassword = [
            'password' => ['required', 'max:20'],
        ];
        $this->attributeNamesUpPassword = [
            'password' => 'contraseña',
        ];
        $this->errorMessagesUpPassword = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->validationRulesAddUser = [
            'name' => 'required|string|max:300',
            'email' => 'required|email|max:100|unique:users',
            'cta_id' => 'required|integer',
            'tipo_emp' => 'required|integer',
            'lvl_id' => 'required|integer',
        ];
        $this->attributeNamesAddServicio = [
            'proyecto' => 'proyecto',
            'id_cliente' => 'id_cliente',
            'proyecto' => 'proyecto',
            'inspeccion' => 'inspeccion',
            'desc_cant_elem' => 'desc_cant_elem',
            'fecha_inicio' => 'fecha_inicio',
            'horario' => 'horario',
            'observacion' => 'observacion',
        ];
        $this->attributeNamesAddJunta = [
            'codigo' => 'codigo',
            'id_servicio' => 'id_servicio',
            'diametro' => 'diametro',
            'resultado' => 'resultado',
            'Comentarios' => 'Comentarios'
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->model = $model;
    }

    public function listServicios(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = Route::current()->getName();
        if ($data['url'] !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                //dd($data['url'], $data);
                return view('servicios/'.$data['url'], $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Permiso no encontrado.', 'danger');
        }
    }

    public function loadServicios(Request $request)
    {
        $data['page'] = ($request->page) ? $request->page : 1;
        $data['order'] = ($request->order) ? $request->order : 'desc';
        $data['order_by'] = ($request->order_by) ? $request->order_by : 'id';
        $data['search'] = ($request->search) ? trim($request->search) : '';
        $data['per_page'] = ($request->limite) ? $request->limite : 10;
        $data['user'] = ($request->user) ? $request->user : 0;
        $data['filter'] = ($request->filter) ? $request->filter : 0;
        $data['act_fc'] = $request->act_fc;
        $data['dt_ini'] = ($request->dt_ini) ? $request->dt_ini : getFirstLastDate('first', 'month');
        $data['dt_fin'] = ($request->dt_fin) ? $request->dt_fin : getFirstLastDate('last', 'month');
        $data['adyacentes'] = 2;

        $total = $this->search($data, 1);
        $results = $this->search($data, 0);
        
        
        $total_pages = ceil($total / $data['per_page']);
        $response['total'] = $total;

        $response['data'] = '';
        if ($total > 0) {
            $response['data'] .= '<div class="table-responsive"><table id="table-servicios" class="table table-row-gray-200  kt_table_servicios">
                <thead>
                    <tr class="row-link">
                        <th class="text-left w-5">
                            <div class="form-check form-check-sm form-check-custom me-3">
                                <input class="form-check-input chk-delete-all" type="checkbox" data-kt-check="true" data-kt-check-target="#table-servicios .form-check-input" value="1" />
                            </div>
                        </th>
                        <th data-field="name"  class="th-link"><i class="bi bi-sort-down"></i> OIS</th>
                        <th data-field="status" class="th-link w-7 text-center"><i class="bi bi-sort-down"></i> Estado</th>';
            
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Cliente</th>';
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Proyecto</th>';
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Inspección</th>';
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Elementos</th>';

            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Fecha Inicio</th>';
            

            $response['data'] .= '<th class="w-10 text-center"><i class="bi bi-check-circle"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($results as $reg) {
                $response['data'] .= '<tr>
                                        <td class="text-center w-3">
                                            <div class="form-check form-check-sm form-check-custom">
                                                <input class="form-check-input chk-select-delete" type="checkbox" data-id="' . $reg->id . '" value="1" id="chk_' . $reg->id . '" name="chk_' . $reg->id . '">
                                                <label for="chk_' . $reg->id . '" class="form-check-label"> ' . $reg->id . '</label>
                                            </div>
                                        </td>
                                        <td class="">    
                                            
                                            <a href="' . route('editServicio', $reg) . '" class="text-gray-800 text-hover-primary mb-1">
                                                ' . $reg->ois . '
                                                <i class="bi bi-building text-danger float-end"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">';
                switch ($reg->status) {
                    case 1:
                        $response['data'] .= '<span class="fs-9 badge text-bg-success">Confirmado</span>';
                        break;
                    case 2:
                        $response['data'] .= '<span class="fs-9 badge text-bg-danger">En Proceso</span>';
                        break;
                    case 3:
                        $response['data'] .= '<span class="fs-9 badge text-bg-warning">Revisión</span>';
                        break;
                    default:
                        $response['data'] .= '<span class="fs-9 badge text-bg-secondary">Concluido</span>';
                        break;
                }

                $response['data'] .= '</td>
                                    <td>';
                $response['data'] .= $reg->nombre;
                $response['data'] .= '</td>
                                      <td>';
                $response['data'] .= $reg->proyecto;

                $response['data'] .= '</td>
                                    <td>';
                $response['data'] .= $reg->inspeccion;

                $response['data'] .= '</td>
                                    <td>';
                $response['data'] .= $reg->desc_cant_elem;
                $response['data'] .= '</td>
                                      <td>';
                $response['data'] .= $reg->fecha_inicio;

                $response['data'] .= '</td>
                                    <td class="text-center">';
                
                $response['data'] .= '<a href="'.route('editServicio', $reg).'" class="text-primary btn btn-link"><i class="text-primary fa fa-edit"></i></a>';
                $response['data'] .= '<button class="btn btn-link mdl-del-reg" data-id="'.$reg->id.'" data-nom="'.$reg->nombre.'" data-bs-toggle="modal" data-bs-target="#del-regs"><i class="text-danger bi bi-trash"></i></button>';
                $response['data'] .= '</td></tr>';
            }
            $response['data'] .= '</tbody></table></div>';
            $response['data'] .= '<div class="border-top">'.paginate($data['page'], $total_pages, $data['adyacentes'], 'load').'</div>';
        } else {
            $response['data'] = '<div class="alert alert-dark text-center" role="alert"><i class="fas fa-search"></i> No hay registros para mostrar.</div>';
        }

        return response()->json($response);
    }

    public function editServicio($id)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = $url = Route::current()->getName();
        if ($url !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                $data['url'] = $data['permiso']->module->back->url_module;
                $reg = Servicio::find($id);
                if ($reg) {
                    $data['reg'] = $reg;
                    $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                    return view('servicios/'.$url, $data);
                }else{
                    redireccionar(route('dashboard'), 'Registro no encontrado.', 'danger');
                }
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }

    public function addServicio(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $clientes = Cliente::where('status','=','1')->get();
        $data['url'] = $url = Route::current()->getName();
        if ($url !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['cliente'] = $clientes;
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                $data['url'] = $data['permiso']->module->url_module;
                $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                //dd($data);
                return view('servicios/'.$url, $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }
    public function storeServicio(Request $request)
    {
        $rules = [
            'id_cliente' => 'required',
            'proyecto' => 'required|string|max:300',
            'inspeccion' => 'required',
            'desc_cant_elem' => 'required|string|max:300',
            'fecha_inicio' => 'required',
            'horario' => 'required|string|max:300',
            'observacion' => 'required|string|max:300',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $this->errorMessages)->setAttributeNames($this->attributeNamesAddServicio);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            
            $servicio = new Servicio();
            //dd($request->input('inspeccion', []));
            $inspeccionSeleccionados = $request->input('inspeccion', []);
            $inspeccionA="";
            // Hacer algo con los valores seleccionados
            foreach ($inspeccionSeleccionados as $inspeccionId) {
                if($inspeccionA==""){
                    $inspeccionA = "$inspeccionId";
                }
                else{
                    $inspeccionA = "$inspeccionA,$inspeccionId";
                }
                
            }
            //dd($inspeccionA);

            $count=Servicio::count();
            $servicio->ois = "00$count-24";
            $servicio->id_cliente=$request['id_cliente'];
            $servicio->proyecto=$request['proyecto'];
            $servicio->inspeccion=$inspeccionA;
            $servicio->fecha_inicio=$request['fecha_inicio'];
            $servicio->desc_cant_elem=$request['desc_cant_elem'];
            $servicio->horario=$request['horario'];
            $servicio->observacion=$request['observacion'];
            $servicio->status=$request['status'];
            $servicio->id_usuario=auth()->user()->id;
            $servicio->save();
            $msg = ['tipo' => 'success',
                'icon' => 'fa fa-check',
                'url' => route('editCliente', $servicio),
                'msg' => 'Registro guardado, redireccionando', ];
        }
        return response()->json($msg);
    }
    private function search($data, $mode)
    {
        $query = Servicio::join('clientes as c','c.id','=','servicios.id_cliente')
        ->select('c.id as id_cliente','c.nombre','servicios.id','servicios.ois','servicios.proyecto','servicios.inspeccion','servicios.desc_cant_elem'
        ,'servicios.status','servicios.fecha_inicio');
        if ($data['act_fc'] == 1) {
            $query = $query->whereBetween('created_at', [$data['dt_ini'], $data['dt_fin']]);
        }

        if ($data['filter'] > 0) {
            $query = $query->where('servicios.status', $data['filter']);
        } else {
            $query = $query->where('servicios.status', '>', 0);
        }

        $words = splitWordSearch($data['search']);
        if ($words) {
            $query = $query->where(function (Builder $q) use ($words) {
                foreach ($words as $word) {
                    $q->whereAny([
                        'c.nombre',
                        'servicios.proyecto',
                        'servicios.inspeccion'
                    ], 'LIKE', '%'.$word.'%');
                }
            });
        }
        $query = $query->orderBy($data['order_by'], $data['order']);
        if ($mode == 0) {
            $data['offset'] = ($data['page'] - 1) * $data['per_page'];
            $query = $query->offset($data['offset'])->limit($data['per_page']);
            $query = $query->get();
        } else {
            $query = $query->count();
        }
        //dd($query);
        return $query;
    }
    public function loadInfoServicio(Request $request)
    {
        $reg = Servicio::find($request->reg);
        $clientes = Cliente::all();
        $clienteOptions = [];
            foreach ($clientes as $c) {
                $clienteOptions[$c->id] = $c->nombre;
            }
        $res['results'] = '';
        if ($reg) {
            $arrayInspeccion = explode(',', $reg->inspeccion);
            $arrayInspeccion = ["RT", "UT"];
            $opciones = ['RT', 'UT', 'VT', 'PT', 'MT', 'UTPA'];
            $seleccionados = is_array($reg->inspeccion) ? $reg->inspeccion : explode(',', $reg->inspeccion);
            $selectE= '<span class="has-float-label">
            <select name="inspeccion[]" id="inspeccion" class="form-control" multiple required>';
            foreach ($opciones as $opcion) {
                $selected = in_array($opcion, $seleccionados) ? ' selected' : '';
                $selectE .= '<option value="' . $opcion . '"' . $selected . '>' . $opcion . '</option>';
            }

            $selectE .= '</select><label for="inspeccion">Inspección:</label>
                    </span>';
            $catsUser = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
            $res['results'] .= '<form class="form-up-reg" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="id" readonly="" value="'.$reg->id.'">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            '.inputSelect('id_cliente', 'Cliente:', $reg->id_cliente, $clienteOptions, ['required' => 'required']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('proyecto', 'Proyecto:', $reg->proyecto, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.$selectE .'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('desc_cant_elem', 'Cantidad Elementos:', $reg->desc_cant_elem, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputDate('fecha_inicio', 'Fecha Inspección:', $reg->fecha_inicio, ['placeholder'=>' ']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('horario', 'Horario:', $reg->horario, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('observacion', 'Observación:', $reg->observacion, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>
                        <div class="col-md-3">
                            '.inputSelect('status', 'Estado:', $reg->status,
                            ['1' => 'Confirmado',
                            '2' => 'En Proceso',
                                '4' => 'Concluido'], ['required' => 'required']).'
                        </div>

                    </div>

                    <div class="row justify-content-end">
                        <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                            <button type="submit" class="btn btn-success w-100" id="btn-up-post">
                                <i class="bi bi-check-circle"></i> Actualizar
                            </button>
                        </div>
                    </div>
                </form>';
        }else{
            $res['results'] = '<div class="alert alert-secondary text-center" role="alert"><i class="bi bi-x-circle"></i> '+
            'No se encontré el registro.</div>';
        }

        return response()->json($res);
    }

    function inputSelect($name, $label, $selected, $options, $attributes = []) 
    {
    $html = '<label for="' . $name . '">' . $label . '</label>';
    $html .= '<select name="' . $name . '"';
    
    foreach ($attributes as $key => $value) {
        $html .= ' ' . $key . '="' . $value . '"';
    }
    
    $html .= '>';
    
    foreach ($options as $value => $text) {
        $html .= '<option value="' . $value . '"';
        if (in_array($value, $selected)) {
            $html .= ' selected';
        }
        $html .= '>' . $text . '</option>';
    }
    
    $html .= '</select>';
    
    return $html;
}
    
    public function upInfoRegServicio(Request $request)
    {
        $rulesAdd = [
            'id_cliente' => 'required',
            'proyecto' => 'required|string|max:300',
            'inspeccion' => 'required',
            'desc_cant_elem' => 'required|string|max:300',
            'fecha_inicio' => 'required',
            'horario' => 'required|string|max:300',
            'observacion' => 'required|string|max:300',
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $rulesAdd, $this->errorMessagesUpProfile)->setAttributeNames($this->attributeNamesUpServicio);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $inspeccionSeleccionados = $request->input('inspeccion', []);
            $inspeccionA="";
            // Hacer algo con los valores seleccionados
            foreach ($inspeccionSeleccionados as $inspeccionId) {
                if($inspeccionA==""){
                    $inspeccionA = "$inspeccionId";
                }
                else{
                    $inspeccionA = "$inspeccionA,$inspeccionId";
                }
                
            }
            //dd($inspeccionA);

            $servicio=Servicio::find($request['id']);
            $servicio->id_cliente=$request['id_cliente'];
            $servicio->proyecto=$request['proyecto'];
            $servicio->inspeccion=$inspeccionA;
            $servicio->fecha_inicio=$request['fecha_inicio'];
            $servicio->desc_cant_elem=$request['desc_cant_elem'];
            $servicio->horario=$request['horario'];
            $servicio->observacion=$request['observacion'];
            $servicio->status=$request['status'];
            $servicio->id_usuario=auth()->user()->id;
            $servicio->update();
                $msg = ['tipo' => 'success',
                    'icon' => 'fa fa-check',
                    'msg' => 'Información actualizada.', ];
        }

        return response()->json($msg);
    }

    public function delServicio(Request $request)
    {
        $list = explode(',', $request->list);
        $edo = $request->slt_edo >= 0 ? $request->slt_edo : 1;
        $update = 0;
        foreach ($list as $id) {
            if (Cliente::where('id', $id)->update(['status' => $edo])) {
                $update++;
            }
        }
        $estados[0] = 'eliminado'.($update > 1 ? 's' : '');
        $estados[1] = 'activado'.($update > 1 ? 's' : '');
        $estados[2] = 'bloqueado'.($update > 1 ? 's' : '');
        $msg = ['tipo' => 'success',
            'icon' => 'bi bi-check-circle',
            'msg' => $update.' registro'.($update > 1 ? 's' : '').' '.$estados[$edo], ];
        return response()->json($msg);
    }

public function loadJuntas(Request $request)
    {
        $regs = Junta::where('id_servicio','=',$request->reg)->get();
        $response['data'] = '';
        if (count($regs) > 0) {
            $response['data'] .= '<div class="table-responsive"><table id="table-submodules" class="table table-submodules table-row-gray-200 align-middle kt_table_users">
                <thead>
                    <tr class="row-link">
                        <th data-field="nom" class="">Codigo</th>
                        <th data-field="desc" class=""> Inspección</th>';
            $response['data'] .= '<th data-field="url_module" class=" text-center"> Resultado</th>';
            $response['data'] .= '<th data-field="back_module_id" class=" text-center"> Fecha Reporte</th>';
            $response['data'] .= '<th data-field="show_on" class=" text-center"> Estado</th>';
            $response['data'] .= '<th data-field="show_on" class=" text-center"> Reporte</th>';
            $response['data'] .= '<th class="w-10 text-center"><i class="bi bi-check-circle"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($regs as $reg) {
                if (is_null($reg->ruta_junta)) {
                    $imageUrl = "<p>Sin Reporte.</p>";
                } else {
                    $imageUrl = "http://127.0.0.1:8000/$reg->ruta_junta";
                    $imageUrl='<a href="'.$imageUrl.'" target="_blank">Ver Archivo</a>';
                }
                
                $response['data'] .= '<tr>
                                        <td class="text-center w-3">
                                            '.$reg->codigo.'
                                        </td>
                                        <td class="">
                                            '.($reg->inspeccion).'
                                        </td>
                                        <td class="">
                                            <span class="fs-9 badge float-end bg-'.($reg->resultado == 1 ? 'success' : 'warning').'">'.($reg->resultado == 1 ? 'Conforme' : 'No Conforme').'</span>
                                        </td>';
                $response['data'] .= '<td class="text-center">'.$reg->fecha_reporte.'</td>';
                $response['data'] .= '<td class="text-center">
                                        <span class="fs-9 badge float-end bg-'.($reg->status == 1 ? 'success' : 'danger').'">'.($reg->status == 1 ? 'Activo' : 'Inactivo').'</span>
                                        </td>';
                $response['data'] .= '<td class="text-center">
                                        '.$imageUrl.'
                                        </td>';
                $response['data'] .= '<td class="text-center">';
                $response['data'] .= '<a href="'.route('editJunta', $reg).'" class="text-primary btn btn-link"><i class="text-primary fa fa-edit"></i></a>';
                $response['data'] .= '<button class="btn btn-link mdl-del-reg" data-id="'.$reg->id.'"  data-bs-toggle="modal" data-bs-target="#del-regs"><i class="text-danger bi bi-trash"></i></button>';
                $response['data'] .= '</td></tr>';
            }
            $response['data'] .= '</tbody></table></div>';
        } else {
            $response['data'] = '<div class="alert alert-dark text-center" role="alert"><i class="fas fa-search"></i> No hay registros para mostrar.</div>';
        }
        return response()->json($response);
    }
public function addJunta($id)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $reg = Servicio::join('clientes as c','c.id','=','servicios.id_cliente')
        ->select('c.id as id_cliente','c.nombre','servicios.id','servicios.ois','servicios.proyecto','servicios.inspeccion','servicios.desc_cant_elem'
        ,'servicios.status','servicios.fecha_inicio')->where('servicios.id','=',$id)->first();
        $clientes = Cliente::where('status','=','1')->get();
        $data['url'] = $url = Route::current()->getName();
        if ($url !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['reg'] = $reg;
                $data['cliente'] = $clientes;
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                $data['url'] = $data['permiso']->module->url_module;
                $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                //dd($data);
                return view('juntas/'.$url, $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }
    public function storeJunta(Request $request)
    {
        $rules = [
            'codigo' => 'required',
            'id_servicio' => 'required',
            'diametro' => 'required',
            'resultado' => 'required|string|max:300',
            'Comentarios' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules, $this->errorMessages)->setAttributeNames($this->attributeNamesAddJunta);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $junta = new Junta();
            //dd($request->input('inspeccion', []));
            $inspeccionSeleccionados = $request->input('inspeccion', []);
            $inspeccionA="";
            // Hacer algo con los valores seleccionados
            foreach ($inspeccionSeleccionados as $inspeccionId) {
                if($inspeccionA==""){
                    $inspeccionA = "$inspeccionId";
                }
                else{
                    $inspeccionA = "$inspeccionA,$inspeccionId";
                }
                
            }
            //dd($request->all());

            $junta->id_servicio=$request['id_servicio'];
            $junta->codigo=$request['codigo'];
            $junta->inspeccion=$inspeccionA;
            $junta->diametro=$request['diametro'];
            $junta->resultado=$request['resultado'];
            $junta->Comentarios=$request['Comentarios'];
            $junta->fecha_reporte=$request['fecha_reporte'];
            $junta->status=1;
            //$junta->id_usuario=auth()->user()->id;
            $junta->save();
            $msg = ['tipo' => 'success',
                'icon' => 'fa fa-check',
                'url' => route('editJunta', $junta),
                'msg' => 'Registro guardado, redireccionando', ];
        }
        return response()->json($msg);
    }

public function editJunta($id)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = $url = Route::current()->getName();
        if ($url !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                $data['url'] = $data['permiso']->module->back->url_module;
                $reg = Servicio::join('clientes as c','c.id','=','servicios.id_cliente')
        ->select('c.id as id_cliente','c.nombre','servicios.id','servicios.ois','servicios.proyecto','servicios.inspeccion','servicios.desc_cant_elem'
        ,'servicios.status','servicios.fecha_inicio')->where('servicios.id','=',$id)->first();
                if ($reg) {
                    $data['reg'] = $reg;
                    $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                    return view('juntas/'.$url, $data);
                }else{
                    redireccionar(route('dashboard'), 'Registro no encontrado.', 'danger');
                }
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }

    public function loadInfoJunta(Request $request)
    {
        $reg = Junta::find($request->reg);
        $servicio = Servicio::join('clientes as c','c.id','=','servicios.id_cliente')
        ->select('c.id as id_cliente','c.nombre','servicios.id','servicios.ois','servicios.proyecto','servicios.inspeccion','servicios.desc_cant_elem'
        ,'servicios.status','servicios.fecha_inicio')->where('servicios.id','=',$reg->id_servicio)->first();
        
        $res['results'] = '';
        if ($reg) {
            $arrayInspeccion = explode(',', $reg->inspeccion);
            $arrayInspeccion = ["RT", "UT"];
            $opciones = ['RT', 'UT', 'VT', 'PT', 'MT', 'UTPA'];
            $seleccionados = is_array($reg->inspeccion) ? $reg->inspeccion : explode(',', $reg->inspeccion);
            $selectE= '<span class="has-float-label">
            <select name="inspeccion[]" id="inspeccion" class="form-control" multiple required>';
            foreach ($opciones as $opcion) {
                $selected = in_array($opcion, $seleccionados) ? ' selected' : '';
                $selectE .= '<option value="' . $opcion . '"' . $selected . '>' . $opcion . '</option>';
            }

            $selectE .= '</select><label for="inspeccion">Inspección:</label>
                    </span>';
            $catsUser = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
            $res['results'] .= '<form class="form-up-junta" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
                        <h5>' .$servicio->nombre.' / '.$servicio->proyecto. '</h5>
                    </div>
                    <input type="hidden" id="id" name="id" readonly="" value="'.$reg->id. '">
                    
                        <div class="col-md-4 mb-3">
                            '.inputText('codigo', 'Código de Junta::', $reg->codigo, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('diametro', 'Diametro:', $reg->diametro, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-3">
                            '.inputSelect('resultado', 'Estado:', $reg->resultado,
                            ['1' => 'Conforme',
                            '2' => 'No Conforme'], ['required' => 'required']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.$selectE .'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('Comentarios', 'Comentarios:', $reg->Comentarios, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputDate('fecha_reporte', 'Fecha Reporte:', $reg->fecha_reporte, ['placeholder'=>' ']).'
                        </div>
                        
                        <div class="col-md-12 mb-2">
                                <div class="input-group custom-file-button">
                                    <input class="form-control upProfileImg" type="file" id="fileimages" name="files[]"  required="required">
                                </div>
                        </div>
                        <div class="col-md-12">
                                <div class="progress">
                                    <div id="progUpAnyImg" class="progress-bar bg-default" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                        <div id="div-cnt-reg" class="col-md-12">

                            </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                            <button type="submit" class="btn btn-success w-100" id="btn-up-post">
                                <i class="bi bi-check-circle"></i> Actualizar
                            </button>
                        </div>
                    </div>
                </form>';
        }else{
            $res['results'] = '<div class="alert alert-secondary text-center" role="alert"><i class="bi bi-x-circle"></i> '+
            'No se encontré el registro.</div>';
        }

        return response()->json($res);
    }
    public function upInfoRegJunta(Request $request)
    {
        $rulesAdd = [
            'codigo' => 'required',
            'id' => 'required',
            'diametro' => 'required',
            'resultado' => 'required|string|max:300',
            'Comentarios' => 'required'
        ];
        $validator = Validator::make($request->all(), $rulesAdd, $this->errorMessagesUpProfile)->setAttributeNames($this->attributeNamesUpJunta);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $inspeccionSeleccionados = $request->input('inspeccion', []);
            $inspeccionA="";
            // Hacer algo con los valores seleccionados
            foreach ($inspeccionSeleccionados as $inspeccionId) {
                if($inspeccionA==""){
                    $inspeccionA = "$inspeccionId";
                }
                else{
                    $inspeccionA = "$inspeccionA,$inspeccionId";
                }
                
            }
            //dd($inspeccionA);

            $junta=Junta::find($request['id']);
            $junta->codigo=$request['codigo'];
            $junta->inspeccion=$inspeccionA;
            $junta->diametro=$request['diametro'];
            $junta->resultado=$request['resultado'];
            $junta->Comentarios=$request['Comentarios'];
            $junta->fecha_reporte=$request['fecha_reporte'];
            //$junta->id_usuario=auth()->user()->id;
            $junta->Update();
            //dd($junta);
                $msg = ['tipo' => 'success',
                    'icon' => 'fa fa-check',
                    'msg' => 'Información actualizada.', ];
        }

        return response()->json($msg);
    }
    public function upFileJunta(Request $request)
    {
        //dd($request->all());
        if ($request->file) {
            $path = 'uploads/juntas/';
            
            if (! is_dir(env('pathFile').$path)) {
                mkdir(env('pathFile').$path, 0775, true);
            }

            $image = Str::random(4).'-'.Str::random(4);
            $imageName = $image.'.'.$request->file->getClientOriginalExtension();

            $request->file->move($path, $imageName);

            $datos['ruta_junta'] = $path.$imageName;
            Junta::where('id', $request->id)->update($datos);
            $msg = [
                'msg' => 'Archivo actualizada correctamente',
                'tipo' => 'success',
                'icon' => 'bi bi-check-circle',
            ];
        } else {
            $msg = ['type' => 'danger',
                'icon' => 'bi bi-x-circle',
                'msg' => 'Seleccione al menos una imagen.', ];
        }
        return response()->json($msg);
    }
    public function loadFileJunta(Request $request)
    {   //dd($request->all());    
        $junta=Junta::find($request->reg);
        //dd($junta);
        $imageUrl = "http://127.0.0.1:8000/$junta->ruta_junta";
        //dd($imageUrl);
        //$res['results'] = '<a href="'.$imageUrl.'" target="_blank">Ver Archivo</a>';
        //$res['results'] = '<iframe src="'.$imageUrl.'" style="width:100%; height:500px;" frameborder="0" allowfullscreen></iframe>';
        if (is_null($junta->ruta_junta)) {
            $res['results'] = '<p>No se ha subido ningún archivo.</p>';
        } else {
            $res['results'] = '<iframe src="'.$imageUrl.'" style="width:100%; height:500px;" frameborder="0" allowfullscreen></iframe>';
        }
        return response()->json($res);
    }
}
