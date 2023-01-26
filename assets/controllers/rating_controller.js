import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    updateRate(event) {

        fetch(`/recruitment_process/${event.target.closest("tr").dataset.recruitmentprocessid}/change-rate/${event.target.dataset.rate}`)
            .then(response => response.json())
            .then((response) => {

                if (response.rate) {
                    const stars = event.target.closest("td").querySelectorAll("i");
                    for (let i=0; i<response.rate; i++) {
                        stars[i].classList.remove('bi', 'bi-star');
                        stars[i].classList.add('bi', 'bi-star-fill');
                    }
                    for (let i=response.rate; i<stars.length; i++) {
                        stars[i].classList.remove('bi', 'bi-star-fill');
                        stars[i].classList.add('bi', 'bi-star');
                    }
                }
            })
    }

    connect() {
    }
}
