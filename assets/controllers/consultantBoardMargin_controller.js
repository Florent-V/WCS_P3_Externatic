import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        if((window.location.pathname).startsWith('/consultant')) {

            document.querySelector(".navbar-toggler").addEventListener('click', (e) => {
                document.querySelector("main").style.marginLeft = "18rem";
            });

            document.querySelector(".close-cross").addEventListener('click', (e) => {
                setTimeout(function () {
                    document.querySelector("main").style.marginLeft = "0rem"
                }, 500);
            });
        }
    }
}
