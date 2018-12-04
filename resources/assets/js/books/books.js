import notify from './notify';
import { options } from './config';

export default class {
    constructor(notifyVue) {
        this.notify = notifyVue;
        this.options = options;
    }

    postOptions(body) {
        const postOptions = this.options;
        postOptions['method'] = 'post';
        postOptions['body'] = JSON.stringify(body);

        return postOptions;
    }

    fetch(query, callback) {
        let url = '/list.json?';
        if (query !== undefined) {
            Object.keys(query).map(k => url += k + '=' + query[k] + '&');
        }

        fetch(url.substring(url.length - 1, -1), this.options).then(async response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return await response.json();
        }).then(result => callback(result));
    }

    before_create(code, callback) {
        fetch('/fetch?code=' + code, this.options).then(async response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            callback(await response.json(), response.headers.get('X-Request-Id'));
        }).catch(async e => notify(this.notify, await e));
    }

    create(entry, reqId) {
        const reqOptions = this.postOptions({ 'id': reqId });
        return fetch('/create', reqOptions).then(async response => {
            notify(this.notify, await response);

            if (!response.ok) {
                return Promise.reject(response);
            }

            return await response.json();
        });
    }

    delete(ids, callback) {
        const reqOptions = this.postOptions({ ids: ids });
        fetch('/delete', reqOptions).then(response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            callback();
        });
    }

    edit(entry, callback) {
        const reqOptions = this.postOptions(entry);
        fetch('/edit', reqOptions).then(response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            callback();
        });
    }
}
