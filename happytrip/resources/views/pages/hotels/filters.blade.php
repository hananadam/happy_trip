<div class="col-md-3 col-sm-4 filter-rsp">

    <div id="accordion" class="panel-group toggle-container filters-container pt-4">
    <div class="panel arrow-right pt-3">
        <div class="panel-heading panel-heading-full">
        <div class="panel-title" id="hotel-name">
            <h5 class="mb-0">
            <h4 class="fs14 d-flex flex-row w-100 justify-content-between panel-title txt-left"
                data-toggle="collapse" data-target="#collapseOne">
                <span>Hotel Name</span>
                <i class="fas fa-chevron-down"></i>
            </h4>
            </h5>
        </div>
        </div>

        <div id="collapseOne" class="panel-collapse collapse show">
        <div class="card-body search-hotel">
            <input class="form-control" placeholder="Hotel Name" wire:model="hotelName" wire:keydown="searchByName" wire:change="searchByName" />
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
            <span>Price Range</span>
            <i class="fas fa-chevron-down"></i>
            </h4>
        </h5>
        </div>
        </div>

        <div id="collapseTwo" class="panel-collapse collapse show">
        <div class="card-body price-range mb-4 pt-0 px-2">
            <div data-role="rangeslider">
            <input id="ex2" type="text" class="span2" value="" data-slider-min="1000" data-slider-max="20000"
                data-slider-step="5" data-slider-value="[1000,20000]" />

            <div class="fl w50 my-2">
                <span class="fl"><span data-tp-currency-symbol="" class="fl fmt-curr">SAR</span><span
                    id="tpSliderMin" class="fl fmt-amt" data-tp-val="1991">1,991</span></span>
            </div>
            <div class="fr w50 txt-right my-2">
                <span class="fr"><span data-tp-currency-symbol="" class="fl fmt-curr">SAR</span><span
                    id="tpSliderMax" class="fl fmt-amt" data-tp-val="19927">19,927</span></span>
            </div>
            </div>
        </div>
        </div>
    </div>

    <div class="panel arrow-right fav">
        <div class="panel-heading panel-heading-full">
        <div class="panel-title" id="favorite">
            <h5 class="mb-0">
            <h4 class="d-flex flex-row w-100 justify-content-between panel-title txt-left mt-2"
                data-toggle="collapse" data-target="#collapseTen">
                <span>Favorite</span>
                <i class="fas fa-chevron-down"></i>
            </h4>
            </h5>
        </div>
        </div>

        <div id="collapseTen" class="panel-collapse collapse show">
        <div class="card-body stars-check">
            <input type="checkbox" id="stareOne" />
            <label for="stareOne" class="form-control">
            <i class="fas fa-heart text-warning mr-2"></i>
            Favorite
            </label>

        </div>
        </div>
    </div>

    <div class="panel arrow-right rate">
        <div class="panel-heading panel-heading-full">
        <div class="card-header" id="star-rating">
            <h5 class="mb-0">
            <h4 class="d-flex flex-row w-100 justify-content-between panel-title txt-left"
                data-toggle="collapse" data-target="#collapseThree">
                <span>Star Rating</span>
                <i class="fas fa-chevron-down"></i>
            </h4>
            </h5>
        </div>
        </div>

        <div id="collapseThree" class="panel-collapse collapse show">
        <div class="card-body stars-check">
            <input type="checkbox" id="stareOne"  @if(request()->query('stars')== 1 ) checked @endif onchange="redirect('stars', 1)" />
            <label for="stareOne"> 1 <i class="fas fa-star text-warning"></i>
            </label>

            <input type="checkbox" id="stareTwo"   @if(request()->query('stars')== 2 ) checked @endif onchange="redirect('stars', 2)"/>
            <label for="stareTwo"> 2 <i class="fas fa-star text-warning"></i>
            </label>

            <input type="checkbox" id="stareThree"   @if(request()->query('stars')== 3 ) checked @endif onchange="redirect('stars', 3)"/>
            <label for="stareThree"> 3 <i class="fas fa-star text-warning"></i>
            </label>

            <input type="checkbox" id="stareFour" @if(request()->query('stars')== 4 ) checked @endif onchange="redirect('stars', 4)"/>
            <label for="stareFour"> 4 <i class="fas fa-star text-warning"></i>
            </label>


            <input type="checkbox" id="starFive"  @if(request()->query('stars')== 5 ) checked @endif onchange="redirect('stars', 5)" />
            <label for="starFive"> 5 <i class="fas fa-star text-warning"></i>
            </label>

        </div>
        </div>
    </div>

    <div class="panel arrow-right facility">
        <div class="panel-heading panel-heading-full"></div>
        <div class="card-header" id="hotel-facilities">
        <h5 class="mb-0">
            <h4 class="btn d-flex flex-row w-100 justify-content-between panel-title txt-left"
            data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"
            aria-controls="collapseFour">
            <span>Hotel Facilities </span>
            <i class="fas fa-chevron-down"></i>
            </h4>
        </h5>
        </div>

        <div id="collapseFour" class="panel-collapse collapse show" aria-labelledby="hotel-facilities" data-parent="
        #accordion">
        <div class="card-body facilities-hotel">
            <div class="">
            <input type="checkbox" class="custom-control-input" id="customCheck1" name="example11" @if(request()->query('facilities')== 'Internet' ) checked @endif onchange="if(this.checked){ redirect('facilities', 'Internet') }else{ redirect('facilities', '') }" >
            <label class="" for="customCheck1">
                <div class="fac-icon-container wi-fi mx-3">
                <i class="fas fa-wifi"></i>
                </div>
                <span class="pt-2">Internet</span>
            </label>
            </div>

            <div class="">
            <input type="checkbox" class="custom-control-input" id="customCheck2" name="example12"  @if(request()->query('facilities')== 'swimming pool' ) checked @endif onchange="if(this.checked){ redirect('facilities', 'swimming pool') }else{ redirect('facilities', '') }">
            <label class="" for="customCheck2">
                <div class="fac-icon-container swim mx-3">
                <i class="fas fa-swimmer"></i>
                </div>
                <span class="pt-2">Swimming pool</span>
            </label>
            </div>

            <div class="">
            <input type="checkbox" class="custom-control-input" id="customCheck3" name="example13"  @if(request()->query('facilities')== 'restaurant' ) checked @endif onchange="if(this.checked){ redirect('facilities', 'restaurant') }else{ redirect('facilities', '') }">
            <label class="" for="customCheck3">
                <div class="fac-icon-container rest mx-3">
                <i class="fas fa-utensils-alt"></i>
                </div>
                <span class="pt-2">Restaurant</span>
            </label>
            </div>

            <div class="">
            <input type="checkbox" class="custom-control-input" id="customCheck4" name="example14"  @if(request()->query('facilities')== 'parking' ) checked @endif onchange=" if(this.checked){ redirect('facilities', 'parking') }else{ redirect('facilities', '') } ">
            <label class="" for="customCheck4">
                <div class="fac-icon-container park mx-3">
                <i class="fas fa-parking-circle"></i>
                </div>
                <span class="pt-2">Parking</span>
            </label>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>