<div class="row mx-0 search-results">
  <div class="container hotel-filter">
    <div class="row mx-0">
      <div class="col-md-3 col-sm-4 filter-rsp">

        <div id="accordion" class="panel-group toggle-container filters-container pt-4">
          <div class="panel arrow-right pt-3">
              <div class="panel-heading panel-heading-full">
              <div class="panel-title" id="hotel-name">
                  <h5 class="mb-0">
                  <h4 class="fs14 d-flex flex-row w-100 justify-content-between panel-title txt-left"
                      data-toggle="collapse" data-target="#collapseOne">
                      <span> {{ __('Hotel Name') }}</span>
                      <i class="fas fa-chevron-down"></i>
                  </h4>
                  </h5>
              </div>
              </div>

              <div id="collapseOne" class="panel-collapse collapse show">
              <div class="card-body search-hotel">
                  <input class="form-control" placeholder="{{__('Hotel Name')}}" wire:model="hotelName" />
                  <i class="fas fa-search"></i>
              </div>
              </div>
          </div>

          <div class="panel arrow-right">
              <div class="panel-heading panel-heading-full">
              <div class="panel-title" id="price-range">
              <h5 class="mb-0">
                  <h4 class="d-flex flex-row w-100 justify-content-between panel-title txt-left mt-3"
                  data-toggle="collapse" data-target="#collapseTwo">
                  <span>{{ __('Price Range') }}</span>
                  <i class="fas fa-chevron-down"></i>
                  </h4>
              </h5>
              </div>
              </div>

              <div id="collapseTwo" class="panel-collapse collapse show">
                <div class="card-body mb-4 pt-0 px-2">
                    <div data-role="rangeslider" wire:ignore>
                      <input id="ex2" type="text" class="span2" value="" data-slider-min="{{$minPrice}}" data-slider-max="{{$maxPrice}}"
                          data-slider-step="5" data-slider-value="[{{$minPrice}},{{$maxPrice}}]" />

                      <div class="fl w50 my-2">
                          <span class="fl">
                            <span data-tp-currency-symbol="" class="fl fmt-curr">
                            {{ (session()->get('currency')) ? session()->get('currency') : $hotel->currency }}
                            </span>
                            <span
                              id="tpSliderMin" class="fl fmt-amt" data-tp-val="{{ (session()->get('rate')) ? round($minPrice * session()->get('rate')) : $minPrice }}">{{ (session()->get('rate')) ? round($minPrice * session()->get('rate')) : $minPrice }}
                            </span>
                          </span>
                      </div>
                      <div class="fr w50 txt-right my-2">
                          <span class="fr"><span data-tp-currency-symbol="" class="fl fmt-curr">
                            {{ (session()->get('currency')) ? session()->get('currency') : $hotel->currency }}
                          </span>
                          <span
                            id="tpSliderMax" class="fl fmt-amt" data-tp-val="{{ (session()->get('rate')) ? round($maxPrice * session()->get('rate')) : $maxPrice }}">{{ (session()->get('rate')) ? round($maxPrice * session()->get('rate')) : $maxPrice }}
                          </span></span>
                      </div>
                    </div>
                </div>
              </div>
          </div>

          <!-- fav aml -->
          <div class="panel arrow-right fav">
            <div class="panel-heading panel-heading-full">
              <div class="panel-title" id="favorite">
                <h5 class="mb-0">
                  <h4 class="d-flex flex-row w-100 justify-content-between panel-title txt-left mt-2"
                    data-toggle="collapse" data-target="#collapseTen">
                    <span>{{__('Favourite')}}</span>
                    <i class="fas fa-chevron-down"></i>
                  </h4>
                </h5>
              </div>
            </div>

            <div id="collapseTen" class="panel-collapse collapse show favorite-filter">
              <div class="panel-content">
                <ul class="fl-li favorite">
                  <li data-tp-filter-type="price-difference" class="">
                      <i class="discount fl">%<br>{{__('Off')}}</i>
                      {{__('Favourite')}}
                      <input type="checkbox" class="hidden" value="night club">
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="panel arrow-right rate">
              <div class="panel-heading panel-heading-full">
              <div class="card-header" id="star-rating">
                  <h5 class="mb-0">
                  <h4 class="d-flex flex-row w-100 justify-content-between panel-title txt-left"
                      data-toggle="collapse" data-target="#collapseThree">
                      <span>{{ __('Star Rating') }}</span>
                      <i class="fas fa-chevron-down"></i>
                  </h4>
                  </h5>
              </div>
              </div>

              <div id="collapseThree" class="panel-collapse collapse show">
              <div class="card-body stars-check">
                  <input type="checkbox" value="1" id="stareOne" wire:model="searchRate" @if(in_array(1,$searchRate)) checked @endif/>
                  <label for="stareOne"> 1 <i class="fas fa-star text-warning"></i>
                  </label>

                  <input type="checkbox" value="2" id="stareTwo" wire:model="searchRate" @if(in_array(2,$searchRate)) checked @endif />
                  <label for="stareTwo"> 2 <i class="fas fa-star text-warning"></i>
                  </label>

                  <input type="checkbox" value="3" id="stareThree" wire:model="searchRate" @if(in_array(3,$searchRate)) checked @endif />
                  <label for="stareThree"> 3 <i class="fas fa-star text-warning"></i>
                  </label>

                  <input type="checkbox" value="4" id="stareFour" wire:model="searchRate" @if(in_array(4,$searchRate)) checked @endif />
                  <label for="stareFour"> 4 <i class="fas fa-star text-warning"></i>
                  </label>

                  <input type="checkbox" value="5" id="starFive" wire:model="searchRate" @if(in_array(5,$searchRate)) checked @endif/>
                  <label for="starFive"> 5 <i class="fas fa-star text-warning"></i>
                  </label>

              </div>
              </div>
          </div>

          <!-- fac aml -->
          <div class="panel arrow-right facility">
            <div class="panel-heading panel-heading-full"></div>
            <div class="card-header" id="hotel-facilities">
              <h5 class="mb-0">
                <h4 class="btn d-flex flex-row w-100 justify-content-between panel-title txt-left"
                  data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"
                  aria-controls="collapseFour">
                  <span>{{ __('Hotel Facilities') }} </span>
                  <i class="fas fa-chevron-down"></i>
                </h4>
              </h5>
            </div>

            <div id="collapseFour" class="panel-collapse collapse show facility-filter" aria-labelledby="hotel-facilities" data-parent="
              #accordion">
              <div class="panel-content">
                <ul class="fl-li facility">
                    <li wire:click="getFacilities({{$internet}})" class="@if(!empty(array_intersect(json_decode($internet), $facilities))) active @endif">
                        <i class="fas fa-wifi f1 fac-icon-container wi-fi mr-3 mt-1"></i>
                        {{__('Internet')}}
                        <input type="checkbox" class="hidden" value="internet" >
                    </li>
                    <li wire:click="getFacilities({{$swimingpool}})" class="@if(!empty(array_intersect(json_decode($swimingpool), $facilities))) active @endif">
                        <i class="fas fa-swimmer fl fac-icon-container swim mr-3 mt-1"></i>
                        {{__('Swimming pool')}}
                        <input type="checkbox" class="hidden" value="swimming_pool">
                    </li>
                    <li wire:click="getFacilities({{$restaurant}})" class="@if(!empty(array_intersect(json_decode($restaurant), $facilities))) active @endif">
                        <i class="fas fa-utensils-alt fl fac-icon-container rest mr-3 mt-1"></i>
                        {{__('Restaurant')}}
                        <input type="checkbox" class="hidden" value="restaurant">
                    </li>
                    <li wire:click="getFacilities({{$parking}})" class="@if(!empty(array_intersect(json_decode($parking), $facilities))) active @endif">
                        <i class="fas fa-parking-circle fl fac-icon-container park mr-3 mt-1"></i>
                        {{__('Parking')}}
                        <input type="checkbox" class="hidden" value="parking">
                    </li>
                    
                </ul>
              </div>
            </div>
          </div>

        </div>
        </div>
      
      <div class="col-md-9 col-sm-8">
        <div class="result-container pt-3">
          <div class="card-body">
            <div class=" search-summary mb20">
              <h4><span>{{$total}} {{ __('Hotels Search Results') }}</span></h4>
              <i class="fas fa-map-marker-alt" data-toggle="modal" data-target="#allLocationsModal"></i>
            </div>
            <div class="sorting-tabs mb-3">
              <ul class="sort-tabs no-margin brbr4 rtl-b4tl4">
                <li class="sort-tabs-tab @if($sortField == 'name') sort-tabs-tab--active @endif" data-tp-sort-type="name">
                  <span class="sort-tabs-tab-text">
                    <a href="javascript:;" id="name" wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Hotel Name') }}</a>
                    <i class="fa @if($sortField == 'name') @if($sortDirection == 'desc') fa-caret-down @else fa-caret-up @endif @endif"></i>
                  </span>
                </li>
                <li class="sort-tabs-tab @if($sortField == 'ratingStars') sort-tabs-tab--active @endif" data-tp-sort-type="star">
                  <span class="sort-tabs-tab-text">
                    <a href="javascript:;" id="ratingStars" wire:click="sortBy('ratingStars')" :direction="$sortField === 'ratingStars' ? $sortDirection : null">{{ __('Star Rating') }}</a>
                    <i class="fa @if($sortField == 'ratingStars') @if($sortDirection == 'desc') fa-caret-down @else fa-caret-up @endif @endif"></i>
                  </span>
                </li>
                <li class="sort-tabs-tab @if($sortField == 'minRateInPoints') sort-tabs-tab--active @endif" data-tp-sort-type="minRateInPoints">
                  <span class="sort-tabs-tab-text">
                    <a href="javascript:;" id="minRateInPoints" wire:click="sortBy('minRateInPoints')" :direction="$sortField === 'minRateInPoints' ? $sortDirection : null">{{ __('Price') }}</a>
                    <i class="fa @if($sortField == 'minRateInPoints') @if($sortDirection == 'desc') fa-caret-down @else fa-caret-up @endif @endif"></i>
                  </span>
                </li>

              </ul>
            </div>
            @php 
            $locations = [];
            $arr_maps=[];
            @endphp
              @foreach ($hotels as $hotel)
                @php 
                  $arr_maps['lng'][]=$hotel->longitude;
                  $arr_maps['lat'][]=$hotel->latitude;
                  $locations = array_map(
                    function($lat, $long) {
                        return $lat . ", " . $long;
                    },
                    $arr_maps['lat'],
                    $arr_maps['lng']
                  )
                @endphp
              <div class="card hotel-card">
                <div class="card-body row mx-0">
                  <div class="col-md-3 p-0 my-0 image-div">
                    <img src="{{ $hotel->mainImage }}" alt="{{ $hotel->name }}" />
                  </div>
                  <div class="col-md-6 col-sm-12 col-xs-12 hotel-details-list pt10">
                    <a href="{{ route('hotels.hotelDetails', ['hotelCode' =>  $hotel->code]) }}?criteria={{ request('criteria') }}">
                      <h3 class="txt-left mb5">{{ $hotel->name }}</h3>
                    </a>
                    <p class="fs14 txt-left fl">
                      <i class="fal fa-map-marker-alt text-violet"></i>
                      {!! $hotel->address !!}
                    </p>
                    <button class="btn mapIcons fr" data-toggle="modal" data-target="#mapModal" data-lat='{{ $hotel->latitude }}' data-lng='{{ $hotel->longitude }}'> {{ __('View on map') }}</button>
                    <div class="clearfix"></div>
                    <div class="five-stars-container">
                      <i class="{{ $hotel->ratingStars >= 1 ? 'fas' : 'far' }} fa-star"></i>
                      <i class="{{ $hotel->ratingStars >= 2 ? 'fas' : 'far' }} fa-star"></i>
                      <i class="{{ $hotel->ratingStars >= 3 ? 'fas' : 'far' }} fa-star"></i>
                      <i class="{{ $hotel->ratingStars >= 4 ? 'fas' : 'far' }} fa-star"></i>
                      <i class="{{ $hotel->ratingStars >= 5 ? 'fas' : 'far' }} fa-star"></i>
                    </div>
                    <ul class="fl-li facilities"></ul>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-12 offset-0 text-center price-col pt20">
                    <span class="price">
                      <span class="price-span ">
                        <span class="ib">
                          <span class="fmt-curr fl">{{ (session()->get('currency')) ? session()->get('currency') : $hotel->currency }}
                          </span>
                          <span class="fmt-amt fl"> 
                            {{ (session()->get('rate')) ? round($hotel->minRateInPoints * session()->get('rate')) : $hotel->minRateInPoints }}
                          </span>
                        </span>
                      </span>
                      <a href="{{ route('hotels.hotelDetails', ['hotelCode' =>  $hotel->code]) }}" class="btn btn-success w-75 mx-auto fs14">{{ __('Select Room') }}</a>
                    </span>
                  </div>
              </div>
            </div>

              @endforeach
                  @php 
                  $locations=json_encode($locations);

                   @endphp
            </div>
            <div class="col-md-12 text-center ht-pink" wire:loading>
              {{ __('More Hotels Loading') }}....
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="allLocationsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                  <div class="row">
                      <div class="location-map" id="map"  style="width: 750px; height: 460px;">

                      </div>
                  </div>
                
              </div>
            </div>
          </div>
      </div>
      <!-- one hotel modal -->
      <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              
                <div class="row">
                    <div class="location-map" id="location-map">
                      <div style="width: 600px; height: 450px;" id="map_canvas"></div>
                    </div>
                </div>
            </div>
          </div>
        </div> 
      </div>
    </div> 
  </div>
</div>

  <script type="text/javascript">
    
    var element = document.getElementById('ex2');
    element.dispatchEvent(new Event('input'));

    window.onload=initMap();
    function initMap() {
      // console.log(<?=$locations?>);
      var locations = <?=$locations?>;
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: new google.maps.LatLng(41.976816, -87.659916),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
      })

      var infowindow = new google.maps.InfoWindow({})

      var marker, i

      for (i = 0; i < locations.length; i++) {
        // console.log(locations[i]);
        var latitude=locations[i].split(",")[0];
        var longitude=locations[i].split(",")[1];
        marker = new google.maps.Marker({
          position: new google.maps.LatLng(latitude, longitude),
          map: map,
        })

        google.maps.event.addListener(
          marker,
          'click',
          (function (marker, i) {
            return function () {
              infowindow.setContent(locations[i][0])
              infowindow.open(map, marker)
            }
          })(marker, i)
        )
      }
    }

    $('#ex2').on('change', function(){
      @this.set('priceRange', $(this).val());
    });
  </script>


