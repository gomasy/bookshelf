import * as types from './mutation-types';

export default {
    [types.SET_SETTINGS](state, payload) {
        state.settings = payload.user_setting;
        state.shelves = payload.shelves;
    },
    [types.SET_VIEWMODE](state, payload) {
        state.viewMode = payload.mode;
        state.imageSize = payload.size;
    },
};
