$(document).ready(function() {

    var currentdate = new Date(); 
    var number =  currentdate.getTime();
	var timestamp = parseInt(number);
	var posts = [];

    console.log(timestamp);

	var win = $(window);

	$.get("/posts/"+USER+"/"+timestamp).done(function(result){

		result.forEach(post => {
			
			posts.push(post)
			$("#profilePinBoard").append(postCreator(post))
			console.log(post);


		});
		
	});

	// Each time the user scrolls
	win.scroll(function() {
		// End of the document reached?
		
		if ($(document).height() - win.height() == win.scrollTop()) {
			let time = posts[posts.length - 1].creationDate;
			console.log(time);
			let unixtime = (new Date(time)).getTime()/1000;
			console.log(unixtime);

			$.get("/posts/"+unixtime).done(function(res){
				let ul = $('#posts');
				if (res.length != 0)
				{
					res.forEach(post => {
						
						posts.push(post)
						//ul.append($('<li>').text(post.creationDate));
						$("#profilePinBoard").append(postCreator(post))
						
					});
				} 
				else 
				{
					console.log("No Post left!")
					$("#postPinBoard").append('<strong>No Posts left!</strong>')
					win.off("scroll");
				}
				
			});

			//$('#loading').show();

		}
	});
});