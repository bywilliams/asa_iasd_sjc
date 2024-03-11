<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<h1 class="text-center">Eventos</h1>

<!-- Family table section -->
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
                <th>Ação</th>
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
<!-- End Family table section -->

<?= $this->end() ?>