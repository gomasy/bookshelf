import notify from './notify.js';

export class Books {
    constructor(notifyVue) {
        this.notify = notifyVue;
        this.headers = {
            'Content-Type': 'application/json',
            'X-Csrf-Token': document.head.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        };
    }

    fetch(query, callback) {
        let url = '/list.json?';
        if (query !== undefined) {
            Object.keys(query).map(k => url += k + '=' + query[k] + '&');
        }

        fetch(url.substring(url.length - 1, -1), { headers: this.headers }).then(async response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            return await response.json();
        }).then(result => callback(result));
    }

    before_create(code, callback) {
        fetch('/fetch?code=' + code, { headers: this.headers }).then(async response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            callback(await response.json(), response.headers.get('X-Request-Id'));
        }).catch(async e => notify(this.notify, await e));
    }

    create(entry, reqId) {
        return fetch('/create', {
            method: 'post',
            headers: this.headers,
            body: JSON.stringify({ 'id': reqId }),
        }).then(async response => {
            notify(this.notify, await response);

            if (!response.ok) {
                return Promise.reject(response);
            }

            return response.json();
        });
    }

    delete(ids, callback) {
        fetch('/delete', {
            method: 'post',
            headers: this.headers,
            body: JSON.stringify({ ids: ids }),
        }).then(response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            callback();
        });
    }

    edit(entry, callback) {
        fetch('/edit', {
            method: 'post',
            headers: this.headers,
            body: JSON.stringify(entry),
        }).then(response => {
            if (!response.ok) {
                return Promise.reject(response);
            }

            callback();
        });
    }
}
