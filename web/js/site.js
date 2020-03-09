$(document)
    .on('click', 'a.subscribe', subscribe)
;

async function subscribe() {
    let response = await fetch('/site/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
        },
        body: 'id='+$(this).data('id')+'&_csrf='+yii.getCsrfToken()
    });

    let result = await response.json();
    alert(result.yes);
}
