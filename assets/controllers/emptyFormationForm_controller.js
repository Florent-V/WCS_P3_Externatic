import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        let formationInputs = document.querySelectorAll('#formationForm input');
        let formationTextarea = document.querySelector('#formationForm textarea');
        for (let input of formationInputs) {
            input.value = "";
        }
        formationTextarea.value = "";
    }
}
