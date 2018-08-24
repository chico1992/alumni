function postCreator(post)
{
    let user = JSON.parse(sessionStorage.getItem("user"));

    let postTime = post.creationDate;
    let date = new Date(postTime);

    let postYear = date.getFullYear();
    let postMonth = date.getMonth()+1;
    let postDay = date.getDate();

    let postHour = date.getHours();
    let postMinute = date.getMinutes();

    postDate = postDay+ "." +postMonth+ "." +postYear;
    postHour = postHour+ ":" + postMinute;

    let mainDiv = $('<div class="col-md-12"></div>');
    let postDiv = $('<div class="card mb-3"></div>');
    let postHeader = $('<div class="card-footer text-muted"></div>');
    let postHeaderIcons = $('<div class="icons"></div>');

    let postBody = $('<div class="card-body"></div>');
    let postBodyContent = $('<div class="media-body"></div>');
    let postBodyContentHeader = $('<div class="media-body"></div>');

    let commentDiv = $('<div class="container p-3 mb-2 border-top border-bottom bg-light text-dark"></div>');
    let commentLoaderArea=$('<div class="media mb-4"></div>');
    let commentLoader =$('<a href="#"> LOAD ALL COMMENTS </a>');
    
    commentLoaderArea.append(commentLoader);
    commentDiv.append(commentLoaderArea);
    commentLoader.click(function(e){
        e.preventDefault();
        console.log('hello comments');
        $.get('/post/comments/'+post.id).done(function(res){
            console.log(res);
            commentDiv.empty();
            if(res.length==0){
                console.log("empty");
                commentDiv.append('<h5>No comments available</h5>');
            }else{
                res.forEach(comment => {
                    commentDiv.append(addComment(comment));
                });
            }
        });
    });

    let commentHeader = $('<div class="comment-box"></div>');
    let commentBody = $('<div class="card-body"></div>');
    let commentBodyForm = $('<form method="post"></form>');
    let commentArea = $('<textarea class="form-control p-3 mb-2 bg-light text-dark" name="content" rows="3" required></textarea>');
    commentBodyForm.submit(function(e){
        e.preventDefault();
        let data = $( this ).serialize();
        console.log(data);
        $.post("/comment/"+post.id, data).done(function(res) {
            console.log(res);
        });
        commentArea.val("");
    });
    let commentBodyFormDiv =$('<div class="form-group"></div>');

    postHeader.append($('<p class="group-name font-weight-light font-italic d-inline">'+"Posted in "+post.visibility.label+" the "+postDate+" at "+postHour+" by "+'</p>'+" "+
    '<a href="#" class="deco-none">'+post.author.firstname+'</a>'));
    
    if (user.id == post.author.id)
    {
        postHeaderIcons.append($('<a href="#" class="deco-none"><i class="fas fa-pencil-alt mr-2 d-inline"></i></a>'));
        postHeaderIcons.append($('<a href="#" class="deco-none"><i class="fas fa-trash-alt mr-2 d-inline"></i></a>'));
    }

    postHeaderIcons.append($('<a href="#" class="deco-none"><i class="fas fa-flag mr-2 d-inline"></i></a>'));

    postHeader.append(postHeaderIcons);

    postBodyContentHeader.append($('<img class="mr-3 mb-3 rounded-circle" src="http://placehold.it/40x40" alt=""></img>'));
    postBodyContentHeader.append($('<h2 class="card-title">'+post.title+'</h2>'));
    postBody.append(postBodyContentHeader);
    postBodyContent.append($('<p class="card-text">'+post.content+'</p>'));
    postBody.append(postBodyContent);

    commentHeader.append($('<h5 class="comment ml-3">Leave a Comment:</h5>'))
    commentBodyFormDiv.append(commentArea);
    commentBodyForm.append(commentBodyFormDiv);
    commentBodyForm.append($('<button type="submit" class="btn btn-dark">Submit</button>'));
    commentBody.append(commentBodyForm);

    
    postDiv.append(postHeader);
    postDiv.append(postBody);
    postDiv.append(commentDiv);
    postDiv.append(commentHeader);
    postDiv.append(commentBody);
    
    mainDiv.append(postDiv);

    return mainDiv;
    
}

function addComment(comment){
    let commentContaiener = $('<div class="media pb-3 mb-3 border-bottom"></div>');
    let commenterImage = $('<img class="d-flex ml-2 mr-3 rounded-circle" src="http://placehold.it/30x30" alt="">');
    commentContaiener.append(commenterImage);
    let commentBody = $('<div class="media-body"></div>');
    let commenterName = $('<h6 class="mt-0 mt-1"><h6>');
    let commenterNameLink = $('<a href="#" class="deco-none"></a>');
    commenterName.append(commenterNameLink);
    commenterNameLink.text(comment.author.username);
    commentBody.append(commenterName);

    let commentIcons = $('<div class="icons-comment"></div>');
    let commentIconEdit = $('<a href="#" class="deco-none"><i class="fas fa-pencil-alt mr-2 d-inline"></i></a>');
    let commentIconDelete = $('<a href="#" class="deco-none"><i class="fas fa-trash-alt mr-2 d-inline"></i></a>');
    let commentIconFlag = $('<a href="#" class="deco-none"><i class="fas fa-flag mr-2 d-inline"></i></a>');
    commentIcons.append(commentIconEdit);
    commentIcons.append(commentIconDelete);
    commentIcons.append(commentIconFlag);
    commentBody.append(commentIcons);

    let commentContent = $('<small></small>');
    commentContent.text(comment.content);
    commentBody.append(commentContent);

    commentContaiener.append(commentBody);
    return commentContaiener;

}