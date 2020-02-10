<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="12">
                <h2 class="text-center mb-4">View secret code</h2>

                <v-expansion-panels>
                    <v-expansion-panel
                        v-for="(secretcode, key) in GET_SECRETCODES"
                        :key="key"
                        class="secretcode"
                    >
                        <v-expansion-panel-header>{{
                            secretcode.name
                        }}</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-textarea
                                outlined
                                filled
                                readonly
                                :value="secretcode.text"
                            ></v-textarea>

                            <span class="font-weight-bold pr-2">Codes:</span>
                            <span
                                v-for="(code, key) in secretcode.codes"
                                :key="key"
                                class="secretcode__code"
                                >{{ code.value }}</span
                            >
                            <span v-show="!secretcode.codes[0]">not found</span>

                            <v-row no-gutters>
                                <v-col cols="6">
                                    <span class="font-weight-bold"
                                        >Created at:</span
                                    >
                                    {{ secretcode.created_at | DATE_TO_STR }}
                                </v-col>
                                <v-col cols="6" class="text-right">
                                    <v-btn
                                        color="indigo"
                                        outlined
                                        class="mb-4"
                                        width="100%"
                                        :disabled="loading.status"
                                        @click="
                                            DELETE_SECRETCODE(secretcode.id)
                                        "
                                    >
                                        <v-progress-circular
                                            v-show="loading.status"
                                            size="18"
                                            color="#F00"
                                            indeterminate
                                        ></v-progress-circular>
                                        <span v-show="!loading.status"
                                            ><v-icon class="pr-1"
                                                >delete_forever</v-icon
                                            >
                                            Delete</span
                                        >
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>

                <v-alert
                    v-if="loading.text !== null"
                    type="error"
                    dense
                    dismissible
                    class="my-2"
                    >{{ loading.text }}
                </v-alert>

                <v-alert
                    v-if="loading.error !== null"
                    type="error"
                    dense
                    dismissible
                    class="my-2"
                    >{{ loading.error }}
                </v-alert>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            loading: {
                status: false,
                text: null,
                error: null
            }
        };
    },
    created() {
        // Load secretcodes
        this.loading.status = true;

        this.$store
            .dispatch("LOAD_SECRETCODES_FROM_BACKEND")
            .then(() => {})
            .catch(error => {
                this.loading.error = error;
                this.loading.status = false;
            })
            .finally(() => {
                this.loading.status = false;
            });
    },
    filters: {
        // Formatting date
        DATE_TO_STR(value) {
            if (!(value instanceof Date)) {
                try {
                    value = new Date(value);
                } catch (e) {
                    return "";
                }
            }
            return value.toLocaleString("ru-RU");
        }
    },
    computed: {
        GET_SECRETCODES() {
            return this.$store.getters.GET_SECRETCODES;
        }
    },
    methods: {
        DELETE_SECRETCODE(id) {
            // Load secretcodes
            this.loading.status = true;
            this.loading.error = null;
            this.loading.text = null;

            this.$store
                .dispatch("DELETE_SECRETCODES_FROM_BACKEND", { id: id })
                .then(response => {
                    this.loading.text = response;
                })
                .catch(error => {
                    this.loading.error = error;
                    this.loading.status = false;
                })
                .finally(() => {
                    this.loading.status = false;
                });
        }
    }
};
</script>

<style lang="scss">
.secretcode {
    &__code {
        border: 1px solid #aaa;
        border-radius: 4px;
        color: rgb(67, 117, 150);
        padding: 2px 6px;
        margin-right: 10px;
    }
}
</style>
