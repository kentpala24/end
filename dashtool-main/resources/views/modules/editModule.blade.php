@extends('layouts.appDash')

@section('breadcrumb')
	@include('layouts.partials._breadcrumbs')
@endsection

@section('content')
@include('modules.mdls')

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 p-3 d-none">
        <div class="card-title m-0">
            <h6 class="fw-bolder m-0">
                <i class="far fa-clipboard-list"></i> {{$title}}
            </h6>
        </div>
    </div>
    <div class="card-body pb-0">
        <ul class="nav nav-tabs nav-stretch nav-line-tabs nav-line-tabs-2x" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary active" data-bs-toggle="tab" href="#info">
                    <i class="fas fa-info-circle"></i> <span class="d-none d-md-inline-block">Información</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary" data-bs-toggle="tab" href="#modulos">
                    <i class="bi bi-boxes"></i> <span class="d-none d-md-inline-block">Sub-módulos</span>
                </a>
            </li>
        </ul>

        <div class="tab-content" id="tabs-profiles">
            <div class="tab-pane fade show active" id="info" role="tabpanel">    
                <div class="row">

                    <div id="div-cnt-profile" class="col-md-12 mt-3"></div>
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
                        <button type="button" class="btn btn-primary btn-add-module-id float-end" data-id="{{$reg->id}}" title='Agregar Sub-Sección' data-bs-toggle="modal" data-bs-target="#mdl-add-reg">
                            <i class="bi bi-plus-circle"></i> Agregar
                        </button>
                    </div>
                    <div id="div-cnt-modules" class="col-md-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/ajxmodule.js')}}"></script>
	<script>
		$(document).ready(function() {
            reg = {{$reg->id}};
            loadInfoReg(reg);
            loadSubModules(reg);
		});
	</script>
@endsection
