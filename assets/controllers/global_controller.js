import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        function alertCounter()
        {
            const alerts = document.getElementsByClassName("alert");
            for (let alert of alerts) {
                alert.textContent += " || suppression dans 5 secondes";
                setTimeout(function () {
                    alert.remove(); }, 5000)
            }
        }
        alertCounter();
    }
}
