<template>
    <b-card>
        <b-form @submit.prevent="addJobCategory">
            <b-row>
                <b-col md="12">
                    <b-form-group :label="$t('name')">
                        <b-form-input
                            id="name"
                            :placeholder="$t('name')"
                            v-model="job_category.name"
                            required
                        />
                    </b-form-group>
                </b-col>
            
                <b-col md="12">
                    <b-form-group :label="$t('description')">
                        <b-form-textarea
                            id="description"
                            v-model="job_category.description"
                            :placeholder="$t('description')"
                            rows="4"
                            style="resize: none; height: 105px"
                        />
                    </b-form-group>
                </b-col>
                <b-col>
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        type="submit"
                        variant="primary"
                        class="mr-1"
                    >
                        {{ $t("save") }}
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
    BFormTextarea,
    BCard,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BRow,
        BCol,
        BFormGroup,
        BFormInput,
        BForm,
        BButton,
        BFormTextarea,
        BCard,
        vSelect,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            options: [
                { value: 1, text: this.$t("active") },
                { value: 2, text: this.$t("inactive") },
            ],
            job_category: {
                name: "",
                status: null,
                message: "",
            },
        };
    },
     created() {
        if (this.$route.params.id) {
      this.$http.get('/api/career-job-categories/' + this.$route.params.id)
        .then(res => {
          this.job_category = res.data.data;
        })
    }
    },
   methods: {
    addJobCategory() {
        let form = new FormData();
        form.append("id", this.job_category.id || "");
        form.append("name", this.job_category.name);
        form.append("status", this.job_category.status);
        form.append("description", this.job_category.description || "");

        this.$http
            .post("/api/career-job-categories", form)
            .then((response) => {
                if (response.data.success === true) {
                    this.popToast(response, "CheckIcon", "success");
                    setTimeout(() => {
                        this.$router.push({ name: "job_category" });
                    }, 1000); 
                } else {
                    this.popToast(response, "AlertTriangleIcon", "danger");
                }
            })
            .catch((error) => {
                this.popToast(
                    { data: { message: error.message } },
                    "AlertTriangleIcon",
                    "danger"
                );
            });
    },
    popToast(response, icon, variant) {
        this.$toast({
            component: ToastificationContent,
            position: "bottom-right",
            props: {
                title: this.$t(response.data.message),
                icon: icon,
                variant: variant,
            },
        });
    },
},
};
</script>