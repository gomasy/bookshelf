import { Settings } from '../books/';
import * as types from './mutation-types';

export default {
    async getSettings({ commit }) {
        commit(types.SET_SETTINGS, await Settings.get());
    },
    setViewMode({ commit }, display_format) {
        commit(types.SET_VIEWMODE, display_format);
    },
};
