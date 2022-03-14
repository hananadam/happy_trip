<div id="hotelSearch" class="py-3">
    <form id="hotelForm">
        <div class="form-group">
            <input type="text" id="txtHtlCity" name="txtHtlCity" class="form-control" 
                wire:model="destination" autocomplete="off" value="{{ $destinationLabel }}" wire:keydown="onChangeDestination" wire:change="onChangeDestination"
                placeholder="{{ __('Select Destination') }}"/>
            @if(!empty($destinations))
                <div class="absolute z-50 list-group bg-white w-full rounded-t-none shadow-lg">
                    @if(!empty($destinations))
                        @foreach($destinations as $i => $item)
                            <label
                                wire:click="choiceDestination('{{$item['label']}}', '{{$item['code']}}')"
                                class="list-item"
                            >{{ $item['label'] }}</label>
                        @endforeach
                    @else
                        <div class="list-item">No results!</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="row Hlt" id="two-inputs">
            <div class="col-md-6">
                <input type="text" class="form-control" id="checkIn" wire:model="checkIn" name="checkIn"/>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" id="checkOut" value="" wire:model="checkOut" name="checkOut"/>
            </div>
        </div>

        <div class="form-group d-flex justify-content-between mt-3" wire:click="openOrCloseForm"
            style="position: relative;">
            <span id="txtPax" class="form-control">
                <span id="guestsNo">{{ $guestCount }}</span>
                <span> Guests - </span>
                <span id="roomsNo">{{ count($this->getRooms()) }}</span>
                <span>Room</span>
            </span>
            <button class="btn btn-arrow" id="roomsBook" type="button">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="form-group rooms-select px-3 @if ($openSelectForm) active @endif" id="rooms">
            <div class="rooms-container">
                @foreach ($this->getRooms() as $index => $room)
                    <div class="row room-div">
                        <p class="col-md-12 my-3">Room <span>{{ $index+1 }}</span></p>
                        <div class="col-md-4">
                            <p class="fl fs11">Adult(12+ Years)</p>
                            <button type="button" class="btn btn-outline-secondary btn-plus-adult"
                                    wire:click="addAdult('{{$index}}')">+
                            </button>
                            <span class="g-no adult-no">{{ $room->getAdult() }}</span>
                            <button type="button" class="btn btn-outline-secondary btn-minus-adult"
                                    wire:click="removeAdult('{{$index}}')" @if ($room->getAdult() == 1) disabled @endif>-
                            </button>
                        </div>
                        <div class="col-md-4">
                            <p class="fl fs11">Child(2 - 12) Years</p>
                            <button type="button" class="btn btn-outline-secondary btn-plus-child"
                                    wire:click="addChild('{{$index}}')">+
                            </button>
                            <span class="g-no kids-no">{{ count($room->getChild()) }}</span>
                            <button type="button" class="btn btn-outline-secondary btn-minus-child"
                                    wire:click="removeChild('{{$index}}')" @if (count($room->getChild()) == 0) disabled @endif>-
                            </button>
                        </div>
                        <div class="col-md-4 child-age">
                            @foreach ($room->getChild() as $child)
                                <div class="col-6">
                                    <p class="fl fs11">Age</p>
                                    <select class="form-control ageSelect mb-0 fs12 age">
                                        @foreach (\App\Models\Search\Hotel\Child::ages as $age)
                                            <option value="{{$age}}"
                                                    @if ($age == $child->getAge()) selected @endif>{{ $age }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="w-100 justify-content-between my-3">
                <button type="button" class="btn btn-sm" id="addRoom" wire:click="addRoom">Add Room</button>
                <button type="button" class="btn btn-sm btn-danger @if (count($this->getRooms()) == 1) d-none @endif"
                        id="removeRoom" wire:click="removeRoom">Remove Room
                </button>
            </div>
            <div class="my-3">
                <button type="button" class="btn btn-success btn-sm float-right" wire:click="openOrCloseForm"
                        id="searchHotel">Done
                </button>
            </div>
        </div>

        <div class="row mx-0 mt-">
            <button @if($criteria) disabled @endif class="btn btn-success btn-block btn-lg" type="button" wire:click="checkAvailability">Check Availability</button>
        </div>
    </form>
</div>