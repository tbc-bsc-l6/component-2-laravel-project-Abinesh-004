{{-- resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - College Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verify-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card verify-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-envelope-check-fill text-primary" style="font-size: 4rem;"></i>
                            <h3 class="mt-3">Verify Your Email</h3>
                            <p class="text-muted">We've sent a verification link to your email address</p>
                        </div>

                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                A new verification link has been sent to your email address!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                        </div>

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send-fill"></i> Resend Verification Email
                                </button>
                            </div>
                        </form>

                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i> Check your spam folder if you don't see the email
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
