<template>
    <v-container class="text-center">
        <v-form ref="form" class="px-4 py-8 mt-6 mb-6 form">
            <h1 class="headline font-weight-bold mb-4">SIGN UP</h1>

            <v-text-field
                label="Name"
                v-model="form.username.value"
                :rules="form.username.rules"
                required
                lazy-validation
                outlined
                class="mb-6"
            >
            </v-text-field>

            <v-text-field
                label="E-mail"
                v-model="form.email.value"
                :rules="form.email.rules"
                required
                lazy-validation
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
                @click:append="form.password.show = !form.password.show"
                required
                lazy-validation
                outlined
                class="mb-6"
            ></v-text-field>

            <v-text-field
                label="Repeat password"
                :append-icon="
                    form.passwordConfirm.show ? 'mdi-eye' : 'mdi-eye-off'
                "
                :rules="form.passwordConfirm.rules"
                :type="form.passwordConfirm.show ? 'text' : 'password'"
                v-model="form.passwordConfirm.value"
                @click:append="
                    form.passwordConfirm.show = !form.passwordConfirm.show
                "
                required
                lazy-validation
                outlined
                class="mb-6"
            ></v-text-field>

            <div class="my-2 caption">
                <v-icon>priority_high</v-icon> Password must contain 8+ symbols,
                1 special and 2 capital letters
            </div>

            <v-btn
                depressed
                x-large
                rounded
                color="primary"
                width="100%"
                class="text-capitalize mt-4"
                :disabled="form.sending"
                @click="SIGN_UP_SUBMIT"
                ><v-progress-circular
                    v-show="form.sending"
                    size="18"
                    color="#FFF"
                    indeterminate
                ></v-progress-circular>
                <span v-show="!form.sending">Sign Up</span></v-btn
            >
        </v-form>

        <v-alert
            v-if="form.error !== null"
            type="error"
            dense
            dismissible
            class="my-2"
            >{{ form.error }}
        </v-alert>

        <p>
            Already have an account?
            <v-spacer></v-spacer>
            <router-link to="/">Sign In</router-link>
        </p>
    </v-container>
</template>

<script>
export default {
    name: "sign_up",
    data() {
        return {
            form: {
                username: {
                    value: "",
                    rules: [
                        value => !!value || "Name is required",
                        value =>
                            (value && value.length <= 50) ||
                            "Name must be less than 50 characters"
                    ]
                },
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
                        value => value.length >= 8 || "Min 8 characters",
                        value =>
                            /^.*[A-Z]+.*[A-Z]+.*$/.test(value) ||
                            "Password must be 2 captital letters",
                        value =>
                            /^.*\W+.*$/.test(value) ||
                            "Password must contain at least one special character"
                    ],
                    show: false
                },
                passwordConfirm: {
                    value: "",
                    rules: [
                        value => !!value || "Password required",
                        value => value.length >= 8 || "Min 8 characters",
                        value =>
                            value == this.form.password.value ||
                            "The password and confirmation password do not match"
                    ],
                    show: false
                },
                error: null,
                sending: false
            }
        };
    },
    methods: {
        SIGN_UP_SUBMIT() {
            if (this.$refs.form.validate()) {
                this.form.error = null;
                this.$store
                    .dispatch("REGISTER_REQUEST", {
                        name: this.form.username.value,
                        email: this.form.email.value,
                        password: this.form.password.value
                    })
                    .then(() => this.$router.push({ name: "Sign_in" }))
                    .catch(err => (this.form.error = err));
            }
        }
    }
};
</script>
