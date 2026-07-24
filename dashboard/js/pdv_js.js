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
        // --- Modal: Detalhes do Item (Adicionar ao Pedido) ---
// Adicionamos a palavra 'async' porque a função vai ter que esperar o banco de dados responder
async function abrirModalDetalhes(idLanche, nome, preco) {
    document.getElementById('detalhe-nome-item').innerText = nome;
    document.getElementById('detalhe-preco-item').innerText = preco;
    
    // Mostra o modal na tela
    document.getElementById('modal-detalhes').classList.add('ativo');

    const lista = document.querySelector('.lista-complementos');
    
    // Coloca uma mensagem de carregamento enquanto o banco pensa
    lista.innerHTML = '<p style="font-size:14px; color: var(--cor-texto-sec); padding: 10px;">Buscando complementos...</p>';

    try {
        // O JS vai até o PHP pedir os complementos daquele lanche (Ajuste a pasta se necessário)
        const resposta = await fetch(`salvarItem/buscarComplementos.php?idLanche=${idLanche}`);
        const complementos = await resposta.json();

        // Limpa o "Buscando..."
        lista.innerHTML = '';

        if (complementos.length === 0) {
            lista.innerHTML = '<p style="font-size:14px; color: var(--cor-texto-sec); padding: 10px;">Nenhum complemento disponível para este item.</p>';
            return;
        }

        // Para cada complemento que veio do banco, ele desenha o HTML na tela
        // Para cada complemento que veio do banco, desenha o HTML com botões - e +
        complementos.forEach(comp => {
            const precoFormatado = parseFloat(comp.precoComplemento).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            
            const div = document.createElement('div');
            div.className = 'item-complemento-qtd';
            div.innerHTML = `
                <div class="info-comp">
                    <span class="nome-comp">${comp.nomeComplemento}</span>
                    <span class="preco-comp" style="font-size: 12px; color: var(--cor-verde);">+ R$ ${precoFormatado}</span>
                </div>
                <div class="controle-qtd-comp">
                    <button type="button" class="btn-qtd-comp btn-menos">-</button>
                    <input type="number" class="qtd-comp-input" value="0" min="0" data-preco="${comp.precoComplemento}" data-nome="${comp.nomeComplemento}" readonly>
                    <button type="button" class="btn-qtd-comp btn-mais">+</button>
                </div>
            `;

            // Lógica de clique dos botões + e - deste complemento específico
            const btnMenos = div.querySelector('.btn-menos');
            const btnMais = div.querySelector('.btn-mais');
            const inputQtd = div.querySelector('.qtd-comp-input');

            btnMais.onclick = () => {
                inputQtd.value = parseInt(inputQtd.value) + 1;
            };

            btnMenos.onclick = () => {
                if(parseInt(inputQtd.value) > 0) {
                    inputQtd.value = parseInt(inputQtd.value) - 1;
                }
            };

            lista.appendChild(div);
        });
    } catch (erro) {
        lista.innerHTML = '<p style="color: #ff4c4c; padding: 10px;">Erro ao carregar complementos.</p>';
        console.error(erro);
    }
}
        function fecharModalDetalhes() { document.getElementById('modal-detalhes').classList.remove('ativo'); }

        // Fechar modais ao clicar no fundo escuro
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('ativo');
            }
        }

        // --- Modal: Editar Categoria ---
        function abrirModalEditarCategoria(id, nomeAtual) {
            document.getElementById('idCategoriaEdit').value = id;
            document.getElementById('nomeCategoriaEdit').value = nomeAtual;
            document.getElementById('modal-editar-categoria').classList.add('ativo');
        }
        function fecharModalEditarCategoria() { 
            document.getElementById('modal-editar-categoria').classList.remove('ativo'); 
}