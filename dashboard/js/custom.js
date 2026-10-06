$(document).ready(function() {
    $('.nav-item').hover(function() {
        $(this).addClass('show');
    });
    $('.nav-item').mouseleave(function() {
        $(this).removeClass('show');
    });
});


//Show Current year

var currentYear = new Date().getFullYear();
$('#current-year').html(currentYear);

// End SHow Current Year