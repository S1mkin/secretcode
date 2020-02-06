<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="12">
                <v-form ref="form" class="form">
                    <h2 class="text-center mb-4">Add secret code</h2>

                    <v-text-field
                        v-model="form.name_secret_code.value"
                        label="Name"
                        outlined
                        required
                        lazy-validation
                        :rules="form.name_secret_code.rules"
                    >
                    </v-text-field>
                    <v-textarea
                        v-model="form.text_secret_code.value"
                        label="Code"
                        outlined
                        required
                        lazy-validation
                        :rules="form.text_secret_code.rules"
                    ></v-textarea>
                    <v-btn
                        color="success"
                        width="100%"
                        class="mb-4"
                        :disabled="form.sending"
                        @click="ADD_SECRET_CODE"
                    >
                        <v-progress-circular
                            v-show="form.sending"
                            size="18"
                            color="#FFF"
                            indeterminate
                        ></v-progress-circular>
                        <span v-show="!form.sending"
                            ><v-icon class="pr-1">add_circle_outline</v-icon>
                            Add to base</span
                        >
                    </v-btn>

                    <v-alert
                        v-if="form.success !== null"
                        type="success"
                        dense
                        dismissible
                        class="my-2"
                        >{{ form.success }}
                    </v-alert>

                    <v-alert
                        v-if="form.error !== null"
                        type="error"
                        dense
                        dismissible
                        class="my-2"
                        >{{ form.error }}
                    </v-alert>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";
export default {
    name: "home",
    data() {
        return {
            form: {
                name_secret_code: {
                    value: "Secret code №1",
                    rules: [
                        value => !!value || "Name secret code is required",
                        value => value.length >= 8 || "Min 8 characters"
                    ]
                },
                text_secret_code: {
                    value:
                        "secret text demis 4 lala-}blab{la ! =)) :( {457}7775 {-1.000001 } 32 {+98} {2} {+3.14} {12637} 9812 {89123789} 1 O O1 01 1O 1}OO {zer}o! {df1000 ggg... {5-} 105} {-2010} wass{auupp!!",
                    rules: [
                        value => !!value || "Text secret code is required",
                        value => value.length >= 8 || "Min 8 characters"
                    ]
                },
                sending: false,
                error: null,
                success: null
            },
            str: "111"
        };
    },
    methods: {
        ADD_SECRET_CODE() {
            this.form.sending = true;
            this.form.error = null;
            axios
                .post("/api/secretcode/add", {
                    name: this.form.name_secret_code.value,
                    code: this.form.text_secret_code.value
                })
                .then(response => {
                    this.form.success = response.data;
                    this.form.name_secret_code.value = "";
                    this.form.text_secret_code.value = "";
                    this.form.sending = false;
                })
                .catch(error => {
                    this.form.error =
                        error.response.data.errors ||
                        error.response.data.message ||
                        error.message;
                    this.form.sending = false;
                });
        }
    }
};
</script>

<style lang="scss">
.form {
    background-color: #fff;
    padding: 40px;
    border-radius: 20px;
    margin: 0 auto;
}
</style>
