<template>
    <div>
        <component :is="this.$route.meta.layout || 'div'">
            <router-view></router-view>
        </component>
    </div>
</template>

<script>
export default {
    name: 'App',
    props: {
        // Define any props if needed
    },
    data() {
        return {
            // Define any reactive data properties if needed
        };
    },
    methods: {
        // Define any methods if needed
    },
    mounted() {
        // Code to run when the component is mounted
    }

};
</script>

<style scoped></style>
