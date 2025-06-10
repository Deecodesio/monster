<template>
    <b-card>
        <b-form @submit.prevent="addJobVacancy">
            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('Job Name')">
                        <b-form-input
                            id="name"
                            :placeholder="$t('name')"
                            v-model="job.job_name"
                            :required="!job.job_name"
                        />
                    </b-form-group>

                    <b-form-group :label="$t('state')">
                        <v-select
                            id="state"
                            v-model="job.state_id"
                            :options="state_list"
                            label="state"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('select state')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!job.state_id"
                            @input="onStateChange"
                        />
                    </b-form-group>

                    <b-form-group :label="$t('Location')">
                        <v-select
                            id="location"
                            v-model="job.location_id"
                            :options="city_list"
                            label="city"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('Select Location')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!job.location_id"
                        />
                    </b-form-group>

                    <b-form-group :label="$t('status')">
                        <v-select
                            v-model="job.status"
                            :options="options"
                            label="text"
                            :reduce="(sel) => sel.value"
                            :placeholder="$t('select status')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!job.status"
                        />
                    </b-form-group>
                    <b-row>
                        <b-col md="12">
                            <b-button
                                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                type="submit"
                                variant="primary"
                                class="mr-1"
                                :disabled="loading"
                            >
                                {{ $t("save") }}
                            </b-button>
                        </b-col>
                    </b-row>
                </b-col>

                <b-col md="6">
                    <div style="padding-bottom: calc(0.438rem + 1px)">
                        Job Details
                    </div>
                    <b-row>
                        <b-col md="12">
                            <b-form-group>
                                <quill-editor
                                    v-model="job.job_details"
                                    :options="snowOption"
                                    style="height: 200px"
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
// Import Quill CSS for proper editor display
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";

import {
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BButton,
    BCard,
    BCardText,
    BFormCheckbox,
    BTooltip,
    BFormSelect,
    BTabs,
    BTab,
} from "bootstrap-vue";
import vSelect from "vue-select";
import { quillEditor } from "vue-quill-editor";
import Ripple from "vue-ripple-directive";

export default {
    components: {
        BFormCheckbox,
        BTooltip,
        BFormSelect,
        BTabs,
        BTab,
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        BForm,
        BButton,
        BCard,
        BCardText,
        quillEditor,
        vSelect,
    },
    data() {
        return {
            state_list: [],
            city_list: [],
            options: [
                { value: 1, text: this.$t("active") },
                { value: 2, text: this.$t("inactive") },
            ],
            job: {
                job_name: "",
                location_id: "",
                state_id: "",
                status: "",
                job_details: "",
            },
            snowOption: {
                theme: "snow",
            },
            loading: false,
        };
    },
    directives: {
        Ripple,
    },
    created() {
        this.$http.get("/admin/state_list").then((res) => {
            this.state_list = res.data;
            console.log(this.state_list);
        });

        if (this.$route.params.id) {
            this.$http
                .get("/api/career-jobs/" + this.$route.params.id)
                .then((res) => {
                    this.job = res.data.data;
                    this.onStateChange(this.job.state_id);
                });
        }
    },
    methods: {
        onStateChange(stateId) {
            this.$http
                .get("/admin/city_list_by_state/" + stateId)
                .then((res) => {
                    this.city_list = res.data;
                    console.log(this.city_list);
                });
        },

        addJobVacancy() {
            this.loading = true;
            const data = new FormData();
            data.append("id", this.job.id || "");
            data.append("job_name", this.job.job_name);
            data.append("location_id", this.job.location_id);
            data.append("state_id", this.job.state_id);
            data.append("status", this.job.status);
            data.append("job_details", this.job.job_details);
            data.append("created_by", 1);

            this.$http
                .post("/api/career-jobs", data)
                .then((response) => {
                    if (response.data.success == true) {
                        this.$router.push({ name: "job_vacancy" });
                        this.popToast(response, "CheckIcon", "success");
                    } else {
                        this.popToast(response, "AlertTriangleIcon", "danger");
                    }
                })
                .catch((error) => {
                    this.$toast({
                        title: this.$t("Failed to save job vacancy."),
                        variant: "danger",
                        position: "bottom-right",
                    });
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped></style>
