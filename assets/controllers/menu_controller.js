import { Controller } from '@hotwired/stimulus';

// Ouvre et ferme le menu de navigation sur mobile.
export default class extends Controller {
    static targets = ['panel', 'button', 'openIcon', 'closeIcon'];

    toggle() {
        this.#setOpen(this.panelTarget.hidden);
    }

    close(event) {
        if (event?.type === 'keydown' && event.key !== 'Escape') {
            return;
        }
        this.#setOpen(false);
    }

    #setOpen(open) {
        this.panelTarget.hidden = !open;
        this.buttonTarget.setAttribute('aria-expanded', String(open));
        this.buttonTarget.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
        this.openIconTarget.toggleAttribute('hidden', open);
        this.closeIconTarget.toggleAttribute('hidden', !open);
    }
}
