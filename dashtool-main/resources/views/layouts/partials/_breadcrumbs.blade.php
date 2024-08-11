<ol class="breadcrumb text-muted fs-6 fw-semibold"">
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i> Inicio</a></li>
    @if(!empty($permiso->module->back))
        <li class="breadcrumb-item">
            <a href="{{route($permiso->module->back->url_module)}}">
                <i class="{{$permiso->module->back->icon}} me-1"></i> {{ $permiso->module->back->desc }}
            </a>
        </li>
    @endif
    <li class="breadcrumb-item text-muted" aria-current="page">
        <i class="text-muted {{$permiso->module->icon}} me-1"></i> {{ $permiso->module->desc }}
    </li>
</ol>
