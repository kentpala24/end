@extends('layouts.appDash')
@section('breadcrumb')
	@include('layouts.partials._breadcrumbs')
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-pencil-square"></i> Información de la cuenta
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div id="div-cnt-reg" class="col-md-12">

                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="input-group custom-file-button">
                                    <input class="form-control upProfileImg" type="file" id="fileimages" name="files[]"  required="required" multiple="multiple" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="progress">
                                    <div id="progUpAnyImg" class="progress-bar bg-default" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-person-circle"></i> Información de perfil
                            </div>
                            <div class="card-body">
                                <form class="form-up-account mt-3" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 mb-3">
                                            <div class="form-group input-group">
                                                <span class="has-float-label">
                                                    <input id="email" type="email" class="form-control float-form @error('email') is-invalid @enderror" placeholder=" " required="required" autocomplete="off" value="{{auth()->user()->email}}" readonly disabled/>
                                                    <label for="email">Email</label>
                                                </span>
                                            </div>
                                        </div>
        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group input-group">
                                                <span class="has-float-label">
                                                    <i class="bi bi-card-text form-icon"></i>
                                                    <input id="name" type="text" class="form-control float-form @error('name') is-invalid @enderror" placeholder=" " required="required" autocomplete="off" name="name" autofocus value="{{auth()->user()->name}}"/>
                                                    <label for="name">Nombre*</label>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                                            <button type="submit" class="btn btn-success w-100" id="btn-up-post">
                                                <i class="bi bi-check-circle"></i> Actualizar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>

                        <div class="card">
                            <div class="card-header">
                                <i class="bi bi-shield-check"></i> Actualización de contraseña
                            </div>
                            <div class="card-body">
                                <form  class="form-up-password mt-3" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="fv-row mb-0">
                                                {!! inputPassword('current_password', 'Contraseña actual: *', old('current_password', ''), 'bi bi-eye-slash', ['placeholder'=>' ', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="fv-row mb-0">
                                                {!! inputPassword('new_password', 'Contraseña nueva: *', old('new_password', ''), 'bi bi-eye-slash', ['placeholder'=>' ', 'required'=>'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="fv-row mb-0">
                                                {!! inputPassword('confirm_new_password', 'Confirmar nueva contraseña: *', old('confirm_new_password', ''), 'bi bi-eye-slash', ['class'=>'password-match', 'placeholder'=>' ', 'required'=>'required', 'data-passwd'=>'new_password']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                                            <button type="submit" class="btn btn-primary w-100" id="btn-up-passwd">
                                                <i class="bi bi-check-circle"></i> Actualizar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/account.js')}}"></script>
	<script>
		$(document).ready(function() {
			loadImageUser();
		});
	</script>
@endsection
