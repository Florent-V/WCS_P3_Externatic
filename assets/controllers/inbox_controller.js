import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const messageCells = document.querySelectorAll(".messageCell");
        const deleteBtns = document.getElementsByClassName("btn-del");

        for (let deleteBtn of deleteBtns) {
            deleteBtn.addEventListener("click", (event) => {
                event.preventDefault();


                /*                let favoriteOfferLink = event.currentTarget;
                let link = favoriteOfferLink.href;
                // Send an HTTP request with fetch to the URI defined in the href
                fetch(link)
                    // Extract the JSON from the response
                    .then(res => res.json())
                    // Then update the icon
                    .then(function (res) {
                        let bookmarkIcon = favoriteOfferLink.firstElementChild;
                        if (res.isInFavorite) {
                            bookmarkIcon.classList.remove('bi-bookmark'); // Remove the .bi-heart (empty heart) from classes in <i> element
                            bookmarkIcon.classList.add('bi-bookmark-fill'); // Add the .bi-heart-fill (full heart) from classes in <i> element
                        } else {
                            bookmarkIcon.classList.remove('bi-bookmark-fill'); // Remove the .bi-heart-fill (full heart) from classes in <i> element
                            bookmarkIcon.classList.add('bi-bookmark'); // Add the .bi-heart (empty heart) from classes in <i> element
                        }
                    });*/

            })
        }

        for (let messageCell of messageCells) {
            messageCell.addEventListener("click", () => {
                window.location.assign(` /message/recruitmentProcess/${messageCell.parentNode.dataset.messageid}`);
            })
        }

    }
}
