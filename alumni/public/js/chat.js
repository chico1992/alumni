$(function() {
    let user = JSON.parse(sessionStorage.getItem('user'));
    let conversations = JSON.parse(sessionStorage.getItem('conversations'));
    if(user == null){
        $.get("http://localhost/user").done(function(res) {
            user = res;
            sessionStorage.setItem('user',JSON.stringify(user));   
        });
    }
    if(conversations == null){
        $.get("http://localhost/user/conversations").done(function(res){
            conversations = res;
            sessionStorage.setItem('conversations',JSON.stringify(conversations));
            conversations.forEach(conversation => {
                showConversation(conversation);
            });
            addClick();
        })
    }
    let socket = io('http://localhost:3000');
    if(user!=null){
        socket.on('connect',function(){
            if(conversations!=null){
                conversations.forEach(conversation => {
                    showConversation(conversation);
                    socket.emit('room',conversation.id);
                });
                addClick();
            }
            socket.emit('room',user.id);
        });
    }

    socket.on('message',function(message){
        console.log('Incoming message:', message);
    });

    socket.on('conversation',function(conversation){
        console.log('Incoming conversation',conversation);
        conversations.push(JSON.parse(conversation));
        showConversation(conversation);
        addClick();
        sessionStorage.setItem('conversations',JSON.stringify(conversations));
    })

    function showConversation(conversation){
        let user = JSON.parse(sessionStorage.getItem('user'))
        let conversationField = $('<div class="chat-message-content" data-value="'+ conversation.id+'"></div>');
        let name = $('<h4></h4>');
        users=conversation.users;
        users.forEach(convUser=> {
            if(user.id != convUser.id){
                name.text(convUser.username);
            }
        });
        conversationField.append(name);
        $('.chat-window-list').append(conversationField);
    }

    
});

function addClick() {

	$('.chat-message-content').click(function(){
        console.log($(this).data("value"));
    });

}