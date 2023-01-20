import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const messageHeaders = document.querySelectorAll("tr.messageHeader");
        for (let messageHeader of messageHeaders)
            messageHeader.addEventListener("click", () =>{
                window.location.assign(`/message/recruitmentProcess/${messageHeader.dataset.messageid}`);
            })
    }
}
