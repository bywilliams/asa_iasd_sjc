<?= $this->layout('template_master', ['title' => $title, 'user' => $user]) ?>

<?= $this->start('conteudo') ?>

<section class="container">
    <h1 class="text-center">Equipe ASA 2024</h1>
    <div class="team-section">
        <div class="team-member">
            <img src="/assets/imgs/team/eliana.png" alt="Team Member 1">
            <h3>Eliana</h3>
            <p class="role">Diretoria</p>
        </div>
    
        <div class="team-member">
            <img src="/assets/imgs/team/leci.png" alt="Team Member 2">
            <h3>Leci</h3>
            <p class="role">Tesouraria</p>
        </div>
    
        <div class="team-member">
            <img src="/assets/imgs/team/laurrane.png" alt="Team Member 3">
            <h3>Laurrane</h3>
            <p class="role">Bazar</p>
        </div>

        <div class="team-member">
            <img src="/assets/imgs/user.png" alt="Team Member 3">
            <h3>Maria Godoi</h3>
            <p class="role">Bazar</p>
        </div>
    
        <div class="team-member">
            <img src="/assets/imgs/user.png" alt="Team Member 3">
            <h3>Mariza</h3>
            <p class="role">Suporte nos projetos</p>
        </div>

        <div class="team-member">
            <img src="/assets/imgs/user.png" alt="Team Member 3">
            <h3>Eliane</h3>
            <p class="role">Visitação</p>
        </div>

        <div class="team-member">
            <img src="/assets/imgs/team/wil_perfil.png" alt="Team Member 3">
            <h3>William</h3>
            <p class="role">Secretária</p>
        </div>
    </div>
</section>

<?= $this->end() ?>