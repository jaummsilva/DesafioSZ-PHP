$(document).ready(function () {
    $("#valorTransf").blur(function(){
   
        var valor = $(this).val();
        
        if(valor.match(/^\.\d{2}\b/)){
           console.log("ok");
        }else{
           console.log("inválido");
        }
     });
     $('#valorTransf').keyup(function(){
        (parseFloat($('#numero').val()).toFixed(2).replace(".", ","));
    });
})