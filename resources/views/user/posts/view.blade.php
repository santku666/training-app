@extends('master-pages.body')
@section('content')
    <div class="container">
        <div class="card w-100 mt-3">
          
            <div class="card-body w-100">
                @isset($post)
                    <h5 class="card-title">{{$post?->title}}</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $post?->description?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end">
                                <span class="text-left">Author</span>
                            </div>
                            <div class="d-flex justify-content-end">
                                <p><b>{{$post?->user['name']}}</b></p>
                            </div>
                        </div>
                    </div>
                @endisset
              
            </div>
          </div>
    </div>
    <script>
        $(document).ready(function(){

        });
    </script>
@endsection
