<script src="{{asset('public/vendor/metronic/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
<script src="{{asset('public/vendor/metronic/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('public/vendor/metronic/js/scripts.bundle.js')}}"></script>

<!-- fastselect -->
<script src="{{asset('public/vendor/fastselect/fastselect.standalone.min.js')}}" type="text/javascript"></script>

<script src="{{asset('public/vendor/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('public/vendor/bootstrap-notify/message-notify.js')}}"></script>

<script src="{{ asset('public/vendor/numeric/autoNumeric.min.js')}}"></script>
<script src="{{asset('public/vendor/audio/audio.js')}}"></script>
<!-- include custom app js -->
<script src="{{ asset('public/vendor/jquery-colorbox/jquery.colorbox.js') }}" defer></script>
<script src="{{ asset('assets/js/custom.app.js')}}" type="text/javascript"></script>

<script src="{{asset('public/vendor/mask/jquery.mask.js')}}"></script>
<script src="{{asset('public/vendor/jquery-ui/jquery-ui.min.js')}}"></script>

<script src="{{asset('public/vendor/owlcarousel/owl.carousel.js')}}"></script>

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
            $('.kt_app_sidebar_menu').animate({
                scrollTop: $('#'+subsec).offset().top-500
            }, 'slow');
        }
    });
    $('.dropdown-not-close').on('click', function(event){
        event.stopPropagation();
    });
</script>
