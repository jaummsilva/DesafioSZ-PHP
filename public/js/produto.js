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
  var quantityCarrinho = parseInt($(`#quantityCarrinho-${id}`).val());
  $(`#quantity-${id}`).val(quantity + 1);
  $(`#quantityCarrinho-${id}`).val(quantityCarrinho + 1);
}

function decrementQtd(id) {
  var quantity = parseInt($(`#quantity-${id}`).val());
  var quantityCarrinho = parseInt($(`#quantityCarrinho-${id}`).val());
  if (quantity > 0) {
    $(`#quantity-${id}`).val(quantity - 1);
  }
  if (quantityCarrinho > 0) {
    $(`#quantityCarrinho-${id}`).val(quantityCarrinho - 1);
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
      window.location.href = "/"
    },
  });
}

function decrementQtdCarrinho(id) {
  var quantityCarrinho = parseInt($(`#quantityCarrinho-${id}`).val());
  if (quantityCarrinho > 0) {
    $(`#quantityCarrinho-${id}`).val(quantityCarrinho - 1);
  }
  quantityCarrinho = quantityCarrinho + -1;

  $.ajax({
    url: "/alterarQuantidadeCarrinho",
    type: "POST",
    data: {
      idProduto: id,
      quantityCarrinho: quantityCarrinho,
    },
    success: function (response) {
      window.location.href = "/"
    },
    error: function (e) {
      alert("Ocorreu um erro");
    },
  });
}
function importarProduto() {
  let formData = new FormData();
  let arquivo = $('#inputImportacaoProduto')[0].files[0];
  formData.append('arquivoProdutoImportacao', arquivo);
  $.ajax({
    url: '/importarProduto',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      window.location.href = "/listagemProdutoAdmin"
    },
  })
}
function adicionar(id) {
  let qtdProduto = $(`#quantity-${id}`).val();

  $.ajax({
    url: "/inserirProdutoCarrinho",
    type: "POST",
    data: {
      idProduto: id,
      qtd_Produto: qtdProduto,
    },
    success: function (response) {
      window.location.href = "/";
    },
    error: function (e) {
      alert("Ocorreu um erro");
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
      alert("Produto recomendado criado com sucesso");
      window.location.href = "/listagemProdutoRecomendadoAdmin"
    },
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
      alert("Produto recomendado editado com sucesso");
      window.location.href = "/listagemProdutoRecomendadoAdmin"
    },
  })
}

async function criarProduto() {
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();
  let formData = new FormData();
  let img = $('#img')[0].files[0];
  let img2 = $('#img')[0].files[0];
  let img3 = $('#img')[0].files[0];
  let img4 = $('#img')[0].files[0];
  formData.append('img', img);
  formData.append('img2', img2);
  formData.append('img3', img3);
  formData.append('img4', img4);
  formData.append('descricao', descricao);
  formData.append('preco', preco);
  formData.append('nome', nome);
  if (nome == '') {
    alert('Digite o nome');
    return;
  }
  if (preco == '') {
    alert('Digite o preço');
    return;
  }
  if (descricao == '') {
    alert('Digite a descrição');
    return;
  }

  $.ajax({
    url: '/registrarProduto',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      alert("Produto criado com sucesso");
      window.location.href = "/listagemProdutoAdmin"
    },
  })
}
async function editarProduto() {
  let id = $('#id').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();
  let formData = new FormData();
  let img = $('#img')[0].files[0];
  let img2 = $('#img')[0].files[0];
  let img3 = $('#img')[0].files[0];
  let img4 = $('#img')[0].files[0];
  formData.append('img', img);
  formData.append('img2', img2);
  formData.append('img3', img3);
  formData.append('img4', img4);
  formData.append('descricao', descricao);
  formData.append('preco', preco);
  formData.append('nome', nome);
  formData.append('id',id);

  if (nome == '') {
    alert('Digite o nome');
    return;
  }
  if (preco == '') {
    alert('Digite o preço');
    return;
  }
  if (descricao == '') {
    alert('Digite a descrição');
    return;
  }
  $.ajax({
    url: '/editarProduto',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      alert("Produto editado com sucesso");
      window.location.href = "/listagemProdutoAdmin"
    },
  })
}
