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
    let formData = new FormData();
    let img = $('#img')[0].files[0];
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();
    formData.append('img', img);
    formData.append('nome', nome);
    formData.append('nascimento', nascimento);
    formData.append('senha', senha);
    formData.append('email', email);
    formData.append('telefone', telefone);

    if (senha != repetirSenha) {
        document.getElementById('al-usuario-cad-senhas').style.display = "flex;"
        return;
    }
    if (nome == '') {
        document.getElementById('p-usuario-cad-nome').style.display = "flex";
        return;
    }
    if (telefone == '') {
        document.getElementById('p-usuario-cad-tel').style.display = "flex";
        return;
    }
    if (nascimento == '') {
        document.getElementById('p-usuario-cad-nascimento').style.display = "flex";
        return;
    }
    if (email == '') {
        document.getElementById('p-usuario-cad-email').style.display = "flex";
        return;
    }
    $.ajax({
        url: '/registrarUsuario',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.mensagem) {
                let div = document.createElement('div');
                div.className = 'alert alert-danger d-flex justify-content-center'
                let p = document.createElement('p');
                p.innerText = response.mensagem;
                div.appendChild(p);
                var divLogin = document.getElementById("cadastro-usuario-error");
                document.body.insertBefore(div, divLogin);
            }
        }
    })
};
function editarUsuario() {
    let formData = new FormData();
    let img = $('#img')[0].files[0];
    let id = $('#id').val();
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();
    formData.append('img', img);
    formData.append('id', id);
    formData.append('nome', nome);
    formData.append('nascimento', nascimento);
    formData.append('senha', senha);
    formData.append('email', email);
    formData.append('telefone', telefone);

    if (senha != repetirSenha) {
        document.getElementById('p-usuario-edit-senhas').style.display = "flex;"
        return;
    }
    if (nome == '') {
        document.getElementById('p-usuario-edit-nome').style.display = "flex";
        return;
    }
    if (telefone == '') {
        document.getElementById('p-usuario-edit-tel').style.display = "flex";
        return;
    }
    if (nascimento == '') {
        document.getElementById('p-usuario-edit-nascimento').style.display = "flex";
        return;
    }
    if (email == '') {
        document.getElementById('p-usuario-edit-email').style.display = "flex";
        return;
    }
    $.ajax({
        url: '/editarUsuario',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
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