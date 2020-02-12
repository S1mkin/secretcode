<template>
    <v-container class="text-center">
        <v-form ref="form" class="px-4 py-8 mt-6 mb-6 form">
            <h1 class="headline font-weight-bold mb-4">SIGN IN</h1>
            <v-text-field
                label="E-mail"
                v-model="form.email.value"
                :rules="form.email.rules"
                lazy-validation
                required
                outlined
                class="mb-6"
            >
            </v-text-field>

            <v-text-field
                label="Password"
                :append-icon="form.password.show ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="form.password.rules"
                :type="form.password.show ? 'text' : 'password'"
                v-model="form.password.value"
                outlined
                class="password"
                @click:append="form.password.show = !form.password.show"
                required
            ></v-text-field>

            <v-btn
                depressed
                x-large
                color="primary"
                width="100%"
                class="text-capitalize mt-4"
                :disabled="form.sending"
                @click="SIGN_IN_SUBMIT"
            >
                <v-progress-circular
                    v-show="form.sending"
                    size="18"
                    color="#FFF"
                    indeterminate
                ></v-progress-circular>
                <span v-show="!form.sending">Sign In</span></v-btn
            >
        </v-form>

        <div class="form-alert-wrap" v-if="form.error !== null">
            <v-alert>
                <v-row align="center" no-gutters>
                    <v-col class="grow">{{ form.error }}</v-col>
                    <v-col class="shrink">
                        <v-icon dark @click="CLOSE_ALERT">mdi-close</v-icon>
                    </v-col>
                </v-row>
            </v-alert>
        </div>

        <p>
            <span class="font-weight-bold">Try:</span> test@mail.ru / qwertyASD!
        </p>
    </v-container>
</template>

<script>
export default {
    name: "sign_in",
    data() {
        return {
            form: {
                email: {
                    value: "",
                    rules: [
                        value => !!value || "E-mail is required",
                        value =>
                            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,5})+$/.test(
                                value
                            ) || "E-mail must be valid"
                    ]
                },
                password: {
                    value: "",
                    rules: [
                        value => !!value || "Password required",
                        value => value.length >= 8 || "Min 8 characters"
                    ],
                    show: false
                },
                error: null,
                sending: false
            }
        };
    },
    methods: {
        SIGN_IN_SUBMIT() {
            if (this.$refs.form.validate()) {
                this.form.sending = true;
                this.form.error = null;
                this.$store
                    .dispatch("SIGN_IN", {
                        email: this.form.email.value,
                        password: this.form.password.value
                    })
                    .then(() => {
                        this.$router.push({ name: "Main" });
                        this.form.sending = false;
                    })
                    .catch(err => {
                        this.form.error = err;
                        this.form.sending = false;
                    });
            }
        },
        // custom close icon for alert
        CLOSE_ALERT() {
            this.form.error = null;
        }
    }
};
</script>
