import { options } from './config';

export default class {
    static get() {
        return fetch('/settings/display', options).then(async response => {
            return await response.json();
        });
    }
}
