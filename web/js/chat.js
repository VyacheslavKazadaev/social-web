$(function() {
    showMessages();

    const chatWindow = $('.chat-panel .panel-body');
    chatWindow.scrollTop($('body').height());

    const chat = new WebSocket('ws://127.0.0.1:9859');
    let btnInput = $('#btn-input');
    chat.onmessage = (e) => {
        btnInput.val('');
        console.log(e.data);

        const response = JSON.parse(e.data);
        if (response.type && response.type === 'chat') {
            $('ul.chat').append(response.message);
            chatWindow.scrollTop($('body').height());
        }
    };

    console.log($('#params-users').html());
    const paramsUsers = JSON.parse($('#params-users').html());

    chat.onopen = (e) => {
        console.log('Connection established! Please, set your message.');
        chat.send( JSON.stringify({
            'action' : 'ping', 'author': paramsUsers.author, 'recipient': paramsUsers.recipient
        }));
    };

    $('#form-chat').submit((e) => {
        e.preventDefault();

        const message = btnInput.val();
        if (message) {
            chat.send( JSON.stringify({
                'action' : 'chat', 'message' : message,
                'author': paramsUsers.author, 'recipient': paramsUsers.recipient
            }));
        } else {
            alert('Enter the message');
        }
    });
});

function showMessages() {
    $.post('http://127.0.0.1:82/chat', $('#params-users').html(), (response) => {
        const chatBlock = $('.chat-panel .chat');
        chatBlock.empty();

        chatBlock.html(JSON.parse(response).response);
        const chatWindow = $('.chat-panel .panel-body');
        chatWindow.scrollTop($('body').height());
    }, 'json');
}
