<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="{{ asset('assets/custom/images/favicon.png')}}" type="image/png">
<meta name="csrf-token" content="{{csrf_token()}}">
<title>ENDGO</title>

<!-- bootstrap -->
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/animate.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/animation.css')}}">

<!-- /jquery -->
<script src="{{asset('assets/vendor/jquery/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<!-- bootstrap-icons -->
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css')}}">

<!-- font-awesome -->
<link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/all.css')}}">

<!-- font-awesome -->
<script src="{{asset('assets/vendor/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap-notify/message-notify.js')}}"></script>


<script src="{{asset('assets/custom/js/scripts.js')}}"></script>

<link href="{{asset('assets/custom/css/styles.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/custom/css/custom.css')}}" rel="stylesheet" type="text/css"/>

<script src="{{asset('assets/vendor/ckeditor5/build/ckeditor.js')}}" type="text/javascript"></script>

@if (auth()->check())
    <meta name="api-token" content="{{auth()->user()->api_token}}">
@endif

<style>
    .fstElement {
        font-size: 0.6em;
    }
    .fstToggleBtn {
        min-width: 2em;
    }
    .submitBtn {
        display: none;
    }
    .fstMultipleMode {
        display: block;
    }
    .fstMultipleMode .fstControls {
        width: 100%;
    }
    .multipleSelect{
        overflow-x: auto !important;
        max-height: 20px !important;
        z-index: 3898;
    }
    .google-maps iframe {
        width: 100% !important;
    }
</style>

<script>
    var base_url = "{{route('/')}}";
    var autorizadoToken = "{{ csrf_token() }}";
    var subsec 	= "start";
    var sec 	= "ini";
    var hostUrl = "assets/";
</script>
