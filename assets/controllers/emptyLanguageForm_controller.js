import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        let languageInputs = document.querySelectorAll('#languageForm input');
        for (let input of languageInputs) {
            input.value = "";
        }
    }
}
