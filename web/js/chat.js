// $(document)
//     .on('click', '#btn-chat', writeInChat)
// ;
//
// async function writeInChat() {
//     let btn = $('#btn-input');
//     let message = btn.val().trim();
//     if (message === '') {
//         return;
//     }
//
//     let response = await fetch('', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
//         },
//         body: `message=${message}&${yii.getCsrfParam()}=${yii.getCsrfToken()}`
//     });
//
//     let result = await response.text();
//     $('ul.chat').append(result);
//     btn.val('');
//     document.location.reload();
// }

$(function() {
    const chatWindow = $('.chat-panel .panel-body');
    chatWindow.scrollTop($('body').height());

    const chat = new WebSocket('ws://localhost:9859');
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

    let author = btnInput.data('author');
    let recipient = btnInput.data('recipient');

    chat.onopen = (e) => {
        console.log('Connection established! Please, set your message.');
        chat.send( JSON.stringify({
            'action' : 'ping', 'author': author, 'recipient': recipient
        }));
    };

    // $('#btn-chat').click(function() {
    $('#form-chat').submit((e) => {
        e.preventDefault();

        const message = btnInput.val();
        if (message) {
            chat.send( JSON.stringify({
                'action' : 'chat', 'message' : message,
                'author': author, 'recipient': recipient
            }));
        } else {
            alert('Enter the message');
        }
    });
});