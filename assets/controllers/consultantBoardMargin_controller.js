import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    addMargin(event) {
        if ((window.location.pathname).startsWith("/consultant")) {
            document.querySelector("main").style.marginLeft = "18rem";
        }
    }

    removeMargin(event) {
        if ((window.location.pathname).startsWith("/consultant")) {
            setTimeout(function () {
                document.querySelector("main").style.marginLeft = ""
            }, 500);
        }
    }

    connect() {
        document.querySelector("main").style.marginLeft = "18rem";

        /*        if ((window.location.pathname).startsWith("/consultant")) {
                    document.querySelector(".navbar-toggler").addEventListener('click', (e) => {
                        document.querySelector("main").style.marginLeft = "18rem";
                    });

                    document.querySelector(".close-cross").addEventListener('click', (e) => {
                        setTimeout(function () {
                            document.querySelector("main").style.marginLeft = "null"
                        }, 500);
                    });
                }*/
    }


}
