import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["error"]

    closeModal(event) {
        console.log("cocou")
        this.errorTarget.close();
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
                    this.errorTarget.show();
                }
            })
    }

    archive(event) {
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

    openMessage(event)
    {
        window.location.assign(`/message/recruitmentProcess/${event.target.parentNode.dataset.messageid}`);
    }

    connect() {
    }
}
