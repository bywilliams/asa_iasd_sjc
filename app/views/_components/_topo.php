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

<body>

    <!-- overlay -->
    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    <!-- sidebar -->
    <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 bg-white shadow sidebar" id="sidebar">
        <div class="text-center logo">
            <img src="/assets/imgs/asa.png" class="card-img-top" alt="..." id="logo-asa"> <span class="d-block mb-2">ASA IASD <br> S.J.C</span>
        </div>
        <div class="list-group rounded-0">
            <a href="/usuario/dashboard" class="list-group-item list-group-item-action active border-0 d-flex align-items-center">
                <span class="bi bi-speedometer icon-menu"></span>
                <span class="ml-2">Inicío</span>
            </a>
            <a href="/family/index" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-people icon-menu"></span>
                <span class="ml-2">Familias</span>
            </a>

            <a href="/event/index" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-calendar-event icon-menu"></span>
                <span class="ml-2">Eventos</span>
            </a>

            <a href="/food/index" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-archive icon-menu"></span>
                <span class="ml-2">Alimentos</span>
            </a>
<!-- 
            <button class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#product-collapse">
                <div>
                    <span class="bi bi-archive icon-menu"></span>
                    <span class="ml-2">Estoque</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button> -->
            <div class="collapse" id="product-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="/food/index" class="list-group-item list-group-item-action border-0 pl-5">Alimentos</a>
                    <!-- <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Roupas</a> -->
                </div>
            </div>

            <button class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#sale-collapse">
                <div>
                    <span class="bi bi-cash icon-menu"></span>
                    <span class="ml-2">Financeiro</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="sale-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Receitas</a>
                    <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Despesas</a>
                </div>
            </div>

            <button class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#purchase-collapse">
                <div>
                    <span class="bi bi-bookmark icon-menu"></span>
                    <span class="ml-2">Categorias</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="purchase-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Receitas | Despesas</a>
                    <!-- <a href="#" class="list-group-item list-group-item-action border-0 pl-5">Despesas</a> -->
                </div>
            </div>
            <a href="/usuario/equipe-asa" class="list-group-item list-group-item-action border-0 align-items-center">
                <span class="bi bi-person-square icon-menu"></span>
                <span class="ml-2">Equipe</span>
            </a>
        </div>
    </div>

    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- top nav -->
        <nav class="w-100 d-flex px-4 py-2 shadow-sm">
            <!-- close sidebar -->
            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-dark h3"></span>
            </button>
            <div class="dropdown ml-auto">
                <button class="btn py-0 d-flex align-items-center" id="logout-dropdown" data-toggle="dropdown" aria-expanded="false">
                    <span class="bi bi-person text-dark h4"></span>
                    <span class="bi bi-chevron-down ml-1 mb-2 small"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm" aria-labelledby="logout-dropdown">
                    <div class="text-center mx-auto">
                        <img src="/assets/imgs/user.png" width="60" height="60"></img>
                        <p> 
                            <?= $this->e($user->username) ?>
                        </p>
                    </div>
                    <hr class="hr w-75">
                    <a class="dropdown-item" href="#">Configurações</a>
                    <a class="dropdown-item" href="/usuario/logout">Sair</a>
                </div>
            </div>
        </nav>
    
    