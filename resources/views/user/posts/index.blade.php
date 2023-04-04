@extends('master-pages.body')
@section('content')
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Posts</li>
        </ol>
      </nav>
      <div class="row mt-3">
        <div class="col-md-6">
          <form class="d-flex" action="{{url('/user/posts')}}" method="GET">
            <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
      @isset($posts)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Created At</th>
                    <th scope="col"><a href="{{ url('/user/posts/create') }}" role="button" class="btn btn-sm btn-primary">New Post</a></th>
                </tr>
            </thead>
            <tbody>
                @if ($posts->total() > 0)
                    @foreach ($posts as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->title}}</td>
                            <td>{{$item->created_at}}</td>
                            <td style="width: 150px">
                              <div class="d-flex justify-content-around">
                                <a href="{{url("/user/posts/$item?->id/edit")}}" class="btn btn-info btn-sm"><i class="fas fa-pen-square text-white"></i></a>
                                <a href="{{url("/user/posts/$item?->id/view")}}" class="btn btn-info btn-sm"><i class="fas fa-eye text-white"></i></a>
                                <form action="{{url("/user/posts/$item?->id")}}" method="POST">
                                  @method('delete')
                                  @csrf
                                  <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash text-white"></i></button>
                                </form>
                              </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                  <tr>
                    <td colspan="5" class="text-center">No Records Found</td>
                  </tr>
                @endif
            </tbody>
        </table>
        <div class="pagination">
          {{$posts->withQueryString()->links()}}
        </div>
      @endisset
    </div>
@endsection
