import { options } from './config';

export default class {
    static async get() {
        const resp = await fetch('/settings/all.json', options);

        return await resp.json();
    }
}
