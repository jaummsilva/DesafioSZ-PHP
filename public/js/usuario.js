$(document).ready(function () {
    $('#imgAlterado').click(function (e) {
        e.preventDefault();
        $('#img').click();
    })
    $('#img').change(function () {
        let nameImg = $('#img')[0].files[0].name;
        $('#inputImgAlterado').val(nameImg)
    })
})

async function criarUsuario() {
    let img = $('#img').val();
    let imgNome = $('#inputImgAlterado').val()
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();

    if (senha != repetirSenha) {
        alert('Senhas devem ser iguais')
        return;
    }

    if (nome == '') {
        alert('Digite o nome');
        return;
    }
    if (telefone == '') {
        alert('Digite o telefone');
        return;
    }
    if (nascimento == '') {
        alert('Informe sua data de nascimento');
        return;
    }
    if (email == '') {
        alert('Digite o seu email');
        return;
    }
    if (!img.endsWith('.png') == true || img == '') {
        alert('Somente imagens em png aceitas');
        return;
    }
    // img principal
    const fileInput = document.querySelector('#img');
    const reader = new FileReader();
    if (fileInput.files[0] instanceof Blob) {
        reader.readAsDataURL(fileInput.files[0]);
        await (
            new Promise(
                (resolve, reject) => {
                    reader.onload = function () {
                        img = reader.result.split(',')[1];
                        resolve();
                    }
                }
            ));
    }
    $.ajax({
        url: '/registrarUsuario',
        type: 'POST',
        data: {
            img,
            imgNome,
            email,
            nascimento,
            senha,
            repetirSenha,
            telefone,
            nome,
        },
        success: function (response) {
            if (response.mensagem) {
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
        }
    })
};

async function editarUsuario() {
    let img = $('#img').val();
    let id = $('#id').val();
    let imgNome = $('#inputImgAlterado').val()
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();

    if (senha != repetirSenha) {
        alert('Senhas devem ser iguais')
        return;
    }

    if (nome == '') {
        alert('Digite o nome');
        return;
    }
    if (telefone == '') {
        alert('Digite o telefone');
        return;
    }
    if (nascimento == '') {
        alert('Informe sua data de nascimento');
        return;
    }
    if (email == '') {
        alert('Digite o seu email');
        return;
    }
    if (!img.endsWith('.png') == true || img == '') {
        alert('Somente imagens em png aceitas');
        return;
    }

    // img principal
    const fileInput = document.querySelector('#img');
    const reader = new FileReader();
    if (fileInput.files[0] instanceof Blob) {
        reader.readAsDataURL(fileInput.files[0]);
        await (
            new Promise(
                (resolve, reject) => {
                    reader.onload = function () {
                        img = reader.result.split(',')[1];
                        resolve();
                    }
                }
            ));
    }

    $.ajax({
        url: '/editarUsuario',
        type: 'POST',
        data: {
            img,
            imgNome,
            email,
            nascimento,
            senha,
            repetirSenha,
            telefone,
            nome,
            id
        },
        success: function (response) {
            if (response.mensagem) {
                let div = document.createElement('div');
                div.className = 'alert alert-danger d-flex justify-content-center'
                let p = document.createElement('p');
                p.innerText = response.mensagem;
                div.appendChild(p);
                var divLogin = document.getElementById("login");
                document.body.insertBefore(div, divLogin);
            }
            else {
                alert('Usuario editado com sucesso');
                window.location.href = '/listagemUsuarioAdmin'
            }
        },
    })
};