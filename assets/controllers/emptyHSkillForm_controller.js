import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        document.querySelector('#hSkillForm input').value = "";
    }
}
