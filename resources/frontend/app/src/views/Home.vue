<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="12">
                <v-form ref="form" class="form">
                    <h2 class="text-center mb-4">Add secret code</h2>

                    <v-text-field
                        v-model="form.name_secretcode.value"
                        label="Name"
                        outlined
                        required
                        lazy-validation
                        :rules="form.name_secretcode.rules"
                    >
                    </v-text-field>
                    <v-textarea
                        v-model="form.text_secretcode.value"
                        label="Code"
                        outlined
                        required
                        lazy-validation
                        :rules="form.text_secretcode.rules"
                    ></v-textarea>
                    <v-btn
                        color="success"
                        width="100%"
                        class="mb-4"
                        :disabled="form.sending"
                        @click="ADD_secretcode"
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
export default {
    name: "home",
    data() {
        return {
            form: {
                name_secretcode: {
                    value: "Secret code №1",
                    rules: [
                        value => !!value || "Name secret code is required",
                        value => value.length >= 8 || "Min 8 characters"
                    ]
                },
                text_secretcode: {
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
        ADD_secretcode() {
            this.form.sending = true;
            this.form.error = null;

            this.$store
                .dispatch("ADD_SECRETCODES_TO_BACKEND", {
                    name: this.form.name_secretcode.value,
                    code: this.form.text_secretcode.value
                })
                .then(response => {
                    this.form.success = response;
                })
                .catch(error => {
                    this.form.error = error;
                })
                .finally(() => {
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
