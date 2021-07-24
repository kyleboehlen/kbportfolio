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

    // Shoot selector
    $('#shoot-selector').on('change', function(){
        var shoot_id = $(this).find(':selected').val();
        window.location.replace(window.location.href + '/' + shoot_id);
    });
});

window.verifyDeleteForm = function (message, formID){
    swal.fire({
        title: 'Delete?',
        icon: 'warning',
        iconColor: '#e3342f',
        html: `<p class="alert-text">` + message + `</p>`,
        padding: '.5rem',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#38c172',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if(result.isConfirmed)
        {
            $(formID).submit();
        }
    });
}