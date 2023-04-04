@extends('master-pages.body')
@section('content')
    <div class="container">
        <div class="card w-100 mt-3">
          
            <div class="card-body w-100">
              <h5 class="card-title">New Post</h5>
              <form action="{{ url('/user/posts') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row d-flex justify-content-center">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Title</label>
                      <input type="text" name="title" value="{{old("title")}}" class="form-control">
                      @if ($errors->has('title'))
                          <label for="" class="text-danger">{{$errors->first('title')}}</label>
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                        <label for="">Description</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10"></textarea>
                        @if ($errors->has('description'))
                          <label for="" class="text-danger">{{$errors->first('description')}}</label>
                        @endif
                    </div>
                  </div>
                  {{-- <div class="row">
                    <div class="col-md-4">
                        <label for="">Mobile No</label>
                        <input type="number" name="mobile_no" value="{{old("mobile_no")}}" class="form-control">
                        @if ($errors->has('mobile_no'))
                          <label for="" class="text-danger">{{$errors->first('mobile_no')}}</label>
                        @endif
                    </div>
                  </div> --}}
                  <div class="row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
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
            $('#description').summernote({
                height: 300
            });
        });
    </script>
@endsection
