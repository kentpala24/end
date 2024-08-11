<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Module;
use App\Models\Cat;
use App\Models\Permit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    protected $model;
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $validationRulesUpProfile;
    protected $attributeNamesUpCliente;
    protected $errorMessagesUpProfile;
    protected $validationRulesUpPassword;
    protected $attributeNamesUpPassword;
    protected $errorMessagesUpPassword;
    protected $validationRulesAddUser;
    protected $attributeNamesAddCliente;

    public function __construct(Cliente $model)
    {
        $this->validationRulesUpProfile = [
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|max:255',
        ];
        $this->attributeNamesUpCliente = [
            'nombre' => 'nombre de Empresa',
            'ruc' => 'ruc',
            'direccion' => 'direccion',
            'telefono' => 'telefono',
            'observacion' => 'observacion',
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
        $this->attributeNamesAddCliente = [
            'nombre' => 'nombre de Empresa',
            'ruc' => 'ruc',
            'direccion' => 'direccion',
            'telefono' => 'telefono',
            'observacion' => 'observacion',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->model = $model;
    }

    public function listCliente(Request $request)
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
                return view('clientes/'.$data['url'], $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Permiso no encontrado.', 'danger');
        }
    }

    public function loadClientes(Request $request)
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
            $response['data'] .= '<div class="table-responsive"><table id="table-clientes" class="table table-row-gray-200  kt_table_clientes">
                <thead>
                    <tr class="row-link">
                        <th class="text-left w-5">
                            <div class="form-check form-check-sm form-check-custom me-3">
                                <input class="form-check-input chk-delete-all" type="checkbox" data-kt-check="true" data-kt-check-target="#table-clientes .form-check-input" value="1" />
                            </div>
                        </th>
                        <th data-field="name"  class="th-link"><i class="bi bi-sort-down"></i> Nombre</th>
                        <th data-field="status" class="th-link w-7 text-center"><i class="bi bi-sort-down"></i> Estado</th>';
            
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Ruc</th>';
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Dirección</th>';

            

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
                                            
                                            <a href="' . route('editCliente', $reg) . '" class="text-gray-800 text-hover-primary mb-1">
                                                ' . $reg->nombre . '
                                                <i class="bi bi-building text-danger float-end"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">';
                switch ($reg->status) {
                    case 1:
                        $response['data'] .= '<span class="fs-9 badge text-bg-success">Activo</span>';
                        break;
                    case 2:
                        $response['data'] .= '<span class="fs-9 badge text-bg-danger">Bloqueado</span>';
                        break;
                    case 3:
                        $response['data'] .= '<span class="fs-9 badge text-bg-warning">Baneado</span>';
                        break;
                    default:
                        $response['data'] .= '<span class="fs-9 badge text-bg-secondary">Eliminado</span>';
                        break;
                }

                $response['data'] .= '</td>
                                    <td>';
                $response['data'] .= $reg->ruc;
                $response['data'] .= '</td>
                                    <td>';
                $response['data'] .= $reg->direccion;

                $response['data'] .= '</td><td class="text-center">';
                
                $response['data'] .= '<a href="'.route('editCliente', $reg).'" class="text-primary btn btn-link"><i class="text-primary fa fa-edit"></i></a>';
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

    public function editCliente($id)
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
                $reg = Cliente::find($id);
                if ($reg) {
                    $data['reg'] = $reg;
                    $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                    return view('clientes/'.$url, $data);
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

    public function addCliente(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = $url = Route::current()->getName();
        if ($url !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                $data['url'] = $data['permiso']->module->url_module;
                $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                return view('clientes/'.$url, $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }
    public function storeCliente(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|max:300',
            'ruc' => 'required|string|max:300',
            'direccion' => 'required|string|max:300',
            'telefono' => 'required|string|max:300',
            'observacion' => 'required|string|max:300',
        ];

        $validator = Validator::make($request->all(), $rules, $this->errorMessages)->setAttributeNames($this->attributeNamesAddCliente);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            
            $cliente = new Cliente();
            $cliente->nombre=$request['nombre'];
            $cliente->ruc=$request['ruc'];
            $cliente->direccion=$request['direccion'];
            $cliente->telefono=$request['telefono'];
            $cliente->observacion=$request['observacion'];
            $cliente->status=$request['status'];
            $cliente->save();
            $msg = ['tipo' => 'success',
                'icon' => 'fa fa-check',
                'url' => route('editCliente', $cliente),
                'msg' => 'Registro guardado, redireccionando', ];
        }
        return response()->json($msg);
    }
    private function search($data, $mode)
    {
        $query = $this->model;
        if ($data['act_fc'] == 1) {
            $query = $query->whereBetween('created_at', [$data['dt_ini'], $data['dt_fin']]);
        }

        if ($data['filter'] > 0) {
            $query = $query->where('status', $data['filter']);
        } else {
            $query = $query->where('status', '>', 0);
        }

        $words = splitWordSearch($data['search']);
        if ($words) {
            $query = $query->where(function (Builder $q) use ($words) {
                foreach ($words as $word) {
                    $q->whereAny([
                        'nombre',
                        'ruc',
                        'direccion',
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
    public function loadInfoCliente(Request $request)
    {
        $reg = Cliente::find($request->reg);
        $res['results'] = '';
        if ($reg) {
            $catsUser = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
            $res['results'] .= '<form class="form-up-reg" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="id" readonly="" value="'.$reg->id.'">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            '.inputText('nombre', 'Cliente:', $reg->nombre, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('ruc', 'Ruc:', $reg->ruc, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('direccion', 'Dirección:', $reg->direccion, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('telefono', 'Teléfono:', $reg->telefono, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('observacion', 'Observación:', $reg->observacion, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>
                        <div class="col-md-3">
                            '.inputSelect('status', 'Estado:', $reg->status,
                            ['1' => 'Activo',
                             '2' => 'Bloquear',
                                '0' => 'Eliminado'], ['required' => 'required']).'
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

    public function upInfoRegCli(Request $request)
    {
        $rulesAdd = [
            'nombre' => 'required|string|max:300',
            'ruc' => 'required|string|max:300',
            'direccion' => 'required|string|max:300',
            'telefono' => 'required|string|max:300',
            'observacion' => 'required|string|max:300',
        ];
        $validator = Validator::make($request->all(), $rulesAdd, $this->errorMessagesUpProfile)->setAttributeNames($this->attributeNamesUpCliente);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $datos = $request->except('id');
            if (Cliente::where('id', $request->id)->update($datos) >= 0) {
                $msg = ['tipo' => 'success',
                    'icon' => 'fa fa-check',
                    'msg' => 'Información actualizada.', ];
            } else {
                $msg = ['tipo' => 'danger',
                    'icon' => 'fa fa-times',
                    'msg' => 'Error interno, intenta más tarde.', ];
            }
        }

        return response()->json($msg);
    }

    public function delCliente(Request $request)
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



}
