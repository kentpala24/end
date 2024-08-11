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
        <form class="form-add-reg" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-4 mb-3">
                    {!!inputEmail('email', 'Email:', null, 'bi bi-at', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                </div>

                <div class="col-md-3 mb-3">
                    {!!inputText('name', 'Nombre:', null, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                </div>

                <div class="col-md-2">
                    {!!inputSelect('status', 'Estado:', null,
                    ['1' => 'Activo',
                        '2' => 'Bloqueado',
                        '3' => 'Baneado',
                        '0' => 'Eliminado'], ['required' => 'required'])!!}
                </div>

                <div class="col-lg-2 col-md-2 col-sm-6 col-6">
                    {!!inputDate('created_at', 'Registro:', null, ['placeholder'=>' '])!!}
                </div>

                <div class="col-md-2">
                    {!!inputSelect('level_cat_id', 'Tipo:', null, $catsUser, ['class' => '', 'required' => 'required'])!!}
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                    <button type="submit" class="btn btn-success w-100" id="btn-add-reg">
                        <i class="bi bi-check-circle"></i> Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/ajxusers.js')}}"></script>
	<script>
		$(document).ready(function() {
            
		});
	</script>
@endsection
