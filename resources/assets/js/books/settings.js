import Request from './request';
import { config } from './config';

export default class {
    static async get() {
        const resp = await Request.exec('/settings/all.json', config);

        return await resp.json();
    }

    static async createShelves(name) {
        return await Request.exec('/settings/shelves/create', Request.options({ name: name }));
    }

    static async removeShelves(id, recursive) {
        return await Request.exec('/settings/shelves/delete', Request.options({ id: id, recursive: recursive }));
    }
}
