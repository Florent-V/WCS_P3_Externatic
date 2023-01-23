import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["error"]

    updateRate(event) {
        console.log(this.currentTarget)

        /*        fetch(`/recruitment_process/${event.currentTarget.dataset.messageid}/change-archive`)
                    .then(response => response.json())
                    .then((response) => {
                        if (response.isArchived === true) {
                            event.target.closest("tr").remove()
                        } else {
                            event.target.className = "bi bi-exclamation-diamond-fill";
                            event.target.title = "erreur";
                            this.errorTarget.show();
                        }*/
    }

    connect() {
    }
}
