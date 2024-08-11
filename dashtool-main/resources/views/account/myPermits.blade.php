@extends('layouts.appDash')
@section('breadcrumb')
	
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="bi bi-check2-circle"></i> Permisos
                </h5>
            </div>
            <div class="card-body">
                <div id="div-cnt-permits" class="row">
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/account.js')}}"></script>
	<script>
        var reg = {{auth()->user()->id}};
		$(document).ready(function() {
			loadPermits(reg);
		});
	</script>
@endsection
