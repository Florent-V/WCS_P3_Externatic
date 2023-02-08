import { Controller } from '@hotwired/stimulus';
const bootstrap = require('bootstrap');
export default class extends Controller {
    connect()
    {
        let formSave = document.getElementById('saveSearch');
        let saveBtn = document.getElementById('saveBtn');
        let results = document.getElementById('results');

        saveBtn.addEventListener('click', sendData);

        function sendData(event)
        {
            // Ne soumet pas le formulaire
            event.preventDefault();
            // Récupère les données du formulaire
            let dataForm = new FormData(formSave);

            // Send an HTTP request with fetch to
            fetch('/search/profile/new', {
                method: 'POST',
                body: dataForm
            })
                // Extract the JSON from the response
                .then(res => res.json())
                // Then update the icon
                .then(function (data) {
                    // Affiche les résultats
                    let div = document.createElement('div');
                    div.setAttribute('class', 'alert alert-success');
                    div.setAttribute('role', 'alert');
                    div.setAttribute('aria-live', 'assertive');
                    let span = document.createElement("span");
                    span.innerHTML = data['result'];
                    results.innerHTML = '';
                    div.appendChild(span);
                    results.appendChild(div);
                    setTimeout(function () {
                        div.remove(); }, 5000)
                });
        }
    }
}
