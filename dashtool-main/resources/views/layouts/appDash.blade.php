<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        @include('layouts.partials._head')
    </head>
    <body id="sb-nav-fixed" class="sb-nav-fixed">
        @include('layouts.partials._navbar')
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                @include('layouts.partials._leftaside')
            </div>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid mt-2">
                        @yield('breadcrumb')
                        @yield('content')
                    </div>
                </main>
                @include('layouts.partials._credits')
            </div>
        </div>
        @yield('script')

        <script type="text/javascript">
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        
                var url = document.location.toString();
                if (url.match('#')) {
                    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
                }
        
                $('.nav-tabs a').on('shown.bs.tab', function (e) {
                    //window.location.hash = e.target.hash;
                    //var anchor = document.location.hash;
                    //$(window).attr('url').replace(anchor, e.target.hash);
                    let stateObj = { id: "100" };
                    window.history.replaceState(stateObj,"Page 3", e.target.hash);
                });
        
                /*$('.nav-tabs a').on('shown.bs.tab', function (e) {
                    window.location.hash = e.target.hash;
                });*/
        
                if (sec!='ini') {
                    $('.sb-nav-fixed').animate({
                        scrollTop: $('#'+subsec).offset().top-500
                    }, 'slow');
                }
            });
            $('.dropdown-not-close').on('click', function(event){
                event.stopPropagation();
            });
        </script>

        @php
		displayNotify();
		@endphp
    </body>
</html>
