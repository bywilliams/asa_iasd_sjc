
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="/assets/js/jquery.mask.js"></script>
<script>
    // Formatação de valores monetarios com ','
    $('.money').mask('000.000.000.000.000,00', {
        reverse: true
    });

    // Formatação de numero celular ao padrão SP
    $('.phone_with_ddd').mask('(00) 0000-0000');

    // Sidebar lateral
    $(function() {
        // Função para lidar com a marcação de itens do menu como ativos
        function setActiveMenu(clickedElement) {
            // Remove a classe 'active' de todos os itens do menu
            $('.list-group-item').removeClass('active');

            // Adiciona a classe 'active' ao item do menu clicado
            $(clickedElement).addClass('active');

            // Armazena o estado da classe 'active' na sessionStorage
            sessionStorage.setItem('activeMenu', $(clickedElement).attr('href'));
        }

        // Função para lidar com o colapso dos sublinks
        function toggleSubMenu(collapseId) {
            // Fecha todos os colapsos, exceto o especificado por collapseId
            $('.collapse').not('#' + collapseId).collapse('hide');
        }

        // Sidebar lateral
        $('#open-sidebar').click(() => {
            alert('clicado');

            // add class active on #sidebar
            $('#sidebar').addClass('active');

            // show sidebar overlay
            $('#sidebar-overlay').removeClass('d-none');
        });

        $('#sidebar-overlay').click(function() {
            // add class active on #sidebar
            $('#sidebar').removeClass('active');

            // show sidebar overlay
            $(this).addClass('d-none');
        });

        // Adiciona um manipulador de evento de clique aos itens do menu
        $('.list-group-item').click(function(e) {
            // Impede a ação padrão do link para evitar a recarga da página
            e.preventDefault();

            // Chama a função para marcar o item do menu como ativo
            setActiveMenu(this);

            // Obtém o ID do colapso associado ao item do menu
            var collapseId = $(this).attr('data-target');

            // Se houver um colapso associado, ativa o colapso e desativa outros
            if (collapseId) {
                toggleSubMenu(collapseId);
            }

            // Navega para a URL usando JavaScript
            window.location.href = $(this).attr('href');
        });

        // Define o item do menu ativo inicialmente (com base na sessionStorage)
        var activeMenu = sessionStorage.getItem('activeMenu');
        if (activeMenu) {
            setActiveMenu($('.list-group-item[href="' + activeMenu + '"]'));
        }
    });


    var status = "<?= $_SESSION['status_report'] ?>";
    var msg = "<?= $_SESSION['message']  ?>";

    if (msg) {
        Swal.fire({
            icon: status == 'success' ? 'success' : 'error',
            text: msg,
            confirmButtonText: 'OK',
            confirmButtonColor: '#0B666A',
            cancelButtonText: 'Fechar',
        });
        <?php $_SESSION['status'] = null ?>
        <?php $_SESSION['message'] = null ?>
    }
</script>
</body>

</html>