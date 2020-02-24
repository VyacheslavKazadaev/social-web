$(document)
    .on('click', 'a.subscribe', subscribe)
;

async function subscribe() {
    let response = await fetch('/site/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({'id': $(this).data('id')})
    });

    let result = await response.json();
    alert(result.yes);
}
