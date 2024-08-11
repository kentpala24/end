@extends('layouts.appDash')

@section('content')
<div class="container-fluid mt-4">
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('home')}}">Inicio</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <div class="row">
        @foreach (auth()->user()->permits as $permiso)
            @php
            $sec['mod_id'] = $permiso->module->module_id;
            @endphp
            <div class="col-xl-3 col-md-6">
                <div class="card bg-{{$permiso->module->color}} text-white mb-4">
                    <div class="card-body">{{$permiso->module->desc}}</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{$permiso->url_module}}">
                            {{$permiso->module->desc}}
                        </a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on("click", ".btnAlerta", function (e) {
                notifyMsg('Hola mundo', '', 'success', '')
            });
        });
    </script>
@endsection