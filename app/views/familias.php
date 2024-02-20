<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1>Famílias</h1>

<!-- Family section form -->
<section>
    <div class="bg-light p-3 mb-4">
        <!-- <h2 class="text-center text-secondary">Pesquisar</h2> -->
        
        <div class="col-lg-12 d-flex justify-content-end">
            <button class="btn btn-outline-secondary" id="limparCampos" title="Limpa todos os campos">Limpar</button>
        </div>
        <form method="get" id="meuFormulario">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="id">Por id:</label>
                        <input type="number" name="id" id="id" class="form-control" placeholder="ex: 5" value="<?= isset($old['id']) ? $old['id'] : ''  ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="nome">Por nome:</label>
                        <input type="text" name="full_name" id="nome" class="form-control" placeholder="Ex: joão da silva" value="<?= isset($old['full_name']) ? $old['full_name'] : ''  ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="filhos">Qtde filhos:</label>
                        <input type="number" name="qtde_childs" id="filhos" min="0" class="form-control" placeholder="Ex: 2" value="<?= isset($old['qtde_childs']) ? $old['qtde_childs'] : ''  ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="sexo">Por sexo:</label>
                        <select class="form-control" name="gender" id="">
                            <option value="">Selecione</option>
                            <option value="F" <?= isset($old['gender']) && $old['gender'] == 'F' ? 'selected' : ''  ?>>Feminino</option>
                            <option value="M" <?= isset($old['gender']) && $old['gender'] == 'M' ? 'selected' : ''  ?>>Masculino</option>
                            <option value="N" <?= isset($old['gender']) && $old['gender'] == 'N' ? 'selected' : ''  ?>>Não informado</option>
                        </select>
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
    <?php if (count($families) > 0) : ?>
    <table class="table table-bordered text-center table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Qtde filhos</th>
                <th>Contato</th>
                <th>Criado em</th>
                <th>Atualizado em</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($families as $family) : ?>
                <tr>
                    <td><?= $family->id ?></td>
                    <td><?= $family->full_name ?></td>
                    <td><?= $family->address ?></td>
                    <td><?= $family->qtde_childs ?></td>
                    <td><?= $family->contact ?></td>
                    <td><?= date("d-m-Y H:i:s", strtotime($family->created_at)) ?> </td>
                    <td><?= isset($family->updated_at) ? date("d-m-Y H:i:s", strtotime($family->updated_at)) : '' ?></td>
                    <td><a href=""> Editar | Excluir </a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <h3 class="text-center text-dark">Não há familias cadastradas</h3>
    <?php endif; ?>
    
    <!-- pagination -->
    <?php if ($totalPaginas > 1) : ?>
        <nav aria-label="Page navigation ">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="/family/index?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
    <!-- pagination -->

</section>
<!-- End Family table section -->

<?= $this->end() ?>
