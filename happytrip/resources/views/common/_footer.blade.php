<div class="upfooter">
    <div class="container">
      <div class="row pb-3">
        <div class="col-md-3 d-flex flex-column footer-inner">
          <p class="mt-3 mb-1">CONTACT US</p>
          <img src="{{ asset('images/happy-trip.png') }}" height="50" width="150" class="mb-3" />
          <ul class="footer-contact">
            <li class="row mb-3">
              <i class="fal fa-phone-volume mt-1 ml-4 mr-2"></i>
              <a href="tel:920033769">920033769</a>
            </li>
            <li class="row mb-3">
              <i class="fal fa-envelope mt-1 ml-4 mr-2"></i>
              <a href="mailto:info@happytbooking.com69">info@happytbooking.com</a>
            </li>
          </ul>
        </div>

        <div class="col-md-3 d-flex flex-column footer-inner">
          <p class="mt-3 mb-0">USEFUL LINKS </p>
          <ul class="footer-menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Flights</a></li>
            <li><a href="#">Hotels</a></li>
            <li><a href="#">Deals</a></li>
            <li><a href="#">Discounts</a></li>
          </ul>
        </div>

        <div class="col-md-3 d-flex flex-column footer-inner">
          <p class="mt-3 mb-0">SOCIAL </p>
          <ul class="footer-menu">
            <li><a href="#">Facebook</a></li>
            <li><a href="#">Twitter</a></li>
            <li><a href="#">Instagram</a></li>
          </ul>
        </div>

        <div class="col-md-3 d-flex flex-column footer-inner">
          <p class="mt-3 mb-0">OTHER INFO </p>
          <ul class="footer-menu">
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms and Conditions</a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <div class="footer">
    <div class="copyright">
      <div class="container ">
        <a class="" data-toggle="modal" data-target="#faq">
          <img src="{{ asset('images/bg-copyrights.png')}}" alt="fr">
        </a>
        <p>© Happy Trip 2019-2020, All rights reserved.</p>
      </div>
    </div>
  </div>
  <a href="#PageTop" id="BackTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
      <div class="modal-content">
        <div class="modal-header border-0 p-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fal fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body login-modal pt-0">
          <div id="lbt" class="useLogin">
            <ul class="nav nav-tabs border-0">
              <li><a data-toggle="tab" href="#login-box" aria-expanded="true" class="active">Login</a></li>
              <li><a data-toggle="tab" href="#signup-box" aria-expanded="false">Signup</a></li>
            </ul>
            <div class="tab-content pt-3">
              <div id="login-box" class="tab-pane fade active in show">
                <form id="loginform" novalidate="novalidate" method="POST" action="{{route('login')}}">
                  {{ csrf_field() }}
                <!--   @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif -->
                  <div class="form-group">
                    <input id="email_login" name="email" type="text" class="form-control" tp-type="email"
                      emailcheck="emailCheck" placeholder="Email">
                  </div>
                  <div class="form-group ">
                    <input id="password_login" name="password" type="password" class="form-control"
                      placeholder="Password">
                  </div>
                  <div class="form-group">
                    <p id="responsemsg" class="red help-block"></p>
                    <div class="checkbox checkbox-inline no-display">
                      <input type="checkbox" id="remember_login">
                      <label for="remember_login">Remember me</label>
                    </div>
                  </div>
                  <div class="form-group pt50">
                    <button id="loginsubmit" type="submit" class="btn btn-success btn-block py-40"
                      value="Login">LOOGIN</button>
                    <div class="clearfix"></div>
                  </div>
                  <div class="seperator"></div>
                  <a id="fg" class="btn btn-link mt15 float-right fs12" href="javascript:void(0)">Forgot Password ?</a>
                </form>


              </div>
              <div id="signup-box" class="tab-pane fade">
                <form id="signupform" novalidate="novalidate" method="POST" action="{{route('register')}}"> 
                  {{ csrf_field() }}
                  @if(session()->has('message'))
                      <div class="alert alert-success">
                          {{ session()->get('message') }}
                      </div>
                  @endif

                  <div class="row">
                    <div class="form-group col-md-6 col-xs-6 {{ $errors->has('firstname_signup') ? ' is-invalid' : '' }}">
                      <input id="firstname_signup" name="firstname_signup" type="text" class="form-control"
                        tp-type="name" placeholder="First Name">
                        @if($errors->has('firstname_signup'))
                          <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('firstname_signup') }}</strong>
                          </span>
                        @endif
                    </div>
                    <div class="form-group col-md-6 col-xs-6 {{ $errors->has('lastname_signup') ? ' is-invalid' : '' }}">
                      <input id="lastname_signup" name="lastname_signup" type="text" class="form-control" tp-type="name"
                        placeholder="Last Name">
                        @if($errors->has('lastname_signup'))
                          <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('lastname_signup') }}</strong>
                          </span>
                        @endif
                    </div>
                  </div>
                  <div class="form-group {{ $errors->has('email_signup') ? ' is-invalid' : '' }}">
                    <input id="email_signup" name="email_signup" type="text" class="form-control" tp-type="email"
                      emailcheck="emailCheck" placeholder="Email">
                      @if($errors->has('email_signup'))
                          <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email_signup') }}</strong>
                          </span>
                        @endif
                  </div>
                  <div class="row">
                    <div class="form-group text-center py-2 col-md-1 ">
                      <span class="fl fs16">+</span>
                    </div>
                    <div class="form-group col-md-10 {{ $errors->has('dialingcode_signup') ? ' is-invalid' : '' }}">
                      <input id="dialingcode_signup" name="dialingcode_signup" type="text" maxlength="5"
                        class="form-control" dialingcodecheck="dialingcodeCheck" tp-type="numeric"
                        placeholder="Dialing Code">
                        @if($errors->has('dialingcode_signup'))
                          <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('dialingcode_signup') }}</strong>
                          </span>
                        @endif

                    </div>
                    <div class="form-group col-md-12 {{ $errors->has('mobile_signup') ? ' is-invalid' : '' }}">
                      <input id="mobile_signup" name="mobile_signup" type="text" class="form-control" tp-type="numeric"
                        placeholder="Mobile">
                        @if($errors->has('mobile_signup'))
                          <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('mobile_signup') }}</strong>
                          </span>
                        @endif
                    </div>

                  </div>
                  <div class="form-group {{ $errors->has('password_signup') ? ' is-invalid' : '' }}">
                    <input id="password_signup" name="password_signup" type="password" class="form-control"
                      passwordcheck="passwordCheck" minlength="6" placeholder="Password">
                      @if($errors->has('password_signup'))
                          <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password_signup') }}</strong>
                          </span>
                        @endif
                  </div>
                 <!--  <div class="form-group {{ $errors->has('password_confirmation_signup') ? ' is-invalid' : '' }}">
                    <input id="password_confirmation_signup" name="password_confirmation_signup" type="password" class="form-control"
                      passwordcheck="passwordCheck" minlength="6" placeholder="Password Confirmation">
                      
                  </div> -->
                  <div class="form-group">
                    <p id="responsemsg" class="red help-block"></p>
                    <a href="/termsconditions" target="_blank" class="link agreTrms mt-5">By signing up, you agree to the terms and
                      conditions </a>
                  </div>
                  <div class="form-group mt-3">
                    <button id="signupsubmit" type="submit" class="btn btn-success btn-block py-40">SINGUP</button>
                    <div class="clearfix"></div>
                  </div>
                  <div class="seperator"></div>
                </form>
              </div>
            </div>
          </div>
          <div id="forgotpassword-box" class="" style="display:none">
            <form id="forgotpasswordform" novalidate="novalidate">
              <h5 class="my-3">Please enter your email &amp; we will send you a password reset mail. </h5>
              <div class="form-group">
                <input id="email_forgotpassword" name="email_forgotpassword" type="text" class="form-control"
                  tp-type="email" emailcheck="emailCheck" placeholder="Email">
                <p id="responsemsg" class="red help-block"></p>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success btn-block py-40">Submit</button>
                <div class="clearfix"></div>
              </div>
              <div class="seperator"></div>
              <a id="lbg" class="btn btn-link mt15 float-right" data-toggle="tab" href="#login-box"><i
                  class="fa fa-chevron-left"></i> Login</a>
            </form>


          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">

  function convertCurrency(elm){    
    var to=elm.id;
    $.ajax({
        dataType: 'json',
        type:'get',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('get_currency')}}",
        data:{'to':to},
        success:function(data)
        {
          console.log(data);
          location.reload();
          
        },
    });

} 

  </script>

