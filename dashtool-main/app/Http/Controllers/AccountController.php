<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cat;
use App\Models\Module;
use App\Models\Permit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Rules\MatchPassword;

class AccountController extends Controller
{
    protected $model;
    protected $validationRules;
    protected $validationUpRules;
    protected $attributeNames;
    protected $errorMessages;
    protected $validationRulesUpPassword;
    protected $attributeNamesUpPassword;
    protected $errorMessagesUpPassword;

    public function __construct()
    {
        $this->middleware('auth');

        $this->validationRules = [
            'name' => 'required|string|max:250',
        ];
        $this->attributeNames = [
            'name' => 'nombre',
        ];
        $this->errorMessages = [
            'required' => 'El campo :attribute es obligatorio.',
        ];

        $this->validationRulesUpPassword = [
            'current_password' => ['required', 'max:20', new MatchPassword],
            'new_password' => ['required'],
            'confirm_new_password' => ['same:new_password'],
        ];
        $this->attributeNamesUpPassword = [
            'current_password' => 'contraseña actual',
            'new_password' => 'nueva contraseña',
            'confirm_new_password' => 'confirmar nueva contraseña',
        ];
        $this->errorMessagesUpPassword = [
            'required' => 'El campo :attribute es obligatorio.',
        ];
    }

    public function profile(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = Route::current()->getName();

        if ($data['url'] !== '') {
            $data['permiso'] = auth()->user()->isPermitUrl($data);
            if ($data['permiso']) {
                $data['title'] = $data['permiso']->module->desc_mod;
                $data['tab'] = $data['permiso']->parentModule->nom_mod;

                return view('account/'.$data['url'], $data);
            } else {
                redireccionar(route('dashboard'), 'Módulo no autorizado.', 'danger');
            }
        } else {
            redireccionar(route('dashboard'), 'Dirección no encontrada.', 'danger');
        }
    }

    public function myPermits(Request $request)
    {
        $data['title'] = 'Módulo';
        $data['tab'] = 'main';
        $data['url'] = Route::current()->getName();
        $data['permiso'] = auth()->user()->isPermitUrl($data);
        $data['title'] = 'Permisos';
        $data['tab'] = 'myPermits';
        return view('account/myPermits', $data);
    }

    public function loadImageUser(Request $request)
    {
        $res['results'] = '<figure class="figure bd-placeholder-img rounded-circle"><img src="'.(!is_null(auth()->user()->avatar) && auth()->user()->avatar!='none.png'? asset(auth()->user()->avatar) : asset('assets/custom/images/404.png')).'" class="figure-img img-fluid img-circle bd-placeholder-img rounded-circle rounded" alt="Image"></figure>';
        return response()->json($res);
    }

    public function upImgUser(Request $request)
    {
        if ($request->file) {
            $path = 'uploads/images/';
            
            if (! is_dir(env('pathFile').$path)) {
                mkdir(env('pathFile').$path, 0775, true);
            }

            $image = Str::random(4).'-'.Str::random(4);
            $imageName = $image.'.'.$request->file->getClientOriginalExtension();

            $request->file->move($path, $imageName);

            $datos['avatar'] = $path.$imageName;
            User::where('id', auth()->user()->id)->update($datos);
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

    public function upProfile(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules, $this->errorMessages)->setAttributeNames($this->attributeNames);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            $datos = $request->except(['id', '_token']);
            if (auth()->user()->update($datos) >= 0) {
                $msg = [
                    'tipo' => 'success',
                    'icon' => 'bi bi-check-circle',
                    'url' => route('profile'),
                    'msg' => 'Perfil actualizado',
                ];
            } else {
                $msg = [
                    'tipo' => 'danger',
                    'icon' => 'bi bi-x-circle',
                    'msg' => 'Error con el servidor de base de datos.',
                ];
            }
        }

        return response()->json($msg);
    }

    public function loadPermits(Request $request)
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

    public function asignPermit(Request $request){
        $data['status'] = ($request->status) ? $request->status : 0;
        $data['module_id'] = ($request->moduleId) ? $request->moduleId : 0;
        $data['sub_module_id'] = ($request->subModuleId) ? $request->subModuleId : 0;
        $data['user_id'] = ($request->userId) ? $request->userId : 0;
        $data['url_module'] = ($request->urlSubModule) ? $request->urlSubModule : 0;
        $data['level'] = 1;

        if ($data['status']==0) {
            if (Permit::where('sub_module_id', $data['sub_module_id'])->where('user_id', $data['user_id'])->delete()) {
                $msg = ['type' => 'success',
                    'icon' => 'bi bi-check-circle',
                    'msg' => 'Permiso revocado correctamente', ];
            }else{
                $msg = ['type' => 'danger',
                    'icon' => 'bi bi-x-circle',
                    'msg' => 'Error al revocar el permiso', ];
            }
        }else{
            if ($reg = Permit::create($data)) {
                $msg = ['type' => 'success',
                    'icon' => 'bi bi-x-circle',
                    'msg' => 'Permiso asignado correctamente.', ];
            }else{
                $msg = ['type' => 'danger',
                    'icon' => 'bi bi-x-circle',
                    'msg' => 'Error al asignar el permiso.', ];
            }
        }
        return response()->json($msg);
    }

    public function upPassword(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRulesUpPassword, $this->errorMessagesUpPassword)->setAttributeNames($this->attributeNamesUpPassword);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {
            if (auth()->user()->update(['password' => Hash::make($request->new_password)])) {
                $msg = ['tipo' => 'success',
                    'icon' => 'fa fa-check',
                    'msg' => 'Contraseña actualizada', ];
            } else {
                $msg = ['tipo' => 'danger',
                    'icon' => 'fa fa-times',
                    'msg' => 'Error interno, intenta más tarde.', ];
            }
        }

        return response()->json($msg);
    }
}
