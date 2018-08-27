$(function() {
    let user = JSON.parse(sessionStorage.getItem('user'));
    let conversations = JSON.parse(sessionStorage.getItem('conversations'));
    if(user == null){
        $.get("/user").done(function(res) {
            user = res;
            sessionStorage.setItem('user',JSON.stringify(user));   
            $.get("/user/conversations").done(function(res){
                conversations = res;
                sessionStorage.setItem('conversations',JSON.stringify(conversations));
                conversations.forEach(conversation => {
                    showConversation(conversation);
                });
                connect();
                infiniteScrollInit();
            });
        });
    }else{
        conversations.forEach(conversation =>{
            showConversation(conversation);
            if(sessionStorage.getItem(conversation.id) != null){
                conv=JSON.parse(sessionStorage.getItem(conversation.id));
                addConversation(conversation);
                if(conv.messages){
                    let messages = conv.messages;
                    messages.forEach(message => {
                        addMessage(message);
                    });
                }
            }
        });
        connect();
        infiniteScrollInit();
    }
    

});

function connect(){
    let conversations = JSON.parse(sessionStorage.getItem('conversations'));
    let user = JSON.parse(sessionStorage.getItem('user'));
    let socket = io(':3000');
    socket.on('connect',function(){
        if(conversations!=[]){
            conversations.forEach(conversation => {
                socket.emit('room',conversation.id);
            });
        }
        socket.emit('room',user.id);
    });

    socket.on('message',function(message){
        addMessage(JSON.parse(message));
    });

    socket.on('conversation',function(conversation){
        let conv = JSON.parse(conversation);
        let exists = false;
        conversations.forEach(con =>{
            if(con.id == conv.id){
                exists = true;
            }
        });
        if(!exists){
            conversations.push(conv);
            showConversation(conv);
            sessionStorage.setItem('conversations',JSON.stringify(conversations));
            socket.emit('room',conv.id);
        }
        addConversation(conv);
        loadMessages(conv);
            
    });
}

function showConversation(conversation){
    let user = JSON.parse(sessionStorage.getItem('user'));
    let conversationField = $('<div class="chat-message-content" data-value="'+ conversation.id+'"></div>');
    let name = $('<h4></h4>');
    users=conversation.users;
    users.forEach(convUser=> {
        if(user.id != convUser.id){
            name.text(convUser.username);
        }
    });
    conversationField.click(function(){
        let convId=conversation.id;
        if(sessionStorage.getItem(convId)==null){
            let conversations = JSON.parse(sessionStorage.getItem("conversations"));
            let conv = {};
            conversations.forEach(conversation =>{
                if(conversation.id == convId){
                    conv = conversation;
                }
            });
            addConversation(conv);
            loadMessages(conv);
        }
    });
    conversationField.append(name);
    $('.chat-window-list').append(conversationField);
}

function addConversation(conversation){
    let user = JSON.parse(sessionStorage.getItem('user'));
    let conversationWindow = $('<div class="live-chat clearfix"><div>');
    /* Header */
    let conversationWindowHeader = $('<header></header>');
    let conversationWindowCloser = $('<a href="#" class="chat-close-live"><i class="fas fa-times-circle"></i></a>');
    conversationWindowCloser.click( function(e) {
        e.preventDefault();
        conversationWindow.fadeOut(300);
        conversationWindow.remove();
        sessionStorage.removeItem(conversation.id);
    });
    let conversationWindowHeaderText = $('<h4></h4>');
    users=conversation.users;
    users.forEach(convUser=> {
        if(user.id != convUser.id){
            conversationWindowHeaderText.text(convUser.username);
        }
    });
    conversationWindowHeader.append(conversationWindowCloser);
    conversationWindowHeader.append(conversationWindowHeaderText);
    conversationWindow.append(conversationWindowHeader);

    /* conversation window */
    let chatWindow = $('<div class="chat-window-live border clearfix"></div>');
    let chatHistory = $('<div class="chat-history"></div>');
    let chatMessageContainer = $('<div id="'+ conversation.id+'"></div>');
    chatHistory.append(chatMessageContainer);
    conversationWindowHeader.click(function() {
        chatWindow.slideToggle(300, 'swing');
    });
    chatWindow.append(chatHistory);
    
    /* form */
    let form = $('<form action="/newMessage" method="post"></form>');
    let inputConversation = $('<input type="text" name="conversation" value="'+conversation.id+'" hidden>');
    let inputMessage = $('<input type="text" name="content" placeholder="Type your message…" autofocus required>');
    let inputButton = $('<input type="submit" class="send btn btn-dark" value="Send">');
    form.append(inputConversation);
    form.append(inputMessage);
    form.append(inputButton);
    form.submit(function(e){
        e.preventDefault();
        let data = $( this ).serialize();
        $.post("/newMessage", data).done(function(res) {
        });
        inputMessage.val("");
    });
    chatWindow.append(form);
    conversationWindow.append(chatWindow);
    $('.chat').append(conversationWindow);
}


function loadMessages(conv){
    $.get("/allMessages/"+conv.id).done(function(res) {
        conv.messages=res;
        sessionStorage.setItem(conv.id,JSON.stringify(conv));
        res.forEach(message=>{
            addMessage(message);
        }); 
    });
}

function addMessage(message){
    let chatWindow = $("#"+message.receiver.id);
    let user = JSON.parse(sessionStorage.getItem('user'));
    let messageBaseContainer = "";
    if(user.id == message.sender.id){
        messageBaseContainer = $('<section class="left clearfix"></section>');
    }else{
        messageBaseContainer = $('<section class="right clearfix"></section>');
    }
    let messageContainer=$('<div class="chat-body clearfix"></div')
    let messageP = $('<p></p>');
    messageP.text(message.content);
    messageContainer.append(messageP);
    messageBaseContainer.append(messageContainer);
    chatWindow.append(messageBaseContainer);
}