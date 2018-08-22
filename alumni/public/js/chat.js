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

});

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
    conversationField.append(name);
    $('.chat-window-list').append(conversationField);
}

function addConversation(conversation){
    let user = JSON.parse(sessionStorage.getItem('user'));
    let conversationWindow = $('<div class="live-chat"><div>');
    /* Header */
    let conversationWindowHeader = $('<header></header>');
    let conversationWindowCloser = $('<a href="#" class="chat-close-live">x</a>');
    conversationWindowCloser.click( function(e) {
        e.preventDefault();
        conversationWindow.fadeOut(300);
        conversationWindow.remove();
    });
    console.log("blah");
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
    let chatWindow = $('<div class="chat-window-live"></div>');
    let chatHistory = $('<div class="chat-history"></div>');
    conversationWindowHeader.click(function() {
        chatWindow.slideToggle(300, 'swing');
    });
    chatWindow.append(chatHistory);
    
    /* form */
    let form = $('<form action="http://localhost/newMessage" method="post"></form>');
    let inputConversation = $('<input type="text" name="conversation" value="'+conversation.id+'" hidden>');
    let inputMessage = $('<input type="text" name="content" placeholder="Type your message…" autofocus>');
    let inputButton = $('<input type="submit" class="send btn btn-dark" value="Send">');
    form.append(inputConversation);
    form.append(inputMessage);
    form.append(inputButton);
    form.submit(function(e){
        e.preventDefault();
        let data = $( this ).serialize();
        console.log(data);
        $.post("http://localhost/newMessage", data).done(function(res) {
            console.log(res);
        });
        inputMessage.val("");
    });
    chatWindow.append(form);
    conversationWindow.append(chatWindow);
    $('.chat').append(conversationWindow);
}

	
        
        // <div class="chat-window-live">			
        //     <div class="chat-history">				
        //         <div class="chat-message bg-light text-dark border-top border-bottom">										
        //             <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, explicabo quasi ratione odio dolorum harum.</p>
        //         </div> <!-- end chat-message -->
        //         <div class="chat-message-me">									
        //             <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error, explicabo quasi ratione odio dolorum harum.</p>
        //         </div> <!-- end chat-message -->
        //     </div> <!-- end chat-history -->
		
        //     <form action="#" method="post">
        //             <input type="text" placeholder="Type your message…" autofocus>
        //             <input type="submit" class="send btn btn-dark" value="Send">
        //             <input type="hidden">
        //     </form>

        // </div> 

function addClick() {

	$('.chat-message-content').click(function(){
        console.log($(this).data("value"));
        let convId=$(this).data("value");
        let conversations = JSON.parse(sessionStorage.getItem("conversations"));
        let conv = {};
        conversations.forEach(conversation =>{
            if(conversation.id == convId){
                conv = conversation;
            }
        });
        addConversation(conv);
    });

}