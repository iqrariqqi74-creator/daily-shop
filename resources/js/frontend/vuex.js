import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

// =====================================================
// CREATE NUMERIC GUEST ID
// =====================================================
function createGuestId() {
    return Math.floor(100000000 + Math.random() * 900000000);
}

// =====================================================
// GET / CREATE GUEST ID
// =====================================================
function getGuestIdFromStorage() {
    let guestId = localStorage.getItem("guest_id");

    // Agar purani string ID hai:
    // guest_1786522902262_xxxxx
    // to usko delete karke new numeric ID banao.
    if (!guestId || !/^\d+$/.test(guestId)) {
        guestId = createGuestId();

        localStorage.setItem("guest_id", String(guestId));
    }

    return Number(guestId);
}

// =====================================================
// STORE
// =====================================================
const store = createStore({
    // =================================================
    // STATE
    // =================================================
    state: {
        // FRONTEND USER TOKEN
        userToken: localStorage.getItem("user_token") || null,

        // FRONTEND USER
        frontendUser: null,

        // NUMERIC GUEST ID
        guestId: getGuestIdFromStorage(),
    },

    // =================================================
    // GETTERS
    // =================================================
    getters: {
        isUserAuthenticated: (state) => {
            return !!state.userToken;
        },

        getFrontendUser: (state) => {
            return state.frontendUser;
        },

        getUserToken: (state) => {
            return state.userToken;
        },

        getGuestId: (state) => {
            return state.guestId;
        },
    },

    // =================================================
    // MUTATIONS
    // =================================================
    mutations: {
        // USER TOKEN
        setUserToken(state, token) {
            state.userToken = token;

            localStorage.setItem("user_token", token);
        },

        clearUserToken(state) {
            state.userToken = null;

            localStorage.removeItem("user_token");
        },

        // FRONTEND USER
        setFrontendUser(state, user) {
            state.frontendUser = user;
        },

        clearFrontendUser(state) {
            state.frontendUser = null;
        },

        // GUEST ID
        setGuestId(state, guestId) {
            state.guestId = Number(guestId);

            localStorage.setItem("guest_id", String(guestId));
        },

        clearGuestId(state) {
            state.guestId = null;

            localStorage.removeItem("guest_id");
        },
    },

    // =================================================
    // ACTIONS
    // =================================================
    actions: {
        // USER TOKEN
        setUserToken({ commit }, token) {
            commit("setUserToken", token);
        },

        clearUserToken({ commit }) {
            commit("clearUserToken");
        },

        // FRONTEND USER
        setFrontendUser({ commit }, user) {
            commit("setFrontendUser", user);
        },

        clearFrontendUser({ commit }) {
            commit("clearFrontendUser");
        },

        // USER LOGOUT
        userLogout({ commit }) {
            commit("clearUserToken");
            commit("clearFrontendUser");
        },

        // SET GUEST ID
        setGuestId({ commit }, guestId) {
            commit("setGuestId", guestId);
        },

        // GET / CREATE GUEST ID
        getOrCreateGuestId({ state, commit }) {
            // Existing numeric ID
            if (state.guestId && /^\d+$/.test(String(state.guestId))) {
                return Number(state.guestId);
            }

            // Create new numeric ID
            const guestId = createGuestId();

            commit("setGuestId", guestId);

            return guestId;
        },

        // CLEAR GUEST
        clearGuestId({ commit }) {
            commit("clearGuestId");
        },
    },

    // =================================================
    // PERSISTED STATE
    // =================================================
    plugins: [createPersistedState()],
});

export default store;
