@extends('master-pages.body')
@section('content')
    <div class="container">
      <div class="row mt-3">
      </div>
      <div class="d-flex justify-content-center">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
              <h5 class="card-title text-center">OTP VERIFICATION</h5>
              <form>
                <div class="row" id="email-div">
                    <div class="col-md-12">
                        <label for="">Email ID</label>
                        @php
                            $email=Auth::user()->email!=""?Auth::user()->email:"";
                        @endphp
                        <input type="email" name="email" value="{{$email}}" id="email" readonly class="form-control">
                    </div>
                </div>
                <div class="row mt-3 d-none" id="otp-div">
                    <div class="col-md-12">
                        <label for="">OTP</label>
                        <input type="number" name="otp" id="otp" placeholder="XXXXXX" class="form-control">
                        <label for=""><a role="button" disabled id="resend-link-btn" style="text-decoration: none">Resend OTP </a></label>
                        <label for="" id="timer-preview"></label>
                        <p>
                            <label for="" id="otp-err" class="text-danger"></label>
                        </p>
                    </div>
                </div> 
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button class="btn btn-primary w-100 btn-sm" id="generate-otp-btn" type="button">SEND OTP</button>
                        <button class="btn btn-primary w-100 btn-sm d-none" id="verify-otp-btn" type="button">VERIFY OTP</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
    <script>
        var Verification={
            verification_obj:{
                isOTPSent:false,
                TimerStarted:false,
                TimerStopped:false,
                timesOtpSent:0
            },
            getVerificationObj:function(){
                return localStorage.getItem('VERIFICATION_OBJ')==undefined? this.verification_obj : JSON.parse(localStorage.getItem('VERIFICATION_OBJ'));
            },
            setVerificationObj:function(){
                if (localStorage.getItem('VERIFICATION_OBJ')==undefined || typeof(localStorage.getItem('VERIFICATION_OBJ'))!=="object") {
                    localStorage.setItem('VERIFICATION_OBJ',JSON.stringify(this.verification_obj));
                }
            },
            submitOtp:function(){
                try {
                    if ($('#otp').val()=="") {
                        $('#otp-err').html("Please Provide OTP");
                    }else{
                        $('#otp-err').html(``);
                        axios.post(`http://localhost:8000/api/user/verify-email/verify-otp`,{
                            email:$('#email').val(),
                            otp:$('#otp').val()
                        }).then(
                            function(response){
                                Verification.verification_obj.TimerStarted=false;
                                Verification.verification_obj.TimerStopped=true;
                                Verification.verification_obj.isOTPSent=true;
                                console.log(Verification.verification_obj);
                                Verification.verification_obj.timesOtpSent=Verification.verification_obj.timesOtpSent + 1;
                                Verification.setVerificationObj();
                            }
                        ).catch(
                            function(error){
                                console.log(error);
                                console.log("Axios Error Occured"+error);
                                if (error.response.status==302) {
                                    window.location="{{url('/user/posts')}}";
                                }else{
                                    $('#otp-err').html(error.response.data.message);
                                }
                            }
                        );
                    }
                } catch (error) {
                    console.log("Error ---"+error);
                }
            },
            sendOtp:function(){
                try {
                    $('#otp-err').html(``);
                    axios.post(`http://localhost:8000/api/user/verify-email/send-otp`,{
                        email:$('#email').val()
                    }).then(
                        function(response){
                            Verification.verification_obj.TimerStarted=true;
                            Verification.verification_obj.TimerStopped=false;
                            Verification.verification_obj.isOTPSent=true;
                            console.log(Verification.verification_obj);
                            Verification.verification_obj.timesOtpSent=Verification.verification_obj.timesOtpSent + 1;
                            Verification.setVerificationObj();
                            Verification.ToggleVerification();
                        }
                    ).catch(
                        function(error){
                            console.log("Axios Error Occured"+error);
                            Verification.verification_obj.TimerStarted=false;
                            Verification.verification_obj.TimerStopped=true;
                            Verification.verification_obj.isOTPSent=false;
                            Verification.setVerificationObj();

                            
                        }
                    );
                } catch (error) {
                    console.log("Error ---"+error);
                    this.verification_obj.TimerStarted=false;
                    this.verification_obj.TimerStopped=false;
                    this.verification_obj.isOTPSent=false;
                    this.setVerificationObj();
                }
            },
            ToggleVerification:function(){
                $('#email-div').addClass('d-none');
                $('#otp-div').removeClass('d-none');
                $('#verify-otp-btn').removeClass('d-none');
                $('#generate-otp-btn').addClass('d-none');
            },
            init:function(){
             this.setVerificationObj();
             console.log(this.getVerificationObj());   
            }
        }

        function get_elapsed_time_string(total_seconds) {
            function pretty_time_string(num) {
                return ( num < 10 ? "0" : "" ) + num;
            }

            var hours = Math.floor(total_seconds / 3600);
            total_seconds = total_seconds % 3600;

            var minutes = Math.floor(total_seconds / 60);
            total_seconds = total_seconds % 60;

            var seconds = Math.floor(total_seconds);

             // Pad the minutes and seconds with leading zeros, if required
            hours = pretty_time_string(hours);
            minutes = pretty_time_string(minutes);
            seconds = pretty_time_string(seconds);

            // Compose the string for display
            var currentTimeString = hours + ":" + minutes + ":" + seconds;

             return currentTimeString;
        }

        // get_elapsed_time_string();

        var elapsed_seconds = 60;
        var timer=setInterval(function() {
            startTimer=Verification.getVerificationObj();
            if (startTimer.TimerStarted===true) {
                elapsed_seconds = elapsed_seconds - 1;
                if (elapsed_seconds <= 0) {
                    stopTimer();
                    $('#resend-link-btn').prop('disabled',false);
                    Verification.verification_obj.TimerStarted=false;
                    Verification.verification_obj.TimerStopped=true;
                    Verification.verification_obj.isOTPSent=true;
                    Verification.setVerificationObj();
                }
                $('#timer-preview').html(get_elapsed_time_string(elapsed_seconds));
                // console.log(get_elapsed_time_string(elapsed_seconds));
            }
        }, 1000);

        function stopTimer(){
            clearInterval(timer);
        }

        $(document).ready(()=>{
            Verification.init();
            $('#generate-otp-btn').click(function(){
               Verification.sendOtp(); 
            });

            $('#verify-otp-btn').click(function(){
                Verification.submitOtp();
            });
        });
    </script>
@endsection
