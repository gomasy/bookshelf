import { headers } from './config';

export default class {
    static get() {
        return fetch('/settings/display', { headers: headers }).then(async response => {
            return await response.json();
        });
    }
}
