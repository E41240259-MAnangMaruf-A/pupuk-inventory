<?php $page = 'signin'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="account-content">
    <div class="login-wrapper bg-img bg-login">
        <div class="login-content authent-content">
            <form action="{{ route('signin.custom') }}" method="POST">
                @csrf
                <div class="login-userset">
                    <!-- <div class="login-logo logo-normal">
                        <img src="{{URL::asset('build/img/logo.svg')}}" alt="img">
                    </div>
                    <a href="{{url('index')}}" class="login-logo logo-white">
                        <img src="{{URL::asset('build/img/logo-white.svg')}}"  alt="Img">
                    </a> -->
                    <div class="login-userheading">
                        <h3>Sign In</h3>
                        <h4 class="fs-16">Masukkan email dan password untuk mengakses aplikasi.</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    </div>
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger"> *</span></label>
                            <div class="input-group">
                                <input type="text" name="email" value="" class="form-control border-end-0">
                                <span class="input-group-text border-start-0">
                                    <i class="ti ti-mail"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger"> *</span></label>
                            <div class="pass-group">
                                <input type="password" name="password" class="pass-input form-control">
                                <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                            </div>
                        </div>
                        <div class="form-login authentication-check">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <div class="custom-control custom-checkbox">
                                        <label class="checkboxs ps-4 mb-0 pb-0 line-height-1 fs-16 text-gray-6">
                                            <input type="checkbox" class="form-control">
                                            <span class="checkmarks"></span>Ingat Saya
                                        </label>
                                    </div>
                                    <div class="text-end">
                                        <a class="text-orange fs-16 fw-medium" href="{{url('forgot-password')}}">Lupa
                                            Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </div>
                        <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                            <p>Copyright &copy; 2025 DreamsPOS</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="login-image">
                <img src="{{ asset('build/img/authentication/login.jpg') }}" alt="Login Background"
                    class="img-fluid w-100 h-100 object-fit-cover">
            </div>

        </div>
    </div>
@endsection