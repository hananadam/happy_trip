<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Trip</title>
    <link rel="shortcut icon" href="{{ asset('images/happy-trip.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@8af0edd/css/all.css" rel="stylesheet"
          type="text/css"/>


    <!-- Styles -->
    
    @if (App::isLocale('en'))
        <link rel="stylesheet" href="{{ asset('bootstrap-4.5.3-dist/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    @else
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css" integrity="sha512-3q8fi8M0VS+X/3n64Ndpp6Bit7oXSiyCnzmlx6IDBLGlY5euFySyJ46RUlqIVs0DPCGOypqP8IRk/EyPvU28mQ==" crossorigin="anonymous" />

        <link rel="stylesheet" href="{{ asset('bootstrap-4.5.3-plus-rtl-rev.1-dist/css/bootstrap-rtl.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @endif    
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> -->
    @if (App::isLocale('en'))
        <script src="{{ asset('bootstrap-4.5.3-dist/js/bootstrap.min.js') }}"></script>
    @else
      <script src="{{ asset('bootstrap-4.5.3-plus-rtl-rev.1-dist/js/bootstrap.min.js') }}"></script>

    @endif  
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script  src="http://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key=AIzaSyCGV6yk3G9FU5TeDoqTN7VUAOUsZT4YTXs">
        </script>
    @yield('additionalstyles')

    @livewireStyles 
    
</head>

<body>
{{ $slot }}
@include('common._footer')

@livewireScripts

</body>
@yield('scripts')
</html>
