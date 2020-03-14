$(document)
    .on('click', '#btn-chat', writeInChat)
;

async function writeInChat() {
    let response = await fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: `message=${$('#btn-input').val()}&${yii.getCsrfParam()}=${yii.getCsrfToken()}`
    });

    let result = await response.text();
    console.log(result);
    $('ul.chat').append(result);
}
