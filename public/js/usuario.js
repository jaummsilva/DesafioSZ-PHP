function criarUsuario() {
    let img = $('#img').val();
    let nome = $('#nome').val();
    let telefone = $('#tel').val();
    let nascimento = $('#nascimento').val();
    let senha = $('#senha').val();
    let repetirSenha = $('#repetirSenha').val();
    let email = $('#email').val();

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
                window.location.href = "/listagemUsuarioAdmin"
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
                window.location.href = "/listagemUsuarioAdmin"
            },
        })
    };
}