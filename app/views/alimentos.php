<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1 class="text-center">Alimentos</h1>

<!-- Family section form -->
<section>
    <div class="bg-light p-3 mb-4">
        <!-- <h2 class="text-center text-secondary">Pesquisar</h2> -->

        <div class="col-lg-12 d-flex justify-content-end">
            <button class="btn btn-outline-secondary" id="limparCampos" title="Limpa todos os campos">Limpar</button>
        </div>
        <form method="get" id="meuFormulario">
            <input type="hidden" name="page" id="page" value="<?= $page ?>">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="id">Por id:</label>
                        <input type="number" name="id" id="id" class="form-control" placeholder="ex: 5" value="<?= isset($old['id']) ? $old['id'] : ''  ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="food">Por alimento:</label>
                        <select name="food_id" id="food" class="form-control">
                            <option value="">Selecione</option>
                            <?php foreach ($foodsList as $food) : ?>
                                <option value="<?= $food->id ?>" <?= isset($old['food_id']) && $old['food_id'] == $food->id ? 'selected' : ''  ?>><?= $food->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="user">Por usuário:</label>
                        <select name="user_id" id="user" class="form-control">
                            <option value="">Selecione</option>
                            <?php foreach ($usersList as $user) : ?>
                                <option value="<?= $user->id ?>" <?= isset($old['user_id']) && $old['user_id'] == $user->id ? 'selected' : ''  ?>><?= $user->nome ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="date">Por data:</label>
                        <input type="date" name="created_at" id="date" class="form-control" value="<?= isset($old['created_at']) ? $old['created_at'] : '' ?>">
                    </div>
                </div>
                <div class="col-lg-1">
                    <div class="form-group mt-4">
                        <input type="submit" class="btn btn-lg btn-success" value="Buscar">
                    </div>
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
            <?php foreach ($foodsStock as $food) : ?>
                <tr>
                    <td><?= $food->id ?></td>
                    <td><?= $food->name ?></td>
                    <td><?= $food->qtde ?></td>
                    <td><?= $food->author ?></td>
                    <td><?= date("d-m-Y", strtotime($food->created_at)) ?> </td>
                    <td><?= isset($food->updated_at) ? date("d-m-Y", strtotime($food->updated_at)) : '' ?></td>
                    <td>
                        <div style="display: flex; justify-content: space-evenly">
                            <a href="#!" onclick="openModalEdit(<?= $food->id ?>)" title="Editar">
                                <i class="fa-regular fa-pen-to-square icon-menu"></i>
                            </a>
                            <a href="#!" onclick="openModalDelete(<?= $food->id ?>)">
                                <i class="fa-solid fa-trash icon-menu"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <!-- pagination -->
    <?php if ($totalPaginas > 1) : ?>
        <nav aria-label="Page navigation ">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <?php if ($_GET) : ?>
                            <a class="page-link" href="/food/index?page=<?= $i ?>&id=<?= $old['id'] ?>&food_id=<?= $old['food_id'] ?>&user_id=<?= $old['user_id'] ?>&created_at=<?= $old['created_at'] ?>"><?= $i ?></a>
                        <?php else : ?>
                            <a class="page-link" href="/food/index?page=<?= $i ?>"><?= $i ?></a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
    <!-- pagination -->

</section>
<!-- End Family table section -->

<?= $this->end() ?>