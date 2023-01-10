import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        this.element.addEventListener('live:connect', (event) => {
            this.component = event.detail.component;
            this.editors = document.querySelectorAll('[data-ckeditor]');
            this._onBlurCKEditors();
            this.component.on('render:finished', (component) => {
                for (let editor of this.editors) {
                    const scripts = editor.parentElement.getElementsByTagName('script');
                    eval(scripts[0].innerHTML);
                    eval(scripts[2].innerHTML);
                }
                this._onBlurCKEditors();
            });

        });
    }

    _onBlurCKEditors()
    {
        for (let editor of this.editors) {
            let editorId = editor.id;
            // eslint-disable-next-line no-undef
            CKEDITOR.instances[editorId].on('blur', () => {
                editor.dispatchEvent(new Event('change', { bubbles: true }));
            })
        }
    }
}
