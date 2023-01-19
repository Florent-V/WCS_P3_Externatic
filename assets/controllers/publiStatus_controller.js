import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('click', changeStatus);

        function changeStatus(event) {
            console.log(event.currentTarget.dataset.annonce);
        }
    }
}
