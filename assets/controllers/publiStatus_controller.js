import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        let annonceSwipers = document.getElementsByClassName("annonceSwiper")
        for (let annonceSwiper of annonceSwipers) {
            annonceSwiper.addEventListener('click', changeStatus);
        }

        function changeStatus(event) {
            let swiper = event.currentTarget;
            let annonceId = event.currentTarget.dataset.annonce;
            let link = `/annonce/change-status/${annonceId}`;
            // Send an HTTP request with fetch to the URI defined in the href
            fetch(link)
                // Extract the JSON from the response
                .then(res => res.json())
                // Then update the icon
                .then(function (res) {
                    if(res.isActivated)
                    {
                        swiper.setAttribute("checked", "true");
                    } else {
                        swiper.setAttribute("checked", "false");
                    }
                });
        }
    }
}
