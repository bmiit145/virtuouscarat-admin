<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ecommerce Laravel - Login Panel</title>
    @include('backend.layouts.head')
</head>

<body class="bg-gradient-info" style="HEIGHT: 100vh;">

    <div class="container h-100">

        <!-- Outer Row -->
        <div class="row justify-content-center h-100 align-items-center">

            <div class="col-xl-10 col-lg-12 col-md-9 mt-5">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('images/virtuouscarat-logo.png') }}" atl="virtuouscarat-logo"
                                    width=80%;>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Password Create</h1>
                                    </div>
                                    <form class="user" method="POST"
                                    action="{{ route('login.updatePassword', $user->id) }}">
                                        @csrf
                                        <div class="form-group">
                                            <input type="password"
                                                class="form-control form-control-user @error('password') is-invalid @enderror"
                                                id="password" placeholder="Password" name="password" required
                                                autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <input type="password"
                                                class="form-control form-control-user @error('password_confirmation') is-invalid @enderror"
                                                id="password_confirmation" placeholder="Confirm Password"
                                                name="password_confirmation" required autocomplete="current-password">
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-success btn-user btn-block">
                                            Update
                                        </button>
                                    </form>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Visit 'codeastro' for more projects -->
        </div>

    </div>
</body>

</html>
