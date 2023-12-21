import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'collection'
    ];

    connect() {}

    addElement() {
        const prototype = this.collectionTarget.dataset.prototype;
        const index = (this.collectionTarget.dataset.index) ? this.collectionTarget.dataset.index : this.collectionTarget.querySelectorAll('.collection-element').length;
        const newForm = prototype.split(this.collectionTarget.getAttribute('data-prototype-name')).join(index);
        this.collectionTarget.setAttribute('data-index', index + 1);
        this.collectionTarget.innerHTML += newForm;
    }

    removeElement(e) {
        e.closest('.collection-element').remove()
    }
}
