import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    connect() {}

    unblurImage(event)
    {
        document.getElementById(event.currentTarget.dataset.target).classList.remove('img-blurred')
        event.currentTarget.remove()
    }

}
