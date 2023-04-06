<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Blogs</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ url('/users') }}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/users') }}">Users</a>
          </li>
          @guest
          <li class="nav-item">
            <a class="nav-link" href="{{url("/user/login")}}">Login</a>
          </li>
          @endguest
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                My Account
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{url('/user/posts')}}">Posts</a></li>
                <li><a class="dropdown-item" href="{{url('/user/logout')}}">Logout</a></li>
              </ul>
            </li>
            @php
              (bool)$IsEmailVerified=Auth::user()->email_verified_at!=null?true:false;
            @endphp
            @if ($IsEmailVerified==false)
              <li class="nav-item">
                <a class="nav-link" href="{{url("/user/verify-email")}}">Verify Email</a>
              </li>
            @endif
          @endauth
        </ul>
      </div>
    </div>
  </nav>