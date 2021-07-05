require('./bootstrap');

$(document).ready(function(){
    // Shortcut to admin panel by right click on splash page logo
    $('.context-logo').on("contextmenu", function(e){
        e.preventDefault();
        window.location.replace('/admin');
    });
});