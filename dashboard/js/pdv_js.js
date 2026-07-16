 // --- Barra de Pedidos ---
        const barraPedido = document.getElementById('barra-pedido');
        function expandirPedido() { barraPedido.classList.remove('resumido'); }
        function fecharPedido() { barraPedido.classList.add('resumido'); }

        // --- Modal: Nova Categoria ---
        function abrirModalCategoria() { document.getElementById('modal-categoria').classList.add('ativo'); }
        function fecharModalCategoria() { document.getElementById('modal-categoria').classList.remove('ativo'); }

        // --- Modal: Novo Item ---
        function abrirModalNovoItem(idCategoria) {
            // Guarda o ID da categoria oculta no formulário
            document.getElementById('id_categoria_destino').value = idCategoria;
            document.getElementById('modal-novo-item').classList.add('ativo');
        }
        function fecharModalNovoItem() { document.getElementById('modal-novo-item').classList.remove('ativo'); }

        // --- Modal: Detalhes do Item (Adicionar ao Pedido) ---
        function abrirModalDetalhes(nome, preco) {
            // Altera o título dinamicamente com base no item clicado
            document.getElementById('detalhe-nome-item').innerText = nome;
            document.getElementById('detalhe-preco-item').innerText = preco;
            document.getElementById('modal-detalhes').classList.add('ativo');
        }
        function fecharModalDetalhes() { document.getElementById('modal-detalhes').classList.remove('ativo'); }

        // Fechar modais ao clicar no fundo escuro
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('ativo');
            }
        }