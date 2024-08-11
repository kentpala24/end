<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    protected $model;
    protected $validationRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $validationRulesAdd;

    public function __construct(Module $model)
    {
        $this->validationRulesAdd = [
            'nom' => 'required|string|max:100|unique:modules',
            'desc' => 'required|string|max:100',
            'icon' => 'required|string|max:100',
            'url_module' => 'required|string|max:100',
        ];

        $this->attributeNames = [
            'nom' => 'nombre',
            'desc' => 'descripción',
            'icon' => 'ícono',
            'url_module' => 'url',
        ];

        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
        ];
        $this->model = $model;
    }
    
    public function listModules(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = Route::current()->getName();
        if ($data['url'] !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc;
                $data['tab'] = $data['permiso']->parentModule->nom;
                $data['lvl_per'] = $data['permiso']->level;

                return view('modules/'.$data['url'], $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Permiso no encontrado.', 'danger');
        }
    }

    public function loadModules(Request $request)
    {
        $data['page'] = ($request->page) ? $request->page : 1;
        $data['order'] = ($request->order) ? $request->order : 'desc';
        $data['order_by'] = ($request->order_by) ? $request->order_by : 'id';
        $data['search'] = ($request->search) ? trim($request->search) : '';
        $data['per_page'] = ($request->limite) ? $request->limite : 10;
        $data['filter'] = ($request->filter) ? $request->filter : 0;
        $data['module_id'] = ($request->mod) ? $request->mod : 0;
        $data['adyacentes'] = 2;

        $total = $this->search($data, 1);
        $results = $this->search($data, 0);

        $total_pages = ceil($total / $data['per_page']);
        $response['total'] = $total;

        $response['data'] = '';
        if ($total > 0) {
            $response['data'] .= '<div class="table-responsive"><table id="table-users" class="table table-row-gray-200 align-middle kt_table_users">
                <thead>
                    <tr class="row-link">
                        <th class="text-left w-5">
                            <div class="form-check form-check-sm form-check-custom me-3">
                                <input class="form-check-input chk-delete-all" type="checkbox" data-kt-check="true" data-kt-check-target="#table-users .form-check-input" value="1" />
                            </div>
                        </th>
                        <th data-field="nom"  class="th-link">' . ($data['order_by'] == 'nom' ? ($data['order'] == 'desc' ? '<i class="fad fa-sort-down"></i>' : '<i class="fad fa-sort-up"></i>') : '<i class="fa fa-sort"></i>') . ' Nombre</th>
                        <th data-field="desc" class="th-link">' . ($data['order_by'] == 'desc' ? ($data['order'] == 'desc' ? '<i class="fad fa-sort-down"></i>' : '<i class="fad fa-sort-up"></i>') : '<i class="fa fa-sort"></i>') . ' Descripción</th>';
            $response['data'] .= '<th data-field="url_module" class="th-link text-center">' . ($data['order_by'] == 'url_module' ? ($data['order'] == 'desc' ? '<i class="fad fa-sort-down"></i>' : '<i class="fad fa-sort-up"></i>') : '<i class="fa fa-sort"></i>') . ' Url</th>';
            $response['data'] .= '<th data-field="show_on" class="th-link text-center">' . ($data['order_by'] == 'show_on' ? ($data['order'] == 'desc' ? '<i class="fad fa-sort-down"></i>' : '<i class="fad fa-sort-up"></i>') : '<i class="fa fa-sort"></i>') . ' Visible</th>';
            $response['data'] .= '<th class="w-10 text-center"><i class="bi bi-check-circle"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($results as $reg) {
                $response['data'] .= '<tr>
                                        <td class="text-center w-3">
                                            <div class="form-check form-check-sm form-check-custom">
                                                <input class="form-check-input chk-select-delete" type="checkbox" data-id="'.$reg->id.'" value="1" id="chk_'.$reg->id.'" name="chk_'.$reg->id.'">
                                                <label for="chk_'.$reg->id.'" class="form-check-label"> '.$reg->id.'</label>
                                            </div>
                                        </td>
                                        <td class="">
                                            '.($reg->nom).'
                                            <span class="small float-end mt-1 badge rounded-pill '.($reg->type == 'module' ? 'bg-dark' : 'bg-primary').'">'.$reg->type.'</span>
                                        </td>
                                        <td class="">
                                            <i class=" '.$reg->icon.'"></i>'.' '.$reg->desc.'
                                            <span class="badge float-end bg-'.($reg->status == 1 ? 'success' : 'danger').'">'.($reg->status == 1 ? 'Activo' : 'Inactivo').'</span>
                                        </td>';
                $response['data'] .= '<td class="text-center">'.$reg->url_module.'</td>';
                $response['data'] .= '<td class="text-center">'.$reg->show_on.'</td>';
                $response['data'] .= '<td class="text-center">';
                $response['data'] .= '<a href="'.route('editModule', $reg).'" class="text-primary btn btn-link"><i class="text-primary fa fa-edit"></i></a>';
                $response['data'] .= '<button class="btn btn-link mdl-del-reg" data-id="'.$reg->id.'" data-modid="0" data-nom="'.$reg->nom.'" data-bs-toggle="modal" data-bs-target="#del-regs"><i class="text-danger bi bi-trash"></i></button>';
                $response['data'] .= '</td></tr>';
            }
            $response['data'] .= '</tbody></table></div>';
            $response['data'] .= '<div class="border-top">'.paginate($data['page'], $total_pages, $data['adyacentes'], 'load').'</div>';
        } else {
            $response['data'] = '<div class="alert alert-dark text-center" role="alert"><i class="fas fa-search"></i> No hay registros para mostrar.</div>';
        }

        return response()->json($response);
    }

    public function editModule($id)
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
                $reg = Module::find($id);
                if ($reg) {
                    $data['reg'] = $reg;
                    $data['backs'] = Module::where('type', 'module')->where('status', 1)->where('module_id', $reg->id)->get();
                    return view('modules/'.$url, $data);
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

    public function storeModule(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRulesAdd, $this->errorMessages)->setAttributeNames($this->attributeNames);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $request['module_id'] = $request->module_id ?? 0;
            $request['type'] = $request->type ?? 'module';
            $reg = Module::create($request->all());
            $msg = ['tipo' => 'success',
                'icon' => 'fa fa-check',
                'msg' => 'Registro guardado', ];
        }

        return response()->json($msg);
    }

    public function loadSubModules(Request $request)
    {
        $module = Module::find($request->reg);
        $regs = $module->subModules;
        $response['data'] = '';
        if (count($regs) > 0) {
            $response['data'] .= '<div class="table-responsive"><table id="table-submodules" class="table table-submodules table-row-gray-200 align-middle kt_table_users">
                <thead>
                    <tr class="row-link">
                        <th data-field="id" class="text-center w-3"> #</th>
                        <th data-field="nom" class=""> Nombre</th>
                        <th data-field="desc" class=""> Descripción</th>';
            $response['data'] .= '<th data-field="url_module" class=" text-center"> Url</th>';
            $response['data'] .= '<th data-field="back_module_id" class=" text-center"> Back url</th>';
            $response['data'] .= '<th data-field="show_on" class=" text-center"> Visible</th>';
            $response['data'] .= '<th class="w-10 text-center"><i class="bi bi-check-circle"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($regs as $reg) {
                $response['data'] .= '<tr>
                                        <td class="text-center w-3">
                                            '.$reg->id.'
                                        </td>
                                        <td class="">
                                            '.($reg->nom).'
                                            <span class="small float-end mt-1 badge rounded-pill '.($reg->type == 'module' ? 'bg-dark' : 'bg-primary').'">'.$reg->type.'</span>
                                        </td>
                                        <td class="">
                                            <i class="'.$reg->icon.'"></i>'.' '.$reg->desc.'
                                            <span class="fs-9 badge float-end bg-'.($reg->status == 1 ? 'success' : 'danger').'">'.($reg->status == 1 ? 'Activo' : 'Inactivo').'</span>
                                        </td>';
                $response['data'] .= '<td class="text-center">'.$reg->url_module.'</td>';
                $response['data'] .= '<td class="text-center">'.($reg->back ? $reg->back->desc : '').'</td>';
                $response['data'] .= '<td class="text-center">'.$reg->show_on.'</td>';
                $response['data'] .= '<td class="text-center">';
                $response['data'] .= '<button type="button" class="btn btn-link mdl-up-reg btn-sm" title="Editar" data-bs-toggle="modal" data-bs-target="#mdl-up-sub-module" data-idreg="' . $reg->id . '" data-modid="' . $reg->module_id . '"><i class="fa fa-edit text-primary"></i></button>';
                $response['data'] .= '<button class="btn btn-link mdl-del-reg" data-id="'.$reg->id.'" data-modid="'.$reg->module_id.'" data-nom="'.$reg->nom.'" data-bs-toggle="modal" data-bs-target="#del-regs"><i class="text-danger bi bi-trash"></i></button>';
                $response['data'] .= '</td></tr>';
            }
            $response['data'] .= '</tbody></table></div>';
        } else {
            $response['data'] = '<div class="alert alert-dark text-center" role="alert"><i class="fas fa-search"></i> No hay registros para mostrar.</div>';
        }
        return response()->json($response);
    }

    public function loadInfoModule(Request $request)
    {
        $data['id'] = $request->reg ? $request->reg : 0;
        $reg = Module::find($data['id']);
        $res['results'] = '';
        if ($reg) {
            $res['results'] .= '<form class="form-up-reg" method="post" accept-charset="utf-8" enctype="multipart/form-data">
									<input type="hidden" name="id" readonly="" value="'.$reg->id.'">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-6 mb-3">'.
                                            inputText('nom', 'Nombre: *', old('nom', $reg->nom), 'bi bi-card-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                        </div>
                                        <div class="col-lg-3 col-md-4 mb-3">'.
                                            inputText('desc', 'Descripción: *', old('desc', $reg->desc), 'bi bi-card-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                        </div>

                                        <div class="col-lg-3 col-md-2 col-sm-6 col-6 mb-3">'.
                                            inputText('icon', 'Ícono:', old('icon', $reg->icon), $reg->icon, ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                        </div>

                                        <div class="col-lg-2 mb-3">
                                            '.inputSelect('type', 'Tipo:', $reg->type,
                                                ['module' => 'Módulo',
                                                    'widget' => 'Widget',], ['required' => 'required']).'
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">'.
                                            inputText('url_module', 'Url:', old('url_module', $reg->url_module), 'bi bi-link', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                        </div>

                                        <div class="col-lg-2 mb-3">
                                        '.inputSelect('color', 'Color:', $reg->color,
                                                ['info' => 'info',
                                                    'danger' => 'danger',
                                                    'warning' => 'warning',
                                                    'success' => 'success',
                                                    'primary' => 'primary',
                                                    'secondary' => 'secondary',
                                                    'dark' => 'dark', ], ['required' => 'required']).'
                                        </div>
                                        <div class="col-lg-2 mb-3">
                                        '.inputSelect('show_on', 'Mostrar en:', $reg->show_on,
                                                        ['none' => 'none',
                                                            'panel' => 'panel',
                                                            'sidebar' => 'sidebar',
                                                            'all' => 'all',
                                                            'left' => 'left',
                                                            'navbar' => 'navbar',
                                                            'right' => 'right', ], ['required' => 'required']).'
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-lg-2 col-md-3 col-sm-4 col-4 mt-2 mb-2">
                                            <button type="submit" class="btn btn-success btn-block" id="btn-up-reg">
                                                <i class="bi bi-check-circle"></i> Actualizar
                                            </button>
                                        </div>
                                    </div>
								</form>';
        } else {
            $res['results'] = '<div class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle"></i> No se encontró el registro.</div>';
        }

        return response()->json($res);
    }

    public function upInfoModule(Request $request)
    {
        $rulesAdd = [
            'nom' => 'required|string|max:40|unique:modules,nom,'.$request->id.',id',
            'desc' => 'required|string|max:100',
            'icon' => 'required|string|max:40',
            'url_module' => 'required|string|max:40',
        ];
        $validator = Validator::make($request->all(), $rulesAdd, $this->errorMessages)->setAttributeNames($this->attributeNames);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $datos = $request->except('id');
            $request['mod'] = $request->module_id ?? 0;
            $request['type'] = $request->type ?? 'module';
            if (Module::where('id', $request->id)->update($datos) >= 0) {
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

    public function loadInfoSubModule(Request $request)
    {
        $data['id_mod'] = $request->reg ? $request->reg : 0;
        $reg = Module::find($data['id_mod']);
        $res['results'] = '';
        if ($reg) {
            $backs = Module::where('type', 'module')->where('module_id', $reg->module_id)->get()->pluck('desc', 'id')->prepend('-- Ninguno --', '')->toArray();
            $res['results'] .= '<input type="hidden" name="id" readonly="" value="'.$reg->id.'">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-6 mb-3">'.
                                        inputText('nom', 'Nombre: *', old('nom', $reg->nom), 'bi bi-card-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                    </div>
                                    <div class="col-lg-3 col-md-4 mb-3">'.
                                        inputText('desc', 'Descripción: *', old('desc', $reg->desc), 'bi bi-card-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-6 col-6 mb-3">'.
                                        inputText('icon', 'Ícono:', old('icon', $reg->icon), $reg->icon, ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                    </div>
                                
                                    <div class="col-lg-2 mb-3">
                                    '.inputSelect('type', 'Tipo:', $reg->type,
                                            ['module' => 'module',
                                                'widget' => 'widget', ], ['class' => 'slt-tipo', 'required' => 'required']).'
                                    </div>
                                
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">'.
                                        inputText('url_module', 'Url:', old('url_module', $reg->url_module), 'bi bi-link', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off']).'
                                    </div>

                                    <div class="col-lg-2 t-modules mb-3">
                                    '.inputSelect('color', 'Color:', $reg->color,
                                                    ['info' => 'info',
                                                        'danger' => 'danger',
                                                        'warning' => 'warning',
                                                        'success' => 'success',
                                                        'primary' => 'primary',
                                                        'secondary' => 'secondary',
                                                        'dark' => 'dark', ], ['class' => 't-module-a', 'required' => 'required']).'
                                    </div>
                                    <div class="col-lg-3 t-modules mb-3">
                                    '.inputSelect('show_on', 'Mostrar en:', $reg->show_on,
                                                            ['none' => 'none',
                                                                'panel' => 'panel',
                                                                'sidebar' => 'sidebar',
                                                                'all' => 'all',
                                                                'left' => 'left',
                                                                'navbar' => 'navbar',
                                                                'right' => 'right', ], ['class' => 't-module-a', 'required' => 'required']).'
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        '.inputSelect('back_module_id', 'Atrás:', $reg->back_module_id, $backs).'
                                    </div>
                                </div>';
        } else {
            $res['results'] = '<div class="alert alert-danger text-center" role="alert"><i class="bi bi-x-circle"></i> No se encontró el registro.</div>';
        }

        return response()->json($res);
    }

    public function delModule(Request $request)
    {
        $list = explode(',', $request->list);
        $edo = $request->slt_edo >= 0 ? $request->slt_edo : 1;
        $update = 0;
        foreach ($list as $id) {
            if (Module::where('id', $id)->update(['status' => $edo])) {
                $update++;
            }
        }
        $estados[0] = 'eliminado'.($update > 1 ? 's' : '');
        $estados[1] = 'activado'.($update > 1 ? 's' : '');
        $estados[2] = 'desactivado'.($update > 1 ? 's' : '');
        $estados[3] = 'baneado'.($update > 1 ? 's' : '');
        $msg = ['tipo' => 'success',
            'icon' => 'bi bi-check-circle',
            'msg' => $update.' registro'.($update > 1 ? 's' : '').' '.$estados[$edo], ];

        return response()->json($msg);
    }

    private function search($data, $mode)
    {
        $query = $this->model;

        if ($data['filter'] > 0) {
            $query = $query->where('status', $data['filter']);
        } else {
            $query = $query->where('status', '>', 0);
        }

        $query = $query->where('module_id', $data['module_id']);

        $words = splitWordSearch($data['search']);
        if ($words) {
            $query = $query->where(function (Builder $q) use ($words) {
                
                foreach ($words as $word) {
                    $q->whereAny([
                        'nom',
                        'desc',
                        'type',
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
