<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
</head>
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .login-form {
        max-width: 400px;
        width: 90%;
        transition: transform 0.3s ease;
    }

    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #357abd;
        border-color: #357abd;
        transform: translateY(-1px);
    }

    .btn-primary:active {
        transform: translateY(1px);
    }

    .form-control {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    @media (max-width: 576px) {
        .login-form {
            padding: 1.5rem !important;
        }

        .btn-lg {
            padding: 0.75rem 1rem;
        }
    }

    .invalid-feedback {
        display: none;
        font-size: 0.875rem;
        color: #dc3545;
        margin-top: 0.25rem;
    }

    .form-control.is-invalid~.invalid-feedback {
        display: block;
    }
</style>

<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="login-form p-4 bg-white shadow-lg rounded-4">
            <div class="text-center mb-4">
                <img src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=120&h=120" alt="Profile"
                    class="rounded-circle mb-3">
                <h2 class="fw-bold">Iniciar Sesión</h2>
            </div>
            <form id="loginForm" action="<?= base_url('/login/authenticate') ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-lg" name="username" id="username" required
                        aria-describedby="emailFeedback">
                    <div id="emailFeedback" class="invalid-feedback">Por favor ingrese un Nombre de usuario válido.
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" name="password" id="password"
                            required minlength="8" aria-describedby="passwordFeedback" autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                            aria-label="Mostrar contraseña">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    <div id="passwordFeedback" class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="submitBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Iniciar Sesión
                </button>
                <div class="text-center">
                    <a href="<?= base_url('PasswordController/forgotPassword') ?>"
                        class="text-decoration-none">¿Olvidaste tu
                        contraseña?</a>
                </div>
            </form>
            <?php if (isset($error)): ?>
                <br>
                <div class="alert alert-danger text-center" role="alert">
                    <?= esc($error) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                const togglePasswordIcon = $('#togglePasswordIcon');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    togglePasswordIcon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    togglePasswordIcon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });
        });
    </script>
</body>

</html>