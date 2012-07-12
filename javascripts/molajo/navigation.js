jQuery(document).ready(function($){
	$(".accordion").tabs(
		".accordion dd",
		{tabs: 'dt', effect: 'slide', initialIndex: 1}
	);

	$('nav[role="navigation"] dd>ul>li').click(function(){
		$(this).children('ul').toggle();
	});

	$('.level-one > dd > ul > li > a').click(function(){

		var dd     = $(this).parents('dd');
		var menu   = $(this).parents('ul').clone(true);
		var width  = $('nav').width();
		var active = dd.prev('dt').html();

		dd.prev('dt')
		.html( $(this).html() )
		.click(function(){
			dd.html( menu );
		});

		dd.html( $(this).siblings( $(this).attr('href') ) );

		

		// $('#main').animate({
		// 	opacity: 0.25,
		// 	width: 'toggle'
		// }, 2000, function() {
		// 	// Animation complete.
		// });
	});
});