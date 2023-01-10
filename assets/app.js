/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

//Import bootstrap Icons
import 'bootstrap-icons/font/bootstrap-icons.css';

require("bootstrap")


function alertCounter()
{
    const alerts = document.getElementsByClassName("alert");
    for (let alert of alerts) {
        if (alertTimeCounter === 5) {
            alert.textContent += " || suppression dans";
            const compteur = alert.appendChild(document.createElement('span')).innerHTML = `${alertTimeCounter--} secondes`;
            setTimeout(alertCounter, 1000);
        } /*elseif (alertTimeCounter === 0){
            alert.remove();
            setTimeout(alertCounter, 1000)
        } else {
        alert:last-child.
        setTimeout(alertCounter, 1000)
        }
        */
    }
}
let alertTimeCounter = 5;
alertCounter();
