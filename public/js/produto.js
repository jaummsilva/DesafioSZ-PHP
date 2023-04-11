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
      alert("Carrinho alterado");
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
      alert("Carrinho alterado");
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
  let img = $('#img').val();
  let imgNome = $('#inputImgAlterado').val()
  let imgNome2 = $('#inputImgAlterado2').val()
  let imgNome3 = $('#inputImgAlterado3').val()
  let imgNome4 = $('#inputImgAlterado4').val()
  let img2 = $('#img2').val();
  let img3 = $('#img3').val();
  let img4 = $('#img4').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();

  if ((!img.endsWith('.png') == true && !img.endsWith('.jpg') == true) || img == '') {
    alert('Somente imagens em png e jpg aceitas');
    return;
  }
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

  // img2
  const fileInput2 = document.querySelector('#img2');
  const reader2 = new FileReader();
  if (fileInput2.files[0] instanceof Blob) {
    reader2.readAsDataURL(fileInput2.files[0]);
    await (
      new Promise(
        (resolve, reject) => {
          reader2.onload = function () {
            img2 = reader2.result.split(',')[1];
            resolve();
          }
        }
      ));
  }
  // img3
  const fileInput3 = document.querySelector('#img3');
  const reader3 = new FileReader();
  if (fileInput3.files[0] instanceof Blob) {
    reader3.readAsDataURL(fileInput3.files[0]);
    await (
      new Promise(
        (resolve, reject) => {
          reader3.onload = function () {
            img3 = reader3.result.split(',')[1];
            resolve();
          }
        }
      ));
  }
  // img4
  const fileInput4 = document.querySelector('#img4');
  const reader4 = new FileReader();
  if (fileInput4.files[0] instanceof Blob) {
    reader4.readAsDataURL(fileInput4.files[0]);
    await (
      new Promise(
        (resolve, reject) => {
          reader4.onload = function () {
            img4 = reader4.result.split(',')[1];
            resolve();
          }
        }
      ));
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
    url: '/registrarProduto',
    type: 'POST',
    data: {
      img,
      imgNome,
      descricao,
      preco,
      nome,
      img2,
      imgNome2,
      img3,
      imgNome3,
      img4,
      imgNome4
    },
    success: function (response) {
      alert("Produto criado com sucesso");
      window.location.href = "/listagemProdutoAdmin"
    },
  })
}

async function editarProduto() {
  let id = $('#id').val();
  let img = $('#img').val();
  let imgNome = $('#inputImgAlterado').val()
  let imgNome2 = $('#inputImgAlterado2').val()
  let imgNome3 = $('#inputImgAlterado3').val()
  let imgNome4 = $('#inputImgAlterado4').val()
  let img2 = $('#img2').val();
  let img3 = $('#img3').val();
  let img4 = $('#img4').val();
  let nome = $('#nome').val();
  let preco = $('#preco').val();
  let descricao = $('#descricao').val();

  if (!img.endsWith('.png') && img != '' && !img.endsWith('.jpg')) {
    alert('Somente imagens em png aceitas');
    return;
  }

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

  // img2
  const fileInput2 = document.querySelector('#img2');
  const reader2 = new FileReader();
  if (fileInput2.files[0] instanceof Blob) {
    reader2.readAsDataURL(fileInput2.files[0]);
    await (
      new Promise(
        (resolve, reject) => {
          reader2.onload = function () {
            img2 = reader2.result.split(',')[1];
            resolve();
          }
        }
      ));
  }
  // img3
  const fileInput3 = document.querySelector('#img3');
  const reader3 = new FileReader();
  if (fileInput3.files[0] instanceof Blob) {
    reader3.readAsDataURL(fileInput3.files[0]);
    await (
      new Promise(
        (resolve, reject) => {
          reader3.onload = function () {
            img3 = reader3.result.split(',')[1];
            resolve();
          }
        }
      ));
  }
  // img4
  const fileInput4 = document.querySelector('#img4');
  const reader4 = new FileReader();
  if (fileInput4.files[0] instanceof Blob) {
    reader4.readAsDataURL(fileInput4.files[0]);
    await (
      new Promise(
        (resolve, reject) => {
          reader4.onload = function () {
            img4 = reader4.result.split(',')[1];
            resolve();
          }
        }
      ));
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
    url: '/editarProduto',
    type: 'POST',
    data: {
      id,
      img,
      imgNome,
      descricao,
      preco,
      nome,
      img2,
      imgNome2,
      img3,
      imgNome3,
      img4,
      imgNome4
    },
    success: function (response) {
      alert("Produto editado com sucesso");
      window.location.href = "/listagemProdutoAdmin"
    },
  })
}
