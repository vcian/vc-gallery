$ = jQuery;
$(window).load( function() {
	var container = document.querySelector('#container');
    var msnry = new Masonry( container, {
        itemSelector: '.item',
        columnWidth: '.item',                
    });       
});
$(document).ready(function() {
	$('.mycontainer').lightGallery();
});



