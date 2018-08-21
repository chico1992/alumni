$(document).ready(function() {

    var currentdate = new Date(); 
    var number =  currentdate.getTime();
	var timestamp = parseInt(number);
	var posts = [];

    console.log(timestamp);

	var win = $(window);

	function postCreator(post)
	{
		let mainDiv = $('<div class="col-md-12"></div>');
		let postDiv = $('<div class="card mb-3"></div>');
		let postHeader = $('<div class="card-footer text-muted"></div>');
		let postHeaderIcons = $('<div class="icons"></div>');

		let postBody = $('<div class="card-body"></div>');
		let postBodyContent = $('<div class="media-body"></div>');
		let postBodyContentHeader = $('<div class="media-body"></div>');

		postHeader.append($('<p class="group-name font-weight-light font-italic d-inline">'+post.visibility.label+'</p>'+post.creationDate+'<a href="#" class="deco-none">'+post.author.firstname+'</a>'));
		postHeaderIcons.append($('<a href="#" class="deco-none"><i class="fas fa-pencil-alt mr-2 d-inline"></i></a>'));
		postHeaderIcons.append($('<a href="#" class="deco-none"><i class="fas fa-trash-alt mr-2 d-inline"></i></a>'));
		postHeaderIcons.append($('<a href="#" class="deco-none"><i class="fas fa-flag mr-2 d-inline"></i></a>'));
		postHeader.append(postHeaderIcons);

		postBodyContentHeader.append($('<img class="mr-3 mb-3 rounded-circle" src="http://placehold.it/40x40" alt=""></img>'));
		postBodyContentHeader.append($('<h2 class="card-title">'+post.title+'</h2>'));
		postBody.append(postBodyContentHeader);
		postBodyContent.append($(' <p class="card-text">'+post.content+'</p>'));
		postBody.append(postBodyContent);

		postDiv.append(postHeader);
		postDiv.append(postBody);

		mainDiv.append(postDiv);

		return mainDiv;
		
	}

	$.get("http://localhost/posts/"+timestamp).done(function(result){
		let X = result.length;
		
		let ul = $('#posts');
		result.forEach(post => {
			
				posts.push(post)
				//ul.append($('<li>').text(post.creationDate));
				$(".row").append(postCreator(post))
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

			$.get("http://localhost/posts/"+unixtime).done(function(res){
				let ul = $('#posts');
				if (res.length != 0)
				{
					res.forEach(post => {
						
						posts.push(post)
						//ul.append($('<li>').text(post.creationDate));
						$(".row").append(postCreator(post))
						
					});
				} 
				else 
				{
					console.log("No Post left!")
					win.off("scroll");
				}
				
		
			});

			

			$('#loading').show();

		}
	});
});