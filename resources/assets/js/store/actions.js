import { Settings } from '../books/';
import * as types from './mutation-types';

export default {
    async getSettings({ commit }) {
        commit(types.SET_SETTINGS, await Settings.get());
    },
    setViewMode({ commit }, payload) {
        commit(types.SET_VIEWMODE, payload);
    },
};
