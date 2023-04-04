@extends('master-pages.body')
@section('content')
    <div class="container">
      <div class="row mt-3">
        <div class="col-md-6">
          <form class="d-flex" action="{{url('/users')}}" method="GET">
            <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
        </div>
      </div>
      @isset($users)
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Contact No</th>
                    <th scope="col"><a href="{{ url('/users/create') }}" role="button" class="btn btn-sm btn-primary">New User</a></th>
                </tr>
            </thead>
            <tbody>
                @if ($users->total() > 0)
                    @foreach ($users as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->mobile_no}}</td>
                            <td style="width: 100px">
                              <div class="d-flex justify-content-around">
                                <a href="{{url("/users/$item?->id/edit")}}" class="btn btn-info btn-sm"><i class="fas fa-pen-square text-white"></i></a>
                                <form action="{{url("/users/$item?->id")}}" method="POST">
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
          {{$users->withQueryString()->links()}}
        </div>
      @endisset
    </div>
@endsection
