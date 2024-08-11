<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use App\Models\Cat;
use App\Models\Permit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $model;
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $validationRulesUpProfile;
    protected $attributeNamesUpProfile;
    protected $errorMessagesUpProfile;
    protected $validationRulesUpPassword;
    protected $attributeNamesUpPassword;
    protected $errorMessagesUpPassword;
    protected $validationRulesAddUser;
    protected $attributeNamesAddUser;

    public function __construct(User $model)
    {
        $this->validationRulesUpProfile = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:users',
            'cta_id' => ['required', 'integer'],
        ];
        $this->attributeNamesUpProfile = [
            'nombre' => 'nombre/área',
            'email' => 'correo electrónico',
            'cta_id' => 'oficina',
            'client_id' => 'cliente',
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
        $this->attributeNamesAddUser = [
            'name' => 'nombre o área',
            'email' => 'email',
            'cta_id' => 'oficina',
            'tipo_emp' => 'tipo usuario',
            'lvl_id' => 'nivel',
            'client_id' => 'cliente',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->model = $model;
    }

    public function listUsers(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = Route::current()->getName();
        if ($data['url'] !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;

                return view('users/'.$data['url'], $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Permiso no encontrado.', 'danger');
        }
    }

    public function editUser($id)
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
                $reg = User::find($id);
                
                if ($reg) {
                    $data['reg'] = $reg;
                    $data['catsUser'] = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
                    
                    return view('users/'.$url, $data);
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

    public function addUser(Request $request)
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
                
                return view('users/'.$url, $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }

    public function storeUser(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:300',
            'email' => 'required|string|max:100|unique:users,email,NULL,id',
            'level_cat_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules, $this->errorMessages)->setAttributeNames($this->attributeNamesAddUser);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $request['user_id'] = auth()->user()->id;

            $request['password'] = Hash::make($request->password);

            $user = User::create($request->all());
            $msg = ['tipo' => 'success',
                'icon' => 'fa fa-check',
                'url' => route('editUser', $user),
                'msg' => 'Registro guardado, redireccionando', ];
        }
        return response()->json($msg);
    }

    public function loadInfoUser(Request $request)
    {
        $reg = User::find($request->reg);
        $res['results'] = '';
        if ($reg) {
            $catsUser = Cat::where('status', 1)->where('filter_on', 'users')->orderBy('nom', 'asc')->get()->pluck('nom', 'id')->toArray();
            $res['results'] .= '<form class="form-up-reg" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="id" readonly="" value="'.$reg->id.'">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-4 mb-3">
                            '.inputEmail('email', 'Email:', $reg->email, 'bi bi-at', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-4 mb-3">
                            '.inputText('name', 'Nombre:', $reg->name, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                        </div>

                        <div class="col-md-3">
                            '.inputSelect('status', 'Estado:', $reg->status,
                            ['1' => 'Activo',
                                '2' => 'Bloqueado',
                                '3' => 'Baneado',
                                '0' => 'Eliminado'], ['required' => 'required']).'
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-6 col-6">
                            '.inputDate('created_at', 'Registro:', fecha($reg->created_at, 'Y-m-d'), ['placeholder'=>' ']).'
                        </div>

                        <div class="col-md-3">
                            '.inputSelect('level_cat_id', 'Tipo:', $reg->level_cat_id, $catsUser, ['class' => '', 'required' => 'required']).'
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

    public function upInfoReg(Request $request)
    {
        $rulesAdd = [
            'name' => 'required|string|max:300',
            'email' => 'required|string|max:100|unique:users,email,' . $request->id,
        ];
        $validator = Validator::make($request->all(), $rulesAdd, $this->errorMessagesUpProfile)->setAttributeNames($this->attributeNamesUpProfile);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $datos = $request->except('id');
            if (User::where('id', $request->id)->update($datos) >= 0) {
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

    public function loadPermitsUser(Request $request)
    {
        $data['user'] = ($request->reg) ? $request->reg : 0;
        $modules = Module::where('status', 1)->where('module_id', 0)->get();
        $res['results'] = '';
        if ($modules) {
            foreach ($modules as $module) {
                $res['results'] .= '
                <div class="col-md-3"><div class="card">
                    <div class="card-header">
                        <i class="'.$module->icon.'"></i> '.$module->desc.'
                        </div>
                        <div class="card-body">';
                $res['results'] .= '<ul class="list-group">';
                foreach ($module->subModules as $subModule) {
                    $userPermit = Permit::where('sub_module_id', $subModule->id)->where('user_id', $data['user'])->first();
                    $res['results'] .= '<li class="list-group-item">
                        <div class="form-check form-switch">
                            <input class="form-check-input add-permit" type="checkbox" role="switch" id="permit-'.$subModule->id.'" data-sub="'.$subModule->id.'" data-moduleId="'.$module->id.'" data-subModuleId="'.$subModule->id.'" data-userId="'.$data['user'].'" '.($userPermit?'checked="checked"':"").' data-urlSubModule="'.$subModule->url_module.'">
                            <label class="form-check-label" for="permit-'.$subModule->id.'">
                               <span class="badge small fw-light text-bg-'.$subModule->color.'">'.$subModule->type.'</span> '.$subModule->desc.'
                            </label>
                            <span id="span-'.$subModule->id.'" class="float-end"></span>
                        </div>
                    </li>';
                }
                $res['results'] .= '</ul>';
                $res['results'] .= '</div></div></div>';
            }
        }else{
            $res['results'] = '<div class="alert alert-dark text-center" role="alert"><i class="fas fa-search"></i> No hay registros para mostrar.</div>';
        }
        
        return response()->json($res);
    }

    public function upPasswordUser(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRulesUpPassword, $this->errorMessagesUpPassword)->setAttributeNames($this->attributeNamesUpPassword);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            if (User::where('id', $request->id)->update(['password' => Hash::make($request->password)])) {
                $msg = [
                    'tipo' => 'success',
                    'icon' => 'fa fa-check',
                    'msg' => 'Contraseña actualizada',
                ];
            } else {
                $msg = [
                    'tipo' => 'danger',
                    'icon' => 'fa fa-times',
                    'msg' => 'Error interno, intenta más tarde.',
                ];
            }
        }
        return response()->json($msg);
    }

    public function loadUsers(Request $request)
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
            $response['data'] .= '<div class="table-responsive"><table id="table-users" class="table table-row-gray-200  kt_table_users">
                <thead>
                    <tr class="row-link">
                        <th class="text-left w-5">
                            <div class="form-check form-check-sm form-check-custom me-3">
                                <input class="form-check-input chk-delete-all" type="checkbox" data-kt-check="true" data-kt-check-target="#table-users .form-check-input" value="1" />
                            </div>
                        </th>
                        <th data-field="name"  class="th-link"><i class="bi bi-sort-down"></i> Nombre</th>
                        <th data-field="status" class="th-link w-7 text-center"><i class="bi bi-sort-down"></i> Estado</th>';
            
            $response['data'] .= '<th data-field="email" class="th-link"><i class="bi bi-sort-down"></i> Email</th>';

            

            $response['data'] .= '<th class="w-10 text-center"><i class="bi bi-ui-checks"></i> Permisos</th>
                        <th class="w-10 text-center"><i class="bi bi-check-circle"></i> Acciones</th>
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
                                            <a href="' . route('editUser', $reg) . '">
                                                <img class="rounded-circle" src="'.($reg->avatar!='none.png'? $reg->avatar : 'public/assets/custom/images/404.png' ).'" alt="" width="32" height="32" ' . '/>
                                            </a>
                                            <a href="' . route('editUser', $reg) . '" class="text-gray-800 text-hover-primary mb-1">
                                                ' . $reg->name . '
                                                <i class="'.$reg->level->icon.' text-'.$reg->level->color.' float-end"></i>
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
                $response['data'] .= $reg->email;
                $response['data'] .= '</td>';

                
                $response['data'] .= '<td class="text-center">';
                if ($reg->user_id == auth()->user()->id) {
                    $response['data'] .= '<a href="' . route('editUser', $reg) . '#permisos">' . count($reg->permisos) . (count($reg->permisos) == 1 ? ' permiso' : ' permisos') . '</a>';
                } else {
                    $response['data'] .= '' . count($reg->permisos) . (count($reg->permisos) == 1 ? ' permiso' : ' permisos');
                }
                $response['data'] .= '</td><td class="text-center">';
                
                $response['data'] .= '<a href="'.route('editUser', $reg).'" class="text-primary btn btn-link"><i class="text-primary fa fa-edit"></i></a>';
                $response['data'] .= '<button class="btn btn-link mdl-del-reg" data-id="'.$reg->id.'" data-nom="'.$reg->name.'" data-bs-toggle="modal" data-bs-target="#del-regs"><i class="text-danger bi bi-trash"></i></button>';
                $response['data'] .= '</td></tr>';
            }
            $response['data'] .= '</tbody></table></div>';
            $response['data'] .= '<div class="border-top">'.paginate($data['page'], $total_pages, $data['adyacentes'], 'load').'</div>';
        } else {
            $response['data'] = '<div class="alert alert-dark text-center" role="alert"><i class="fas fa-search"></i> No hay registros para mostrar.</div>';
        }

        return response()->json($response);
    }

    public function delUser(Request $request)
    {
        $list = explode(',', $request->list);
        $edo = $request->slt_edo >= 0 ? $request->slt_edo : 1;
        $update = 0;
        foreach ($list as $id) {
            if (User::where('id', $id)->update(['status' => $edo])) {
                $update++;
            }
        }
        $estados[0] = 'eliminado'.($update > 1 ? 's' : '');
        $estados[1] = 'activado'.($update > 1 ? 's' : '');
        $estados[2] = 'bloqueado'.($update > 1 ? 's' : '');
        $estados[3] = 'baneado'.($update > 1 ? 's' : '');
        $msg = ['tipo' => 'success',
            'icon' => 'bi bi-check-circle',
            'msg' => $update.' registro'.($update > 1 ? 's' : '').' '.$estados[$edo], ];
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
        $query = $query->where('id', '!=', auth()->user()->id);

        $query = $query->where('level_cat_id', '<', 3);

        $words = splitWordSearch($data['search']);
        if ($words) {
            $query = $query->where(function (Builder $q) use ($words) {
                foreach ($words as $word) {
                    $q->whereAny([
                        'name',
                        'email',
                        'level_cat_id',
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

        return $query;
    }
}
