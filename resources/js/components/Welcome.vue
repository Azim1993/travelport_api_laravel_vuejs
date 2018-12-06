<template>
<div>
    <app-header v-if="!isFullRoute"></app-header>
    <transition name="fade" mode="out-in">
        <router-view></router-view>
    </transition>
</div>
</template>

<script>
    import appHeader from './Header'
    export default {
        name: 'welcome',
        components: {
            appHeader
        },
        computed: {
		    isFullRoute() {
				return (this.$route.meta && this.$route.meta.isFull === true);
			}
        }
    }
</script>