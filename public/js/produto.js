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
  if(quantityCarrinho > 0 ) {
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
  if(quantityCarrinho > 0 ) {
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
    url: "/alterarQuantidadeCarrinho",
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

function remover(id,idUsuario) {
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
  $('.input-number').attr("onchange",`remover(${id,idUsuario})`)
}


function criarProduto() {
  let img = $('#img').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();

  if(nome == '' || preco == '' || descricao == '' || img == '') {
    alert('Falta completar alguma campo');
    return;
  }

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
              nome
          },
          success: function (response) {
            
            alert(response);
          },
      })
  };
}
function editarProduto() {
  let id = $('#id').val();
  let img = $('#img').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();

  if(nome == '' || preco == '' || descricao == '' || img == '') {
    alert('Falta completar alguma campo');
    return;
  }

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
            nome
          },
          success: function (response) {
              window.location.href = "/listagemProdutoAdmin"
          },
      })
  };
}


