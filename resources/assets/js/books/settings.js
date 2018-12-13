import Request from './request';
import { options } from './config';

export default class {
    static async get() {
        const resp = await fetch('/settings/all.json', options);

        return await resp.json();
    }

    static async createShelves(name) {
        const reqOptions = Request.postOptions({ 'name': name });

        return await fetch('/settings/shelves/create', reqOptions);
    }

    static async removeShelves(id, recursive) {
        const reqOptions = Request.postOptions({ 'id': id, 'recursive': recursive });

        return await fetch('/settings/shelves/delete', reqOptions);
    }
}
