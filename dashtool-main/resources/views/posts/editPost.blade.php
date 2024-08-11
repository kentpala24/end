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
            </ul>
    
            <div class="tab-content mb-4" id="tabs-profiles">
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    
                    <div class="row">
                        <div class="col-md-3 mt-3">
                            <div class="row">
                                <div id="div-cnt-reg" class="col-md-12">

                                </div>
                                <div class="col-md-12 mb-2">
                                    <div class="input-group custom-file-button">
                                        <input class="form-control formAddImgAny" type="file" id="fileimages" name="files[]"  required="required" multiple="multiple" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="progress">
                                        <div id="progUpAnyImg" class="progress-bar bg-default" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <form  class="form-up-pos mt-3" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$reg->id}}" readonly>
                                        <div class="row">
                                            <div class="col-md-10 mb-3">
                                                {!!inputText('title', 'Título', $reg->title, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                                            </div>
                            
                                            <div class="col-md-2">
                                                {!!inputSelect('status', 'Estado', $reg->status,
                                                ['1' => 'Activo',
                                                    '1' => 'Privado',
                                                    '2' => 'Publicado',], ['required' => 'required'])!!}
                                            </div>
        
                                            <div class="col-md-12 mb-3">
                                                {!! inputTextArea('content', 'Contenido*', old('content', $reg->content), 'bi bi-person-badge', ['placeholder'=>' ', 'class'=>'cnt-post', 'autocomplete'=>'off']) !!}
                                            </div>
                                        </div>
                                        <div class="row justify-content-end mb-6 mt-3">
                                            <div class="col-lg-3 col-md-3 col-sm-4 col-6 mt-2 mb-2">
                                                <button type="submit" class="btn btn-success w-100" id="btn-up-post">
                                                    <i class="bi bi-check-circle"></i> Actualizar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
	<script src="{{asset('assets/custom/ajx/ajxpost.js')}}"></script>
	<script>
		reg = {{$reg->id}};
        loadImagePost();

        let editor;
        ClassicEditor.create(document.querySelector('.cnt-post'), {
            ckfinder: {
                uploadUrl: base_url+'/upload',
            },
            height: '300px',
            toolbar: {
                items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'imageUpload',
                    'blockQuote',
                    'insertTable',
                    'undo',
                    'redo',
                    'alignment',
                    'fontSize'
                ]
            },
            language: 'es',
            image: {
                styles: [
                    'alignLeft', 'alignCenter', 'alignRight'
                ],
                resizeOptions: [
                    {
                        name: 'resizeImage:original',
                        label: 'Original',
                        value: null
                    },
                    {
                        name: 'resizeImage:50',
                        label: '50%',
                        value: '50'
                    },
                    {
                        name: 'resizeImage:75',
                        label: '75%',
                        value: '75'
                    }
                ],
                toolbar: [
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
                    '|',
                    'resizeImage',
                    '|',
                    'imageTextAlternative'
                ],
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
        })
        .then( newEditor => {
            window.editor = newEditor;
            editor = newEditor;
        })
        .catch( error => {
            console.error( 'Oops, something went wrong!' );
            console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
            console.warn( 'Build id: q6l505nuvif2-xw3ce1wx5aqw' );
            console.error( error );
        });
	</script>
@endsection
