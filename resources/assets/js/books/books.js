import Request from './request';
import notify from './notify';
import { options } from './config';

export default class {
    constructor(notifyVue) {
        this.notify = notifyVue;
    }

    async fetch(query) {
        let url = '/list.json?';
        if (query !== undefined) {
            Object.keys(query).map(k => url += k + '=' + query[k] + '&');
        }
        const resp = await Request.exec(url.substring(url.length - 1, -1), options);

        return await resp.json();
    }

    async before_create(sid, code, callback) {
        try {
            const resp = await Request.exec('/fetch?sid=' + sid + '&code=' + code, options);
            callback(await resp.json(), resp.headers.get('X-Request-Id'));
        } catch (e) {
            notify(this.notify, e);
        }
    }

    async create(entry, reqId) {
        try {
            const reqOptions = Request.postOptions({ 'id': reqId });
            const resp = await Request.exec('/create', reqOptions);
            notify(this.notify, resp);

            return await resp.json();
        } catch (e) {
            notify(this.notify, e);
        }
    }

    async delete(ids) {
        return await Request.exec('/delete', Request.postOptions({ ids: ids }));
    }

    async edit(entry) {
        return await Request.exec('/edit', Request.postOptions(entry));
    }
}
