$(document).ready(function(){
    window.setTimeout(function() {
        $('.alert-auto-close').hide();
    }, 2500);
});
$(document).on('click', '.btn-history-back', function() {
    history.back();
});
