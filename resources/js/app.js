require('./bootstrap');

$(document).ready(function(){
    $('.lazy').Lazy({
        appendScroll: $('.photography-container'),
        visibleOnly: true,
        afterLoad: function(element) {
            element.css('min-width', '0px');
        },
    });

    // When the copy button is clicked, select the value of the text box, attempt
    // to execute the copy command
    $('#copy-button').bind('click', function() {
        var input = document.querySelector('#copy-input');
        input.focus();
        input.select();
        var success = document.execCommand('copy');
    });
    
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

    jQuery.fn.extend({
        showFullSize: function()
        {
            $('.full-res-img').hide();
            $('#full-res-loader').show();
    
            $('#full-size-viewer').show();
    
            var shoot_id = $(this).data('shoot-id');
    
            if($('#viewer-shoot-link').length)
            {
                var shoot_url = $('#viewer-shoot-link').attr('href');
    
                shoot_url = shoot_url.substring(0, shoot_url.lastIndexOf('/'));
                shoot_url = shoot_url + '/' + shoot_id;
        
                $('#viewer-shoot-link').attr('href', shoot_url);
            }
    
            var photo_id = $(this).data('photo-id');

            $('#full-size-viewer').data('photo-id', photo_id);

            var full_res_img = $('#full-res-img-' + photo_id);
    
            var asset = $(this).data('asset');
            var asset_src = full_res_img.attr('src');
    
            asset_src = asset_src.substring(0, asset_src.lastIndexOf('/'));
            asset_src = asset_src + '/' + asset;
    
            full_res_img.attr('src', asset_src);
    
            if($('#download-link').length)
            {
                $('#download-link').attr('href', asset_src);
            }
    
            full_res_img.on('load', function(){
                $(this).fadeIn(500);
                $('#full-res-loader').hide();
            });
        }
    });

    // Full size viewer
    $('.photo-container').on('click', function(){
        $(this).showFullSize();
    });

    $('#viewer-left').on('click', function(){
        var photos_array = $('#full-size-viewer').data('photo-ids');
        var photo_id = $('#full-size-viewer').data('photo-id');
        var index = photos_array.indexOf(photo_id);

        if(index <= 0)
        {
            index = photos_array.length - 1;
        }
        else
        {
            index--;
        }

        photo_id = photos_array[index];
        $('#photo-container-' + photo_id).showFullSize();
    });

    $('#viewer-right').on('click', function(){
        var photos_array = $('#full-size-viewer').data('photo-ids');
        var photo_id = $('#full-size-viewer').data('photo-id');
        var index = photos_array.indexOf(photo_id);

        if(index >= (photos_array.length - 1))
        {
            index = 0;
        }
        else
        {
            index++;
        }

        photo_id = photos_array[index];
        $('#photo-container-' + photo_id).showFullSize();
    });

    $('#viewer-close').on('click', function(){
        $('#full-size-viewer').hide();
    });

    $('#full-res-container').on('click', function(){
        $('#full-size-viewer').hide();
    }).children().click(function(){
        return false;
    });

    $('#full-size-viewer').hide();
});

window.verifyDeleteForm = function (message, formID, title = 'Delete?'){
    swal.fire({
        title: title,
        icon: 'warning',
        iconColor: '#e3342f',
        html: `<p class="alert-text">` + message + `</p>`,
        padding: '.5rem',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#38c172',
        confirmButtonText: 'Yes, do it!',
    }).then((result) => {
        if(result.isConfirmed)
        {
            $(formID).submit();
        }
    });
}