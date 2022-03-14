<div class="col-md-9 col-sm-8">
  <div class="result-container pt-3">
    <div class="card-body">
      <div class=" search-summary mb20">
      
        <h4><span>{{$hotels->count()}} Search Results</span></h4>
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
          <li class="sort-tabs-tab @if($sortField == 'star') sort-tabs-tab--active @endif" data-tp-sort-type="star">
            <span class="sort-tabs-tab-text">
              <a href="javascript:;" id="star" wire:click="sortBy('star')" :direction="$sortField === 'star' ? $sortDirection : null">{{ __('Star Rating') }}</a>
              <i class="fa @if($sortField == 'star') @if($sortDirection == 'desc') fa-caret-down @else fa-caret-up @endif @endif"></i>
            </span>
          </li>
          <li class="sort-tabs-tab @if($sortField == 'price') sort-tabs-tab--active @endif" data-tp-sort-type="price">
            <span class="sort-tabs-tab-text">
              <a href="javascript:;" id="price" wire:click="sortBy('price')" :direction="$sortField === 'price' ? $sortDirection : null">Price</a>
              <i class="fa @if($sortField == 'price') @if($sortDirection == 'desc') fa-caret-down @else fa-caret-up @endif @endif"></i>
            </span>
          </li>

        </ul>
      </div>

      @foreach ($hotels as $hotel)
      <livewire:hotels.hotel-card :hotel="$hotel"  :key="$loop->index" />
      @endforeach

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
@section('scripts') 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">
   window.onload=initMap();
  // function initMap() {alert("ok");}
function initMap() {
  var locations = <?php echo $this->getResults()->items; ?>;

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 13,
    center: new google.maps.LatLng(41.976816, -87.659916),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  })

  var infowindow = new google.maps.InfoWindow({})

  var marker, i

  for (i = 0; i < locations.length; i++) {
    console.log(locations[i]['latitude']);
    console.log(locations[i]['longitude']);
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i]['latitude'], locations[i]['longitude']),
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

</script>
@endsection