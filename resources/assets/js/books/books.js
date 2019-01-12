import Request from './request';
import notify from './notify';
import { config } from './config';

export default class {
    constructor(notifyVue) {
        this.notify = notifyVue;
    }

    getUrl(url, query) {
        url += '?';
        Object.keys(query).map(k => url += k + '=' + query[k] + '&');

        return url.substring(url.length - 1, -1);
    }

    async fetch(query) {
        const resp = await Request.exec(this.getUrl('/list.json', query), config);

        return await resp.json();
    }

    static async detection(image, controller) {
        const headers = { 'Content-Type': 'image/png' };
        const resp = await Request.exec('/detect', Request.options(image, controller, headers, true));

        return await resp.json();
    }

    async beforeCreate(sid, type, payload, success, complete, controller) {
        let options = config;
        if (controller) {
            options = { ...config, ...{ signal: controller.signal }};
        }

        try {
            const query = { sid: sid, type: type, p: payload };
            const resp = await Request.exec(this.getUrl('/fetch', query), options);
            success(await resp.json());
        } catch (e) {
            notify(this.notify, e);
        } finally {
            complete();
        }
    }

    async create(sid, payload) {
        try {
            const body = { sid: sid, p: payload };
            const resp = await Request.exec('/create', Request.options(body));
            notify(this.notify, resp);

            return await resp.json();
        } catch (e) {
            notify(this.notify, e);
        }
    }

    static async delete(ids) {
        return await Request.exec('/delete', Request.options({ ids: ids }));
    }

    static async edit(entry) {
        return await Request.exec('/edit', Request.options(entry));
    }

    static async move(ids, sid, to) {
        return await Request.exec('/move', Request.options({ ids: ids, sid: sid, to_sid: to }));
    }
}
