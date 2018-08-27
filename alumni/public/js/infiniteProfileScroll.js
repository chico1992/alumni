function infiniteScrollInit() {

    var currentdate = new Date(); 
    var number =  currentdate.getTime();
	var timestamp = parseInt(number);
	var posts = [];


	var win = $(window);

	$.get("/posts/"+USER+"/"+timestamp).done(function(result){

		result.forEach(post => {
			
			posts.push(post);
			$("#profilePinBoard").append(postCreator(post));
		});
		
	});

	// Each time the user scrolls
	win.scroll(function() {
		// End of the document reached?
		
		if ($(document).height() - win.height() == win.scrollTop()) {
			let time = posts[posts.length - 1].creationDate;
			let unixtime = (new Date(time)).getTime()/1000;

			$.get("/posts/"+USER+"/"+unixtime).done(function(res){
				if (res.length != 0)
				{
					res.forEach(post => {
						
						posts.push(post);
						$("#profilePinBoard").append(postCreator(post));
						
					});
				} 
				else 
				{
					$("#profilePinBoard").append('<strong>No Posts left!</strong>');
					win.off("scroll");
				}
				
			});
			//$('#loading').show();
		}
	});
}