$(function() {
    const wposts = new WebSocket('ws://localhost:9859');
    wposts.onmessage = (e) => {
        const response = JSON.parse(e.data);
        if (response.type && response.type === 'posts') {
            console.log(e.data);
        }
    };

    let author = $('[name="author"]').val();

    wposts.onopen = (e) => {
        console.log('Connection established! Please, set your message.');
        wposts.send( JSON.stringify({
            'action' : 'ping', 'author': author
        }));
    };

    $('form.message-edit-block').submit((e) => {
        e.preventDefault();

        const message = $('[name="message"]').val().trim();
        if (message !== '') {
            wposts.send( JSON.stringify({
                'action' : 'posts', 'message' : message,
                'author': author
            }));
            sendForm();
            return true;
        } else {
            alert('Enter the message');
        }
    });
});

async function sendForm() {
    let data = $('form.message-edit-block').serialize();
    let response = await fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: data + '&submit-message=1'
    });

    let result = await response.text();
    document.location.reload();
}
