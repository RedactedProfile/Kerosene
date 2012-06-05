// JavaScript Document

// custom image slider

// prep slider settings
var slide_parent = '#slides-wrapper';
var slide_obj_group = '#slides .content';
var slide_cap_group = '#slides_captions';

var curslidenum;
var activeslide;
var nextslide;
var capnum;
var timerID;

jQuery(document).ready(function($){
	// setup objects
	var activeset = false;
	var slidenum = 1;
	$(slide_obj_group).each(function(){
		if(!activeset){
			$(this).addClass('active').attr('rel', slidenum).css({opacity: 1.0});
			activeset = true;
		}else{
			$(this).removeClass('active').attr('rel', slidenum);
		}
		slidenum++;
	});
	
	if(slide_cap_group != ''){
		var slidenum = 1;
		$(slide_cap_group+' li').each(function(){
			if($(this).attr('rel') == '' || $(this).attr('rel') == undefined){
				$(this).attr('rel', slidenum);
			}
			if(slidenum == 1){
				capactive(1);
			}else{
				capnormal(slidenum);
			}
			slidenum++;
		});
	}
	
	$(slide_cap_group+' li a').click(function(e){
		e.preventDefault();
		capnum = $(this).parent().attr('rel');
		if(jQuery(slide_parent+' .active').attr('rel') != capnum){
			if(transition == 'fade'){
				activeslide = jQuery(slide_parent+' .active');		
				if (activeslide.length == 0) activeslide = jQuery(slide_obj_group+':last');
				jQuery(slide_parent+' .active').stop().css({opacity: 1.0});
				window.clearInterval(timerID);
				
				nextslide = jQuery(slide_obj_group+'[rel="'+capnum+'"]');
				activeslide.addClass('last-active');
	
				nextslide.css({opacity: 0.0})
					.addClass('active')
					.animate({opacity: 1.0}, transition_time, function(){
						activeslide.removeClass('active last-active').css({opacity: 0.0});
						if(slide_cap_group != ''){
							// deselect cap
							capnormal(activeslide.attr('rel'));
							
							// select cap
							capactive(nextslide.attr('rel'));
						}
					});
				timerID = setInterval("slideSwitch()", wait_time);
			}else if(transition == 'slideleft'){
			}else if(transition == 'slideright'){
			}else if(transition == 'slideup'){
			}else if(transition == 'slidedown'){
			}
		}
		return false;
	});
});

function capnormal(num){
	var css;
	if(num > 0){
		jQuery(slide_cap_group+' li[rel='+num+'] a').removeClass('active');
	}else{
		jQuery(slide_cap_group+' li a').each(function($){
			$(this).removeClass('active');
		});
	}
}

function capactive(num){
	var css;
	jQuery(slide_cap_group+' li[rel='+num+'] a').addClass('active');	
}
		
// run slider
function slideSwitch(){
	if(transition == 'fade'){
		activeslide = jQuery(slide_parent+' .active');		
		if (activeslide.length == 0) activeslide = jQuery(slide_obj_group+':last');

		nextslide = activeslide.next().length ? activeslide.next() : jQuery(slide_obj_group+':first');
		activeslide.addClass('last-active');

		nextslide.css({opacity: 0.0})
			.addClass('active')
			.animate({opacity: 1.0}, transition_time, function(){
				activeslide.removeClass('active last-active').css({opacity: 0.0});
				if(slide_cap_group != ''){
					// deselect cap
					capnormal(activeslide.attr('rel'));
					
					// select cap
					capactive(nextslide.attr('rel'));
				}
			});
	}else if(transition == 'slideleft'){
	}else if(transition == 'slideright'){
	}else if(transition == 'slideup'){
	}else if(transition == 'slidedown'){
	}
}

// start the ball rolling
timerID = setInterval("slideSwitch()", wait_time);
