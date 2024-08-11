@extends('layouts.appDash')
@section('breadcrumb')
	@include('layouts.partials._breadcrumbs')
@endsection

@section('content')
@include('users.mdls')

<div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 p-3">
        <div class="card-title m-0">
            <h6 class="fw-bolder m-0">
                <i class="far fa-clipboard-list"></i> {{$title}}
            </h6>
        </div>
    </div>
    <div class="card-body pb-0">
        <form class="form-add-junta" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-12">
                    <h5>{{$reg->nombre}} / {{$reg->proyecto}}</h5>
                </div>
                <input type="hidden" name="id_servicio" readonly="" value="{{$reg->id}}">
                <div class="col-md-3 mb-3">
                    {!!inputText('codigo', 'Código Junta:', null, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                </div>

                <div class="col-md-3 mb-3">
                    {!!inputText('diametro', 'Diametro:', null, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                </div>

                <div class="col-md-2">
                    {!!inputSelect('resultado', 'Resultado:', null,
                    ['1' => 'Conforme',
                    '0' => 'No Conforme'], ['required' => 'required'])!!}
                </div>

                <div class="col-md-3 mb-3">
                    {!!inputText('Comentarios', 'Comentarios:', null, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                </div>

                <div class="col-md-3 mb-3">
                {!!inputSelect('inspeccion[]', 'Tipo Inspección:', null,
                    ['RT' => 'RT',
                    'UT' => 'UT',
                    'VT' => 'VT',
                    'PT' => 'PT',
                    'MT' => 'MT',
                    'UTPA' => 'UTPA'], ['required' => 'required', 'multiple' => 'multiple'])!!}
                </div>

                <div class="col-md-3 mb-3">
                {!!inputDate('fecha_reporte', 'Fecha Reporte:', null, ['placeholder'=>' '])!!}
                </div>               
                
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                    <button type="submit" class="btn btn-success w-100" id="btn-add-junta">
                        <i class="bi bi-check-circle"></i> Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/ajxservicios.js')}}"></script>
	<script>
		$(document).ready(function() {
            
		});
	</script>
@endsection
