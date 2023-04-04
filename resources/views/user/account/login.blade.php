@extends('master-pages.body')
@section('content')
    <div class="container">
      <div class="row mt-3">
      </div>
      <div class="d-flex justify-content-center">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
              <h5 class="card-title text-center">Login</h5>
              <form action="{{url('/user/login/perform')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Email ID</label>
                        <input type="email" name="email" value="{{old("email")}}" class="form-control">
                        @if ($errors->has("email"))
                            <label for="" class="text-danger">{{$errors->first("email")}}</label>
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label for="">Password</label>
                        <input type="password" name="password" value="{{old("password")}}" placeholder="******" class="form-control">
                        @if ($errors->has("password"))
                            <label for="" class="text-danger">{{$errors->first("password")}}</label>
                        @endif
                        <label for=""><a href="" style="text-decoration: none">Forgot Password ?</a></label>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
@endsection
