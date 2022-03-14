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
    if (child > 0) {
      $(".child-age").append(`
      <div class="col-6">
        <p>child's Age</p>
        <select class="form-control">
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
  let child = parseInt($(e.target).siblings("span").text()) - 1;
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

  //**************** Rooms Book **********/
  $("#roomsBook").click(function () {
    $("#rooms").toggleClass("active").removeAttr("style");
  });

  // calculateGests();

  $("#adultNo").text(ticket.adults);
  $("#kidsNo").text(ticket.child);
  $("#infantNo").text(ticket.infants);

  calculatePassengers();

  // $("#searchHotel").click(function () {
  //   rooms = [
  //     {
  //       adults: 2,
  //       child: 0,
  //     },
  //   ];
  //   $($(".room-div")[2 - rooms.length]).remove();
  //   $(".rooms-select").removeClass("active").removeAttr("style");
  //   calculateGests();
  // });

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
      <div class="row">
        <div class="col-md-6">
          <input class="form-control" type="date" id="txtAirDD" name="txtAirDD" />
        </div>
        <div class="col-md-6">
          <input class="form-control d-none" type="date" id="txtAirAD" name="txtAirAD" />
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
});
