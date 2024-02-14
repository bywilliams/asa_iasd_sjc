<!-- Revenue Modal -->
<div class="modal fade modal_bg" id="revenue_create" tabindex="-1" aria-labelledby="revenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar transação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/transacao/store" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="user_id" value="<?= $this->e($user->id) ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Titulo</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <h6>Tipo (Receita/Despesa): *</h6>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="type1" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">Receita</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" type="type2" value="2">
                                <label class="form-check-label" for="inlineRadio2">Despesa</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-group d-none" id="revenue">
                            <label for="categories_revenue">Categoria: *</label>
                            <select name="category_id" id="categories_revenue" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="1">Venda de produto</option>
                                <option value="2">Doação</option>
                                <option value="3">Oferta</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="expense">
                            <label for="categories_expense">Categoria: *</label>
                            <select name="category_id" id="categories_expense" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="4">Compra de produto/alimento</option>
                                <option value="6">Aluguel</option>
                                <option value="7">Alimentação</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="value">Valor: *</label>
                        <input type="text" name="value" id="value" class="form-control money" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Data: *</label>
                        <input type="date" name="created_at" id="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
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
<!-- End Revenue Modal -->

<!-- Event Modal -->
<div class="modal fade modal_bg" id="event_create" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>
<!-- End Event Modal -->

<!-- Food Modal -->
<div class="modal fade modal_bg" id="food_create" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar alimentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/food/stock-store" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="user_id" value="<?= $this->e($user->id) ?>">
                <div class="modal-body">
                    <p>Obrigatório *</p>
                    <div class="form-group">
                        <label for="name">Alimento: *</label>
                        <select name="food_id" id="" class="form-control">
                            <option value="">Selecione</option>
                            <?php foreach ($allFoods as $food) : ?>
                                <option value="<?= $food->id ?>"><?= $food->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <h6>Alimento da cesta? *</h6>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="basic_basket" id="inlineRadio1" value="S" required>
                                <label class="form-check-label" for="inlineRadio1">Sim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="basic_basket" id="inlineRadio2" value="N">
                                <label class="form-check-label" for="inlineRadio2">Não</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="qtde">Quantidade: *</label>
                                <input type="number" name="qtde" id="qtde" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="created_at">Data: *</label>
                                <input type="date" name="created_at" id="created_at" class="form-control" required>
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
<!-- End Food Modal -->

<!-- Family Modal -->
<div class="modal fade modal_bg" id="family_create" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastrar familia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/family/store" method="post">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="user_id" value="<?= $this->e($user->id) ?>">
                <div class="modal-body">
                    <p>Obrigatório *</p>
                    <div class="form-group">
                        <label for="familyName">Nome completo: *</label>
                        <input type="text" name="fullname" id="familyName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <h6>Sexo: *</h6>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="M" required>
                                <label class="form-check-label" for="inlineRadio1">Masculino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="F">
                                <label class="form-check-label" for="inlineRadio2">Feminino</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio3" value="N">
                                <label class="form-check-label" for="inlineRadio3">Não informado</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço: *</label>
                        <input type="text" name="end" id="endereco" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="cel">Celular/Whatsapp: *</label>
                                <input type="text" name="contact" id="cel" class="form-control phone_with_ddd" required>
                            </div>
                            <div class="col">
                                <label for="profissao">Profissão:</label>
                                <input type="text" name="job" id="profissao" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="filhos">Quantidade de filhos: *</label>
                                <input type="number" name="qtde_childs" id="filhos" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="idade">Idade dos filhos:</label>
                                <input type="text" name="age" id="idade" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="data_visita">Dias e horários para visitas:</label>
                        <textarea class="form-control" name="schedule" id="data_visita"></textarea>
                    </div>
                    <div class="form-group">
                        <h6>Critérios: *</h6>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="criterion" value="1" required>
                                <label class="form-check-label" for="inlineRadio1">Escola Bíblica</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="criterion" value="2">
                                <label class="form-check-label" for="inlineRadio2">Cultos</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="criterion" value="3">
                                <label class="form-check-label" for="inlineRadio3">Cursos</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="criterion" value="4">
                                <label class="form-check-label" for="inlineRadio3">Aventureiros</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="criterion" value="5">
                                <label class="form-check-label" for="inlineRadio3">Desbravadores</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="criterion" value="6">
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
<!-- End Family Modal -->

<script>
    $(document).ready(function() {

        $('input[name="type"]').on('change', function() {

            // Esconde ambas as divs
            $('#revenue').addClass('d-none');
            $('#expense').addClass('d-none');

            // Obtém o valor do radio button selecionado
            var selectedValue = $('input[name="type"]:checked').val();

            if (selectedValue == 1) {
                $('#revenue').removeClass('d-none');
                $('#categories_expense').prop('disabled', true);
                $('#categories_revenue').prop('disabled', false);
            } else if (selectedValue == 2) {
                $('#expense').removeClass('d-none');
                $('#categories_revenue').prop('disabled', true);
                $('#categories_expense').prop('disabled', false);
            }
        });
    });
</script>