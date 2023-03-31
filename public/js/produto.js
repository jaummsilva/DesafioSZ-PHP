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
      alert("Item adicionado com sucesso");
      window.location.href = '/'
    },
    error: function (e) {
      alert("Ocorreu um erro");
    },
  });
}

function criarCarrinho(preco) {
  $.ajax({
    url: "/criarPedido",
    type: "POST",
    data: {
      preco: preco,
    },
    success: function (response) {
      $('#exampleModal').modal('show');
    },
    error: function (e) {
      alert("Ocorreu um erro");
    },
  });
}


function adicionarCarrinho(id) {
  let qtdProdutoCarrinho = $(`#quantityCarrinho-${id}`).val();
  console.log(qtdProdutoCarrinho);
  
  $.ajax({
    url: "/inserirProdutoCarrinho",
    type: "POST",
    data: {
      idProduto: id,
      qtd_produto_carrinho: qtdProdutoCarrinho
    },
    success: function (response) {
      window.location.href = '/'
      $('#exampleModal').modal('show');
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
              window.location.href = "/listagemProdutoAdmin"
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


