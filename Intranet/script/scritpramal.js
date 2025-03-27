
        function editarRamal(ramal, usuario, email, setor) {
            document.getElementById('editRamal').value = ramal;
            document.getElementById('editUsuario').value = usuario;
            document.getElementById('editEmail').value = email;
            document.getElementById('editSetor').value = setor;
            $('#editModal').modal('show');
        }

        function editarRamal(ramal, usuario, email, setor) {
        // Preenche os campos do modal com os valores do ramal clicado
        document.getElementById('editRamal').value = ramal;
        document.getElementById('editUsuario').value = usuario;
        document.getElementById('editEmail').value = email;
        document.getElementById('editSetor').value = setor;
        $('#editModal').modal('show');  // Exibe o modal
    }

    // Função para submeter o formulário de atualização
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Impede o envio padrão do formulário

        const formData = new FormData(this);

        fetch('', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
        .then(data => {
            console.log(data);  // Exibe a resposta do PHP no console
            window.location.reload();  // Recarrega a página após sucesso
        }).catch(error => console.error('Erro:', error));
    });

    function showSuccessMessage(message) {
        // Injeta a mensagem no modal
        document.getElementById('successMessage').textContent = message;

        // Exibe o modal de sucesso
        $('#successModal').modal('show');
    }
