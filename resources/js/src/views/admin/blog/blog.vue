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
                            selectedBrand ? selectedBrand.name : ""
                        }}</strong>
                        banner
                    </p>
                </b-modal>

                <!-- <b-row>
          <b-col md="6">
            <div class="custom-search d-flex justify-content-start">
              <b-button variant="primary" :to="{ name: 'add_bulk' }">
                <i class="fa-solid fa-plus"></i> {{ $t('add bulk order') }}
              </b-button>
            </div> 
          </b-col>
          
          <b-col md="6">
        
            <div class="custom-search d-flex justify-content-end">
              <b-form-group>
                <div class="d-flex align-items-center">
                  <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                  <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text" class="d-inline-block" />
                </div>
              </b-form-group>
            </div>
           
          </b-col>
        </b-row> -->
                <b-row style="margin-bottom: 10px !important">
                    <!-- Left Section: Bulk Order + Export Buttons -->
                    <b-col md="6">
                        <div
                            class="d-flex justify-content-start align-items-center"
                        >
                            <b-button
                                variant="primary"
                                :to="{ name: 'add_blog' }"
                                class="mr-2"
                                style="box-shadow: none !important"
                            >
                                <i class="fa-solid fa-plus"></i>
                                {{ $t("Add Blog") }}
                            </b-button>

                            <!-- <b-button variant="primary" class="mr-2" @click="exportPDF" style=" box-shadow: none !important;">
                <i class="fa fa-download mr-1"></i> Export PDF
              </b-button>

              <b-button variant="primary" @click="exportExcel" style=" box-shadow: none !important;">
                <i class="fa fa-download mr-1"></i> Export Excel
              </b-button> -->
                        </div>
                    </b-col>

                    <!-- Right Section: Search Input -->
                    <b-col md="6">
                        <div
                            class="d-flex justify-content-end align-items-center"
                        >
                            <label class="mr-1">{{
                                $t("message.seachLabel")
                            }}</label>
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
                        <!-- Column: Name -->
                        <span v-if="props.column.field === 'blogName'">
                            {{ props.row.title }}
                        </span>

                        <div
                            v-if="props.column.field === 'blogImage'"
                            class="text-nowrap"
                        >
                            <b-img
                                :src= "'/blogs/' + props.row.featured_image"
                                rounded
                                fluid
                                width="100%"
                                height="50%"
                                id="blog-img"
                                alt="blog photo"
                            />
                        </div>

                        <!-- Column: Date -->
                        <span v-else-if="props.column.field === 'category'">
                            {{
                                props.row.category
                                    ? props.row.category.name
                                    : ""
                            }}
                        </span>

                        <!-- Column: Product -->
                        <span v-else-if="props.column.field === 'excerpt'">
                            {{ props.row.excerpt }}
                        </span>

                        <span v-else-if="props.column.field === 'status'">
                            <b-button
                                v-if="props.row.status === 1"
                                type="submit"
                                variant="outline-success"
                                class="mr-1"
                                @click="changedefault(props.row.id)"
                            >
                                {{ $t("active") }}
                            </b-button>
                            <b-button
                                v-if="props.row.status === 2"
                                type="submit"
                                variant="outline-warning"
                                class="mr-1"
                                @click="changedefault(props.row.id)"
                            >
                                {{ $t("inactive") }}
                            </b-button>
                        </span>

                        <!-- Column: Action -->
                        <!-- <span v-else-if="props.column.field === 'action'">
              <feather-icon :id="`invoice-row-${props.row.id}-preview-icon`" icon="EditIcon" size="16" class="mx-1"
                cursor="pointer" @click="$router.push({ name: 'edit_Brand', params: { id: props.row.id } })" />
              <b-tooltip placement="left" :title="$t('edit_country')" :target="`invoice-row-${props.row.id}-preview-icon`" />
            </span> -->

                        <span v-else-if="props.column.field === 'action'">
                            <!-- Edit Icon -->
                            <feather-icon
                                :id="`invoice-row-${props.row.slug}-preview-icon`"
                                icon="EditIcon"
                                size="16"
                                class="mx-1"
                                style="cursor: pointer"
                                @click="
                                    $router.push({
                                        name: 'edit_blog',
                                        params: { id: props.row.slug },
                                    })
                                "
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('Edit Blog')"
                                :target="`invoice-row-${props.row.id}-preview-icon`"
                            />

                            <!-- Delete Icon -->
                            <feather-icon
                                :id="`invoice-row-${props.row.id}-delete-icon`"
                                icon="Trash2Icon"
                                size="16"
                                class="mx-1 text-danger"
                                style="cursor: pointer"
                                @click="deleteBlog(props.row)"
                            />
                            <b-tooltip
                                placement="left"
                                :target="`invoice-row-${props.row.id}-delete-icon`"
                            />
                        </span>

                        <!-- Default case -->
                        <span v-else>
                            {{ props.formattedRow[props.column.field] }}
                        </span>
                    </template>

                    <!-- pagination -->
                    <template slot="pagination-bottom" slot-scope="props">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-0 mt-1">
                                <span class="text-nowrap">
                                    {{ $t("message.pagelength") }}
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
                                        <feather-icon
                                            icon="ChevronLeftIcon"
                                            size="18"
                                        />
                                    </template>
                                    <template #next-text>
                                        <feather-icon
                                            icon="ChevronRightIcon"
                                            size="18"
                                        />
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
import { BRow, BCol } from "bootstrap-vue";
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import {
    BImg,
    BMedia,
    BAvatar,
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BCard,
    BButton,
    BTooltip,
} from "bootstrap-vue";
import { VueGoodTable } from "vue-good-table";
import store from "@/store/index";
import { codeColumnSearch } from "./code";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BRow,
        BCol,
        BCardCode,
        VueGoodTable,
        BAvatar,
        BBadge,
        BPagination,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BDropdown,
        BDropdownItem,
        BCard,
        BButton,
        BTooltip,
        BImg,
        BMedia,
    },
    data() {
        return {
            selectedBlog: null,
            pageLength: 10,
            dir: false,
            Loading: true,
            codeColumnSearch,
            columns: [
                {
                    label: this.$t("Blog Name"),
                    field: "blogName",
                },
                {
                    label: this.$t("Blog Image"),
                    field: "blogImage",
                },
                {
                    label: this.$t("Category"),
                    field: "category",
                },
                {
                    label: this.$t("excerpt"),
                    field: "excerpt",
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
        statusVariant() {
            const statusColor = {
                1: "light-success",
                2: "light-warning",
                3: "light-danger",
                4: "light-warning",
                5: "light-info",
            };
            return (status) => statusColor[status];
        },
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
        this.$http.get("/api/getBlogs").then((res) => {
            this.rows = res.data.data;
            this.Loading = false;
        });
    },
    methods: {
        deleteBlog(blog) {
            this.selectedBlog = blog;
            this.$refs.confirmDeleteModal.show();
        },

        confirmDelete() {
            const id = this.selectedBlog.id;
            this.$http
                .delete(`/api/blogs/${id}`)
                .then(() => {
                    this.$bvToast.toast("Blog deleted successfully", {
                        title: "Success",
                        variant: "success",
                        solid: true,
                    });
                    // Refresh blog list
                    this.$http.get("/api/getBlogs").then((res) => {
                        this.rows = res.data.data;
                    });
                })
                .catch(() => {
                    this.$bvToast.toast("Failed to delete blog", {
                        title: "Error",
                        variant: "danger",
                        solid: true,
                    });
                });
        },

        handleAction(row) {
            console.log("Action clicked for row:", row);
        },

        changedefault(id) {
            this.$http.get("/admin/brand_status/" + id).then((res) => {
                this.$toast({
                    component: ToastificationContent,
                    position: "bottom-right",
                    props: {
                        title: this.$t(res.data.message),
                        icon:
                            res.data.message === "Status Active"
                                ? "CheckIcon"
                                : "HeartIcon",
                        variant:
                            res.data.message === "Status Active"
                                ? "success"
                                : "danger",
                    },
                });
                this.$http.get("/admin/brands_list").then((res) => {
                    this.rows = res.data;
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
