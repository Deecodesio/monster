<template>
    <b-row>
        <b-col cols="12">
            <b-card>
                <b-modal
                    id="confirm-delete-modal"
                    ref="confirmDeleteModal"
                    title="Please Confirm"
                    ok-title="Yes"
                    cancel-title="No"
                    ok-variant="danger"
                    cancel-variant="outline-secondary"
                    @ok="confirmDelete"
                >
                    <p class="my-2">
                        Please confirm that you want to delete
                        <strong>{{
                            selectedCategory ? selectedCategory.name : ""
                        }}</strong>
                        category
                    </p>
                </b-modal>

                <b-row style="margin-bottom: 10px !important">
                    <b-col md="6">
                        <div class="d-flex justify-content-start align-items-center">
                            <b-button
                                variant="primary"
                                :to="{ name: 'add_blog_category' }"
                                class="mr-2"
                                style="box-shadow: none !important"
                            >
                                <i class="fa-solid fa-plus"></i>
                                {{ $t("Blog Category") }}
                            </b-button>
                        </div>
                    </b-col>
                    <b-col md="6">
                        <div class="d-flex justify-content-end align-items-center">
                            <label class="mr-1">{{ $t("message.seachLabel") }}</label>
                            <b-form-input
                                v-model="searchTerm"
                                :placeholder="$t('message.seachLabel')"
                                type="text"
                                class="d-inline-block w-50"
                            />
                        </div>
                    </b-col>
                </b-row>

                <!-- table -->
                <vue-good-table
                    :columns="columns"
                    :line-numbers="true"
                    :rows="rows"
                    :rtl="direction"
                    :search-options="{
                        enabled: true,
                        externalQuery: searchTerm,
                    }"
                    :pagination-options="{
                        enabled: true,
                        perPage: pageLength,
                    }"
                >
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field === 'name'">
                            {{ props.row.name }}
                        </span>
                        <span v-else-if="props.column.field === 'message'">
                            {{ props.row.message }}
                        </span>
                        <span v-else-if="props.column.field === 'action'">
                            <b-button size="sm" variant="danger" @click="deleteCategory(props.row)">
                                <feather-icon icon="TrashIcon" size="16" />
                            </b-button>
                        </span>
                    </template>

                    <template slot="pagination-bottom" slot-scope="props">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-0 mt-1">
                                <span class="text-nowrap">
                                    {{ $t("message.description") }}
                                </span>
                                <b-form-select
                                    v-model="pageLength"
                                    :options="['10', '50', '100']"
                                    class="mx-1"
                                    @input="
                                        (value) =>
                                            props.perPageChanged({
                                                currentPerPage: value,
                                            })
                                    "
                                />
                                <span class="text-nowrap">
                                    {{ $t("message.of") }} {{ props.total }}
                                    {{ $t("message.pageText2") }}
                                </span>
                            </div>
                            <div>
                                <b-pagination
                                    :value="1"
                                    :total-rows="props.total"
                                    :per-page="pageLength"
                                    first-number
                                    last-number
                                    align="right"
                                    prev-class="prev-item"
                                    next-class="next-item"
                                    class="mt-1 mb-0"
                                    @input="
                                        (value) =>
                                            props.pageChanged({
                                                currentPage: value,
                                            })
                                    "
                                >
                                    <template #prev-text>
                                        <feather-icon icon="ChevronLeftIcon" size="18" />
                                    </template>
                                    <template #next-text>
                                        <feather-icon icon="ChevronRightIcon" size="18" />
                                    </template>
                                </b-pagination>
                            </div>
                        </div>
                    </template>

                    <div
                        slot="emptystate"
                        style="text-align: center"
                        v-if="Loading"
                    >
                        <b-img src="/admin_loader.svg" fluid alt="Scan" />
                    </div>
                </vue-good-table>
            </b-card>
        </b-col>
    </b-row>
</template>

<script>
import { BRow, BCol, BCard, BButton, BFormInput, BFormSelect, BPagination, BImg } from "bootstrap-vue";
import { VueGoodTable } from "vue-good-table";
import store from "@/store/index";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BRow,
        BCol,
        BCard,
        VueGoodTable,
        BButton,
        BFormInput,
        BFormSelect,
        BPagination,
        BImg,
    },
    data() {
        return {
            selectedCategory: null,
            pageLength: 10,
            dir: false,
            Loading: true,
            columns: [
                {
                    label: this.$t("Blog Name"),
                    field: "name",
                },
                {
                    label: this.$t("Description"),
                    field: "message",
                },
                {
                    label: this.$t("action"),
                    field: "action",
                },
            ],
            rows: [],
            searchTerm: "",
        };
    },
    computed: {
        direction() {
            if (store.state.appConfig.isRTL) {
                this.dir = true;
                return this.dir;
            }
            this.dir = false;
            return this.dir;
        },
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            this.$http.get("/api/blog-categories").then((res) => {
                this.rows = res.data;
                this.Loading = false;
            });
        },
        deleteCategory(category) {
            this.selectedCategory = category;
            this.$refs.confirmDeleteModal.show();
        },
        confirmDelete() {
            const id = this.selectedCategory.id;
            this.$http
                .delete(`/api/blog-categories/${id}`)
                .then(() => {
                    this.$bvToast.toast("Category deleted successfully", {
                        title: "Success",
                        variant: "success",
                        solid: true,
                    });
                    this.fetchCategories();
                })
                .catch(() => {
                    this.$bvToast.toast("Failed to delete category", {
                        title: "Error",
                        variant: "danger",
                        solid: true,
                    });
                });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";
@import "~@resources/scss/vue/pages/page-profile.scss";

.custom-search .b-form-input {
    max-width: 250px;
}
</style>