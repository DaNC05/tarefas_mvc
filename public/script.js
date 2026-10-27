const subCheckboxes = document.querySelectorAll('.check-subtarefa');
const tarefaCheckboxes = document.querySelectorAll('.check-tarefa');
const btnFav = document.querySelectorAll('.btn-favoritar');
const inputDescricao = document.querySelectorAll('.input-descricao');
const inputTitulo = document.querySelectorAll('.input-titulo');
const inputData = document.querySelectorAll('.input-data');


btnFav.forEach((btn) => {
    btn.addEventListener('click', (e) => {
        const id = e.target.value
        let idFavoritos = new FormData();
        idFavoritos.append("id", id)
        e.target.innerText.trim() === '☆' ? e.target.innerText = '★' : e.target.innerText = '☆'
        fetch('index.php?rota=favoritar_ajax', {
            method: "POST",
            body: idFavoritos
        }
        )
    })
})
subCheckboxes.forEach((subCheckbox) => {
    subCheckbox.addEventListener('change', (e) => {
        const id = e.target.value
        const id_lista = e.target.dataset.lista;
        let status = e.target.checked
        let status_texto = status ? 'concluida' : 'pendente'
        let dados = new FormData();
        dados.append("id_lista", id_lista)
        dados.append("id", id)
        dados.append("status", status_texto)
        status ? e.target.nextElementSibling.classList.add('riscado') : e.target.nextElementSibling.classList.remove('riscado')
        fetch('index.php?rota=concluir_subtarefa', {
            method: "POST",
            body: dados
        })
    })
})

tarefaCheckboxes.forEach((tarefaCheckbox) => {
    tarefaCheckbox.addEventListener('change', (e) => {
        const id = e.target.value;
        const id_lista = e.target.dataset.lista;
        let status = e.target.checked;
        let status_texto = status ? 'concluida' : 'pendente';

        let dados = new FormData();
        dados.append("id_lista", id_lista);
        dados.append("id", id);
        dados.append("status", status_texto);

        const linkDaTarefa = e.target.nextElementSibling;
        status ? linkDaTarefa.classList.add('riscado') : linkDaTarefa.classList.remove('riscado');

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('rota') === 'concluidas' && !status) {
            const liDaTarefa = e.target.closest('li');
            liDaTarefa.style.transition = 'opacity 0.3s ease';
            liDaTarefa.style.opacity = '0';
            setTimeout(() => { liDaTarefa.style.display = 'none'; }, 300);
        }

        fetch('index.php?rota=concluir_tarefa', {
            method: "POST",
            body: dados
        });
    });
});

inputDescricao.forEach((inputDescricao) => {
    inputDescricao.addEventListener('change', (e) => {
        const id = e.target.dataset.id
        let descricao = e.target.value
        let dados = new FormData();
        dados.append("id", id)
        dados.append("descricao", descricao)

        fetch('index.php?rota=atualizar_descricao', {
            method: "POST",
            body: dados,
            keepalive: true
        })
    })
})

inputTitulo.forEach((inputTitulo) => {
    inputTitulo.addEventListener('change', (e) => {
        const id = e.target.dataset.id
        let titulo = e.target.value
        let dados = new FormData();
        dados.append("id", id)
        dados.append("titulo", titulo)

        const tituloNaLista = document.querySelector(`.check-tarefa[value="${id}"]`).nextElementSibling.querySelector('h3');
        if (tituloNaLista) {
            tituloNaLista.innerText = titulo;
        }
        fetch('index.php?rota=atualizar_titulo', {
            method: "POST",
            body: dados,
            keepalive: true
        })
    })
})

inputData.forEach((inputData) => {
    inputData.addEventListener('change', (e) => {
        const id = e.target.dataset.id
        let data_vencimento = e.target.value
        let dados = new FormData();
        dados.append("id", id)
        dados.append("data", data_vencimento)
        const dataNaLista = document.querySelector(`.check-tarefa[value="${id}"]`).parentElement.querySelector('span');
        if (dataNaLista) {
            dataNaLista.innerText = data_vencimento;
        }
        fetch('index.php?rota=atualizar_data', {
            method: "POST",
            body: dados,
            keepalive: true
        })
    })
})






