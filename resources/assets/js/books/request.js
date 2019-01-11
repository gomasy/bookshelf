import { config } from './config';

export default class {
    static options(body, controller, headers, isBlob) {
        const options = { ...config };
        options.method = 'post';
        options.body = isBlob ? body : JSON.stringify(body);

        if (controller) {
            options.signal = controller.signal;
        }

        if (headers) {
            options.headers = { ...options.headers, ...headers };
        }

        return options;
    }

    static async exec(path, options) {
        const resp = await fetch(path, options);

        if (!resp.ok) {
            return Promise.reject(resp);
        }

        return resp;
    }
}
