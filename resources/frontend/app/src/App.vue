<template>
    <v-app>
        <link
            href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons"
            rel="stylesheet"
        />
        <v-app-bar app flat>
            <v-toolbar-title class="headline text-uppercase">
                <v-icon>vpn_key</v-icon> <span>SECRET</span>
                <span class="font-weight-light">CODE</span>
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <v-btn text to="/add_secretcode" v-show="IS_AUTH">
                <v-icon left>add_circle_outline</v-icon>
                <span class="mr-2">Add</span>
            </v-btn>
            <v-btn text to="/secretcodes" v-show="IS_AUTH">
                <v-icon left>list</v-icon> <span class="mr-2">View</span>
            </v-btn>

            <v-btn text to="/" v-show="!IS_AUTH">
                <v-icon left>account_circle</v-icon>
                <span class="mr-2">Sign in</span>
            </v-btn>
            <v-btn text v-show="IS_AUTH" @click="SIGN_OUT">
                <v-icon left>exit_to_app</v-icon>
                <span class="mr-2">Sign out</span>
            </v-btn>
        </v-app-bar>
        <v-content>
            <transition name="transition-fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </v-content>
    </v-app>
</template>

<script>
import Store from "./store";
export default {
    name: "App",
    data: () => ({}),
    computed: {
        IS_AUTH: () => Store.getters.IS_AUTHENTICATED
    },
    methods: {
        SIGN_OUT() {
            Store.dispatch("AUTH_LOGOUT").then(() => {
                this.$router.push({ name: "Sign_in" });
            });
        }
    }
};
</script>

<style lang="scss" rel="stylesheet/scss">
$main-width: 100%;
header {
    margin: 0 auto;
    max-width: $main-width !important;
}

#app {
    background-color: #efefef;
    position: relative;
    max-width: $main-width;
    margin: 0 auto;
    padding: 20px 40px;
}

/* Fade transition */
.transition-fade-enter-active,
.transition-fade-leave-active {
    transition: opacity 0.2s;
}

.transition-fade-enter-active {
    transition-delay: 0.2s;
}

.transition-fade-enter,
.transition-fade-leave-active {
    opacity: 0;
}
</style>
