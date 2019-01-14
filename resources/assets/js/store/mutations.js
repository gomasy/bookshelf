import * as types from './mutation-types';

export default {
    [types.SET_SETTINGS](state, payload) {
        state.statuses = payload.statuses;
        state.settings = payload.user_setting;
        state.shelves = payload.shelves;
    },
    [types.SET_VIEWMODE](state, display_format) {
        switch (display_format) {
        case 0:
            [ state.viewMode, state.imageSize ] = [ 'listview', 'thumb' ];
            break;
        case 1:
            [ state.viewMode, state.imageSize ] = [ 'album', 'large' ];
            break;
        }
    },
};
