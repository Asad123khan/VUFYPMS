<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
 <link href="{{ asset('css/login-logout-common.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="min-vh-100 d-flex align-items-center justify-content-center py-md-5 py-3 auth-bg-custom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                        <div class="text-center mb-4">
                            <a href="/" class="text-decoration-none">
                                <x-application-logo style="width: 80px; height: 80px;" class="text-secondary" />
                            </a>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4 p-md-5">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
