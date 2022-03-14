<div class="card hotel-card">
  <div class="card-body row mx-0">
    <div class="col-md-3 p-0 my-0 image-div">
      <img src="{{ $mainImage }}" alt="hotel" />
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12 hotel-details-list pt10">
      <a href="{{ route('hotels.hotelDetails', ['hotelCode' =>  $hotel->code]) }}?criteria={{ request('criteria') }}">
        <h3 class="txt-left mb5">{{ $hotel->name }}</h3>
      </a>
      <p class="fs14 txt-left fl">
        <i class="fal fa-map-marker-alt text-violet"></i>
        {!! $hotel->address !!}
      </p>
      <button class="btn mapIcons fr" data-toggle="modal" data-target="#mapModal" data-lat='{{ $hotel->latitude }}' data-lng='{{ $hotel->longitude }}'>View on map</button>
      <div class="clearfix"></div>
      <div class="five-stars-container">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <ul class="fl-li facilities"></ul>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-12 offset-0 text-center price-col pt20">
      <span class="price">
        <span class="price-span ">
          <span class="ib">
            <span class="fmt-curr fl">SAR</span>
            <span class="fmt-amt fl">{{ $this->getPrice() }}</span>
          </span>
        </span>
        <a href="{{ route('hotels.hotelDetails', ['hotelCode' =>  $hotel->code]) }}?criteria={{ request('criteria') }}" class="btn btn-success w-75 mx-auto fs14">Select Room</a>
      </span>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="row">
                <div class="location-map" id="location-map">
                  <div style="width: 600px; height: 400px;" id="map_canvas"></div>
                </div>
            </div>
          
        </div>
      </div>
    </div>
</div>



