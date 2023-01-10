import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('live:connect', (event) => {
            // @todo handle blur on CKEDITOR
            // this.component = event.detail.component;
            //
            // this.component.on('render:finished', (component) => {
            //     const editor= document.getElementById('annonce_description');
            //     const scripts = editor.parentElement.getElementsByTagName('script');
            //     eval(scripts[0].innerHTML);
            //     eval(scripts[2].innerHTML);
            // });
        });
    }
}
