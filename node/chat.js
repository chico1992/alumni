let amqp = require('amqplib/callback_api');
let io = require('socket.io').listen(3000);

io.sockets.on("connection",function(socket){
    socket.on("room",function(room){
        socket.join(room);
    });
});

amqp.connect('amqp://rabbit', function(err, conn) {
    conn.createChannel(function(err, ch) {
        let q = 'messages';

        ch.assertQueue(q, {durable: false});
        console.log(" [*] Waiting for messages in %s. To exit press CTRL+C", q);
        ch.consume(q, function(msg) {
            console.log(" [x] Received %s", msg.content.toString());
            let data=JSON.parse(msg.content);
            let conv=data["receiver"]["id"];
            console.log(conv);
            io.sockets.in(conv).emit('message',msg.content.toString());
        }, {noAck: true});
    });
});