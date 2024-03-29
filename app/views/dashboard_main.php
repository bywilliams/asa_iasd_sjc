<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<!-- Finance Cards Section -->
<section class="row">
    <div class="col-md-6 col-lg-3">
        <!-- card -->
        <article class="p-4 rounded shadow border-left mb-3 ">
            <h4 class="text-dark">Receitas</h4>
            <div class="text-success">
                <h2>R$ <?= $totalRevenue ?? 0 ?></h2>
            </div>
        </article>
    </div>
    <div class="col-md-6 col-lg-3">
        <article class="p-4 rounded shadow border-left mb-3">
            <h4 class="text-dark">Despesas</h4>
            <div class="text-danger">
                <h2>R$ <?= $totalExpense ?? 0 ?></h2>
            </div>
        </article>
    </div>
    <div class="col-md-6 col-lg-3">
        <article class="p-4 rounded shadow border-left mb-3">
            <h4 class="text-dark">Saldo</h4>
            <div class="<?= $totalBalance > 0 ? 'text-success' : 'text-danger' ?> ">
                <h2>R$ <?= $totalBalance ?? 0 ?></h2>
            </div>

        </article>
    </div>
    <div class="col-md-6 col-lg-3">
        <article class="p-3 rounded shadow border-left my-1">

            <div class="text-info">
                <h5>Famílias atívas: <span class="text-dark"><?= $totalActiveFamilies ?? 0 ?></span></h5>
                <h5>Total de alimentos: <span class="text-dark"><?= $totalStockFoods ?? 0 ?></span></h5>
                <h5>Total de cestas: <span class="text-dark"><?= $totalBaskets ?? 0 ?></span></h5>
            </div>
        </article>
    </div>
</section>
<!-- End Finance Cards Section -->

<?php if ($user->nivel_acesso == 1) : ?>
    <!-- Action Section -->
    <section class="p-2 my-3 bg-white rounded shadow-sm border-left acesso">
        <h4 class="font-weight-normal text-center mb-2 text-secondary">Acesso rápido</h4>
        <div class="row offset-lg-1">
            <div class="container row mx-auto my-2">
                <div class="col-lg-2 col-md-6 mb-2">
                    <button type="button" class="d-flex justify-content-center btn btn-outline-info btn-block" data-toggle="modal" data-target="#revenue_create">
                        <span class="bi bi-cash-stack icon-menu"> Transação</span>
                    </button>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <button type="button" class="d-flex justify-content-center btn btn-outline-dark btn-block" data-toggle="modal" data-target="#food_create">
                        <span class="bi"><i class="fa-solid fa-utensils icon-menu"></i> Estoque</span>
                    </button>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <button type="button" class="d-flex justify-content-center btn btn-outline-primary btn-block" data-toggle="modal" data-target="#family_create">
                        <span class="bi"><i class="fa-solid fa-people-roof icon-menu"></i> Familia</span>
                    </button>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <button type="button" class="d-flex justify-content-center btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#event_create">
                        <span class="bi"><i class="fa-solid fa-map-location-dot icon-menu"></i> Evento</span>
                    </button>
                </div>
                <div class="col-lg-2 col-md-6 mb-2">
                    <button type="button" class="d-flex justify-content-center btn btn-outline-success btn-block" data-toggle="modal" data-target="#basket_donated" <?= $totalBaskets <= 0 ? 'disabled' : null ?>>
                        <span class="bi"><i class="fa-solid fa-basket-shopping icon-menu"></i> Doar cesta</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!-- Action Section -->
<?php endif ?>

<!-- Events Section -->
<section class="p-2 my-3  bg-white rounded shadow-sm border-left events">
    <div class="container">
        <h3 class="text-secondary text-center mb-3">Calendário de eventos</h3>
        <div class="row text-center">
            <div class="col-lg-6 col-md-12">
                <div class="card border-0 mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="/assets/imgs/calendar.png" alt="calendario">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">Hoje: <?= date('d-m-y') ?></h5>
                                <?php if (count($todayEvent) > 0) : ?>
                                    <p class="text-secondary font-weight-bold">Há um evento marcado para hoje, fique atento!</p>
                                <?php else : ?>
                                    <p class="card-text">Nenhum evento marcado para este dia.</p>
                                    <a href="#!" data-toggle="modal" data-target="#event_create">
                                        <p class="card-text text-info">Clique aqui para criar um evento.</p>
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mt-2 ">
                <table class="table table-sm table-bordered rounded table-events">
                    <tbody class="text-center">
                        <?php if (count($latestEvents) > 0) : ?>
                            <?php foreach ($latestEvents as $event) : ?>
                                <tr>
                                    <td>
                                        <h5><?= $event->name ?></h5>
                                    </td>
                                    <td><?= date("d-m-Y H:i:s", strtotime($event->event_date)) ?> </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <h5 class="text-info">Não há nenhum evento cadastrado.</h5>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</section>
<!-- End Events Section -->

<!-- Food Products Section  -->
<section class="row">
    <h3 class="text-secondary text-center mx-auto ">Inventário de alimentos</h3>
    <table class="content-table mx-2 text-center">
        <thead>
            <tr class="text-center">
                <th>id</th>
                <th>Name</th>
                <th>Qtde</th>
                <th>Usuário</th>
                <th>Cadastrado em</th>
                <th>Atualizado em</th>
                <?php if ($user->nivel_acesso == 1) : ?>
                    <th></th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($latestStockFoods as $food) : ?>
                <tr>
                    <td>
                        <?= $food->id ?>
                    </td>
                    <td>
                        <?= $food->name ?>
                    </td>
                    <td>
                        <?= $food->qtde ?>
                    </td>
                    <td>
                        <?= $food->author ?>
                    </td>
                    <td>
                        <?= date("d-m-Y", strtotime($food->created_at)) ?>
                    </td>
                    <td>
                        <?= isset($food->updated_at) ? date("d-m-Y", strtotime($food->updated_at)) : '' ?>
                    </td>
                    <?php if ($user->nivel_acesso == 1) : ?>
                        <td>
                            <div style="display: flex; justify-content: space-evenly">
                                <a href="#!" onclick="openModalEdit()">
                                    <i class="fa-regular fa-pen-to-square icon-menu"></i>
                                </a>
                                <a href="#!" onclick="openModalDelete()">
                                    <i class="fa-solid fa-trash icon-menu"></i>
                                </a>
                            </div>
                        </td>
                    <?php endif ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<!-- End Food Products Section  -->

<?= $this->insert('_components/_bootstrap/_modals/_dashboard_main', ['user' => $user, 'getActiveFamilies' => $getActiveFamilies, 'revenueCategories' => $revenueCategories, 'expenseCategories' => $expenseCategories, 'allFoods' => $allFoods]); ?>

<?= $this->end() ?>