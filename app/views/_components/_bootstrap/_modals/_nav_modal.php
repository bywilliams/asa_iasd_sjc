<!--  Categorie modal create -->
        <div class="modal fade" id="categorieCreate" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Criar categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" arial-label="fechar">
                            <span arial-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= $BASE_URL ?>categories_process.php" method="post">
                            <input type="hidden" name="type" value="register">
                            <div class="form-group">
                                <label for="title">Nome da categoria:</label>
                                <input type="text" name="category_name" id="title" class="form-control" placeholder="Ex: mercado" value="">
                            </div>
                            <div class="form-group">
                                <label for="category_type">Tipo:</label>
                                <select class="form-control" name="category_type" id="category_type">
                                    <option value="">Selecione</option>
                                    <option value="1" >Receita</option>
                                    <option value="2" >Despesa</option>
                                </select>
                            </div>
                            <input type="submit" value="Adicionar" class="btn btn-lg btn-success">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End catgorie modal create -->
