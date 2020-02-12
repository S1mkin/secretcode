<template>
    <v-form ref="form" class="form mb-6">
        <v-row align="center">
            <v-col class="d-flex" cols="12" sm="2">
                Code:
            </v-col>
            <v-col class="d-flex" cols="12" sm="5">
                <v-select
                    v-model="form.condition.value"
                    :items="form.condition.items"
                    label="Condition"
                    outlined
                    required
                    lazy-validation
                    :rules="form.condition.rules"
                ></v-select>
            </v-col>
            <v-col class="d-flex" cols="12" sm="5">
                <v-text-field
                    v-model="form.code.value"
                    label="Code"
                    type="number"
                    outlined
                    required
                    lazy-validation
                    :rules="form.code.rules"
                >
                </v-text-field>
            </v-col>
            <v-col class="d-flex" cols="12" sm="6">
                <v-btn
                    color="success"
                    width="100%"
                    x-large
                    :disabled="form.sending"
                    @click="FILTER_SECRETCODE"
                >
                    <v-progress-circular
                        v-show="form.sending"
                        size="18"
                        color="#FFF"
                        indeterminate
                    ></v-progress-circular>
                    <span v-show="!form.sending"
                        ><v-icon class="pr-1">search</v-icon> Set filter</span
                    >
                </v-btn>
            </v-col>
            <v-col class="d-flex" cols="12" sm="6">
                <v-btn
                    color="success"
                    width="100%"
                    x-large
                    :disabled="form.sending"
                    @click="LOAD_ALL_SECRETCODE"
                >
                    <v-progress-circular
                        v-show="form.sending"
                        size="18"
                        color="#FFF"
                        indeterminate
                    ></v-progress-circular>
                    <span v-show="!form.sending"
                        ><v-icon class="pr-1">list</v-icon> Show all</span
                    >
                </v-btn>
            </v-col>
        </v-row>
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
</template>

<script>
export default {
    data() {
        return {
            form: {
                condition: {
                    value: null,
                    items: [
                        { text: "<", value: "<" },
                        { text: ">", value: ">" },
                        { text: "=", value: "=" }
                    ],
                    rules: [value => !!value || "Filter condition is required"]
                },
                code: {
                    value: null,
                    rules: [value => !!value || "Filter code is required"]
                },
                sending: false,
                error: null,
                success: null
            }
        };
    },
    methods: {
        LOAD_ALL_SECRETCODE() {
            this.form.sending = true;
            this.form.error = null;

            this.$store
                .dispatch("LOAD_SECRETCODES_FROM_BACKEND")
                .then(() => {})
                .catch(error => {
                    this.form.error = error;
                })
                .finally(() => {
                    this.form.sending = false;
                });
        },
        FILTER_SECRETCODE() {
            if (this.$refs.form.validate()) {
                this.form.sending = true;
                this.form.error = null;

                this.$store
                    .dispatch("FILTER_SECRETCODES", {
                        condition: this.form.condition.value,
                        code: this.form.code.value
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
    }
};
</script>

<style lang="scss">
.form {
    background-color: #fff;
    padding: 20px 30px 10px;
    border-radius: 6px;
}
</style>
