$(document).ready(function () {
  $('.btn-carrinho').click(function () {
    $('#exampleModal').modal('show');
  })
  $('.btn-favorito').click(function () {
    $('#exampleModal3').modal('show');
  })
  $('#imgAlteradoRecomendado2').click(function (e) {
    e.preventDefault();
    $('#imgRecomendado2').click();
  })
  $('#imgRecomendado2').change(function () {
    let nameImg = $('#imgRecomendado2')[0].files[0].name;
    $('#inputImgAlteradoRecomendado2').val(nameImg)
  })
  $('#imgAlteradoRecomendado').click(function (e) {
    e.preventDefault();
    $('#imgRecomendado').click();
  })
  $('#imgRecomendado').change(function () {
    let nameImg = $('#imgRecomendado')[0].files[0].name;
    $('#inputImgAlteradoRecomendado').val(nameImg)
  })
  $('#imgAlterado').click(function (e) {
    e.preventDefault();
    $('#img').click();
  })
  $('#img').change(function () {
    let nameImg = $('#img')[0].files[0].name;
    $('#inputImgAlterado').val(nameImg)
  })
  $('#imgAlterado2').click(function (e) {
    e.preventDefault();
    $('#img2').click();
  })
  $('#img2').change(function () {
    let nameImg = $('#img2')[0].files[0].name;
    $('#inputImgAlterado2').val(nameImg)
  })
  $('#imgAlterado3').click(function (e) {
    e.preventDefault();
    $('#img3').click();
  })
  $('#img3').change(function () {
    let nameImg = $('#img3')[0].files[0].name;
    $('#inputImgAlterado3').val(nameImg)
  })
  $('#imgAlterado4').click(function (e) {
    e.preventDefault();
    $('#img4').click();
  })
  $('#img4').change(function () {
    let nameImg = $('#img4')[0].files[0].name;
    $('#inputImgAlterado4').val(nameImg)
  })
})

function incrementQtd(id) {
  var quantity = parseInt($(`#quantity-${id}`).val());
  $(`#quantity-${id}`).val(quantity + 1);
}

function decrementQtd(id) {
  
  var quantity = parseInt($(`#quantity-${id}`).val());
  if (quantity > 1) {
    $(`#quantity-${id}`).val(quantity - 1);
  }
}

function incrementQtdCarrinho(id) {
  var quantityCarrinho = parseInt($(`#quantityCarrinho-${id}`).val());
  $(`#quantityCarrinho-${id}`).val(quantityCarrinho + 1);
  quantityCarrinho = quantityCarrinho + 1;

  $.ajax({
    url: "/alterarQuantidadeCarrinho",
    type: "POST",
    data: {
      idProduto: id,
      quantityCarrinho: quantityCarrinho,
    },
    success: function (response) {
      document.getElementById('total').innerHTML = "Total: R$" +  response.valorTotal;
    },
  });
}

function decrementQtdCarrinho(id) {
  var quantityCarrinho = parseInt($(`#quantityCarrinho-${id}`).val());
  if (quantityCarrinho > 1) {
    $(`#quantityCarrinho-${id}`).val(quantityCarrinho + -1);
  }
  if(quantityCarrinho > 1) {
    quantityCarrinho = quantityCarrinho + -1;
  }

  $.ajax({
    url: "/alterarQuantidadeCarrinho",
    type: "POST",
    data: {
      idProduto: id,
      quantityCarrinho: quantityCarrinho,
    },
    success: function (response) {
      document.getElementById('total').innerHTML = "Total:  R$" +  response.valorTotal;
    },
    error: function (e) {
      alert("Ocorreu um erro");
    },
  });
}
function adicionar(id) {
  let qtdProduto = parseInt($(`#quantity-${id}`).val());
  $.ajax({
    url: "/inserirProdutoCarrinho",
    type: "POST",
    dataType: 'json',
    data: {
      idProduto: id,
      qtd_Produto: qtdProduto,
    },
    success: function (response) {
      document.getElementById('numeroContador').innerHTML = response.contador;
      $('#exampleModal').load(
        "/modalCarrinho",undefined,() => {
          $('#exampleModal').modal('show');
        });
      },
  });
}

function remover(id, idUsuario) {
  $.ajax({
    url: "/removerProdutoCarrinho",
    type: "POST",
    data: {
      idProduto: id,
      idUsuario: idUsuario
    },
    success: function (response) {
      window.location.href = "/";
    },
    error: function (e) {
      window.location.href = "/"
      alert("Ocorreu um erro");
    },
  });
  $('.input-number').attr("onchange", `remover(${id, idUsuario})`)
}
function removerFavorito(id) {
  $.ajax({
    url: "/removerFavorito",
    type: "POST",
    data: {
      idProduto : id
    },
    success: function (response) {
      window.location.href = "/"
    },
    error: function (e) {
      window.location.href = "/"
      alert("Ocorreu um erro");
    },
  });
}
function adicionarFavorito(id) {
  $.ajax({
    url: "/adicionarFavorito",
    type: "POST",
    data: {
      idProduto : id
    },
    success: function (response) {
      window.location.href = "/"
    },
    error: function (e) {
      window.location.href = "/"
      alert("Ocorreu um erro");
    },
  });
}

function criarProdutoRecomendado() {

  let formData = new FormData();
  let img = $('#imgRecomendado')[0].files[0];
  formData.append('img', img);
  let numero_sequencia = $('#numero_sequencia').val();
  let idProduto = $('#produto_id').val();
  formData.append('numero_sequencia', numero_sequencia);
  formData.append('idProduto', idProduto);
  $.ajax({
    url: '/registrarProdutoRecomendado',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.sucesso) {
        var divSucesso = document.getElementById("cadastrar-produto-recomendado-sucesso");
        divSucesso.className = 'alert alert-success m-5 text-center'
        let p = document.createElement('p');
        p.innerText = "Produto recomendado cadastrado com sucesso";
        divSucesso.appendChild(p);
        setTimeout(() => {
            window.location.href = "/listagemProdutoRecomendadoAdmin"
        },3000)
    }
    }
  })
}

function editarProdutoRecomendado() {
  let id = $('#idRecomendado').val();
  let formData = new FormData();
  let img = $('#imgRecomendado2')[0].files[0];
  let numero_sequencia = $('#numero_sequencia').val();
  let idProduto = $('#produto_id_editar').val();

  formData.append('img', img);
  formData.append('numero_sequencia', numero_sequencia);
  formData.append('id', id);
  formData.append('idProduto', idProduto);

  $.ajax({
    url: '/editarProdutoRecomendado',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.sucesso) {
        var divSucesso = document.getElementById("editar-produto-recomendado-sucesso");
        divSucesso.className = 'alert alert-success m-5 text-center'
        let p = document.createElement('p');
        p.innerText = "Produto recomendado editado com sucesso";
        divSucesso.appendChild(p);
        setTimeout(() => {
            window.location.href = "/listagemProdutoRecomendadoAdmin"
        },3000)
    }
    }
  })
}

function criarProduto() {
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();
  let formData = new FormData();
  let img = $('#img')[0].files[0];
  let img2 = $('#img2')[0].files[0];
  let img3 = $('#img3')[0].files[0];
  let img4 = $('#img4')[0].files[0];
  
  formData.append('img', img);
  formData.append('img2', img2);
  formData.append('img3', img3);
  formData.append('img4', img4);
  formData.append('descricao', descricao);
  formData.append('preco', preco);
  formData.append('nome', nome);
  
  if (nome == '') {
    document.getElementById('p-produto-nome').style.display = "flex";
    return;
  }
  if (preco == '') {
    document.getElementById('p-produto-preco').style.display = "flex";
    return;
  }
  if (descricao == '') {
    document.getElementById('p-produto-descricao').style.display = "flex";
    return;
  }
  // Verifica se pelo menos uma imagem foi selecionada
  if (img == undefined) {
    document.getElementById('al-produto-img').style.display = "flex"
    return;
  }

  // Verifica se a imagem principal é PNG ou JPG
  if (img != undefined && !(img.type == 'image/png' || img.type == 'image/jpeg')) {
    document.getElementById('p-produto-img').style.display = "flex"
    return;
  }
  if (img2 && !(img2.type == 'image/png' || img2.type == 'image/jpeg')) {
    document.getElementById('p-produto-img-2').style.display = "flex"
    return;
  }
  if (img3 && !(img3.type == 'image/png' || img3.type == 'image/jpeg')) {
    document.getElementById('p-produto-img-3').style.display = "flex"
    return;
  }
  if (img4 && !(img4.type == 'image/png' || img4.type == 'image/jpeg')) {
    document.getElementById('p-produto-img-4').style.display = "flex"
    return;
  }

  $.ajax({
    url: '/registrarProduto',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.sucesso) {
        var divSucesso = document.getElementById("cadastrar-produto-sucesso");
        divSucesso.className = 'alert alert-success m-5 text-center'
        let p = document.createElement('p');
        p.innerText = "Produto cadastrado com sucesso";
        divSucesso.appendChild(p);
        setTimeout(() => {
            window.location.href = "/listagemProdutoAdmin"
        },3000)
    }
    }
  })
}
function editarProduto() {
  let id = $('#id').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();
  let formData = new FormData();
  let img = $('#img')[0].files[0];
  let img2 = $('#img2')[0].files[0];
  let img3 = $('#img3')[0].files[0];
  let img4 = $('#img4')[0].files[0];
  formData.append('img', img);
  formData.append('img2', img2);
  formData.append('img3', img3);
  formData.append('img4', img4);
  formData.append('descricao', descricao);
  formData.append('preco', preco);
  formData.append('nome', nome);
  formData.append('id',id);

  if (nome == '') {
    document.getElementById('p-produto-nome').style.display = "flex";
    return;
  }
  if (preco == '') {
    document.getElementById('p-produto-preco').style.display = "flex";
    return;
  }
  if (descricao == '') {
    document.getElementById('p-produto-descricao').style.display = "flex";
    return;
  }
  if (img && !(img.type == 'image/png' || img.type == 'image/jpeg')) {
    document.getElementById('p-produto-img').style.display = "flex"
    return;
  }
  if (img2 && !(img2.type == 'image/png' || img2.type == 'image/jpeg')) {
    document.getElementById('p-produto-img-2').style.display = "flex"
    return;
  }
  if (img3 && !(img3.type == 'image/png' || img3.type == 'image/jpeg')) {
    document.getElementById('p-produto-img-3').style.display = "flex"
    return;
  }
  if (img4 && !(img4.type == 'image/png' || img4.type == 'image/jpeg')) {
    document.getElementById('p-produto-img-4').style.display = "flex"
    return;
  }
  $.ajax({
    url: '/editarProduto',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.sucesso) {
        var divSucesso = document.getElementById("editar-produto-sucesso");
        divSucesso.className = 'alert alert-success m-5 text-center'
        let p = document.createElement('p');
        p.innerText = "Produto editado com sucesso";
        divSucesso.appendChild(p);
        setTimeout(() => {
            window.location.href = "/listagemProdutoAdmin"
        },3000)
    }
    }
  })
}
