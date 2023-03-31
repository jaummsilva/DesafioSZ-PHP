function criarUsuario() {
    let img = $('#img').val();
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();

    if(senha != repetirSenha) {
        alert('Senhas devem ser iguais')
        return;
    }
    if(nome == '' || telefone == '' || nascimento == '' || senha == '' || repetirSenha == '' || email == '' || img == '') {
        alert('Falta completar alguma campo');
        return;
    }
    const fileInput = document.querySelector('#img');
    const reader = new FileReader();
    reader.readAsDataURL(fileInput.files[0]);
    reader.onload = function () {
        img = reader.result.split(',')[1];

        $.ajax({
            url: '/registrarUsuario',
            type: 'POST',
            data: {
                img,
                email,
                nascimento,
                senha,
                repetirSenha,
                telefone,
                nome,
            },
            success: function (response) {
                if(response.mensagem) {
                    let div = document.createElement('div');
                    div.className = 'alert alert-danger d-flex justify-content-center'
                    let p = document.createElement('p');
                    p.innerText = response.mensagem;
                    div.appendChild(p);
                    var divLogin = document.getElementById("login");
                    document.body.insertBefore(div, divLogin);
                }
                else {
                    alert('Criado com sucesso');
                    window.location.href = '/listagemUsuarioAdmin'
                }
            },
        })
    };
}
function editarUsuario() {
    let img = $('#img').val();
    let id = $('#id').val();
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();
    
    if(senha != repetirSenha) {
        alert('Senhas devem ser iguais')
        return;
    }
    if(nome == '' || telefone == '' || nascimento == '' || senha == '' || repetirSenha == '' || email == '' || img == '') {
        alert('Falta completar alguma campo');
        return;
    }

    const fileInput = document.querySelector('#img');
    const reader = new FileReader();
    reader.readAsDataURL(fileInput.files[0]);
    reader.onload = function () {
        img = reader.result.split(',')[1];

        $.ajax({
            url: '/editarUsuario',
            type: 'POST',
            data: {
                img,
                email,
                nascimento,
                senha,
                repetirSenha,
                telefone,
                nome,
                id
            },
            success: function (response) {
                if(response.mensagem) {
                    let div = document.createElement('div');
                    div.className = 'alert alert-danger d-flex justify-content-center'
                    let p = document.createElement('p');
                    p.innerText = response.mensagem;
                    div.appendChild(p);
                    var divLogin = document.getElementById("login");
                    document.body.insertBefore(div, divLogin);
                }
                else {
                    alert('Criado com sucesso');
                    window.location.href = '/listagemUsuarioAdmin'
                }
            },
        })
    };
}