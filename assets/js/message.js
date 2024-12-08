let like_button = document.getElementsByClassName('btn-like')[0];

let is_button_liked = Number(like_button.getAttribute('is_liked'));
if (is_button_liked)
    like_button.innerHTML = '<i class="fa-solid fa-heart-crack"></i> Unlike';
else
    like_button.innerHTML = '<i class="fa-solid fa-heart"></i> Like';

function add_event_listener_like_button() {
    if (!csrf_token)
        return;

    like_button.addEventListener('click', () => {
        let is_button_liked = Number(like_button.getAttribute('is_liked'));
        const message_id = Number(like_button.getAttribute('message_id'));
        const dest = !is_button_liked ? '../../api/like-message.php' : '../../api/unlike-message.php';

        fetch(dest, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf_token,
            },
            body: JSON.stringify({ message_id: message_id }),
        }).then(() => {
            if (!is_button_liked) {
                like_button.innerHTML = '<i class="fa-solid fa-heart-crack"></i> Unlike';
                like_button.setAttribute('is_liked', '1');
            } else {
                like_button.innerHTML = '<i class="fa-solid fa-heart"></i> Like';
                like_button.setAttribute('is_liked', '0');
            }
        });
    });
}

add_event_listener_like_button();
