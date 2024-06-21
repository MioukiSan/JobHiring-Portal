<nav class="navbar navbar-expand-lg bg-body-light p-0">
    <div class="container rounded-bottom-5 shadow pl-2">
        <a class="navbar-brand fs-6 dost-color text fw-bold d-flex align-middle" href="{{route('home')}}">
            <img src="{{asset('assets/image 1.png')}}" alt="Logo" width="50" height="50" class="p-0">
            <img src="{{asset('assets/DEPARTMENT OF SCIENCE AND TECHNOLOGY REGION V - BICOL.png')}}" class="img-thumbnail border-0 m-2" style="height: 32px;" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#buttonnavbar" aria-controls="buttonnavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="buttonnavbar">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <button class="btn" data-bs-target="#loginToggle" data-bs-toggle="modal">LOG IN</button>
                    </li>
                    <li class="nav-item">
                        <button class="btn" style="color: #000789;" data-bs-target="#dataPrivacy" data-bs-toggle="modal">REGISTER</button>
                    </li>
                @else
                    @if (Auth::user()->usertype == 'user')
                        <li class="nav-item dropdown" id="dropdownNotif">
                            <a href="#" class="nav-link position-relative" id="dropdown-notif" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <ion-icon name="notifications" size="large" style="color: #000789;"></ion-icon>
                                <span class="position-absolute top-6 start-80 translate-middle badge rounded-pill bg-light text-dark shadow border-2" id="unread-count">
                                    0
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-notif" id="notifications-menu">
                                <p>
                                    
                                </p>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <ion-icon size="large" name="person-circle" style="color: #000789;"></ion-icon>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile.index') }}">Account Profile</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @elseif (Auth::user()->usertype == 'guest')
                        <li class="nav-item">
                            <button class="btn btn-link nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>
</nav>
<div class="modal fade" id="dataPrivacy" aria-hidden="true" aria-labelledby="PrivacyLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="fw-bold m-4" style="color: #000789;">DATA PRIVACY ACT</h3>
                            <span>To provide you with the best job search experience, we need your consent to collect and process your personal data. By using our services, you agree to our Privacy Policy and allow us to collect, process, and store your personal information. Click "I agree" to continue.</span>
                            <div class="d-grid gap-2 m-3 d-md-flex justify-content-md-center">
                                <button class="btn border rounded-1 btn-sm me-md-2 text-light" style="background-color: #000789;" data-bs-target="#signuptoggle" data-bs-toggle="modal">AGREE</button>
                                <button type="button" class="btn border btn-sm rounded-1" data-bs-dismiss="modal">CANCEL</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="loginToggle" aria-hidden="true" aria-labelledby="eloginToggle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 justify-content-center">
                            <h2 class="text-center py-4" style="color: #000789;">LOG IN <i class="bi bi-box-arrow-right"></i></h2>
                            <form method="POST" action="{{ route('login') }}" class="px-4 py-2">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                    <label for="floatingPassword">Password</label>
                                </div>
                                 <div class="form-group clearfix">
                                    <div class="icheck d-inline">
                                        <input type="checkbox" name="remember">
                                        <label for="remember">
                                        Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center py-2">
                                    <button type="submit" class="btn text-light text-center" style="background-color: #000789;">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mb-3">
                <button class="btn" data-bs-target="#dataPrivacy" data-bs-toggle="modal">Doesn't have an account yet?</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="signuptoggle" aria-hidden="true" aria-labelledby="signuptoggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container justify-content-center">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center py-3" style="color: #000789;">REGISTER <i class="bi bi-person-add"></i></h2>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <label for="name" class="form-label">Fullname</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter your full name" required>
                                </div>
                                <div class="mb-2">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                                </div>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control form-control-sm" name="address" placeholder="Enter your address" required>
                                        </div>
                                        <div class="col-6">
                                            <label for="number" class="form-label">Contact Number</label>
                                            <input type="text" class="form-control" name="number" placeholder="Enter your number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                                </div>
                                <div class="mb-2">
                                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password" required>
                                </div>
                                <div class="text-center py-2">
                                    <button type="submit" class="btn text-light" style="background-color: #000789;">Sign Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mb-3">
                <button class="btn text-underline" data-bs-target="#loginToggle" data-bs-toggle="modal">Already have an account?</button>
            </div>
        </div>
    </div>
</div>
