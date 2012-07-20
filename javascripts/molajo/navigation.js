jQuery(document).ready(function($){
	// $("nav .accordion").tabs(
	// 	"ul",
	// 	{tabs: 'li', effect: 'slide', initialIndex: 1}
	// );

	$('li').click(function(){
		$(this).addClass('current');
		// var current = $(this);
		// $(this).parents('ul').toggleCss('display');
		// console.log($(this).parents('ul'));
		// $(this).siblings('li').click(function(){
		// 	current.parents('.level-two, .level-three').hide();
		// });
	})
	;

	$('nav[role="navigation"] li').click(function(){


		// $(this).siblings('li').children('ul').hide();
		// $(this).parents('ul').css('display', 'block');

		// var active = dd.prev('dt').html();

		// dd.prev('dt')
		// .html( $(this).html() )
		// .click(function(){
		// 	dd.html( menu );
		// });

		// dd.html( $(this).siblings( $(this).attr('href') ) );

		

		// $('#main').animate({
		// 	opacity: 0.25,
		// 	width: 'toggle'
		// }, 2000, function() {
		// 	// Animation complete.
		// });
	});
});