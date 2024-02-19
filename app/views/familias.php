<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1>Famílias</h1>

<!-- Family section form -->
<section>
    <div class="bg-light p-3 mb-4">
        <h2 class="text-center text-secondary mb-3">Pesquisar</h2>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="id">Por id:</label>
                    <input type="number" name="family_id" id="id" class="form-control" placeholder="ex: 5">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="nome">Por nome:</label>
                    <input type="text" name="fullname" id="nome" class="form-control" placeholder="Ex: joão da silva">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="filhos">Qtde filhos:</label>
                    <input type="number" name="childs" id="filhos" min="0" class="form-control" placeholder="Ex: 2">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="sexo">Por sexo:</label>
                    <select class="form-control" name="" id="">
                        <option value="">Selecione</option>
                        <option value="f">Feminino</option>
                        <option value="f">Masculino</option>
                        <option value="f">Não informado</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group mt-4">
                    <input type="submit" class="btn btn-lg btn-success" value="Buscar">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Family section form -->

<!-- Family table section -->
<section>
    <table class="table table-bordered text-center">
        <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Qtde filhos</th>
                <th>Contato</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>João da Silva</td>
                <td>Rua união 22</td>
                <td>3</td>
                <td>(11) 91234-5678</td>
                <td><a href=""> Editar | Excluir </a></td>
            </tr>
        </tbody>
    </table>
</section>
<!-- End Family table section -->

<?= $this->end() ?>