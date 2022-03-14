<x-page-layout :class="'mt-0'">
  @section('additionalstyles')

  
    <!-- Owl Stylesheets -->
  <link rel="stylesheet" href="{{asset('owl/owlcarousel/assets/owl.carousel.min.css')}}">
  <link rel="stylesheet" href="{{asset('owl/owlcarousel/assets/owl.theme.default.min.css')}}">
  <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="{{asset('css/style.css')}}" />
  <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Google+Sans:400,500,700">

 
  <script src="{{asset('owl/owlcarousel/owl.carousel.js')}}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  @endsection
  
  @include('pages.common._navbar')
 
<?php 
   // dd($data->facilities);
    //dd($data,$avhotels);
    //dd($data,$reservationDetails->checkIn);
    // dd($data['destination']['name']['content']); 
?> 
  <div class="sub-header">
    <div class="sub-header-info">
        <div class="container">
            <span class="fl">Happy Trip</span>
            <span class="fas fa-circle text-success fs12 fl navigation-circle-green"></span><span class="fl"> {{ __('Hotel Booking')}}</span>
            <span class="fas fa-circle fs12 fl navigation-circle-pink"></span><span class="fl">{{ $data->name }}</span>
            <span class="fas fa-circle text-warning fs12 fl navigation-circle-yellow"></span><span class="fl">{{ $data->destinationName }}         {{Str::limit($data->description,100  )}} </span>
        </div>
    </div>
  </div>


  <div class="row mx-0 sliderMap">
    <div class="col-md-8 px-0">
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          @if(!empty($data->image))
          <div class="carousel-item active">
            <img class="d-block w-100" src="http://photos.hotelbeds.com/giata/original/{{ $data->image[0]['path'] }}" alt="First slide">
          </div>
          @foreach(array_slice($data->image,1) as $key=>$image)
          <div class="carousel-item">
            <img class="d-block w-100" src="http://photos.hotelbeds.com/giata/original/{{ $image['path'] }}" alt="">
          </div>
          @endforeach
          @endif
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">{{ __('Previous')}}</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">{{ __('Next')}}</span>
        </a>
      </div>
    </div>
    <div class="col-md-4 px-0">
       <iframe src="https://maps.google.com/maps?q={{$data->latitude}}, {{$data->longitude}}&z=15&output=embed" style="width: 100%;" height="500" frameborder="0" style="border:0;" allowfullscreen=""
        aria-hidden="false" tabindex="0"></iframe>
    </div>
  </div>
  <div class="container card main-hotel-card pt-4 pl-4 pb-2">
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 room-data">
          <div class="col-pics px-3">
            
            <div class="row">
                <div class="large-12 columns " style="overflow: hidden;">
                  <div class="owl-carousel owl-theme">
                    <!-- <img src="{{asset('images/108694a_hb_a_030.jpg') }}"> -->

                    @if(!empty($data->image))
                    @foreach($data->image as $index=>$img)
                        <div class="item">
                          <img src="http://photos.hotelbeds.com/giata/original/{{ $img['path'] }}" />
                        </div>
                    @endforeach
                    @endif
                </div>
              </div>
            </div>
 
          </div> 
          
          <div class="row stars my-3 px-3">
            <i class="{{ $data->ratingStars >= 1 ? 'fas' : 'far' }} fa-star mx-1"></i>
            <i class="{{ $data->ratingStars >= 2 ? 'fas' : 'far' }} fa-star mx-1"></i>
            <i class="{{ $data->ratingStars >= 3 ? 'fas' : 'far' }} fa-star mx-1"></i>
            <i class="{{ $data->ratingStars >= 4 ? 'fas' : 'far' }} fa-star mx-1"></i>
            <i class="{{ $data->ratingStars >= 5 ? 'fas' : 'far' }} fa-star mx-1"></i>

          </div>

          <h4 class="px-1">{{ $data->name }}</h4>
          <p class="px-1"><i class="fal fa-map-marker-alt text-violet pr-2"></i>{{$data->address }},
            {{  $data->name }} ...
          </p>
          <p class="desc px-1">
            <!--description -->
            <a href="#details" class="read-more-icons">{{ __('Read More')}}</a>
          </p>
        </div>
        <div class="col-md-4 pl-4">
          <div class="priceOffre row">
            <div class="text-pink px-2 col-md-3 col-xs-6 realPrice"><p class="">{{ (session()->get('currency')) ? session()->get('currency') : $data->currency }} </p> <b class="">{{ (session()->get('rate')) ? round($data->minRateInPoints * session()->get('rate')) : $data->minRateInPoints }}</b></div>
            <div class="px-2 text-muted oldPrice col-md-5 col-xs-6 offerPrice"><p class="">{{ (session()->get('currency')) ? session()->get('currency') : $data->currency }} </p> <b class="">{{ (session()->get('rate')) ? round($data->maxRateInPoints * session()->get('rate')) : $data->maxRateInPoints }}</b></div>
            <div class="text-pink bg-pink px-3 col-md-4 pt-2">Save {{ (session()->get('rate')) ? round(($data->maxRateInPoints - $data->minRateInPoints) * session()->get('rate')) : ($data->maxRateInPoints - $data->minRateInPoints) }} {{ (session()->get('currency')) ? session()->get('currency') : $data->currency }}</div>
          </div>
          
          <div class="row my-3 dates">
            <div class="col-md-6 li">
              <i class="far fa-calendar mr-3"></i>
              <span>{{$data->checkIn}}</span>
            </div>
            <div class="col-md-6 li">
              <i class="far fa-calendar mr-3"></i>
              <span>{{$data->checkOut}}</span>
            </div>
          </div>
          <div class="row my-3 dates">
            <div class="col-12 li">
              <i class="fal fa-user mr-5"></i>
              <span>{{$reservationDetails->adultsCount}} {{ __('Adults')}} ,
                {{$reservationDetails->childrenCount}} {{ __('Children')}}
                <span class="red-circle"></span>
                <i class="icon icon-rooms"></i>
                {{$reservationDetails->roomsCount}} {{ __('Room')}}</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="container my-4 moreChoises">
    <div class="col-md-12 col-xs-12 pb20 info-inner">
      <h4 class="fw600 choice-tag">{{ __('ROOM CHOICES')}}</h4>
      <span class="fr refund-chck">
          <span class="refundable-checkbox mr10">
              <label>
                  <input type="checkbox" id="refundablerooms" class="hidden" name="refundablerooms">
                  <span class="refundablerooms-checkmark checkmark green"></span>
                  <label for="refundablerooms" class="ht-green">Refundable</label>
              </label>
          </span>
          <span class="breakfastincluded-checkbox">
              <label>
                  <input type="checkbox" id="breakfastincluded" class="hidden" name="breakfastincluded">
                  <span class="breakfastincluded-checkmark checkmark pink"></span>
                  <label for="breakfastincluded" class="ht-pink">Breakfast Included</label>
              </label>
          </span>
      </span>
    </div>
@foreach($data->rooms as $room)
    <div class="card p-0 my-3">
      <div class="card-header">
        {{$room['name']}}
      </div>
      <div class="card-body row">
        <div class="col-md-3 col-xs-12 d-flex flex-column img-container">
          <img src="images/108694a_hb_a_030.jpg" class="img-responsive" />
          <ul>
            <li><i class="fal fa-bed-alt fl"></i>{{$room['name']}} </li>
            <li><i class="fal fa-bed fl"></i>Single Bed</li>
            <li><i class="fal fa-air-conditioner fl"></i>Air Conditioner</li>
          </ul>
        </div>
        <div class="col-md-9 col-xs-12 detail-container">
          @foreach($room['rates'] as $roomRate)
          <div class="row room-option">
            <div class="col-md-4 col-xs-8 my-3">
              <h4>Whats Includes</h4>
              <p class="board-desc"> {{$roomRate['boardName']}}</p>
              <ul>
                <li>
                  <a href="javascript:;" data-target="#policyModal" data-toggle="modal">
                    <span class="green">Refundable</span>
                    <i class="fa fa-info-circle fs12" aria-hidden="true"></i>
                  </a>
                </li>
                <li>{{$roomRate['boardName']}}</li>
              </ul>
            </div>
            <div class="col-md-3 col-xs-4 my-3">
              <h4 class="text-center">Occupancy</h4>
              <h4 class="text-center">

                <i class="{{ $roomRate['adults'] >= 1 ? 'fas fa-user' : '' }}"></i>
                <i class="{{ $roomRate['adults'] >= 2 ? 'fas fa-user' : '' }}"></i>
                <i class="{{ $roomRate['adults'] >= 3 ? 'fas fa-user' : '' }}"></i>
                @if($roomRate['children'] != 0)
                <span class="">+</span>
                <i class="{{ $roomRate['children'] >= 1 ? 'fa fa-child' : '' }}"></i>
                <i class="{{ $roomRate['children'] >= 2 ? 'fa fa-child' : '' }}"></i>
                <i class="{{ $roomRate['children'] >= 3 ? 'fa fa-child' : '' }}"></i>
                @endif

            </h4>
            </div>
            <div class="col-md-3 col-xs-8 my-3">
              <h4>Total for Stay  </h4>
              <div class="inr-pad">
                <span class="price-span inline-block">
                  <span class="ib">
                    <span class="fmt-curr fl">{{ (session()->get('currency')) ? session()->get('currency') : $data->currency }}</span>
                    <span class="fmt-amt fl">{{ (session()->get('rate')) ? round($roomRate['net'] * session()->get('rate')) : $roomRate['net'] }} </span>
                  </span>
                </span><br>
                <small class="pax-no fmt-num fs12">{{$reservationDetails->roomsCount}} {{ __('Room')}} , 2 Nights</small>
              </div>
            </div>
            <div class="col-md-2 col-xs-4 txt-center my-3 p-0">
              <button class="btn btn-success btn-book fs14"> {{__('Book Now')}}</button>
            </div>
              <!-- policy modal -->
              <div class="modal fade" id="policyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">
                              {{date('M, d, Y', strtotime($roomRate['cancellationPolicies'][0]['from'])) }}
                            </p>
                        </div>
                      
                    </div>
                  </div>
                </div>
            </div>
            <!-- policy modal -->
          </div> 
          @endforeach
          <hr/>
          
          
        </div>
      </div>
      <a class="card-footer bg-light-green text-right btn-more">
        {{__('Show More')}}
        <i class="fal fa-angle-down pl5" aria-hidden="false"></i>
      </a>
    </div>
@endforeach
   
  </div>


  <div class="col-md-12 bg-light">
    <div class="container p-3" id="details">
      <h3 class="pt-2">{{ __('About')}} {{ $data->name}}</h3>
      <p class="text-justify">
         {{$data->description}}
      </p>
    </div>
  </div>


  <div class="container py-3" id="details">
    <h3 class="pt-4">{{ __('HOTEL FACILITIES')}}</h3>
    <div class="row h-facilities">
      <div class="col-md-4">
        <ul>
          @if($data->facilities)
          @foreach(array_slice($data->facilities , 0, 8) as $facility)
          <li><i class="fas fa-check mr-2"></i>{{$facility['description']['content']}} @if(isset($facility['number'])) : {{$facility['number']}}@endif</li>
          @endforeach
          @endif

        </ul>

      </div>
     
    </div>
  </div>

  <div class="container p-3">
    <h3 class="pt-5">{{ __('RECOMMENDED HOTELS')}}</h3>
    <div class="row recommended my-5">
      @foreach($avhotels as $hotel)
      <div class="col-md-3">
        <div class="card">
          <div class="card-body p-0">
            <img src="{{ $hotel->mainImage }}" class="w-100" alt="{{ $hotel->name }}" />
             <a href="{{ route('hotels.hotelDetails', ['hotelCode' =>  $hotel->code]) }}?criteria={{ request('criteria') }}">
            <h4 class="m-3">{{$hotel->name}}</h4>
            <p class="fs14 txt-left fl mx-3">
              <i class="fal fa-map-marker-alt text-violet"></i>
                {!! $hotel->address !!}
            </p>
            <div class="m-3">
              <button class="btn mapIcons fr" data-toggle="modal" data-target="#mapModal" data-lat='{{ $hotel->latitude }}' data-lng='{{ $hotel->longitude }}'> {{ __('View on map') }}</button>
              
              <div class="five-stars-container">
                <i class="{{ $hotel->ratingStars >= 1 ? 'fas' : 'far' }} fa-star"></i>
                <i class="{{ $hotel->ratingStars >= 2 ? 'fas' : 'far' }} fa-star"></i>
                <i class="{{ $hotel->ratingStars >= 3 ? 'fas' : 'far' }} fa-star"></i>
                <i class="{{ $hotel->ratingStars >= 4 ? 'fas' : 'far' }} fa-star"></i>
                <i class="{{ $hotel->ratingStars >= 5 ? 'fas' : 'far' }} fa-star"></i>
              </div>
            </div>
            <div class="col-12 m-3">
              <div class="fac-icon-container wi-fi mx-1 fl">
                <i class="fas fa-wifi"></i>
              </div>
              <div class="fac-icon-container swim  mx-1 fl">
                <i class="fas fa-swimmer"></i>
              </div>
              <div class="fac-icon-container rest  mx-1 fl">
                <i class="fas fa-utensils-alt "></i>
              </div>
              <div class="fac-icon-container park mx-1 fl">
                <i class="fas fa-parking-circle"></i>
              </div>
            </div>
            <div class="col-12 m-3">
              <!-- <button class="btn btn-success w-100 my-3 mx-auto py-2">
                Select Rooms
              </button> -->
              <a href="{{ route('hotels.hotelDetails', ['hotelCode' =>  $hotel->code]) }}" class="btn btn-success w-75 mx-auto fs14">{{ __('Select Room') }}</a>
            </div>

          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="container p-3">
    <div class="reviews">
      <h3>{{ __('REVIEWS')}} <span class="reviewCount">({{$reviews->count()}})</span></h3>
      @foreach($reviews as $review)
      @foreach($review['commentsByRates'] as $rate)
      @foreach($rate['comments'] as $comment)
      <div class="row">
        <div class="col-md-3">
          <div class="user pb-2 pt-5">
            <div class="userDiv pr-5">
              <p><i class="fas fa-user-circle fa-5x"></i></p>
              <h5>Ramon, UAE</h5>
              <p>1 Night Trip</p>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <p>{{date('M d, Y',strtotime($comment['dateStart']))}}</p>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="comment pb-0 pt-5 ml-4">
            <h4>The staff at this hotel</h4>
            <p>{{$comment['description']}}</p>
          </div>
        </div>
      </div>
      @endforeach
      @endforeach
      @endforeach
      <hr>
     
    </div>
  </div>
  
</x-page-layout>
<script>
    $(document).ready(function(){
    $('.btn-more').click(function(){
      let heightArrLength = $('.more-op.show-div').find('.room-option').length
      $('.more-op').height(0)
      $('.more-op.show-div').height($('.room-option').height() * heightArrLength)
      $($($(this).siblings('.card-body')[0]).find('.more-op')[0]).toggleClass('show-div')
      // if ($('.btn-more').text() == "show more") {
      //   $(this).text("show less")
      // } else {
      //   $(this).text("show more")
      // }

    })

    $('#soldOutModal').modal(); 
  
    $('.owl-carousel').owlCarousel({
      loop:true,
      margin:10,
      nav:true,
      dots:false,
      responsive:{
          0:{
              items:1
          },
          600:{
              items:1
          },
          1000:{
              items:4
          }
      }
    })
    $('.owl-nav').removeClass('disabled');
  })

  /***** Back to Top ****/
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

</script>
