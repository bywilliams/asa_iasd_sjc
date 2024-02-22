<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1 class="text-center">Famílias</h1>

<!-- Family section form -->
<section>
    <div class="bg-light p-3 mb-4">
        <h3 class=" text-secondary mb-3">Pesquisar</h2>

            <form method="get" id="meuFormulario">
                <input type="hidden" name="page" id="page" value="<?= $page ?>">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="family_id">Por id:</label>
                            <input type="number" name="id" id="family_id" class="form-control inputForm" placeholder="ex: 5" value="<?= isset($old['id']) ? $old['id'] : ''  ?>" oninput="verificarInput()">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="nome">Por nome:</label>
                            <input type="text" name="full_name" id="nome" class="form-control inputForm" placeholder="Ex: joão da silva" value="<?= isset($old['full_name']) ? $old['full_name'] : ''  ?>" oninput="verificarInput()">
                        </div>
                    </div>
                    <!-- <div class="col-lg-2">
                    <div class="form-group">
                        <label for="filhos">Qtde filhos:</label>
                        <input type="number" name="qtde_childs" id="filhos" min="0" class="form-control inputForm" placeholder="Ex: 2" value="<?= isset($old['qtde_childs']) ? $old['qtde_childs'] : ''  ?>" oninput="verificarInput()">
                    </div>
                </div> -->
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="sexo">Por sexo:</label>
                            <select class="form-control inputForm" name="gender" id="sexo" oninput="verificarInput()">
                                <option value="">Selecione</option>
                                <option value="F" <?= isset($old['gender']) && $old['gender'] == 'F' ? 'selected' : ''  ?>>Feminino</option>
                                <option value="M" <?= isset($old['gender']) && $old['gender'] == 'M' ? 'selected' : ''  ?>>Masculino</option>
                                <option value="N" <?= isset($old['gender']) && $old['gender'] == 'N' ? 'selected' : ''  ?>>Não informado</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="sits">Por situação:</label>
                            <select class="form-control inputForm" name="sits_family_id" id="sits" oninput="verificarInput()">
                                <option value="">Selecione</option>
                                <option value="1" <?= isset($old['sits_family_id']) && $old['sits_family_id'] == '1' ? 'selected' : ''  ?>>Ativo</option>
                                <option value="2" <?= isset($old['sits_family_id']) && $old['sits_family_id'] == '2' ? 'selected' : ''  ?>>Inativo</option>
                                <option value="3" <?= isset($old['sits_family_id']) && $old['sits_family_id'] == '3' ? 'selected' : ''  ?>>Aguardando</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="date">Por data de criação:</label>
                            <input type="date" name="created_at" id="date" class="form-control inputForm" value="<?= isset($old['created_at']) ? $old['created_at'] : null ?>" oninput="verificarInput()">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-lg btn-outline-secondary mt-4" id="limparCampos" title="Limpa todos os campos">Limpar</button>
                    </div>
                    <div class="col-lg-1">
                        <div class="form-group mt-4">
                            <input type="submit" class="btn btn-lg btn-success" id="search" value="Buscar">
                        </div>
                    </div>
                </div>
            </form>
    </div>
</section>
<!-- End Family section form -->

<!-- Family table section -->
<section>
    <?php if (count($families) > 0) : ?>
        <table class="table table-bordered content-table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Qtde filhos</th>
                    <th>Contato</th>
                    <th>Situação</th>
                    <th>Criado em</th>
                    <th>Atualizado em</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalFamilies = 0;
                $itemsPerPage = 3;
                $currentPage = $page; 
                ?>
                <?php foreach ($families['data'] as $family) : ?>
                    <tr>
                        <td><?= $family->id ?></td>
                        <td><?= $family->full_name ?></td>
                        <td><?= $family->address ?></td>
                        <td><?= $family->qtde_childs ?></td>
                        <td><?= $family->contact ?></td>
                        <td><?= $family->situacao ?></td>
                        <td><?= date("d-m-Y H:i:s", strtotime($family->created_at)) ?> </td>
                        <td><?= isset($family->updated_at) ? date("d-m-Y H:i:s", strtotime($family->updated_at)) : '' ?></td>
                        <td>
                            <div style="display: flex; justify-content: space-evenly">
                                <a href="#!" onclick="openModalEdit(<?= $family->id ?>)">
                                    <i class="fa-regular fa-pen-to-square icon-menu"></i>
                                </a>
                                <a href="#!" onclick="openModalDelete(<?= $family->id ?>)">
                                    <i class="fa-solid fa-trash icon-menu"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php $totalFamilies += 1; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="text-center">
                    <td colspan="9">
                        <strong class="mr-2">
                            Total de famílias: <?= min($totalFamilies + (($currentPage - 1) * $itemsPerPage), $families['totalRegistros']) . ' de ' .  $families['totalRegistros'] ?>
                        </strong>
                    </td>
                </tr>
            </tfoot>

        </table>
    <?php else : ?>
        <h3 class="text-center text-dark">Não há familias cadastradas</h3>
    <?php endif; ?>

    <!-- TODO: Fazer o mesmo que foi feito em alimentos  -->
    <!-- pagination -->
    <?php if ($totalPaginas > 1) : ?>
        <nav aria-label="Page navigation ">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="/family/index?page=<?= $i ?><?= isset($old['id']) && $old['id'] != null ? "&id={$old['id']}" : null ?><?= isset($old['full_name']) && $old['full_name'] != null ? "&full_name={$old['full_name']}" : null ?><?= isset($old['qtde_childs']) && $old['qtde_childs'] != null ? "&qtde_childs={$old['qtde_childs']}" : null ?><?= isset($old['gender']) && $old['gender'] != null ? "&gender={$old['gender']}" : null ?><?= isset($old['sits_family_id']) && $old['sits_family_id'] != null ? "&sits_family_id={$old['sits_family_id']}" : null ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
    <!-- pagination -->


</section>
<!-- End Family table section -->

<?= $this->end() ?>