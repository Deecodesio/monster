<template>
    <b-card>
        <b-form @submit.prevent="addBulkOrder">
            <b-row>
                <b-col md="6">
                    <b-form-group label="Job Name">
                        <b-form-input
                            class="rose-border"
                            v-model="bulkOrder.name"
                        />
                    </b-form-group>

                    <b-form-group label="Location">
                        <b-form-input
                            class="rose-border"
                            v-model="bulkOrder.city"
                        />
                    </b-form-group>

                    <b-form-group label="State/Union Territory">
                        <b-form-input
                            class="rose-border"
                            v-model="bulkOrder.state"
                        />
                    </b-form-group>

                    <b-form-group label="Date of Create">
                        <b-form-input
                            class="rose-border"
                            type="date"
                            v-model="bulkOrder.date"
                        />
                    </b-form-group>

                    <b-form-group label="Created By">
                        <b-form-input
                            class="rose-border"
                            v-model="bulkOrder.created_by"
                        />
                    </b-form-group>

                    <b-form-group label="Status">
                        <b-form-input
                            class="rose-border"
                            v-model="bulkOrder.status"
                        />
                    </b-form-group>
                </b-col>

                <b-col md="6">
                    <div class="container-fluid card-editor">Job Details</div>
                </b-col>
            </b-row>

            <b-row>
                <b-col>
                    <b-button type="submit" variant="primary" class="mr-1">
                        Save
                    </b-button>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BButton,
    BCard,
} from "bootstrap-vue";

export default {
    components: {
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        BForm,
        BButton,
        BCard,
    },
    data() {
        return {
            bulkOrder: {
                name: "",
                city: "",
                state: "",
                date: "",
                created_by: "",
                status: "",
            },
        };
    },
    methods: {
        addBulkOrder() {
            console.log("Form submitted:", this.bulkOrder);
            // API call logic here
        },
    },
};
</script>

<style scoped>
.rose-border {
    border: 1px solid #e91e63;
    box-shadow: none;
}

.card-editor {
    margin-left: 115px;
}
</style>
