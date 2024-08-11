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
                    <i class="far fa-user"></i> Editar Servicio
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
                <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#modulos">
                    <i class="bi bi-boxes"></i> <span class="d-none d-md-inline-block"> Resultados Juntas</span>
                </a>
            </li>
        </ul>
    
            <div class="tab-content mb-4" id="tabs-profiles">
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="card mt-3">
                                <div id="div-cnt-profile" class="card-body"></div>
                            </div>

                        </div>
                    </div>
                </div>
    
                <div class="tab-pane fade" id="modulos" role="tabpanel">
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <div class="has-float-label">
                                <input id="filter-q" class="form-control txt-live-filter" data-table="table-submodules" name="filter-q" placeholder=" " required autocomplete="off">
                                <label for="filter-q">Buscar</label>
                                <i class="bi bi-search form-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mb-3">
                    <a href="{{route('addJunta',4)}}" class="btn btn-primary" title="Agregar registro">
                                    <i class="bi bi-plus-circle"></i> Agregar
                                </a>
                    </div>
                    <div id="div-cnt-modules" class="col-md-12"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/ajxservicios.js')}}"></script>
	<script>
		reg = {{$reg->id}};
        loadInfoServicio(reg);
        loadJuntas(reg);
	</script>
@endsection
