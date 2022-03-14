<div class="container search-section">
<div class="col-lg-5 col-sm-8 col-md-6 col-xs-12 search-form">
    <h1 id="searchTitle">Looking For {{ $type }}?</h1>
    <div class="search-type mb-4">

    <div class="row m-0 d-flex justify-content-between p-2">
        <button type="button" class="btn btn-search flightTab @if ($type == 'Flight') active @endif" data-link="Flight"> 
        <i class="fas fa-plane"></i>
        <span>Flights</span>
        </button>
        <button type="button" class="btn btn-search hotelTab @if ($type == 'Hotel') active @endif" data-link="Hotel">
        <i class="fas fa-building"></i>
        <span>Hotels</span>
        </button>
    </div>

    @if ($type == 'Flight')
    <div class="row mx-0 d-none justify-content-between px-3" id="flightOptions">
        <button class="btn btn-fly-option active" type="button" data-type='one'>One Way</button>
        <button class="btn btn-fly-option" type="button" data-type='round'>Round Trip</button>
        <button class="btn btn-fly-option" type="button" data-type='multi'>Multi-city</button>
    </div>
    @endif
    </div>

    <livewire:welcome-search-flight/>
    <livewire:welcome-search-hotel/>
    
</div>
</div>

