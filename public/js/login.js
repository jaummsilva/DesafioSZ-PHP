function showPassword() {
    
    var key_attr = $('#key').attr('type');

    if(key_attr != 'text') {
        
        $('.checkbox').addClass('show');
        $('#key').attr('type', 'text');
        
    } else {
        $('.checkbox2').removeClass('show');
        $('#key').attr('type', 'password');
        
    }
}