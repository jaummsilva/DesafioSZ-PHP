$(document).ready(function(){

    function adicionar(id) {
        $.ajax({
            url: 'inserirProdutoCarrinho',
            type: 'POST',
            data: {
                idUsuario: id,
                
            },
            success: function(response) {
                window.location.href = "/";
                alert("Item adicionado com sucesso");
            },
            error: function(e){
                window.location.href = "/";
                alert("Ocorreu um erro");
            }
        })
}
   

    var quantitiy=0;
       $('.quantity-right-plus').click(function(e){
            
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var quantity = parseInt($('#quantity').val());
            
            // If is not undefined
                
                $('#quantity').val(quantity + 1);
    
              
                // Increment

                $.ajax({
                    url: 'produto',
                    type: 'GET',
                    success: function() {
                    },
                    error: function(){
                        
                    }
                })
            
        });
    
         $('.quantity-left-minus').click(function(e){
            // Stop acting like a button
            e.preventDefault();
            // Get the field name
            var quantity = parseInt($('#quantity').val());
            
            // If is not undefined
          
                // Increment
                if(quantity>0){
                $('#quantity').val(quantity - 1);
                }
        });
function adicionar(id) {
            $.ajax({
                url: 'inserirProdutoCarrinho',
                type: 'POST',
                data: {
                    idUsuario: id,

                },
                success: function(response) {
                    window.location.href = "/listagemUsuarioAdmin"
                    alert("Deletado com sucesso")
                },
                error: function(e){
                    window.location.href = "/listagemUsuarioAdmin"
                    alert("Ocorreu um erro")
                }
            })
}
$('.btn-carrinho').click(function (e) {
    let text = e.target.id;
    let idText = text.split("-");
    let id = idText[3];
    console.log(id);
})

});