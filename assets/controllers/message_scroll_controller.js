import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['messages', 'formNewMessage'];
    connect()
    {
        this.formNewMessageTarget.addEventListener('submit', () => {
            setTimeout(() => {
                this.formNewMessageTarget.querySelector('textarea').value = ''
            }, 300)
        })
        this.element.addEventListener('live:connect', (event) => {
            this.component = event.detail.component;
            this.totalArticles = this.messagesTarget.querySelectorAll('article').length;
            this.component.on('render:finished', (component) => {
                let numArticles = this.messagesTarget.querySelectorAll('article').length;
                if (this.totalArticles !== numArticles) {
                    this.messagesTarget.scrollTop = this.messagesTarget.scrollHeight;
                    this.totalArticles = numArticles;
                }
            });
        });
    }
}
