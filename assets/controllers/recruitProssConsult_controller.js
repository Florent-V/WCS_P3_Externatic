import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["error"]

    closeModal(event) {
        this.errorTarget.close();
    }

    updateRate(event) {
        fetch(`/recruitment_process/${event.target.parentNode.dataset.recruitmentprocessid}/change-rate/${event.target.dataset.rate}`)
            .then(response => response.json())
            .then((response) => {
                if (response) {
                    const stars = event.target.parentNode.querySelectorAll("i");
                    for (let i = 0; i < response.rate; i++) {
                        stars[i].classList.remove('bi', 'bi-star');
                        stars[i].classList.add('bi', 'bi-star-fill');
                    }
                    for (let i = response.rate; i < stars.length; i++) {
                        stars[i].classList.remove('bi', 'bi-star-fill');
                        stars[i].classList.add('bi', 'bi-star');
                    }
                }
            })
    }

    addAppointment(event) {
        this.errorTarget.show();
    }

    connect() {
    }
}
