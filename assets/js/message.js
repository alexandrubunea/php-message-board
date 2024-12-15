let like_button = document.getElementsByClassName('btn-like')[0];
let delete_button = document.getElementsByClassName('btn-delete')[0];

let is_button_liked = Number(like_button.getAttribute('is_liked'));
if (is_button_liked)
    like_button.innerHTML = '<i class="fa-solid fa-heart-crack"></i> Unlike';
else
    like_button.innerHTML = '<i class="fa-solid fa-heart"></i> Like';

let likes_count = document.getElementById('number_of_likes');

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
                likes_count.textContent = String(Number(likes_count.textContent) + 1);
            } else {
                like_button.innerHTML = '<i class="fa-solid fa-heart"></i> Like';
                like_button.setAttribute('is_liked', '0');
                likes_count.textContent = String(Number(likes_count.textContent) - 1);
            }
        });
    });
}

function add_event_listener_delete_button() {
    if(!csrf_token)
        return;

    const message_id = Number(delete_button.getAttribute('message_id'));

    delete_button.addEventListener('click', () => {
        fetch('../../api/delete-message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrf_token,
            },
            body: JSON.stringify({ message_id: message_id })
        }).then(() => {
            window.location.replace('../../pages/messages.php');
        });
    });
}

add_event_listener_like_button();
add_event_listener_delete_button();
