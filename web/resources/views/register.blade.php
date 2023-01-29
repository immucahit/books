<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{config('app.name')}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="vh-100 d-flex justify-content-center align-items-center">
      <div class="container">
        <div class="row d-flex justify-content-center">
          <div class="col-12 col-md-8 col-lg-6">
            <div class="card bg-white">
              <div class="card-body p-5">
                <form class="mb-3 mt-md-4" method="post" action="{{route('user.register')}}">
                  @csrf
                  <h2 class="fw-bold mb-2 text-uppercase ">Register</h2>
                  @if ($errors->any())
                    <div class="mb-3">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                  </div>
                @endif
                  <div class="mb-3">
                    <label for="displayName" class="form-label ">Full Name</label>
                    <input type="text" class="form-control" name="displayName" id="displayName" value="{{ old('displayName') }}">
                  </div>
                  <div class="mb-3">
                    <label for="username" class="form-label ">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label ">Password</label>
                    <input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}">
                  </div>
                  <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Sign Up</button>
                  </div>
                </form>
                <div>
                  <p class="mb-0  text-center"><a href="{{route('user.login')}}" class="text-primary fw-bold">Login</a></p>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>