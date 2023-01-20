import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['messages'];
    connect()
    {
        this.element.addEventListener('live:connect', (event) => {
            this.component = event.detail.component;
            this.component.on('render:finished', (component) => {
                this.messagesTarget.scrollTop = this.messagesTarget.scrollHeight;
            });
        });
    }
}
