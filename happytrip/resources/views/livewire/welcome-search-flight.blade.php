<div id="flightSearch" class="py-3 d-none">
        <form id="flightForm">
        <div id="multiCity">
            <div class="w-100">
            <div class="row my-3">
                <div class="col-md-6">
                <input list="cities2" class="form-control" type="text" placeholder="From" />
                <datalist id="cities2">
                    <option value="Cairo">
                    <option value="Other Cities">
                </datalist>
                
                <span class="arrow-dest transition3s" data-flip-airport="1">⇄</span>
                </div>
                <div class="col-md-6">
                <input list="cities3" class="form-control" type="text" placeholder="To" />
                <datalist id="cities3">
                    <option value="Cairo">
                    <option value="Other Cities">
                </datalist>
                </div>
            </div>
            <div class="row Flt" id="two-inputs">
                <div class="col-md-6">
                <i class="icon fal fa-calendar"></i>
                <input type="text" class="form-control" id="txtFltDD" name="txtFltDD" value="08-Feb-2021"/>
                </div>
                <div class="col-md-6">
                <i class="icon fal fa-calendar"></i>
                <input type="text" class="form-control" id="txtFltAD" name="txtFltAD" value="12-Feb-2021"/>
                </div>
            </div>
            </div>
        </div>

        <div class="w-100 justify-content-between my-3 d-none" id="addCitySec">
            <button type="button" class="btn btn-sm p-1" id="addCity">Add City</button>
            <button type="button" class="btn btn-sm btn-danger d-none p-1" id="removeCity">Remove City</button>
        </div>

        <div  id="flightBook" class="form-group d-flex justify-content-between mt-3" style="position: relative;">
            <span id="txtPax" class="form-control">
            <span id="travelerNo"></span>
            <span> Traveller - </span>
            <span id="flightClass">Economy</span>
            </span>
            <button class="btn btn-arrow" type="button">
            <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="flight-div">
            <div class="row mx-0">
            <div class="col-md-4 counter px-0">
                <p class="w-100 mb-0 pl-1"><span class="inline-block mt10 w100">Adult<span class="text-xs"> (12+ Years)</span></span></p>
                <button type="button" class="btn btn-outline-secondary ml-1" id="btnMinusAdult">-</button>
                <span id="adultNo"></span>
                <button type="button" class="btn btn-outline-secondary" id="btnPlusAdult">+</button>
            </div>
            <div class="col-md-4 counter px-0">
                <p class="w-100 mb-0 pl-1"><span class="inline-block mt10 w100">Child<span class="text-xs"> (2 - 12) Years</span></span></p>
                <button type="button" class="btn btn-outline-secondary ml-1" id="btnMinusChild">-</button>
                <span id="kidsNo"></span>
                <button type="button" class="btn btn-outline-secondary" id="btnPlusChild">+</button>
            </div>
            <div class="col-md-4 counter px-0">
                <p class="w-100 mb-0 pl-1"><span class="inline-block mt10 w100">Infants<span class="text-xs"> (0 - 2) Years</span></span></p>
                <button type="button" class="btn btn-outline-secondary ml-1" id="btnMinusInfant">-</button>
                <span id="infantNo"></span>
                <button type="button" class="btn btn-outline-secondary" id="btnPlusInfant">+</button>
            </div>
            </div>
            <div class="row mx-0 my-3">
            <div class="col-md-6">
                <select name="ddlClass" id="ddlClass" class="form-control">
                <option value="EC">Economy</option>
                <option value="SE">Standard Economy</option>
                <option value="PE">Premium Economy</option>
                <option value="BC">Business</option>
                <option value="FC">First</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="custom-control custom-checkbox my-3">
                <input type="checkbox" class="custom-control-input" id="customCheck" name="example1">
                <label class="custom-control-label" for="customCheck">None Stop</label>
                </div>
            </div>
            </div>
            <div class="my-3">
            <button type="button" class="btn btn-success btn-xs fr mt10 mb10t" id="searchFlight">DONE</button>
            </div>
        </div>
        <div class="row mx-0 mt-5">
            <button class="btn btn-success btn-block btn-lg fs14">Search</button>
        </div>


        </form>
    </div>