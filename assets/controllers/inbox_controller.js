import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    archive(event)
    {
        fetch(`/recruitment_process/${event.currentTarget.dataset.messageid}/change-read`)
            .then(response => response.json())
            .then((response) => {
                if (response.isRead === true) {
                    event.target.closest("tr").classList.remove('isUnread');
                    event.target.closest("tr").classList.add('isRead');
                    event.target.className = "bi bi-envelope";
                    event.target.title = "marquer non-lu"
                } else if (response.isRead === false) {
                    event.target.closest("tr").classList.remove('isRead');
                    event.target.closest("tr").classList.add('isUnread');
                    event.target.className = "bi bi-envelope-open";
                    event.target.title = "marquer lu";
                }
            })
    }

    delete(event) {
        fetch(`/recruitment_process/${event.currentTarget.dataset.messageid}/change-archive`)
            .then(response => response.json())
            .then((response) => {
                if (response.isArchived === true) {
                    event.target.closest("tr").remove()
                } else {
                    event.target.className = "bi bi-exclamation-diamond-fill";
                    event.target.title = "erreur";
                }
            })
    }


    connect() {
        const messageCells = document.querySelectorAll("td:not(:has(> button))");
        for (let messageCell of messageCells) {
            messageCell.addEventListener("click", () => {
                window.location.assign(` /message/recruitmentProcess/${messageCell.parentNode.dataset.messageid}`);
            })
        }


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
    }
}
