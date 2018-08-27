function infiniteScrollInit() {

    var currentdate = new Date(); 
    var number =  currentdate.getTime();
	var timestamp = parseInt(number);
	var posts = [];


	var win = $(window);

	$.get("/posts/"+timestamp).done(function(result){
		let postCount = result.length;
		
		if(postCount == 0){
			$("#postPinBoard").append($('<main role="main" class="container" id="first"></main>'));
			$("#first").append($('<div class="starter-template mx-auto mt-1 text-center" id="second"></div>'));
			$("#second").append($('<h1 class="text-uppercase"> <strong> Welcome to Alumni <strong> </h1>'));
			$("#second").append($('<p class="lead">Feel free to create posts, comment or chat with other users.</p>'));
		}
		result.forEach(post => {
			
			posts.push(post);
			$("#postPinBoard").append(postCreator(post));
		});
		
	});

	// Each time the user scrolls
	win.scroll(function() {
		// End of the document reached?
		
		if ($(document).height() - win.height() == win.scrollTop()) {
			let time = posts[posts.length - 1].creationDate;
			let unixtime = (new Date(time)).getTime()/1000;

			$.get("/posts/"+unixtime).done(function(res){
				
				if (res.length != 0)
				{
					res.forEach(post => {
						
						posts.push(post);
						$("#postPinBoard").append(postCreator(post));
						
					});
				} 
				else 
				{
					$("#postPinBoard").append('<strong>No Posts left!</strong>');
					win.off("scroll");
				}
				
			});

			//$('#loading').show();

		}
	});
}