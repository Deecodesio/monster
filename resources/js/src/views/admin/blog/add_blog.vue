<template>
    <b-card>
        <b-form @submit.prevent="addJobVacancy">
            <b-row>
                <b-col md="6">
                    <b-form-group label="Blog Name">
                        <b-form-input class="rose-border" v-model="job.name" />
                    </b-form-group>

                    <!-- <b-form-group label="Date ">
                        <b-form-input
                            class="rose-border"
                            type="date"
                            v-model="job.date"
                        />
                    </b-form-group> -->

                    <!-- <b-form-group label="Created By">
                        <b-form-input
                            class="rose-border"
                            v-model="job.created_by"
                        />
                    </b-form-group> -->

                    <b-form-group label="Category">
                        <b-form-select
                            class="rose-border"
                            v-model="job.status"
                            :options="categoryOptions"
                            :placeholder="$t('Category')"
                        />
                    </b-form-group>
                    <b-form-group :label="$t('Blog image')">
                        <b-form-file
                            v-model="carrier.image"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                            @change="onFileChange1"
                            accept=".jpg, .png, .jpeg"
                        />
                    </b-form-group>
                    <b-form-group :label="$t('Export')">
                        <b-form-textarea
                            id="message"
                            v-model="bulkOrder.message"
                            :placeholder="$t('Export')"
                            rows="4"
                            style="resize: none; height: 105px"
                        />
                    </b-form-group>
                    <b-row>
                        <b-col md="12">
                            <b-button
                                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                type="submit"
                                variant="primary"
                                :disabled="loading"
                            >
                                {{ $t("save") }}
                            </b-button>
                        </b-col>
                    </b-row>
                </b-col>

                <b-col md="6">
                    <div class="container-fluid card-editor mb-0">
                        Blog Details
                    </div>
                    <b-row>
                        <b-col class="quill-editor ql-editor" md="12">
                            <b-form-group>
                                <quill-editor
                                    v-model="job.details"
                                    :options="snowOption"
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
} from "bootstrap-vue";
import { quillEditor } from "vue-quill-editor";
import Ripple from "vue-ripple-directive";

export default {
    components: {
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        BForm,
        BButton,
        BCard,
        BCardText,
        quillEditor,
    },
    data() {
        return {
            job: {
                name: "",
                city: "",
                state: "",
                date: "",
                created_by: "",
                status: "",
                details: "",
            },
            snowOption: {
                theme: "snow",
            },
            loading: false,
            categoryOptions: [{ value: "", text: "Category" }],
            carrier: {
                image: null,
            },
            bulkOrder: {
                message: "",
            },
        };
    },
    directives: {
        Ripple,
    },
    methods: {
        addJobVacancy() {
            this.loading = true;
            const data = new FormData();
            data.append("name", this.job.name);
            data.append("city", this.job.city);
            data.append("state", this.job.state);
            data.append("date", this.job.date);
            data.append("created_by", this.job.created_by);
            data.append("status", this.job.status);
            data.append("details", this.job.details);

            this.$http
                .post("/admin/job-vacancy", data)
                .then((response) => {
                    this.$toast({
                        title: this.$t("Job vacancy saved successfully!"),
                        variant: "success",
                        position: "bottom-right",
                    });
                    // Optionally reset form here
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

<style scoped>
.rose-border {
    border: 1px solid #e91e63;
    box-shadow: none;
    border-radius: 6px;
}
.card-editor {
    margin-left: 115px;
}
.quill-editor {
    min-height: 200px;
    max-height: 300px;
    overflow-y: auto;
}
.ql-editor {
    min-height: 180px;
    max-height: 260px;
    overflow-y: auto;
}
</style>
