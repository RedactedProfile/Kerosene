// JavaScript Document
jQuery(document).ready(function($) {
	$('.sorting-nav li a').click(function(e){
		e.preventDefault();
		
		var href = $(this).attr('href');
		var href_parts = href.split('/');
		var filter = href_parts[href_parts.length - 2];

		if(filter.indexOf('.') < 0){
			$('.post-wrapper article').each(function(){
				$(this).stop();
				if($(this).is(':visible'))
					$(this).fadeOut(200);
			});
			$('.post-wrapper article.category-'+filter).fadeIn();
		}else{
			$('.post-wrapper article').each(function(){
				$(this).fadeIn(200);
			});
		}
		return false;
	});
});