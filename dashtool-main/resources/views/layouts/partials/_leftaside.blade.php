<script type="text/javascript">
subsec 	= "ini";
sec 	= "{{$tab??'inicio'}}";
</script>
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav" id="ini">
            <a class="nav-link {{($tab==='main'? 'active' :'')}}" href="{{route('home')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Seccciones</div>

            @foreach (getModules() as $permiso)
                <a class="nav-link {{$tab==$permiso->nom?' show ':'collapsed'}} {{$tab==$permiso->nom?' active ':''}}" href="#" data-bs-toggle="collapse" data-bs-target="#{{$permiso->nom}}" aria-expanded="{{$tab==$permiso->nom?' true ':'false'}}" aria-controls="{{$permiso->nom}}">
                    <div class="sb-nav-link-icon">
                        <i class="{{$permiso->icon}}"></i>
                    </div>
                    {{$permiso->desc}}
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{$tab==$permiso->nom?' show ':''}}" id="{{$permiso->nom}}" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @php
                            $inMod['mod_id'] 	= $permiso->id;
                            $inMod['show_on'] 	= ['sidebar', 'all'];
                            $subMods = getSubModules($inMod);
                        @endphp
                        @foreach ($subMods as $sub)
                            @if($tab == $permiso->nom)
                                <script type="text/javascript">subsec = "{{$permiso->nom}}";</script>
                            @endif
                            <a class="nav-link {{$url == $sub->url_module?'active':''}}" href="{{route($sub->url_module)}}">
                                <i class="{{$sub->icon}} me-1"></i> {{$sub->desc}}
                            </a>
                        @endforeach
                    </nav>
                </div>
            @endforeach
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Usuario:</div> {{auth()->user()->name}} - <span class="badge text-bg-{{auth()->user()->level->color}}">{{auth()->user()->level->nom}}</span>
    </div>
</nav>