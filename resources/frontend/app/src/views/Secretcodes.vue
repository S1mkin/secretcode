<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="12">
                <h2 class="text-center mb-4">View secret code</h2>

                <v-expansion-panels>
                    <v-expansion-panel
                        v-for="(secretcode, key) in secretcodes"
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
                            <p>
                                Created at:
                                {{ secretcode.created_at | DATE_TO_STR }}
                            </p>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-expansion-panels>

                <v-btn
                    color="success"
                    width="100%"
                    class="mt-4"
                    :disabled="loading.value"
                    @click="GET_SECRET_CODE"
                >
                    <v-progress-circular
                        v-show="loading.value"
                        size="18"
                        color="#FFF"
                        indeterminate
                    ></v-progress-circular>
                    <span v-show="!loading.value"
                        ><v-icon class="pr-1">add_circle_outline</v-icon> Load
                        secret codes</span
                    >
                </v-btn>

                <v-alert
                    v-if="loading.success !== null"
                    type="success"
                    dense
                    dismissible
                    class="my-2"
                    >{{ loading.success }}
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
import axios from "axios";
export default {
    data() {
        return {
            secretcodes: [],
            loading: {
                value: false,
                success: null,
                error: null
            }
        };
    },
    created() {
        // Load secretcodes
        this.loading.value = true;
        this.loading.error = null;
        this.loading.success = null;
        axios
            .get("/api/secretcode/get")
            .then(response => {
                this.secretcodes = response.data;
                this.loading.value = false;
            })
            .catch(error => {
                this.load_error =
                    error.response.data.errors ||
                    error.response.data.message ||
                    error.message;
                this.loading.value = false;
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
    methods: {
        GET_SECRET_CODE() {
            // Load secretcodes
            this.loading.value = true;
            this.loading.error = null;
            this.loading.success = null;
            axios
                .get("/api/secretcode/get")
                .then(response => {
                    this.secretcodes = response.data;
                    this.loading.value = false;
                })
                .catch(error => {
                    this.load_error =
                        error.response.data.errors ||
                        error.response.data.message ||
                        error.message;
                    this.loading.value = false;
                });
        }
    }
};
</script>
