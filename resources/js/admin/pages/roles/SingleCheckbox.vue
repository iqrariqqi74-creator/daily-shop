<template>
    <div>
        <label class="w-100" :for="`permission_${module.module}`">
            <div class="bg-success text-white form-control">
                <input type="checkbox" :checked="selectAll" @change="toggleAllItems($event)" />
                {{ module.module.toUpperCase() }}
            </div>
            <ul class="p-0">
                <li v-for="(permission_name, index) in module.permissions" :key="index" class="list-unstyled">
                    <div class="form-control border-0">
                        <label>
                            <input :id="`permission_${module.module}_${permission_name.id}`" type="checkbox"
                                :value="permission_name.id" v-model="selected_permission.permission_ids" />
                            {{ permission_name.permission }}
                        </label>
                    </div>
                </li>
            </ul>
        </label>
    </div>
</template>

<script>
export default {
    name: "SingleCheckbox",
    props: ["module", "selected_permission"],

    computed: {
        // Determines whether all permissions in this module are selected
        selectAll() {
            const permissionIds = this.module.permissions.map((p) => p.id);
            return permissionIds.every((id) =>
                this.selected_permission.permission_ids.includes(id)
            );
        },
    },

    methods: {
        toggleAllItems(event) {
            const isChecked = event.target.checked;
            const permissionIds = this.module.permissions.map((p) => p.id);

            if (isChecked) {
                // Add all permission IDs if not already in the array
                permissionIds.forEach((id) => {
                    if (!this.selected_permission.permission_ids.includes(id)) {
                        this.selected_permission.permission_ids.push(id);
                    }
                });
            } else {
                // Remove all permission IDs for this module
                this.selected_permission.permission_ids =
                    this.selected_permission.permission_ids.filter(
                        (id) => !permissionIds.includes(id)
                    );
            }
        },
    },
};
</script>
