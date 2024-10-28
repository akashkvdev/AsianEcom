<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">

	{{-- <link rel="stylesheet" href="{{ asset('css/login.css') }}"> --}}
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('css/adminlte.min.css')}}">
	<link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(45deg, #2980b9, #6dd5ed);
            background-size: 400% 400%;
            animation: gradient-animation 15s ease infinite;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #bdc3c7;
        }

        .form-control:focus {
            border-color: #2980b9;
            box-shadow: 0 0 0 0.2rem rgba(41, 128, 185, 0.25);
        }

        .btn-primary {
            border-radius: 5px;
            padding: 10px;
            font-weight: bold;
            background-color: #2980b9;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1a5276;
        }

        .text-center a {
            color: #2980b9;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px 0 0 5px;
        }

        .invalid-feedback {
            display: block; /* Ensure error message is visible */
            font-size: 0.875em; /* Smaller font for error messages */
            color: #dc3545; /* Bootstrap's danger color */
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
		<h2 class="text-center">Login</h2>
		@include('admin.message')
        <form action="{{ route('admin.authenticate') }}" method="post">
            @csrf
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email address" value="{{ old('email') }}" >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-3">
            <a href="#">Forgot your password?</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script src="{{ asset('plugins/jquery/jquery.min.js')}}"></script>
		<!-- Bootstrap 4 -->
		<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="js/demo.js"></script>
</body>

</html>
