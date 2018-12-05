import { options } from './config';

export default class {
    static get() {
        return fetch('/settings.json', options).then(async response => {
            return await response.json();
        });
    }
}
