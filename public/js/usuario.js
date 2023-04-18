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

function criarUsuario() {
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
        document.getElementById('p-usuario-cad-senhas').style.display = "flex";
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
                var divLogin = document.getElementById("cadastro-usuario-error");
                divLogin.className = "alert alert-danger m-5 text-center"
                let p = document.createElement('p');
                p.innerText = response.mensagem;
                divLogin.appendChild(p);
            }
            if(response.sucesso != false) {
                var divSucesso = document.getElementById("cadastro-usuario-error");
                divSucesso.className = 'alert alert-success m-5 text-center'
                let p = document.createElement('p');
                p.innerText = "Usuario cadastrado com sucesso";
                divSucesso.appendChild(p);
                setTimeout(() => {
                    window.location.href = "/listagemUsuarioAdmin"
                },3000)
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
        document.getElementById('p-usuario-edit-senhas').style.display = "flex";
        return;
    }
    if (nome == '') {
        document.getElementById('p-usuario-edit-nome').style.visibility = "visible";
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
            if (response.sucesso) {
                var divSucesso = document.getElementById("editar-usuario-sucesso");
                divSucesso.className = 'alert alert-success m-5 text-center'
                let p = document.createElement('p');
                p.innerText = "Usuario editado com sucesso";
                divSucesso.appendChild(p);
                setTimeout(() => {
                    window.location.href = "/listagemUsuarioAdmin"
                },3000)
            }
        },
    })
};