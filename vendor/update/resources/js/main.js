function initMenu(){
	
	if($.fn.singlePageNav){
		$('.main-menu').singlePageNav({
			'speed': 700,
			'currentClass': 'active'
		});
	}

}

function initGoToLinks () {
	$('a.goto').click(function (e) {
		e.preventDefault();
		     
		var target = $($(this).attr('href'));

		var distance = target.offset().top;

		$('html, body').stop().animate({
			'scrollTop': distance
		}, 500, 'swing');

	});
}

$(function () {

	$('.ui.sticky').sticky({
		context: '#main-content',
		offset: 30
	});

	$('.ui.accordion').accordion();

	initMenu();
	initGoToLinks();
	
});
