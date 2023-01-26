import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["error", "resultList", "inputAddress"]

    closeModal(event) {
        this.errorTarget.close();
    }

    searchAddress(event) {
        let search = event.target.value;
        if (event.target.value.length >= 5) {
            console.log(`https://api-adresse.data.gouv.fr/search/?q=${search}&limit=5`)
            fetch(`https://api-adresse.data.gouv.fr/search/?q=${search}&limit=5`)
                .then(response => response.json())
                .then(adresses => {
                    this.resultListTarget.innerHTML = "";
                    for (let feature of adresses.features) {
                        const li = document.createElement("li");
                        const link = document.createElement('a');
                        link.href="#";
                        li.append(link);
                        link.addEventListener('click', (event) => {
                            event.preventDefault();
                            this.inputAddressTarget.value = event.target.innerText;
                            this.resultListTarget.innerHTML = "";
                        })
                        link.innerHTML = `${feature?.properties?.label}`;
                        this.resultListTarget.append(li);
                    }
                });
        }
    }

    updateRate(event) {
        let link = `/recruitment_process/${event.target.parentNode.dataset.recruitmentprocessid}/change-rate/${event.target.dataset.rate}`;
        console.log(link)
        fetch(link)
            .then(response => response.json())
            .then((response) => {
                if (response) {
                    console.log(response);
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
