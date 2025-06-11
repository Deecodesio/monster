<template>
    <b-card>
        <b-form @submit.prevent="addBlog">
            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('name')">
                        <b-form-input
                            id="name"
                            :placeholder="$t('Blog Name')"
                            v-model="blog.title"
                            :required="!blog.title"
                        />
                    </b-form-group>

                    <b-form-group label="Category">
                        <v-select
                            id="state"
                            v-model="blog.category_id"
                            :options="categoryOptions"
                            label="name"
                            :reduce="(sel) => sel.id"
                            :placeholder="$t('Select Category')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :required="!blog.category_id"
                        />
                    </b-form-group>
                    <b-form-group :label="$t('Blog image')">
                        <b-form-file
                            v-model="blog.featured_image"
                            drop-placeholder="Drag your image"
                            @change="onFileChange1"
                            accept=".jpg, .png, .jpeg"
                        />
                        <b-form-group>
                            <b-img
                                :src="url1 ? url1 : ('/blogs/' + blog.featured_image)"
                                rounded
                                fluid
                                id="avatar-img"
                                alt="category photo"
                                v-if="blog.featured_image"
                            />
                            <b-img
                                src="/no_image.png"
                                id="avatar-img"
                                fluid
                                alt="Scan"
                                v-else
                            />
                        </b-form-group>

                        <!-- <b-form-file
                            v-model="blog.featured_image"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                            @change="onFileChange1"
                            accept=".jpg, .png, .jpeg"  
                        /> -->
                    </b-form-group>
                    <b-form-group :label="$t('Excerpt')">
                        <b-form-textarea
                            id="excerpt"
                            v-model="blog.excerpt"
                            :placeholder="$t('Excerpt')"
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
                    <div style="padding-bottom: calc(0.438rem + 1px)">
                        Blog Details
                    </div>
                    <b-row>
                        <b-col md="12">
                            <b-form-group>
                                <quill-editor
                                    v-model="blog.content"
                                    :options="snowOption"
                                    style="height: 275px"
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
import vSelect from "vue-select";
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
        vSelect,
    },
    watch: {
        "blog.content"(val) {
            console.log("Quill updated:", val);
        },
    },
    data() {
        return {
            url1: null,
            blog: {
                title: "",
                created_by: "",
                status: "",
                featured_image: null,
                category_id: "",
                content: "",
            },
            snowOption: {
                theme: "snow",
            },
            loading: false,
            categoryOptions: [],
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
    created() {
        this.$http.get("/api/blog-categories").then((res) => {
            this.categoryOptions = res.data.data;
        });
        console.log(this.$route.params);
        console.log(this.$route.params.id);
        if (this.$route.params.id) {
            this.$http
                .get("/api/blogs/" + this.$route.params.id)
                .then((res) => {
                    this.blog = res.data;
                    console.log("content", this.blog.content);
                });
        }
    },
    methods: {
        onFileChange1(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.url1 = reader.result;
            };
        },

        addBlog() {

            console.log("Adding blog with data:", this.blog.featured_image);
            // return null;
            
            this.loading = true;
            const data = new FormData();
            data.append("id", this.blog.id || "");
            data.append("title", this.blog.title);
            data.append("created_by", localStorage.admin_id);
            data.append("status", this.blog.status);
            data.append("content", this.blog.content);
            data.append("excerpt", this.blog.excerpt);
            data.append("featured_image", this.blog.featured_image);
            data.append("category_id", this.blog.category_id);

            this.$http
                .post("/api/blogs", data)
                .then((response) => {
                    console.log("Response from API:", response);
                    if (response.data.success == true) {
                        this.$router.push({ name: "blog" });
                        this.popToast(response, "CheckIcon", "success");
                    } else {
                        this.popToast(response, "AlertTriangleIcon", "danger");
                    }
                })
                .catch((error) => {
                    this.$toast({
                        title: this.$t("Failed to save blog."),
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
#avatar-img {
    width: 9.92rem;
}
.rose-border {
    border: 1px solid #e91e63;
    box-shadow: none;
    border-radius: 6px;
}
.card-editor {
    margin-left: 115px;
}
/* .quill-editor {
    min-height: 200px;
    max-height: 300px;
    overflow-y: auto;
} */
.ql-editor {
    min-height: 180px;
    max-height: 260px;
    overflow-y: auto;
}
</style>
