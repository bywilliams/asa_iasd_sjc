<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1 class="text-center">Eventos</h1>

<!-- Event section form -->
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
                        <label for="title">Por nome:</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Ex: mutirão páscoa">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="user">Por usuário:</label>
                        <select name="user_id" id="user" class="form-control inputForm" oninput="verificarInput()">
                            <option value="">Selecione</option>
                            <?php foreach ($usersList as $usuario) : ?>
                                <option value="<?= $usuario->id ?>" <?= isset($old['user_id']) && $old['user_id'] == $usuario->id ? 'selected' : ''  ?>><?= $usuario->nome ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="data">Por data:</label>
                        <input type="date" name="created_at" id="data" class="form-control">
                    </div>
                </div>
                <div class="col-lg-1 col-md-12 text-center">
                    <button type="submit" class="btn btn-success px-3 shadow-sm" id="search" value="Buscar">Buscar
                </div>
            </div>
        </form>
    </div>
</section>
<!-- End Event section form -->

<!-- Event table section -->
<section>
    <table class="table table-bordered content-table table-hover ">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Local</th>
                <th>Descrição</th>
                <th>Autor</th>
                <th>Criado em</th>
                <th>Atualizado em</th>
                <?php if ($user->nivel_acesso == 1) : ?>
                    <th>Ação</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listEvents as $event) : ?>
                <tr>
                    <td><?= $event->id ?></td>
                    <td><?= $event->name ?></td>
                    <td><?= $event->place ?></td>
                    <td><?= $event->description ?></td>
                    <td><?= $event->author ?></td>
                    <td><?= date("d-m-Y H:i:s", strtotime($event->created_at)) ?> </td>
                    <td><?= isset($event->updated_at) ? date("d-m-Y H:i:s", strtotime($event->updated_at)) : '' ?></td>
                    <?php if ($user->nivel_acesso == 1) : ?>
                        <td>
                            <div style="display: flex; justify-content: space-evenly; cursor: pointer;">
                                <a data-toggle="modal" data-target="#family_edit_<?= $event->id ?>">
                                    <i class="fa-regular fa-pen-to-square icon-menu"></i>
                                </a>
                                <a data-toggle="modal" data-target="#family_delete_<?= $event->id ?>">
                                    <i class="fa-solid fa-trash icon-menu"></i>
                                </a>
                            </div>
                        </td>
                    <?php endif ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <!-- TODO: Fazer o mesmo que foi feito em alimentos  -->
    <!-- pagination -->
    <!-- <?php if ($totalPaginas > 1) : ?>
        <nav aria-label="Page navigation ">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="/family/index?page=<?= $i ?><?= isset($old['id']) && $old['id'] != null ? "&id={$old['id']}" : null ?><?= isset($old['full_name']) && $old['full_name'] != null ? "&full_name={$old['full_name']}" : null ?><?= isset($old['qtde_childs']) && $old['qtde_childs'] != null ? "&qtde_childs={$old['qtde_childs']}" : null ?><?= isset($old['gender']) && $old['gender'] != null ? "&gender={$old['gender']}" : null ?><?= isset($old['sits_family_id']) && $old['sits_family_id'] != null ? "&sits_family_id={$old['sits_family_id']}" : null ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?> -->
    <!-- pagination -->


</section>
<!-- End Event table section -->

<?= $this->end() ?>