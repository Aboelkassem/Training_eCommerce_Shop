$(function () {

	'use strict';

	// Switch Between Login & Sign Up

	$('.login-page h1 span').click(function(){
		$(this).addClass('selected') .siblings().removeClass('selected');

		$('.login-page form').hide();

		$('.' + $(this).data('class')).fadeIn(100); // become class // اظهر الفورم الي الكلاس بتاعها نفس الداتا كلاس بتاع السبام 
	});

	// Trigger The SelectboxIt

	$("select").selectBoxIt({

		// Uses the jQuery 'fadeIn' effect when opening the drop down
		showEffect: "fadeIn",

		// Sets the jQuery 'fadeIn' effect speed to 400 milleseconds
		showEffectSpeed: 400,

		// Uses the jQuery 'fadeOut' effect when closing the drop down
		hideEffect: "fadeOut",

		// Sets the jQuery 'fadeOut' effect speed to 400 milleseconds
		hideEffectSpeed: 400,

		autoWidth:false

	  });


	// Hide Placeholder On Form Focus

	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));

	});

	// Add Asterisk On Required Field

	$('input').each(function () {

		if ($(this).attr('required') === 'required') {

			$(this).after('<span class="asterisk">*</span>');

		}

	});


	// Confirmation Message On Button

	$('.confirm').click(function(){

		return confirm('Are You Sure To Delete this Member ?');

	});

	// live doing thing to show resualt fawry

	$('.live').keyup(function(){

		$($(this).data('class')).text($(this).val());

	});

});
