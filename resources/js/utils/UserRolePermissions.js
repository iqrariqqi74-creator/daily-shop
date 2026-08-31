import store from "../admin/vuex";
export default function canAccess(permissionToAccess) {
    let user = store.getters.getUser;
    if (user === null) {
        return false;
    }
    if (user.is_super_admin == 1) {
        return true;
    }
    let permissions = store.getters.getUserPermissions;
    if (permissions.includes(permissionToAccess)) {
        return true;
    }
    return false;
}
