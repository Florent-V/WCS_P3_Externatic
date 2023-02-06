import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {

        let certificationInputs = document.querySelectorAll('#certificationForm input');
        let certificationTextarea = document.querySelector('#certificationForm textarea');
        for (let input of certificationInputs) {
            input.value = "";
        }
        certificationTextarea.value = "";


    }
}
