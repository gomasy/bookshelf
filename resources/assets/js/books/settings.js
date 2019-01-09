import Request from './request';
import { config } from './config';

export default class {
    static async get() {
        const resp = await fetch('/settings/all.json', config);

        return await resp.json();
    }

    static async createShelves(name) {
        return await fetch('/settings/shelves/create', Request.options({ 'name': name }));
    }

    static async removeShelves(id, recursive) {
        return await fetch('/settings/shelves/delete', Request.options({ 'id': id, 'recursive': recursive }));
    }
}
