<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAewsome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <!-- SweeetAlert 2 para avisos internos na plataforma -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">

    <!-- Estilos locais -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <title><?= $this->e($title) ?></title>
</head>

<body class="bg-dark">

<div class="container d-flex justify-content-center align-items-center vh-100" >
    <div class="row content">
        <div class="col-md-6 text-center">
            <h2>ASA IASD</h2>
            <h5>S.J.C</h5>
            <img src="assets/imgs/asa.png" width="55%" height="auto" alt="logo_asa">
        </div>
        <div class="col-md-6 login">
            
            <h3 class="signin-text"> Login</h3>
            <h6 class="mb-4">Acesso exclusivo administrativo.</h6>
            <form action="/usuario/login" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <button class="btn btn btn-outline-success">Entrar</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '_components/_footer.php' ?>

