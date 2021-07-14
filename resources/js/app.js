require('./bootstrap');

$(document).ready(function(){
    // Shortcut to admin panel by right click on splash page logo
    $('.context-logo').on('contextmenu', function(e){
        e.preventDefault();
        window.location.replace('/admin');
    });

    // Disable codebase link for private codebase
    $('#private-codebase-checkbox').on('change', function() {
        var checked = $(this).is(":checked");
        if(checked)
        {
            $('#codebase-link-input').val('');
            $('#codebase-link-input').prop('disabled', true);
        }
        else
        {
            $('#codebase-link-input').prop('disabled', false);
        }
      });
});