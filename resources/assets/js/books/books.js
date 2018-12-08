import notify from './notify';
import { options } from './config';

export default class {
    constructor(notifyVue) {
        this.notify = notifyVue;
        this.options = options;
    }

    postOptions(body) {
        const postOptions = {...this.options};
        postOptions['method'] = 'post';
        postOptions['body'] = JSON.stringify(body);

        return postOptions;
    }

    async request(path, options) {
        const resp = await fetch(path, options);

        if (!resp.ok) {
            return Promise.reject(resp);
        }

        return resp;
    }

    async fetch(query) {
        let url = '/list.json?';
        if (query !== undefined) {
            Object.keys(query).map(k => url += k + '=' + query[k] + '&');
        }
        const resp = await this.request(url.substring(url.length - 1, -1), this.options);

        return await resp.json();
    }

    async before_create(code, callback) {
        try {
            const resp = await this.request('/fetch?code=' + code, this.options);
            callback(await resp.json(), resp.headers.get('X-Request-Id'));
        } catch (e) {
            notify(this.notify, e);
        }
    }

    async create(entry, reqId, sid) {
        try {
            const reqOptions = this.postOptions({ 'id': reqId, 'sid': sid });
            const resp = await this.request('/create', reqOptions);
            notify(this.notify, resp);

            return resp.json();
        } catch (e) {
            notify(this.notify, e);
        }
    }

    async delete(ids) {
        return await this.request('/delete', this.postOptions({ ids: ids }));
    }

    async edit(entry) {
        return await this.request('/edit', this.postOptions(entry));
    }
}
