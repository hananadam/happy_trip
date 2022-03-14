<x-page-layout :class="'mt-0'">
    @include('pages.common._navbar')

    <div class="container d-flex flex-row justify-content-between modify py-4">
        <div class="col-8 d-flex">
            <div class="d-flex flex-column">
                <h3> Happytrip <i class="fas fa-circle"></i> Contact us </h3>
            </div>
        </div>
    </div>

    <div class="row mx-0 contact-div">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-6">
                    <h2 class="mb-3">Contact Us</h2>
                    <form id="contactusform" method="post" action="/contactus" novalidate="novalidate">
                        <div id="tpPassengerContainer">
                            <div class="form-group row">
                                <div class="col-sm-6 col-md-6 my-3">
                                    <select id="title_contactus" name="title_contactus" class="form-control" aria-invalid="false"><option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-6 my-3">
                                    <input class="form-control" id="firstname_contactus" name="firstname_contactus" tp-type="name" type="text" placeholder="First Name" value="">
                                </div>
                                <div class="col-sm-6 col-md-6 my-3">
                                    <input class="form-control" id="middlename_contactus" name="middlename_contactus" tp-type="name" type="text" placeholder="Middle Name" value="">
                                </div>
                                <div class="col-sm-6 col-md-6 my-3">
                                    <input class="form-control" id="lastname_contactus" name="lastname_contactus" tp-type="name" type="text" placeholder="Last Name" value="">
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-12 col-md-12 my-3">
                                    <input class="form-control" id="email_contactus" name="email_contactus" tp-type="email" emailcheck="emailCheck" type="text" placeholder="Email" value="">
                                </div>
                                <div class="col-sm-4 col-md-4 my-3 d-flex justify-content-between">
                                    <span class="mt-2">+</span> <input id="dialingcode_contactus" name="dialingcode_contactus" type="text" maxlength="5" class="w-75 form-control" dialingcodecheck="dialingcodeCheck" tp-type="numeric" placeholder="Dialing Code" value="966">
                                </div>
                                <div class="col-sm-8 col-md-8 my-3">
                                    <input id="mobile_contactus" name="mobile_contactus" type="text" class="form-control" tp-type="numeric" placeholder="Mobile" value="">
                                </div>
                                <div class="col-sm-12 col-md-12 my-3">
                                    <textarea class="form-control required" id="query_contactus" name="query_contactus" rows="4" placeholder="Message"></textarea>
                                </div>
                                <div class="col-sm-12 col-md-12 my-3 offset-0 txt-left pt10">
                                </div><div class="clearfix"></div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-success btn-lg btn-block">Submit</button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <h2 class="mb-3">Address</h2>
                    <p>
                        <i class="fal fa-map-marker-alt mr-3"></i>
                        <span>Al Iskandariyyah St. Al Hamra, Jeddah, Saudi Arabia</span>
                    </p>
                    <p>
                        <i class="fal fa-phone-alt mr-3"></i>
                        <a href="tel:920033769" class="text-violet">920033769</a>
                    </p>
                    <p>
                        <i class="fal fa-envelope mr-3"></i>
                        <a href="mailto: info@happytbooking.com" class="text-violet"> info@happytbooking.com</a>
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-page-layout>
