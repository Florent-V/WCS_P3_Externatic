import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        document.querySelector('#hobbyForm input').value = "";
    }
}
