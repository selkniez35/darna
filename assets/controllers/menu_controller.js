import { Controller } from '@hotwired/stimulus';

// Ouvre et ferme le menu de navigation sur mobile.
export default class extends Controller {
    static targets = ['panel', 'button'];

    toggle() {
        const open = this.panelTarget.hidden;
        this.panelTarget.hidden = !open;
        this.buttonTarget.setAttribute('aria-expanded', String(open));
    }

    close(event) {
        if (event.type === 'keydown' && event.key !== 'Escape') {
            return;
        }
        this.panelTarget.hidden = true;
        this.buttonTarget.setAttribute('aria-expanded', 'false');
    }
}
