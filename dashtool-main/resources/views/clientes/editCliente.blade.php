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
                    <i class="far fa-user"></i> Editar Cliente
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
	<script src="{{asset('assets/custom/ajx/ajxclientes.js')}}"></script>
	<script>
		reg = {{$reg->id}};
        loadInfoCliente(reg);

	</script>
@endsection
