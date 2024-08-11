@extends('layouts.appDash')

@section('content')
@include('modules.mdls')

<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('home')}}">Inicio</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Módulos</li>
            </ol>
        </nav>
    </div>
</div>


<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0">
        <div class="card-title m-0">
            <h6 class="fw-bolder m-0">
                <i class="far fa-clipboard-list"></i> Modules
            </h6>
        </div>
    </div>
    <div class="card-body pt-4 pb-0">
        <form action="post" class="form-search" method="post" enctype="multipart/form-data" accept-charset="utf-8">
            <div class="row g-3">
                <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                    <button id="btn-limit" class="btn btn-default dropdown-toggle btn-block" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list"></i> <span class="d-none d-md-inline-block">10</span>
                    </button>
                    <ul class="dropdown-menu select-dropdown dropdown-limit">
                        <li>
                            <a class="dropdown-item" href="#" data-desc="10" data-icon="bi bi-list" data-edo="10" data-btn="btn-limit">10</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-desc="20" data-icon="bi bi-list" data-edo="20" data-btn="btn-limit">20</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-desc="30" data-icon="bi bi-list" data-edo="30" data-btn="btn-limit">30</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-desc="40" data-icon="bi bi-list" data-edo="40" data-btn="btn-limit">40</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-desc="50" data-icon="bi bi-list" data-edo="50" data-btn="btn-limit">50</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-7">
                    <div class="input-group mb-3">
                        <button id="btn-filter" class="btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list-task"></i> <span class="d-none d-md-inline-block">Todos</span>
                        </button>
                        <ul class="dropdown-menu select-dropdown dropdown-edo">
                            <li>
                                <a class="dropdown-item" href="#" data-desc="Todos" data-icon="bi bi-list-task" data-edo="0" data-btn="btn-filter">
                                    <i class="align-middle bi bi-list-task"></i> Todos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-desc="Activos" data-icon="bi bi-check-circle" data-edo="1" data-btn="btn-filter">
                                    <i class="align-middle bi bi-check-circle"></i> Activos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-desc="Inactivos" data-icon="bi bi-x-circle" data-edo="2" data-btn="btn-filter">
                                    <i class="align-middle bi bi-x-circle"></i> Inactivos
                                </a>
                            </li>
                        </ul>
                        <input id="txt-search" type="text" class="form-control pb-1 pt-1 border rounded-0 border-end-0 border-secondary" aria-label="Text" autocomplete="off">
                        <button type="submit" class="btn border border-secondary border-start-0 border-top border-end border-bottom btn-search">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-3 align-items-end">
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-success btn-add-module-id" title="Agregar" data-bs-toggle="modal" data-bs-target="#mdl-add-reg" data-id="0">
                                <i class="bi bi-plus-circle"></i> <span class="d-none d-md-inline-block">Agregar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-12 mb-2">
                <span id="h5-cnt-total" class="float-end"></span>
            </div>
        </div>
        <div class="row">
            <div id="div-cnt-load" class="col-md-12 mb-3"></div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="{{asset('assets/custom/ajx/ajxmodule.js')}}"></script>
    <script>
        $(document).ready(function() {
            load(1);
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on("click", ".btnAlerta", function (e) {
                notifyMsg('Hola mundo', '', 'success', '')
            });
        });
    </script>
@endsection