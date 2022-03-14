<x-page-layout :class="'mt-0'">
  @section('additionalstyles')
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css"
    integrity="sha512-3q8fi8M0VS+X/3n64Ndpp6Bit7oXSiyCnzmlx6IDBLGlY5euFySyJ46RUlqIVs0DPCGOypqP8IRk/EyPvU28mQ=="
    crossorigin="anonymous" />
  <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Google+Sans:400,500,700">
  <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
  <script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/jquery.daterangepicker.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"
    integrity="sha512-f0VlzJbcEB6KiW8ZVtL+5HWPDyW1+nJEjguZ5IVnSQkvZbwBt2RfCBY0CBO1PsMAqxxrG4Di6TfsCPP3ZRwKpA=="
    crossorigin="anonymous"></script>
  <script src="{{ asset('js/script.js') }}"></script>

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
  
  @include('pages.common._navbar')


  <div class="modify pt-4">
    <div class="container">
      <div class="row mx-0">
        <div class="col-md-10 d-flex px-0">
          <div class="hotel-icon">
            <i class="fas fa-building"></i>
          </div>
          <div class="d-flex flex-column">
            <span class="fs14">  {{ __('SEARCH SUMMARY')}} </span>
            <h4 class="m-0">{{ $destination->name }}, {{ $destination->country->description }}</h4>
            <p class="check-in">
              <span>{{ date('d-M-Y', strtotime($checkIn)) }} </span>
              <i class="fas fa-circle"></i>
              <span class="mr-4">{{ date('d-M-Y', strtotime($checkOut)) }} </span>
              <span>{{ $guests }} {{ __('Guests')}} </b>
                <i class="fas fa-circle"></i>
                <span>{{ $roomCount }} {{ __('Room')}} </b></span>
          </div>
        </div>
        <div class="col-md-2 d-flex flex-column pt-2">
          <button class="btn btn-success" style="padding:10px 20px" data-toggle="modal"
            data-target="#modifyModal">{{ __('Modify')}}</button>
        </div>
      </div>
    </div>
  </div>

  <livewire:hotels.search-result 
  :internet="$internet" 
  :swimingpool="$swimingpool" 
  :restaurant="$restaurant" 
  :parking="$parking"
  />

 

  <div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content search-form">
      <div class="modal-header">
        <div class="col-md-6 col-xs-9 txt-left fl">
          <h2 class="modify-heading">{{ __('Modify Search')}}</h2>
        </div>
        <div class="col-md-6 col-xs-3 txt-right">
          <button class="btn btn-xs btn-danger" data-dismiss="modal" aria-label="Close">{{ __('CANCEL')}}</button>
        </div>
      </div>
      <div class="modal-body">
        <div id="hotelSearch">
            <livewire:hotels.search-result-modify 
            :destinationCode="$destination->code" 
            :destinationname="$destination->name" 
            :destinationcountry="$destination->country->description"
            :checkIn="date('d-M-Y', strtotime($checkIn))"
            :checkOut="date('d-M-Y', strtotime($checkOut))"
            :guestCount="$guests"
            />
          </div>
        </div>
      </div>
    </div>
  </div>

  @section('scripts')
    <script>

      $("#ex2").slider({});


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

        /******User Menu *****/
        $("li.user-info ").click(function(){
            $(this).toggleClass("open");
        });

    </script>

    <script type="text/javascript">
        window.onscroll = function(ev) {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                window.livewire.emit('load-more');
            }
        };
    </script>

    <script type="text/javascript">
      $(document).ready(function() {
          var map = null;
          var myMarker;
          var myLatlng;

          //marker.setMap(map);
          function initializeGMap(lat, lng) {
            myLatlng = new google.maps.LatLng(lat,lng);

            var myOptions = {
              zoom: 12,
              zoomControl: true,
              center: myLatlng,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
     
           map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            myMarker = new google.maps.Marker({
              position: myLatlng
            });
            myMarker.setMap(map);
          }

          // Re-init map before show modal
          $('#mapModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            initializeGMap(button.data('lat'), button.data('lng'));
            $("#location-map").css("width", "100%");
            $("#map_canvas").css("width", "100%");
          });

          // Trigger map resize event after modal shown
          $('#mapModal').on('shown.bs.modal', function() {
            google.maps.event.trigger(map, "resize");
            map.setCenter(myLatlng);
          });
      });

  
 

    </script>
    
  @endsection

</x-page-layout>
