let like_buttons = [...document.getElementsByClassName("btn-like")];

like_buttons.forEach((button, i) => {
    let is_button_liked = Number(button.getAttribute('is_liked'));
    if (is_button_liked)
        button.innerHTML = '<i class="fa-solid fa-heart-crack"></i> Unlike';
    else
        button.innerHTML = '<i class="fa-solid fa-heart"></i> Like';

    let likes_count = document.getElementById('number_of_likes_' + i);

    if (!csrf_token)
        return;

    button.addEventListener('click', () => {
        let is_button_liked = Number(button.getAttribute('is_liked'));
        const message_id = Number(button.getAttribute('message_id'));
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
                button.innerHTML = '<i class="fa-solid fa-heart-crack"></i> Unlike';
                button.setAttribute('is_liked', '1');
                likes_count.textContent = String(Number(likes_count.textContent) + 1);
            } else {
                button.innerHTML = '<i class="fa-solid fa-heart"></i> Like';
                button.setAttribute('is_liked', '0');
                likes_count.textContent = String(Number(likes_count.textContent) - 1);
            }
        });
    });
});
