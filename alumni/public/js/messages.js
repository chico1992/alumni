(function() {

	$('.list-chat header').click(function() {

		$('.chat-window-list').slideToggle(300, 'swing');

	});

}) ();

(function() {

	$('.live-chat header').click(function() {

		$('.chat-window-live').slideToggle(300, 'swing');

	});

	$('.chat-close-live').click( function(e) {

		e.preventDefault();
        $('.live-chat').fadeOut(300);
	});

}) ();