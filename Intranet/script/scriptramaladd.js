// Função para abrir o modal de edição com os dados preenchidos
function editarRamal(ramal, usuario, email, setor) {
    document.getElementById('editRamal').value = ramal;
    document.getElementById('editUsuario').value = usuario;
    document.getElementById('editEmail').value = email;
    document.getElementById('editSetor').value = setor;

    // Exibir o modal de edição
    $('#editModal').modal('show');
}

// Função para enviar os dados atualizados para o PHP e atualizar o ramal no banco de dados
function atualizarRamal() {
    const updatedRamal = {
        ramal: document.getElementById('editRamal').value,
        usuario: document.getElementById('editUsuario').value,
        email: document.getElementById('editEmail').value,
        setor: document.getElementById('editSetor').value
    };

    // Enviar requisição de atualização para o PHP
    fetch('path_to_php_file.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            update: true,
            ...updatedRamal
        })
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        $('#editModal').modal('hide');
        location.reload(); // Recarrega a página para refletir as atualizações
    })
    .catch(error => console.error('Erro ao atualizar o ramal:', error));
}

// Função para excluir um ramal
function excluirRamal(ramal) {
    if (confirm('Tem certeza de que deseja excluir este ramal?')) {
        fetch('path_to_php_file.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                delete: true,
                ramal: ramal
            })
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            location.reload(); // Recarrega a página para refletir as alterações
        })
        .catch(error => console.error('Erro ao excluir o ramal:', error));
    }
}

// Função para adicionar um novo ramal
function adicionarRamal() {
    const newRamal = {
        ramal: document.getElementById('ramal').value,
        usuario: document.getElementById('usuario').value,
        email: document.getElementById('email').value,
        setor: document.getElementById('setor').value
    };

    // Enviar requisição para adicionar o novo ramal ao PHP
    fetch('path_to_php_file.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            add: true,
            ...newRamal
        })
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        location.reload(); // Recarrega a página para refletir o novo ramal
    })
    .catch(error => console.error('Erro ao adicionar o ramal:', error));
}
