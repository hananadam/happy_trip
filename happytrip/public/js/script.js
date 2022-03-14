let ticket = {
  adults: 1,
  child: 0,
  infants: 0,
};

let ticketClass = [
  {
    label: "Economy",
    value: "EC",
  },
  {
    label: "Standard Economy",
    value: "SE",
  },
  {
    label: "Premium Economy",
    value: "PE",
  },
  {
    label: "Business",
    value: "BC",
  },
  {
    label: "First Class",
    value: "FC",
  },
];

function calculatePassengers() {
  $("#travelerNo").text(ticket.adults + ticket.child + ticket.infants);
}

/********** guest number calculate */
let rooms = [
  {
    adults: 2,
    child: 0,
  },
];

function calculateGests() {
  let roomsArray = rooms.map((room) => room.adults + room.child);
  $("#guestsNo").text(roomsArray.reduce((a, b) => a + b));
  if (rooms.length > 1) {
    $("#removeRoom").removeClass("d-none");
  } else {
    $("#removeRoom").addClass("d-none");
  }
}

/** add adult */
function addAdult(i, e) {
  console.log($(e.target).siblings("span").text());
  let adults = parseInt($(e.target).siblings("span").text()) + 1;
  if (adults <= 4 && adults >= 1) {
    rooms[i].adults = adults;
    $(e.target).siblings("span").text(adults);
    calculateGests();
  }
}

/** remove adult */
function removeAdult(i, e) {
  let adults = parseInt($(e.target).siblings("span").text()) - 1;
  if (adults <= 4 && adults >= 1) {
    rooms[i].adults = adults;
    $(e.target).siblings("span").text(adults);
    calculateGests();
  }
}

/** add child */
function addChild(i, e) {
  let child = parseInt($(e.target).siblings("span").text()) + 1;
  if (child <= 2 && child >= 0) {
    rooms[i].child = child;
    $(e.target).siblings("span").text(child);
    let chAges = $($(e.target).parents('.room-div').find('.child-age')[0])
    if (child > 0) {
      chAges.append(`
      <div class="col-6 px-0 ml-1">
        <p class="mb-0">Age</p>
        <select class="form-control mb-0 fs12 age">
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
        </select>
      </div>
    `);
    } else {
      $(".child-age .col-6").remove();
    }
    calculateGests();
  }
}

/** remove child */
function removeChilds(i, e) {
  let child = parseInt($(e.target).siblings("span").text())-1;
  console.log(child)
  if (child <= 4 && child >= 0) {
    rooms[i].child = child;
    $(e.target).siblings("span").text(child);
    if (child === 1) {
      $(".child-age .col-6")[0].remove();
    } else {
      $(".child-age .col-6").remove();
    }
    calculateGests();
  }
}

$(document).ready(function () {
  //************** Toggle Search Type ****** */
  $(".btn-search").click(function () {
    $(this).addClass("active").attr("disabled", "disabled");
    $(this).siblings().removeClass("active").removeAttr("disabled");
    let searchType = $(".btn-search.active").attr("data-link");
    $("#searchTitle").text(`Looking for  ${searchType}?`);
    if (searchType === "Hotel") {
      $("#hotelSearch").removeClass("d-none");
      $("#flightSearch").addClass("d-none");
      $("#flightOptions").addClass("d-none").removeClass("d-flex");
    } else {
      $("#hotelSearch").addClass("d-none");
      $("#flightSearch").removeClass("d-none");
      $("#flightOptions").removeClass("d-none").addClass("d-flex");
    }
  });

  //**************** Rooms Book **********/
  $("#roomsBook").click(function () {
    $("#rooms").toggleClass("active").removeAttr("style");
  });

  $(".rooms-container").append(`
    <div class="row room-div" >
      <p class="col-md-12 mt-2 mb-0">Room <span>1</span></p>
      <div class="col-md-4 col-sm-3 col-xs-6 adults">
        <p class="mb-0"><span class="fl">Adult</span>
          <span class="fl fs11">(12+ Years)</span>
        </p>
        <div class="ddl-box ml-0">
          <button type="button" class="btn btn-outline-secondary btn-minus-adult" onclick="removeAdult(0,event);">-</button>
          <span class="g-no adult-no"></span>
          <button type="button" class="btn btn-outline-secondary btn-plus-adult" onclick="addAdult(0,event);">+</button>
        </div>    
      </div>
      <div class="col-md-4 col-sm-3 col-xs-6 adults">
        <p class="mb-0"><span class="fl">Child</span>
        <span class="fl fs11">(2 - 12) Years</span></p>

        <div class="ddl-box ml-0">
          <button type="button" class="btn btn-outline-secondary btn-minus-child" onclick="removeChilds(0,event)">-</button>
          <span class="g-no kids-no"></span>
          <button type="button" class="btn btn-outline-secondary btn-plus-child" onclick="addChild(0,event)">+</button>
        </div>

      </div>
      <div class="col-md-4 col-sm-3 col-xs-6 sm-ddl pb10 child-age">
      
      </div>
    </div>
    `);
  calculateGests();

  // /** first room details */
  $("#guestsNo").text(rooms[0].adults + rooms[0].child);
  $("#roomsNo").text(rooms.length);
  $(".adult-no").text(rooms[0].adults);
  $(".kids-no").text(rooms[0].child);

  /** Add Room */
  $("#addRoom").click(function () {
    rooms.push({
      adults: 2,
      child: 0,
    });

    let newIndex = rooms.length - 1;
    $(".rooms-container").append(`
    <div class="row room-div">
      <p class="col-md-12 my-3">Room <span>${rooms.length}</span></p>
      <div class="col-md-4 col-sm-3 col-xs-6">
        <p class="mb-0"><span class="fl">Adult</span>
          <span class="fl fs11">(12+ Years)</span>
        </p>
        <button type="button" class="btn btn-outline-secondary btn-minus-adult" onclick="removeAdult(${newIndex}, event);">-</button>
        <span class="g-no adult-no mx-0">${rooms[newIndex].adults}</span>
        <button type="button" class="btn btn-outline-secondary btn-plus-adult" onclick="addAdult(${newIndex}, event);">+</button>

      </div>
      <div class="col-md-4 col-sm-3 col-xs-6">
        <p class="mb-0"><span class="fl">Child</span>
          <span class="fl fs11">(2 - 12) Years</span></p>
        <button type="button" class="btn btn-outline-secondary btn-minus-child" onclick="removeChilds(${newIndex},event)">-</button>
        <span class="g-no kids-no mx-0">${rooms[newIndex].child}</span>
        <button type="button" class="btn btn-outline-secondary btn-plus-child" onclick="addChild(${newIndex}, event)">+</button>

      </div>
      <div class="col-md-4 col-sm-3 col-xs-6 sm-ddl pb10 child-age">
        
      </div>
    </div>
    `);
    $(".rooms-select.active").css("height", $(".rooms-select.active").height() + $(".room-div").height());
    calculateGests();
  });

  /** Delete Room */
  $("#removeRoom").click(function () {
    if (rooms.length > 1) {
      rooms.pop();
      $($(".room-div")[rooms.length]).remove();
      $(".rooms-select.active").css("height", $(".rooms-select.active").height() - $(".room-div").height());
    }
    calculateGests();
  });

  $("#adultNo").text(ticket.adults);
  $("#kidsNo").text(ticket.child);
  $("#infantNo").text(ticket.infants);

  calculatePassengers();

  $("#searchHotel").click(function () {
    rooms = [
      {
        adults: 2,
        child: 0,
      },
    ];
    $($(".room-div")[2 - rooms.length]).remove();
    $(".rooms-select").removeClass("active").removeAttr("style");
    calculateGests();
  });

  $(".btn-fly-option").click(function () {
    $(this).addClass("active");
    $(this).siblings("button").removeClass("active");
    let flightType = $(".btn-fly-option.active").attr("data-type");

    if (flightType === "one") {
      $("#txtAirAD").addClass("d-none").removeClass("d-block");
      $("#addCitySec").addClass("d-none").removeClass("d-flex");
    } else if (flightType === "round") {
      $("#txtAirAD").addClass("d-block").removeClass("d-none");
      $("#addCitySec").addClass("d-none").removeClass("d-flex");
    } else {
      $("#txtAirAD").addClass("d-none").removeClass("d-block");
      $("#addCitySec").removeClass("d-none").addClass("d-flex");
    }
  });

  $("#addCity").click(function () {
    $("#multiCity").append(`
    <div class="w-100">
        <div class="row">
        <div class="col-md-6">
          <input class="form-control" type="text" placeholder="From" />
        </div>
        <div class="col-md-6">
          <input class="form-control" type="text" placeholder="To"/>
        </div>
      </div>

      <div class="row" id="two-inputsRoundTrip">
        <div class="col-md-6">
          <i class="icon fal fa-calendar"></i>
          <input class="form-control" type="text" id="txtAirDD" name="txtAirDD" value="10-Feb-2021"/>
        </div>
        <div class="col-md-6">
          <i class="icon fal fa-calendar"></i>
          <input class="form-control d-none" type="text" id="txtAirAD" name="txtAirAD" value="12-Feb-2021"/>
        </div>
      </div>
    </div>
    `);
    if($('#multiCity').children().length > 1){
      $('#removeCity').removeClass('d-none')
    }else{
      $('#removeCity').addClass('d-none')
    }
  });

  $('#removeCity').click(function () {
    let citiesArrLength = $('#multiCity').children().length
    if(citiesArrLength > 1){
      $('#multiCity').children()[citiesArrLength-1].remove();
    }
    if($('#multiCity').children().length === 1){
      $('#removeCity').addClass('d-none')
    }
  })

  $("#flightBook").click(function () {
    $(".flight-div").toggleClass("active");
  });

  $("#btnPlusAdult").click(function () {
    ticket.adults = ticket.adults + 1;
    $("#adultNo").text(ticket.adults);
    calculatePassengers();
  });

  $("#btnMinusAdult").click(function () {
    if (ticket.adults >= 1) {
      ticket.adults = ticket.adults - 1;
      $("#adultNo").text(ticket.adults);
      calculatePassengers();
    }
  });

  $("#btnPlusChild").click(function () {
    if (ticket.child <= 4) {
      ticket.child = ticket.child + 1;
      $("#kidsNo").text(ticket.child);
      calculatePassengers();
    }
  });

  $("#btnMinusChild").click(function () {
    if (ticket.child >= 1) {
      ticket.child = ticket.child - 1;
      $("#kidsNo").text(ticket.child);
      calculatePassengers();
    }
  });

  $("#btnPlusInfant").click(function () {
    if (ticket.infants <= 4) {
      ticket.infants = ticket.infants + 1;
      $("#infantNo").text(ticket.infants);
      calculatePassengers();
    }
  });

  $("#btnMinusInfant").click(function () {
    if (ticket.infants >= 1) {
      ticket.infants = ticket.infants - 1;
      $("#infantNo").text(ticket.infants);
      calculatePassengers();
    }
  });

  $("#ddlClass").change(function (e) {
    $("#flightClass").text(ticketClass.find((tc) => tc.value === e.target.value).label);
  });

  $("#searchFlight").click(function () {
    ticket = {
      adults: 1,
      child: 0,
      infants: 0,
    };
    calculatePassengers();
    $("#adultNo").text(ticket.adults);
    $("#kidsNo").text(ticket.child);
    $("#infantNo").text(ticket.infants);
    $(".flight-div").removeClass("active");
  });

    /************* Date Picker **********/
    // $('#two-inputsRoundTrip').dateRangePicker(
    //   {
    //     separator : ' to ',
    //     getValue: function()
    //     {
    //       if ($('#txtAirDD').val() && $('#txtAirAD').val() )
    //         return $('#txtAirDD').val() + ' to ' + $('#txtAirAD').val();
    //       else
    //         return '';
    //     },
    //     setValue: function(s,s1,s2)
    //     {
    //       $('#txtAirDD').val(s1);
    //       $('#txtAirAD').val(s2);
    //     }
    // });

    //favourite filter
    $(".favorite-filter>div>ul>li").click(function () {
      console.log('atrrr')
      var oChk = $(this).find("[type=checkbox]");
      if ($(this).hasClass("active")) {
          $(oChk).removeAttr("checked");
          $(this).removeClass("active");
      }
      else {
          $(oChk).attr("checked", "checked");
          $(this).addClass("active");
      }
    });

    //facilities filter
    $(".facility-filter>div>ul>li").click(function () {
      var oChk = $(this).find("[type=checkbox]");
      if ($(this).hasClass("active")) {
          $(oChk).removeAttr("checked");
          $(this).removeClass("active");
      }
      else {
          $(oChk).attr("checked", "checked");
          $(this).addClass("active");
      }
    });
    

});


