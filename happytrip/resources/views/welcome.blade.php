<x-page-layout :class="null">
    @section('additionalstyles')
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.css') }}">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Google+Sans:400,500,700">

    <style>
        .shadow-lg.bg-white.list-group { 
            padding: 1rem 2rem; 
            margin-top: -2.5rem; 
        } 
        
        .shadow-lg.bg-white.list-group .list-item{ 
            line-height: 25px;
            border-bottom: 1px solid #ececec; 
        } 
    </style>
    @endsection

    <!-- content -->
    <div class="row m-0 top-background" id="PageTop">
        @include('welcome._navbar')

        <livewire:welcome-search />
    </div>

    @include('welcome._deals')
    @include('welcome._loyalty')
    @include('welcome._getApp')
    @include('welcome._why')

    <livewire:welcome-subscibe/>

    @section('scripts')
    <script type="text/javascript" src="{{ asset('js/moment.js') }} "></script>
    <script type="text/javascript" src="{{ asset('js/jquery.daterangepicker.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>

   
    $("#fg").click(function () {
        $("#forgotpassword-box").show();
        $("#lbt").hide();
    });

    $("#lbg").click(function () {
        $("#forgotpassword-box").hide();
        $("#lbt").show();
    });


    /************* <!-- per page -->Back to Top **********/
    var btn = $('#BackTop');

    $(window).scroll(function () {
        if ($(window).scrollTop() > 300) {
        btn.addClass('show');
        } else {
        btn.removeClass('show');
        }
    });

    btn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, '300');
    });
    

    /************* Date Picker **********/
    $('#two-inputs.Hlt').dateRangePicker(
        {
            separator : ' to ',
            getValue: function()
            {
                if ($('#checkIn').val() && $('#checkOut').val() )
                    return $('#checkIn').val() + ' to ' + $('#checkOut').val();
                else
                    return '';
            },
            setValue: function(s,s1,s2)
            {
                $('#checkIn').val(s1);
                $('#checkOut').val(s2);
                Livewire.emit('checkInCheckOutInputChanged', s1,s2) 
            }
    });


    // $('#two-inputs.Flt').dateRangePicker(
    //     {
    //         separator : ' to ',
    //         getValue: function()
    //         {
    //             if ($('#checkIn').val() && $('#checkOut').val() )
    //                 return $('#checkIn').val() + ' to ' + $('#checkOut').val();
    //             else
    //                 return '';
    //         },
    //         setValue: function(s,s1,s2)
    //         {
    //             $('#checkIn').val(s1);
    //             $('#checkOut').val(s2);
    //         }
    // });

    /********* OWL *********/
    var owl = $('.owl-carousel');
    owl.owlCarousel({
        margin: 10,
        nav: false,
        dots:false,
        loop: true,
        responsiveClass:true,
        responsive: {
        0: {
            items: 1,
            autoplay:true
        },
        600: {
            items: 3,
            autoplay:true
        },
        1000: {
            items: 4
        }
        }
    })

    // function initMap() {
    //     var input = document.getElementById('searchMapInput');
    
    //     var autocomplete = new google.maps.places.Autocomplete(input);
    // }
    </script>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap&key=AIzaSyC6tr0M0vvGTa0OS60_WnIUQycyjuwEcPg" async defer></script> -->
    @endsection

    
</x-page-layout>
