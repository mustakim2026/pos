<template>
    <div class="container-fluid">
        <AppHeader></AppHeader>
        <RouterView></RouterView>
    </div>
</template>

<script>
import AppHeader from './components/Layout/AppHeader.vue';

export default {
    name: 'App',
    components: {
        AppHeader
    }
}
</script>
