<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" md="12">
                <h2 class="mb-4">Secret codes</h2>

                <v-alert
                    v-if="load_success !== null"
                    type="success"
                    dense
                    dismissible
                    class="my-2"
                    >{{ load_success }}
                </v-alert>

                <v-alert
                    v-if="load_error !== null"
                    type="error"
                    dense
                    dismissible
                    class="my-2"
                    >{{ load_error }}
                </v-alert>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            secretcodes: "",
            load_success: null,
            load_error: null
        };
    }
};
</script>
