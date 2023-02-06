import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {

        let experienceInputs = document.querySelectorAll('#experienceForm input');
        let experienceTextarea = document.querySelector('#experienceForm textarea');
        for (let input of experienceInputs) {
            input.value = "";
        }
        experienceTextarea.value = "";

    }
}
