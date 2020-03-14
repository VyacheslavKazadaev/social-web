$(document)
    .on('click', '#btn-chat', writeInChat)
;

async function writeInChat() {
    let message = $('#btn-input').val().trim();
    if (message === '') {
        return;
    }

    let response = await fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: `message=${message}&${yii.getCsrfParam()}=${yii.getCsrfToken()}`
    });

    let result = await response.text();
    $('ul.chat').append(result);
    document.location.reload();
}
