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

    let commentDiv = $('<div class="container p-3 mb-2 bg-light text-dark border-top border-bottom"></div>');
    let commentHeader = $('<div class="container ml-2 mt-1"></div>');
    let commentBody = $('<div class="card-body"></div>');
    let commentBodyForm = $('<form></form>');
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

    commentHeader.append($('<h5>Leave a Comment:</h5>'))
    commentBodyFormDiv.append($('<textarea class="form-control p-3 mb-2 bg-light text-dark" rows="3"></textarea>'));
    commentBodyForm.append(commentBodyFormDiv);
    commentBodyForm.append($('<button type="submit" class="btn btn-dark">Submit</button>'));
    commentBody.append(commentBodyForm);

    commentDiv.append(commentHeader);
    commentDiv.append(commentBody);

    postDiv.append(postHeader);
    postDiv.append(postBody);
    postDiv.append(commentDiv);

    mainDiv.append(postDiv);

    return mainDiv;
    
}