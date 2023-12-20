<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>MKM Pallet Tracing</title>
        <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
        <link rel="icon" href="{{ asset('assets/img/logo_kop2.gif') }}">
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container-xl px-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <!-- Basic login form-->
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    
                                    <div class="card-header text-center justify-content-center"><img class="animation__shake" src="{{ asset('assets/img/Logo Option 3 (1).png') }}" alt="MatrixLogo" height="50" width="180"></div>
                                    
                                    <div class="card-body">
                                        <!--alert success -->
                                        @if (session('statusLogin'))
                                        <div class="alert alert-warning" role="alert">
                                            <strong>{{ session('statusLogin') }}</strong>
                                        </div> 
                                        @elseif(session('statusLogout'))
                                        <div class="alert alert-success" role="alert">
                                            <strong>{{ session('statusLogout') }}</strong>
                                        </div> 
                                        @endif

                                        <!--alert success -->
                                        <h1 class="text-center" >Mitsubishi Krama Yudha Motors and Manufacturing</h1>
                                        <!-- Login form-->
                                        <form action="{{ url('auth/login') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <!-- Form Group (email address)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter email address" name="email"/>
                                            </div>
                                            <!-- Form Group (password)-->
                                            <div class="mb-3">
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" name="password"/>
                                            </div>
                                            <!-- Form Group (remember password checkbox)-->
                                            {{-- <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="rememberPasswordCheck" type="checkbox" value="" />
                                                    <label class="form-check-label" for="rememberPasswordCheck">Remember password</label>
                                                </div>
                                            </div> --}}
                                            <!-- Form Group (login box)-->
                                            <div class=" text-center  mb-0">
                                                <button type="submit" class="btn btn-dark">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center justify-content-center">
                                        <div class="col-12 small">Copyright PT Mitsubishi Krama Yudha Motors and Manufacturing&copy; 2023</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('assets/js/scripts.js')}}"></script>
    </body>
</html>
