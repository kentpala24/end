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
        <form class="form-add-reg" method="post">
            @csrf
            <div class="row">
                <div class="col-md-10 mb-3">
                    {!!inputText('title', 'Título:', null, 'bi bi-file-text', ['required' => 'required', 'placeholder' => ' ', 'autocomplete' => 'off'])!!}
                </div>

                <div class="col-md-2">
                    {!!inputSelect('status', 'Estado:', null,
                    ['1' => 'Activo',
                        '1' => 'Privado',
                        '2' => 'Publicado',], ['required' => 'required'])!!}
                </div>
            </div>

            <div class="col-md-12 mt-3">
                {!! inputTextArea('content', 'Contenido*', old('content', ''), 'bi bi-person-badge', ['placeholder'=>' ', 'class'=>'cnt-post', 'autocomplete'=>'off']) !!}
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
	<script src="{{asset('assets/custom/ajx/ajxpost.js')}}"></script>
	<script>
		$(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let editor;
            ClassicEditor.create(document.querySelector('.cnt-post'), {
                ckfinder: {
                    uploadUrl: base_url+'/upload',
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                    // Configure the available image resize options.
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
                    // You need to configure the image toolbar, too, so it shows the new style
                    // buttons as well as the resize buttons.
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
		});
	</script>
@endsection
