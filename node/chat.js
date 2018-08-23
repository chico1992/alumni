let amqp = require('amqplib/callback_api');
let io = require('socket.io').listen(3000);

io.sockets.on("connection",function(socket){
    socket.on("room",function(room){
        socket.join(room);
    });
});

amqp.connect('amqp://rabbit', function(err, conn) {
    conn.createChannel(function(err, ch) {
        let messages = 'messages';
        let conversations = 'conversations';

        ch.assertQueue(messages, {durable: false});
        console.log(" [*] Waiting for messages in %s. To exit press CTRL+C", messages);
        ch.consume(messages, function(msg) {
            console.log(" [x] Received message %s", msg.content.toString());
            let data=JSON.parse(msg.content);
            let conv=data["receiver"]["id"];
            console.log(conv);
            io.sockets.in(conv).emit('message',msg.content.toString());
        }, {noAck: true});

        ch.assertQueue(conversations, {durable: false});
        console.log(" [*] Waiting for conversations in %s. To exit press CTRL+C", conversations);
        ch.consume(conversations, function(msg) {
            console.log(" [x] Received conversation %s", msg.content.toString());
            let data=JSON.parse(msg.content);
            data.users.forEach(user => {
                console.log(user.id);
                io.sockets.in(user.id).emit('conversation',msg.content.toString());
            });
        }, {noAck: true});
    });
});