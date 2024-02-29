<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1 class="text-center">Alimentos</h1>

<!-- Family section form -->
<section>
    <div class="bg-light p-3 mb-4 rounded">

        <div class="row mx-4">
            <div class="col-lg-12 d-flex justify-content-between">
                <h3 class="text-secondary mb-3">Pesquisar:</h5>
                    <button class="btn btn-outline-secondary mr-2" id="limparCampos" title="Limpa todos os campos">Limpar</button>
            </div>
        </div>

        <form method="get" id="meuFormulario">
            <input type="hidden" name="page" id="page" value="<?= $page ?>">
            <div class="row mx-4">
                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <label for="id">Por id:</label>
                        <input type="number" name="id" id="id" class="form-control inputForm" placeholder="ex: 5" value="<?= isset($old['id']) ? $old['id'] : ''  ?>" oninput="verificarInput()">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="food">Por alimento:</label>
                        <select name="food_id" id="food" class="form-control inputForm" oninput="verificarInput()">
                            <option value="">Selecione</option>
                            <?php foreach ($foodsList as $food) : ?>
                                <option value="<?= $food->id ?>" <?= isset($old['food_id']) && $old['food_id'] == $food->id ? 'selected' : ''  ?>><?= $food->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="basket">Por tipo:</label>
                        <select name="basic_basket" id="basket" class="form-control inputForm" oninput="verificarInput()">
                            <option value="">Selecione</option>
                            <option value="S" <?= isset($old['basic_basket']) && $old['basic_basket'] == 'S' ? 'selected' : ''  ?>>Alimentos da Cesta Básica</option>
                            <option value="N" <?= isset($old['basic_basket']) && $old['basic_basket'] == 'N' ? 'selected' : ''  ?>>Alimentos Fora da Cesta Básica</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="user">Por usuário:</label>
                        <select name="user_id" id="user" class="form-control inputForm" oninput="verificarInput()">
                            <option value="">Selecione</option>
                            <?php foreach ($usersList as $user) : ?>
                                <option value="<?= $user->id ?>" <?= isset($old['user_id']) && $old['user_id'] == $user->id ? 'selected' : ''  ?>><?= $user->nome ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-1 col-md-12 text-center">
                    <button type="submit" class="btn btn-success px-3 shadow-sm" id="search" value="Buscar">Buscar
                </div>
            </div>
        </form>
    </div>
</section>
<!-- End Family section form -->

<!-- Family table section -->
<section>
    <table class="table table-bordered content-table table-hover ">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Qtde</th>
                <th>Usuário</th>
                <th>Cadastrado em</th>
                <th>Atualizado em</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalItens = 0;
            $totalFoods = 0;
            $currentPage = $page;
            $itemsPerPage = 10;
            ?>
            <?php foreach ($foodsStock['data'] as $food) : ?>
                <tr>
                    <td><?= $food->id ?></td>
                    <td><?= $food->name ?></td>
                    <td><?= $food->qtde ?></td>
                    <td><?= $food->author ?></td>
                    <td><?= date("d-m-Y", strtotime($food->created_at)) ?> </td>
                    <td><?= isset($food->updated_at) ? date("d-m-Y", strtotime($food->updated_at)) : '' ?></td>
                    <td>
                        <div class="d-flex" style="justify-content: space-evenly">
                            <a href="#!" onclick="openModalEdit(<?= $food->id ?>)" title="Editar">
                                <i class="fa-regular fa-pen-to-square icon-menu"></i>
                            </a>
                            <a href="#!" onclick="openModalDelete(<?= $food->id ?>)">
                                <i class="fa-solid fa-trash icon-menu"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                $totalItens += 1;
                $totalFoods += $food->qtde; 
                ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="text-center">
                <td colspan="9">
                    <strong class="mr-2">
                        Total de registros: <?= min($totalItens + (($currentPage - 1) * $itemsPerPage), $foodsStock['totalRegistros']) . ' de ' .  $foodsStock['totalRegistros'] ?>
                    </strong>
                </td>
            </tr>
            <tr class="text-center">
                <td colspan="9">
                    <strong class="mr-2">
                        Total de alimentos página <?= $page ?>: <?= $totalFoods ?>
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>


    <!-- pagination -->
    <?php if ($totalPaginas > 1) : ?>
        <nav aria-label="Page navigation ">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="/food/index?page=<?= $i ?><?= isset($old['id']) && $old['id'] != null ? "&id={$old['id']}" : null ?><?= isset($old['food_id']) && $old['food_id'] != null ? "&food_id={$old['food_id']}" : null ?><?= isset($old['user_id']) && $old['user_id'] != null ? "&user_id={$old['user_id']}" : null ?><?= isset($old['basic_basket']) && $old['basic_basket'] != null ? "&basic_basket={$old['basic_basket']}" : null ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
    <!-- pagination -->


</section>
<!-- End Family table section -->

<?= $this->end() ?>