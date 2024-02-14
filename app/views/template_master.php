<?= $this->insert('_components/_topo', ['title' => $title, 'user' => $user]) ?>

<!-- main content -->
<main class="container-fluid p-4 min-vh-100">

<?php require_once '_components/_bootstrap/_breadcrumb.php'; ?>

<?= $this->section('conteudo') ?>
</main>
<!-- endmain content -->

</div>
<?= $this->insert('_components/_footer') ?>