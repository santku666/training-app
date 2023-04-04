@extends('master-pages.body')
@section('content')
    <div class="container">
        <div class="card w-100 mt-3">
          
            <div class="card-body w-100">
              <h5 class="card-title">New User</h5>
              <form action="{{ url('/users') }}" method="POST">
                @csrf
                <div class="row d-flex justify-content-center">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Name</label>
                      <input type="text" name="name" value="{{old("name")}}" class="form-control">
                      @if ($errors->has('name'))
                          <label for="" class="text-danger">{{$errors->first('name')}}</label>
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                        <label for="">Email ID</label>
                        <input type="email" name="email" value="{{old("email")}}" class="form-control">
                        @if ($errors->has('email'))
                          <label for="" class="text-danger">{{$errors->first('email')}}</label>
                        @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                        <label for="">Mobile No</label>
                        <input type="number" name="mobile_no" value="{{old("mobile_no")}}" class="form-control">
                        @if ($errors->has('mobile_no'))
                          <label for="" class="text-danger">{{$errors->first('mobile_no')}}</label>
                        @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Password</label>
                      <input type="password" name="password" value="{{old("password")}}" id="password" class="form-control">
                      <input type="checkbox" id="visiblity-toggle"> show password
                      @if ($errors->has('password'))
                        <label for="" class="text-danger">{{$errors->first('password')}}</label>
                      @endif
                    </div>
                  </div>
                  <div class="row mt-2">
                    <div class="col-md-4 d-flex justify-content-end">
                      <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
    </div>
    <script>
        $(document).ready(function(){
          $(`#visiblity-toggle`).change((e)=>{
            $(e.target).is(":checked")==true?$('#password').prop('type','text'):$('#password').prop('type','password');
          });
        });
    </script>
@endsection
