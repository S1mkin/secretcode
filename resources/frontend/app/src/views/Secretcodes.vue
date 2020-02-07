<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="12">
                <h2 class="text-center mb-4">View secret code</h2>

                <div class="text-center">
                    <v-progress-circular
                        v-show="loading.status"
                        size="18"
                        color="#F00"
                        indeterminate
                    ></v-progress-circular>
                </div>

                <v-expansion-panels v-show="!loading.status">
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
                                :value="secretcode.code"
                            ></v-textarea>

                            <v-row no-gutters>
                                <v-col cols="6">
                                    Created at:
                                    {{ secretcode.created_at | DATE_TO_STR }}
                                </v-col>
                                <v-col cols="6" class="text-right">
                                    <v-btn
                                        color="indigo"
                                        outlined
                                        class="mb-4"
                                        :disabled="loading.status"
                                        @click="
                                            DELETE_SECRETCODE(secretcode.id)
                                        "
                                    >
                                        <v-progress-circular
                                            v-show="loading.status"
                                            size="18"
                                            color="#FFF"
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
                error: null
            }
        };
    },
    created() {
        // Load secretcodes
        this.loading.status = true;
        this.loading.error = null;

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

            this.$store
                .dispatch("DELETE_SECRETCODES_FROM_BACKEND", { id: id })
                .then(() => {})
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
