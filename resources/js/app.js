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

    // Photography portfolio filter stuffs
    $('.filter-btn-check').on('change', function(){
        $('#photography-filter-btn').data('filters-changed', true);
    });

    $('#photography-filter-btn').on('click', function(){
        if($(this).data('filters-changed'))
        {
            var base_url = window.location.pathname;
            var filter_param = '';
            var first = true;
            $('.filter-btn-check').each(function(){
                if($(this).is(':checked'))
                {
                    if(first)
                    {
                        filter_param = filter_param.concat('?');
                        first = false;
                    }
                    else
                    {
                        filter_param = filter_param.concat('&');
                    }

                    filter_param = filter_param.concat('filters[]=' + $(this).data('filter-id'));
                }
            });

            window.location.replace(base_url + filter_param);
        }
    })
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