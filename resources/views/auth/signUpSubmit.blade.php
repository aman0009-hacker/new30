@extends('layouts.guest')
@section('content')
<div class="main">
  <div class="container-fluid p-4">
    <div class="sign-up-page">
      <div class="row">
        <div class="col-12 col-md-7">
          <div class="background-image d-flex align-items-center">
            <div class="row">
              <div class="col-12">
                <div class="row">
                  <div class="col-12">
                    <div class="user-welocme">
                      <div class="row">
                        <div class="col-12">
                          <span class="welcome-text">WELCOME TO
                            <span class="welcome-border"></span>
                          </span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 industries-export-text">
                          <span>Punjab Small Industries & Export Corporation</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <span class="state-text">(A State Government Undertaking)</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="sign-up-footer">
                      <div class="row align-items-baseline">
                        <div class="col-4">
                          <div class="policy-warranty-link">
                            <a href="#">Privacy Policy</a>
                            <span class="text-white"> | </span>
                            <a href="#">PSIEC Product Warranty</a>
                          </div>
                        </div>
                        <div class="col-8 copy-right-section">
                          <div class="row">
                            <div class="col-12">
                              <p class="copy-right-text">© Copyright 2023 PSIEC. All rights reserved.</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12 owners-text">
                              <p>All trademarks used herein are property of their respective owners.</p>
                              <p>Any use of third party trademarks is for identification purposes only and does not
                                imply endorsement.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-5 user-signUp">
          <div class="user-signUp-form">
            <div class="row text-center">
              <div class="col-12">
                <h1 class="sign-up-text">Sign Up</h1>
                <p class="details-text">Please enter your details to Sign Up</p>
              </div>
            </div>
            @if (Auth::check())
            <?php 
              if(isset(Auth::user()->state))
              {
                Session::put('currentId', Auth::user()->id );
                Session::put('contact_number', Auth::user()->contact_number );
                Session::put('email', Auth::user()->email );
               
               

               $contact_number=Session::get('contact_number');
               $userCurrentId=Session::get('currentId');
               $email=Session::get('currentId');
              
              }
            ?>
            @else
            @if (Session::has('contact_number') && Session::has('currentId') && Session::get('contact_number')!="" &&
            !empty(Session::get('contact_number')) && !empty(Session::get('currentId')) )
            <div class="alert alert-success" id="successmsg">
              <strong>Great!</strong> Your information has saved successfully. Kindly fill the <b>OTP</b> to complete
              the <b>Registration</b>.
            </div>
            @else
            <script>
              window.location.href="/signup";
            </script>
            @endif
            @endif
            @if (Session::has('contact_number'))
            <?php
                    $contact_number=Session::get('contact_number');
                    //echo $contact_number;
                ?>
            @endif
            @if (Session::has('currentId'))
            <?php
                  $userCurrentId=Session::get('currentId');
                  //echo $userCurrentId;
              ?>
            @endif
            @if(session::has('email'))
            <?php
                  $email=Session::get('email');
            ?>
            @endif
            @if (Session::has('data'))
            @if (Session::get('data')=="notsuccess")
            <div class="alert alert-warning" id="wrongotpmsg">
              <strong>Oops!</strong> OTP is <b>wrong</b>.
            </div>
            <script>
              $("#successmsg").hide();
            </script>
            @endif
            @endif
            {{-- <form class="sign-up-form" id="register-form-submit" action="{{ route('store') }}" method="POST"
              autocomplete="off"> --}}
              @csrf
              @if(count($errors))
              <div class="alert alert-danger">
                <ul>
                  @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif
              <br>
              <input type="hidden" name="id" id="id" value="{{  $userCurrentId ?? '' }}" />
              <div class="mb-3 position-relative form-control-new">
                <input type="text" class="form-control form-input bg-transparent" id="contactNumber"
                  name='contact_number' title="Contact Number" maxlength="10" minlength="10"
                  onkeypress="return isNumberKey(event)" readonly aria-describedby="contactNumberHelp"
                  placeholder="Contact Number (10 digits)" required
                  oninvalid="this.setCustomValidity('Enter Contact Number Here (10 digits)')"
                  oninput="setCustomValidity('')" value="{{ $contact_number ?? ''}}">
                <label for="contact_number" class="form-label">Contact Number</span></label>
                
              </div>
              <div class="row g-3 align-items-center mb-3 send-otp" id="otp_div">
                <div class="col-6">
                  <div class="position-relative form-control-new">
                    <input type="text" id="userOtp" class="form-control  form-input bg-transparent"
                      aria-describedby="otpHelpInline" placeholder="Enter OTP ★" required name="userOtp"
                      oninvalid="this.setCustomValidity('Enter the required OTP')" title="Enter OTP"
                      oninput="setCustomValidity('')" maxlength="10">
                     
                    <label for="userOtp" class="form-label">Enter OTP <span style="color:red">★</span></span></label>
                  </div>
                </div>
                <div class="col-6">
                  <span id="otpHelpInline" class="form-text">
                    <button type="button"class="btn btn-link btn-sm" id="verfiy_user_sms">Verfiy SMS</button>
                    <button type="button" class="btn btn-link btn-sm" id="otpHandleBtn" class="ms-4">Resend
                      OTP</button>
                    {{-- <a href="" id="otpHandleBtn">Resend OTP</a> --}}
                    <span id="msg" style="display:none"><b>OTP WILL BE SHARED BY SMS</b></span>
                  </span>
                </div>





{{-- emailotp --}}





                <div class="mb-0 position-relative form-control-new">
                  <input type="text" class="form-control form-input bg-transparent" id="EmailId"
                  name='contact_number' title="EmailId" maxlength="10" minlength="10"
                  onkeypress="return isNumberKey(event)" readonly aria-describedby="Email"
                  placeholder="Emailid" required
                  oninvalid="this.setCustomValidity('Enter Your Email Here  )')"
                  oninput="setCustomValidity('')" value="{{ $email ?? ''}}">
                  <label for="contact_number" class="form-label">Email</span></label>
                </div>

                <div class="row g-3 align-items-center mb-3 send-otp" id="otp_div"style="margin-top:0px">
                  <div class="col-6">
                    <div class="position-relative form-control-new">
                      <input type="text" id="useremailOtp" class="form-control  form-input bg-transparent"
                        aria-describedby="otpHelpInline" placeholder="Enter OTP ★" required name="useremailOtp"
                        oninvalid="this.setCustomValidity('Enter the required OTP')" title="Enter OTP"
                        oninput="setCustomValidity('')" maxlength="10">
                      <label for="userOtp" class="form-label">Enter OTP <span style="color:red">★</span></span></label>
                    </div>
                  </div>
                  <div class="col-6">
                    <span id="otpHelpInline" class="form-text">
                      <button type="button"class="btn btn-link btn-sm" id="verfiy_user_email">Verfiy Email</button>
                      <button type="button" class="btn btn-link btn-sm" id="otpEmailBtn" class="ms-4">Resend
                        OTP</button>
                      {{-- <a href="" id="otpHandleBtn">Resend OTP</a> --}}
                      <span id="msg" style="display:none"><b>OTP has been sent to your email</b></span>
                    </span>
                  </div>
                 
                </div>






{{-- end email otp --}}




              </div>
              <div class="row">
                <div class="col-6">
                  <div class="action">
                    {{-- <button type="submit" class="btn continue-btn w-100"> Send OTP</button> --}}
                    <button type="submit" class="btn continue-btn w-100" disabled id="registerBtn">Validate</button>
                  </div>
                </div>
                <div class="col-6">
                  <div class="action">
                  </div>
                </div>
              </div>
            {{-- </form> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
  $(document).ready(function()
  {
    $('#verfiy_user_sms').on('click',function()
    {
      let sms=document.getElementById('userOtp').value;

      if(sms===""|| sms===null)
      {
         
      }
     
    $.ajax({
      url:"checkusersms/"+sms,
      type:'get',
      datatype:"JSON",
      success:function()
      {
        
         
      }
    })
    })
   
  })

</script>