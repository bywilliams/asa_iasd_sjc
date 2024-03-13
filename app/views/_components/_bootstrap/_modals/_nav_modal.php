<!--  Categorie modal create -->
<div class="modal fade modal_bg" id="categorieCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Criar categoria</h5>
                <button type="button" class="close" data-dismiss="modal" arial-label="fechar">
                    <span arial-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/categories/store" method="post">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group">
                        <label for="title">Nome da categoria:</label>
                        <input type="text" name="category_name" id="title" class="form-control" placeholder="Ex: mercado" value="<?= isset($old['category_name']) ?? null ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category_type">Tipo:</label>
                        <select class="form-control" name="category_type" id="category_type" required>
                            <option value="">Selecione</option>
                            <option value="1" <?= isset($old['category_type']) && $old['category_type'] == '1' ? 'selected' : null ?>>Receita</option>
                            <option value="2" <?= isset($old['category_type']) && $old['category_type'] == '2' ? 'selected' : null ?>>Despesa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End catgorie modal create -->