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
        })
    }
    let socket = io('http://localhost:3000');
    console.log(user!=null);
    if(user!=null){
        socket.on('connect',function(){
            if(conversations!=null){
                conversations.forEach(conversation => {
                    socket.emit('room',conversation.id);
                });
            }
            socket.emit('room',user.id);
        });
    }

    socket.on('message',function(message){
        console.log('Incoming message:', message);
    });

    socket.on('conversation',function(message){
        console.log('Incoming conversation',message);
        conversations.push(JSON.parse(message));
        sessionStorage.setItem('conversations',JSON.stringify(conversations));
    })

});