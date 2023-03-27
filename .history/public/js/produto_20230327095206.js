$(document).ready(function () {
     $('#valorTransf').keyup(function(){
        (parseFloat($('#valorTransf').val()).toFixed(2).replace(".", ","));
    });
})