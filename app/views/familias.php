<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1 class="text-center">Famílias</h1>

<!-- Family section form -->
<section>
    <div class="bg-light p-2 mb-4 rounded">
        <!-- <h3 class=" text-secondary mb-3">Pesquisar</h2> -->

        <div class="row mx-4">
            <h3 class="text-secondary mb-3">Pesquisar:</h5>
                <div class="col-lg-12 d-flex justify-content-end">
                    <button class="btn btn-outline-secondary mr-2" id="limparCampos" title="Limpa todos os campos">Limpar</button>
                </div>
        </div>

        <form method="get" id="meuFormulario">
            <input type="hidden" name="page" id="page" value="<?= $page ?>">
            <div class="row mx-4">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="family_id">Por id:</label>
                        <input type="number" name="id" id="family_id" class="form-control inputForm" placeholder="ex: 5" value="<?= isset($old['id']) ? $old['id'] : ''  ?>" oninput="verificarInput()">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="nome">Por nome:</label>
                        <input type="text" name="full_name" id="nome" class="form-control inputForm" placeholder="Ex: joão da silva" value="<?= isset($old['full_name']) ? $old['full_name'] : ''  ?>" oninput="verificarInput()">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="food">Por endereço:</label>
                        <input type="text" name="address" id="" class="form-control" placeholder="ex: rua conceição" value="<?= isset($old['address']) ? $old['address'] : null  ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
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
                <div class="col-lg-4 col-md-6">
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
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="sits">Por qtde de filhos:</label>
                        <input type="number" min="0" name="qtde_childs" id="" class="form-control" placeholder="ex: 3" value="<?= isset($old['qtde_childs']) ? $old['qtde_childs'] : null  ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <label for="date">Por data de criação:</label>
                        <input type="date" name="created_at" id="date" class="form-control inputForm" value="<?= isset($old['created_at']) ? $old['created_at'] : null ?>" oninput="verificarInput()">
                    </div>
                </div>
                <div class="col-lg-2 col-md-12 text-center">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="search">Buscar</button>
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
                        <div style="display: flex; justify-content: space-evenly; cursor: pointer;">
                            <a data-toggle="modal" data-target="#family_edit_<?= $family->id ?>">
                                <i class="fa-regular fa-pen-to-square icon-menu"></i>
                            </a>
                            <a data-toggle="modal" data-target="#family_delete_<?= $family->id ?>">
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

<!-- Modals section -->
<section>
    <!-- Family Modal Edit -->
    <?php foreach ($families['data'] as $family) : ?>
        <div class="modal fade modal_bg" id="family_edit_<?= $family->id ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar familia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="/family/update/<?= $family->id ?>" method="POST">
                        <input type="hidden" name="_METHOD" value="PUT">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <div class="modal-body">
                            <p>Obrigatório *</p>
                            <div class="form-group">
                                <label for="familyName">Nome completo: *</label>
                                <input type="text" name="full_name" id="familyName" class="form-control" value="<?= $family->full_name ?>" required>
                            </div>
                            <div class="form-group">
                                <h6>Sexo: *</h6>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="M" <?= $family->gender == 'M' ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="inlineRadio1">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="F" <?= $family->gender == 'F' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio2">Feminino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="N" <?= $family->gender == 'N' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio3">Não informado</label>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="endereco">Endereço: *</label>
                                <input type="text" name="address" id="endereco" class="form-control" value="<?= $family->address ?>" required>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="cel">Celular/Whatsapp: *</label>
                                        <input type="text" name="contact" id="cel" class="form-control phone_with_ddd" value="<?= $family->contact ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="profissao">Profissão:</label>
                                        <input type="text" name="job" id="profissao" class="form-control" value="<?= $family->job ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="filhos">Quantidade de filhos: *</label>
                                        <input type="number" name="qtde_childs" id="filhos" class="form-control" value="<?= $family->qtde_childs ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="idade">Idade dos filhos:</label>
                                        <input type="text" name="age" id="idade" class="form-control" value="<?= $family->age ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="data_visita">Dias e horários para visitas:</label>
                                <textarea class="form-control" name="obs" id="data_visita" placeholder="ex: domingo as 11h"><?= $family->obs ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="situação">Situação cadastral:</label>
                                <select name="sits_family_id" id="situação" class="form-control">
                                    <option value="">Selecione</option>
                                    <option value="1" <?= $family->sits_family_id == '1' ? 'selected' : null ?>>Ativo</option>
                                    <option value="2" <?= $family->sits_family_id == '2' ? 'selected' : null ?>>Inativo</option>
                                    <option value="3" <?= $family->sits_family_id == '3' ? 'selected' : null ?>>Aguardando</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <h6>Critérios: *</h6>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="criteria_id" value="1" <?= $family->criteria_id  == 1 ? 'checked' : ''  ?> required>
                                        <label class="form-check-label" for="inlineRadio1">Escola Bíblica</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="criteria_id" value="2" <?= $family->criteria_id  == 2 ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="inlineRadio2">Cultos</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="criteria_id" value="3" <?= $family->criteria_id  == 3 ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="inlineRadio3">Cursos</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="criteria_id" value="4" <?= $family->criteria_id  == 4 ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="inlineRadio3">Aventureiros</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="criteria_id" value="5" <?= $family->criteria_id  == 5 ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="inlineRadio3">Desbravadores</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="criteria_id" value="6" <?= $family->criteria_id  == 6 ? 'checked' : ''  ?>>
                                        <label class="form-check-label" for="inlineRadio3">Escolinha infantil</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- End Family Modal Edit -->

    <!-- Family Modal Delete -->
    <?php foreach ($families['data'] as $family) : ?>
        <div class="modal fade modal_bg" id="family_delete_<?= $family->id ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir família</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <h5>Tem certeza de deseja excluir família de: <br> <?= $family->full_name ?></h5>
                    </div>
                    <div class="modal-footer">
                        <form action="/family/delete/<?= $family->id ?>" method="POST">
                            <input type="hidden" name="_METHOD" value="DELETE">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- End Family Modal Delete -->

</section>

<?= $this->end() ?>