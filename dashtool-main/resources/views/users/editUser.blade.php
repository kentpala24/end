@extends('layouts.appDash')
@section('breadcrumb')
	@include('layouts.partials._breadcrumbs')
@endsection

@section('content')
@include('users.mdls')
<div id="kt_content_container" class="container-fluid p-2 mt-0 pt-0">
    <div class="card mb-3">
        <div class="card-header d-none">
            <div class="card-title m-0">
                <h6 class="fw-bolder m-0">
                    <i class="far fa-user"></i> Editar usuario
                </h6>
            </div>
        </div>
        <div class="card-body pt-4 pb-0 p-4">
            <ul class="nav nav-tabs nav-stretch nav-line-tabs nav-line-tabs-2x" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary active" data-bs-toggle="tab" href="#info">
                        <i class="fas fa-info-circle"></i> <span class="d-none d-md-inline-block">Información</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#permisos">
                        <i class="bi bi-boxes"></i> <span class="d-none d-md-inline-block">Permisos</span>
                    </a>
                </li>
            </ul>
    
            <div class="tab-content mb-4" id="tabs-profiles">
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    
                    <div class="row">
                        <div class="col-md-2">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <figure class="figure bd-placeholder-img rounded-circle"><img src="{{(!is_null($reg->avatar) && $reg->avatar!='none.png'? asset($reg->avatar) : asset('assets/custom/images/404.png'))}}" class="figure-img img-fluid img-circle bd-placeholder-img rounded-circle rounded" alt="Image"></figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="card mt-3">
                                <div id="div-cnt-profile" class="card-body"></div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <form class="form-up-password" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                        <input type="hidden" name="id" readonly="" value="{{$reg->id}}">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! inputPassword('password', 'Contraseña', old('password', ''), 'bi bi-eye-slash', ['placeholder'=>' ', 'required'=>'required']) !!}
                                            </div>
                                        
                                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mb-2">
                                                <button type="submit" class="btn btn-primary w-100" id="btn-up-passwd">
                                                    <i class="bi bi-check-circle"></i> Actualizar contraseña
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="tab-pane fade" id="permisos" role="tabpanel">
                    <div id="div-cnt-permits" class="row mt-3">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/ajxusers.js')}}"></script>
	<script>
		reg = {{$reg->id}};
        loadInfoUser(reg);

        loadPermitsUser(reg);
	</script>
@endsection
