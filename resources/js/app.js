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
    });

    // Full size viewer
    $('.photo-container').on('click', function(){
        $('.full-res-img').hide();
        $('#full-res-loader').show();

        $('#full-size-viewer').show();

        var shoot_id = $(this).data('shoot-id');
        var shoot_url = $('#viewer-shoot-link').attr('href');

        shoot_url = shoot_url.substring(0, shoot_url.lastIndexOf('/'));
        shoot_url = shoot_url + '/' + shoot_id;

        $('#viewer-shoot-link').attr('href', shoot_url);

        var photo_id = $(this).data('photo-id');
        var full_res_img = $('#full-res-img-' + photo_id);

        var asset = $(this).data('asset');
        var asset_src = full_res_img.attr('src');

        asset_src = asset_src.substring(0, asset_src.lastIndexOf('/'));
        asset_src = asset_src + '/' + asset;

        full_res_img.attr('src', asset_src);

        full_res_img.on('load', function(){
            $(this).fadeIn(500);
            $('#full-res-loader').hide();
        });
    });

    $('#viewer-close').on('click', function(){
        $('#full-size-viewer').hide();
    });

    $('#full-size-viewer').hide();
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