$(function() {
    const wposts = new WebSocket('ws://localhost:9859');
    wposts.onmessage = (e) => {
        const response = JSON.parse(e.data);
        if (response.type && response.type === 'posts') {
            $('#block-news').prepend(response.message);
            console.log(e.data);
        }
    };

    wposts.onopen = (e) => {
        console.log('Connection established! Please, set your message.');
        wposts.send( JSON.stringify({
            'action' : 'ping', 'author': $('#author').val()
        }));
    };
});
