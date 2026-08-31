import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";

const store = createStore({
    // =====================================================
    // STATE
    // =====================================================
    state: {
        // =================================================
        // OLD ADMIN AUTH
        // YE PURANA SYSTEM SAME HAI
        // =================================================
        token: localStorage.getItem("admin_token") || null,
        user: null,

        // =================================================
        // FRONTEND USER AUTH
        // NEW
        // =================================================
        userToken: localStorage.getItem("user_token") || null,
        frontendUser: null,

        // =================================================
        // GUEST USER
        // NEW
        // =================================================
        guestId: localStorage.getItem("guest_id") || null,
    },

    // =====================================================
    // GETTERS
    // =====================================================
    getters: {
        // =================================================
        // OLD ADMIN GETTERS
        // SAME AS BEFORE
        // =================================================

        isAuthenticated: (state) => !!state.token,

        getUser: (state) => state.user,

        getToken: (state) => state.token,

        // =================================================
        // FRONTEND USER GETTERS
        // =================================================

        isUserAuthenticated: (state) => {
            return !!state.userToken;
        },

        getFrontendUser: (state) => {
            return state.frontendUser;
        },

        getUserToken: (state) => {
            return state.userToken;
        },

        // =================================================
        // GUEST GETTER
        // =================================================

        getGuestId: (state) => {
            return state.guestId;
        },
    },

    // =====================================================
    // MUTATIONS
    // =====================================================
    mutations: {
        // =================================================
        // OLD ADMIN MUTATIONS
        // SAME AS YOUR OLD FILE
        // =================================================

        setToken(state, token) {
            state.token = token;

            localStorage.setItem("admin_token", token);
        },

        clearToken(state) {
            state.token = null;

            localStorage.removeItem("admin_token");
        },

        setUser(state, user) {
            state.user = user;
        },

        clearUser(state) {
            state.user = null;
        },

        // =================================================
        // FRONTEND USER MUTATIONS
        // =================================================

        setUserToken(state, token) {
            state.userToken = token;

            localStorage.setItem("user_token", token);
        },

        clearUserToken(state) {
            state.userToken = null;

            localStorage.removeItem("user_token");
        },

        setFrontendUser(state, user) {
            state.frontendUser = user;
        },

        clearFrontendUser(state) {
            state.frontendUser = null;
        },

        // =================================================
        // GUEST MUTATIONS
        // =================================================

        setGuestId(state, guestId) {
            state.guestId = guestId;

            localStorage.setItem("guest_id", guestId);
        },

        clearGuestId(state) {
            state.guestId = null;

            localStorage.removeItem("guest_id");
        },
    },

    // =====================================================
    // ACTIONS
    // =====================================================
    actions: {
        // =================================================
        // OLD ADMIN ACTIONS
        // SAME AS YOUR OLD FILE
        // =================================================

        setToken({ commit }, token) {
            commit("setToken", token);
        },

        clearToken({ commit }) {
            commit("clearToken");
        },

        setUser({ commit }, user) {
            commit("setUser", user);
        },

        clearUser({ commit }) {
            commit("clearUser");
        },

        logout({ commit }) {
            commit("clearToken");

            commit("clearUser");
        },

        // =================================================
        // FRONTEND USER ACTIONS
        // =================================================

        setUserToken({ commit }, token) {
            commit("setUserToken", token);
        },

        clearUserToken({ commit }) {
            commit("clearUserToken");
        },

        setFrontendUser({ commit }, user) {
            commit("setFrontendUser", user);
        },

        clearFrontendUser({ commit }) {
            commit("clearFrontendUser");
        },

        // =================================================
        // FRONTEND USER LOGOUT
        // =================================================

        userLogout({ commit }) {
            commit("clearUserToken");

            commit("clearFrontendUser");
        },

        // =================================================
        // GUEST
        // =================================================

        setGuestId({ commit }, guestId) {
            commit("setGuestId", guestId);
        },

        // ==============================================
        // CREATE / GET GUEST ID
        // ==============================================

        getOrCreateGuestId({ state, commit }) {
            if (state.guestId) {
                return state.guestId;
            }

            const guestId =
                "guest_" +
                Date.now() +
                "_" +
                Math.random().toString(36).substring(2, 10);

            commit("setGuestId", guestId);

            return guestId;
        },

        clearGuestId({ commit }) {
            commit("clearGuestId");
        },
    },

    // =====================================================
    // PERSISTED STATE
    // =====================================================
    plugins: [createPersistedState()],
});

export default store;
