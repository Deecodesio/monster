<template>
    <b-row>
        <b-col cols="12">
            <b-card>
                <b-row>
                    <b-col md="6">
                        <div class="custom-search d-flex justify-content-start">
                            <b-button
                                variant="primary"
                                :to="{ name: 'add_banner' }"
                            >
                                <i class="fa-solid fa-plus"></i
                                >{{ $t("add_banner") }}
                            </b-button>
                        </div>
                    </b-col>
                    <b-col md="6">
                        <!-- input search -->
                        <div class="custom-search d-flex justify-content-end">
                            <b-form-group>
                                <div class="d-flex align-items-center">
                                    <label class="mr-1">{{
                                        $t("message.seachLabel")
                                    }}</label>
                                    <b-form-input
                                        v-model="searchTerm"
                                        :placeholder="$t('message.seachLabel')"
                                        type="text"
                                        class="d-inline-block"
                                    />
                                </div>
                            </b-form-group>
                        </div>

                        <!-- input search -->
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
                        <div
                            v-if="props.column.field === 'image'"
                            class="text-nowrap"
                        >
                            <div>
                                {{ currentUser }}
                            </div>
                            <b-img
                                :src="props.row.banner_image"
                                rounded
                                fluid
                                id="profile-img"
                                alt="profile photo"
                            />
                        </div>
                      

                        <div
                            v-if="props.column.field === 'banner_type'"
                            class="text-nowrap"
                        >
                            <span class="text-nowrap">
                             {{ getBannerTypeText(props.row.banner_type) }}   
                            </span>
                        </div>

                        <span v-else-if="props.column.field === 'featured'">
                            <b-button
                                :id="'featured' + props.row.banner_id"
                                v-if="props.row.featured == 0"
                                type="submit"
                                variant="outline-warning"
                                class="mr-1"
                                @click="changefeatured(props.row.banner_id)"
                            >
                                {{ $t("Not Featured") }}
                            </b-button>
                            <b-button
                                :id="'nfeatured' + props.row.banner_id"
                                v-if="props.row.featured == 1"
                                type="submit"
                                variant="outline-success"
                                class="mr-1"
                                @click="changefeatured(props.row.banner_id)"
                            >
                                {{ $t(" Featured") }}
                            </b-button>
                            <b-tooltip
                                placement="left"
                                :title="$t('Remove From Featured')"
                                :target="'nfeatured' + props.row.banner_id"
                                v-if="props.row.featured == 1"
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('Set as Featured')"
                                :target="'featured' + props.row.banner_id"
                                v-if="props.row.featured == 0"
                            />
                        </span>

                        <span v-else-if="props.column.field === 'status'">
                            <b-button
                                v-if="props.row.status === 1"
                                type="submit"
                                variant="outline-success"
                                class="mr-1"
                                @click="changedefault(props.row.banner_id)"
                            >
                                {{ $t("active") }}
                            </b-button>
                            <b-button
                                v-if="props.row.status === 2"
                                type="submit"
                                variant="outline-warning"
                                class="mr-1"
                                @click="changedefault(props.row.banner_id)"
                            >
                                {{ $t("inactive") }}
                            </b-button>
                        </span>

                        <!-- Column: Action -->
                        <span v-else-if="props.column.field === 'action'">
                            <feather-icon
                                :id="`invoice-row-${props.row.id}-preview-icon`"
                                icon="EditIcon"
                                size="16"
                                class="mx-1"
                                cursor="pointer"
                                @click="
                                    $router.push({
                                        name: 'edit_banner',
                                        params: { id: props.row.banner_id },
                                    })
                                "
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('edit_banner')"
                                :target="`invoice-row-${props.row.id}-preview-icon`"
                            />

                            <feather-icon
                                :id="`invoice-row-delete-preview-icon`"
                                icon="Trash2Icon"
                                size="16"
                                class="mx-1"
                                cursor="pointer"
                                @click="
                                    showMsgBoxTwo(
                                        props.row.banner_id,
                                        props.row.restaurant_name
                                    )
                                "
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('delete_banner')"
                                :target="`invoice-row-delete-preview-icon`"
                            />
                        </span>

                        <!-- Column: Common -->
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

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "prismjs";
import "prismjs/themes/prism-tomorrow.css";
import Prism from "vue-prism-component";
import { codeColumnSearch } from "./code";

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
        Prism,
        BImg,
        BMedia,
    },
    data() {
        return {
            pageLength: 10,
            dir: false,
            Loading: true,
            codeColumnSearch,
            banner_options: [
                { value: 1, text: this.$t("Home Banner") },
                { value: 2, text: this.$t("Product Page Banner") },
            ],
            columns: [
                {
                    label: this.$t("Stores"),
                    field: "restaurant_name",
                },
                {
                    label: this.$t("city"),
                    field: "city_name",
                },
                {
                    label: this.$t("picture"),
                    field: "image",
                },
                {
                    label: this.$t("Banner Type"),
                    field: "banner_type",
                },
                {
                    label: this.$t("Featured"),
                    field: "featured",
                    tdClass: "featured",
                },

                {
                    label: this.$t("status"),
                    field: "status",
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
        currentUser() {
            return this.$store.state.user;
        },
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
                // eslint-disable-next-line vue/no-side-effects-in-computed-properties
                this.dir = true;
                return this.dir;
            }
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.dir = false;
            return this.dir;
        },
    },
    created() {
        this.$http.get("/admin/banner_list").then((res) => {
            this.rows = res.data;
            this.Loading = false;
        });
    },
    methods: {
       getBannerTypeText(value) {
        const found = this.banner_options.find(opt => opt.value === value);
        return found ? found.text : '';
    },
        showMsgBoxTwo(id, res_name) {
            this.boxTwo = "";
            this.$bvModal
                .msgBoxConfirm(
                    "Please confirm that you want to delete " +
                        res_name +
                        " banner.",
                    {
                        title: "Please Confirm",
                        size: "sm",
                        okVariant: "primary",
                        okTitle: "Yes",
                        cancelTitle: "No",
                        cancelVariant: "outline-secondary",
                        hideHeaderClose: false,
                        centered: true,
                    }
                )
                .then((value) => {
                    if (value == true) {
                        this.$http
                            .get("/admin/delete_banner/" + id)
                            .then((res) => {
                                if (res.data.status == true) {
                                    this.$toast({
                                        component: ToastificationContent,
                                        position: "bottom-right",
                                        props: {
                                            title: this.$t(res.data.message),
                                            icon: "CheckIcon",
                                            variant: "success",
                                        },
                                    });
                                    this.$http
                                        .get("/admin/banner_list")
                                        .then((res) => {
                                            this.rows = res.data;
                                        });
                                }
                            });
                    }
                });
        },
        changedefault(id) {
            this.$http.get("/admin/banner_status/" + id).then((res) => {
                if (res.data.message == "Status Active") {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: this.$t(res.data.message),
                            icon: "CheckIcon",
                            variant: "success",
                        },
                    });
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: this.$t(res.data.message),
                            icon: "HeartIcon",
                            variant: "danger",
                        },
                    });
                }

                this.$http.get("/admin/banner_list").then((res) => {
                    this.rows = res.data;
                });
            });
        },
        changefeatured(id) {
            this.$http.get("/admin/set_featured/" + id).then((res) => {
                if (res.data.status == true) {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: res.data.message,
                            icon: "CheckIcon",
                            variant: "success",
                        },
                    });
                    this.$http.get("/admin/banner_list").then((res) => {
                        this.rows = res.data;
                    });
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: res.data.message,
                            icon: "CheckIcon",
                            variant: "danger",
                        },
                    });
                }
            });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";
@import "~@resources/scss/vue/pages/page-profile.scss";

#profile-img {
    width: 75px;
}

table.vgt-table td {
    vertical-align: middle;
    color: #6e6b7b;
}
</style>
