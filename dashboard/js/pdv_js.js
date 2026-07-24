        // --- Barra de Pedidos ---
        const barraPedido = document.getElementById('barra-pedido');
        function expandirPedido() { barraPedido.classList.remove('resumido'); }
        function fecharPedido() { barraPedido.classList.add('resumido'); }

        // --- Modal: Nova Categoria ---
        function abrirModalCategoria() { document.getElementById('modal-categoria').classList.add('ativo'); }
        function fecharModalCategoria() { document.getElementById('modal-categoria').classList.remove('ativo'); }

        // --- Modal: Novo Item ---
        function abrirModalNovoItem(idCategoria) {
            document.getElementById('id_categoria_destino').value = idCategoria;
            document.getElementById('modal-novo-item').classList.add('ativo');
        }
        function fecharModalNovoItem() { 
            document.getElementById('modal-novo-item').classList.remove('ativo');
            // Limpa os complementos se o usuário fechar a tela
            document.getElementById('lista-complementos-form').innerHTML = '';
        }

        // --- Criação Dinâmica de Complementos ---
        function adicionarCampoComplemento() {
            const container = document.getElementById('lista-complementos-form');
            const row = document.createElement('div');
            row.className = 'linha-complemento';

            row.innerHTML = `
                <input type="text" name="nomeComplemento[]" placeholder="Ex: Bacon Extra" required>
                <input type="number" step="0.01" name="precoComplemento[]" class="input-preco" placeholder="R$ 0.00" required>
                <button type="button" class="btn-remover-comp" onclick="this.parentElement.remove()">X</button>
            `;

            container.appendChild(row);
        }

        // --- Modal: Detalhes do Item (Adicionar ao Pedido) ---
        function abrirModalDetalhes(nome, preco) {
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