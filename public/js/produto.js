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
      alert("Ok");
    },
    error: function (e) {
      alert("Carrinho alterado");
      $('')
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
      alert("Carrinho alterado");
    },
    error: function (e) {
      alert("Ocorreu um erro");
    },
  });
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
      window.location.href = "/"
      alert("Carrinho alterado");
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
      alert('Item removido do carrinho');
      window.location.href = "/";
    },
    error: function (e) {
      window.location.href = "/"
      alert("Ocorreu um erro");
    },
  });
  $('.input-number').attr("onchange", `remover(${id, idUsuario})`)
}


function criarProduto() {
  let img = $('#img').val();
  let img2 = $('#img2').val();
  let img3 = $('#img3').val();
  let img4 = $('#img4').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();

  if (nome == '' || preco == '' || descricao == '' || img == '') {
    alert('Falta completar alguma campo');
    return;
  }

  // img2
  const fileInput2 = document.querySelector('#img2');
  const reader2 = new FileReader();
  if(fileInput2.files[0] instanceof Blob) {
    reader2.readAsDataURL(fileInput2.files[0]);
    reader2.onload = function () {
    img2 = reader2.result.split(',')[1];
  }}
  // img3
  const fileInput3 = document.querySelector('#img3');
  const reader3 = new FileReader();
  if(fileInput3.files[0] instanceof Blob) {
    reader3.readAsDataURL(fileInput3.files[0]);
    reader3.onload = function () {
    img3 = reader3.result.split(',')[1];
  }}
  // img4
  const fileInput4 = document.querySelector('#img4');
  const reader4 = new FileReader();
  if(fileInput4.files[0] instanceof Blob) {
    reader4.readAsDataURL(fileInput4.files[0]);
    reader4.onload = function () {
    img4 = reader4.result.split(',')[1];
  }}

  // img principal
  const fileInput = document.querySelector('#img');
  const reader = new FileReader();
  reader.readAsDataURL(fileInput.files[0]);
  reader.onload = function () {
    img = reader.result.split(',')[1];
    $.ajax({
      url: '/registrarProduto',
      type: 'POST',
      data: {
        img,
        descricao,
        preco,
        nome,
        img2,
        img3,
        img4
      },
      success: function (response) {
        alert("Produto criado com sucesso");
        window.location.href = "/listagemProdutoAdmin"
      },
    })
  };
}
function editarProduto() {
  let id = $('#id').val();
  let img = $('#img').val();
  let img2 = $('#img2').val();
  let img3 = $('#img3').val();
  let img4 = $('#img4').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();

  if (nome == '' || preco == '' || descricao == '' || img == '') {
    alert('Falta completar alguma campo');
    return;
  }

  // img2
  const fileInput2 = document.querySelector('#img2');
  const reader2 = new FileReader();
  if(fileInput2.files[0] instanceof Blob) {
    reader2.readAsDataURL(fileInput2.files[0]);
    reader2.onload = function () {
    img2 = reader2.result.split(',')[1];
  }}
  // img3
  const fileInput3 = document.querySelector('#img3');
  const reader3 = new FileReader();
  if(fileInput3.files[0] instanceof Blob) {
    reader3.readAsDataURL(fileInput3.files[0]);
    reader3.onload = function () {
    img3 = reader3.result.split(',')[1];
  }}
  // img4
  const fileInput4 = document.querySelector('#img4');
  const reader4 = new FileReader();
  if(fileInput4.files[0] instanceof Blob) {
    reader4.readAsDataURL(fileInput4.files[0]);
    reader4.onload = function () {
    img4 = reader4.result.split(',')[1];
  }}
  // img principal
  const fileInput = document.querySelector('#img');
  const reader = new FileReader();
  reader.readAsDataURL(fileInput.files[0]);
  reader.onload = function () {
    img = reader.result.split(',')[1];
    $.ajax({
      url: '/editarProduto',
      type: 'POST',
      data: {
        id,
        img,
        descricao,
        preco,
        nome,
        img2,
        img3,
        img4
      },
      success: function (response) {
        alert("Produto alterado com sucesso");
        window.location.href = "/listagemProdutoAdmin"
      },
    })
  };
}

