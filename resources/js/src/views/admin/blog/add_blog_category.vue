<template>
    <b-card>
        <b-form @submit.prevent="addBlogCategory">
            <b-row>
                <b-col md="12">
                    <b-form-group :label="$t('name')">
                        <b-form-input
                            id="name"
                            :placeholder="$t('name')"
                            v-model="blog_category.name"
                            required
                        />
                    </b-form-group>
                </b-col>
            
                <b-col md="12">
                    <b-form-group :label="$t('description')">
                        <b-form-textarea
                            id="message"
                            v-model="blog_category.message"
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
            blog_category: {
                name: "",
                status: null,
                message: "",
            },
        };
    },
   methods: {
    addBlogCategory() {
        let form = new FormData();
        form.append("name", this.blog_category.name);
        form.append("status", this.blog_category.status);
        form.append("description", this.blog_category.message);

        this.$http
            .post("/api/blog-categories", form)
            .then((response) => {
                if (response.data.success === true) {
                    this.popToast(response, "CheckIcon", "success");
                    setTimeout(() => {
                        this.$router.push({ name: "blog_category_list" });
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