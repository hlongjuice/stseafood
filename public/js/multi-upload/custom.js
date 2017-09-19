$(document).ready(function(){

	$('.filer_input').filer({
		showThumbs: true,
		addMore: true,
		allowDuplicates: false
	});

	$('.single_filer_input').filer({
		showThumbs: true,
		addMore: false,
		limit:1,
		allowDuplicates: false
	});

});
