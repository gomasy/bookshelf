import { options } from './config';

export default class {
    static postOptions(body) {
        const postOptions = { ...options };
        postOptions['method'] = 'post';
        postOptions['body'] = JSON.stringify(body);

        return postOptions;
    }

    static async exec(path, options) {
        const resp = await fetch(path, options);

        if (!resp.ok) {
            return Promise.reject(resp);
        }

        return resp;
    }
}
