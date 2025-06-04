<template>
    <b-row>
        <b-col cols="12">
            <b-card>
                <b-row>
                    <b-col md="8" style="display: flex">
                        <!-- <div class="custom-search justify-content-start">
                            <b-button
                                variant="primary"
                                :to="{ name: 'add_product' }"
                            >
                                <i class="fa-solid fa-plus"></i>{{ $t("add") }}
                                {{ $t("product") }}
                            </b-button>
                        </div> -->

                        <div
                            class="custom-search justify-content-start"
                            style="margin-left: 1%"
                        >
                            <b-button variant="primary" @click="upload()">
                                <i class="fa-solid fa-plus"></i>
                                {{ $t("upload") }} {{ $t("CSV") }}
                            </b-button>
                        </div>

                        <div
                            class="custom-search justify-content-start"
                            style="margin-left: 1%"
                        >
                            <b-button variant="primary" @click="generate()">
                                <i class="fa-solid fa-plus"></i>
                                {{ $t("generate") }} {{ $t("CSV") }}
                            </b-button>
                        </div>

                        <div
                            class="custom-search justify-content-start"
                            style="margin-left: 1%"
                        >
                            <a
                                :href="this.downlodPath"
                                id="download"
                                style="display: none"
                                download
                            >
                                <b-button variant="primary">
                                    <i class="fa-solid fa-plus"></i>
                                    {{ $t("download") }} {{ $t("CSV") }}
                                </b-button>
                            </a>
                        </div>
                    </b-col>

                    <b-col md="4">
                        <!-- input search -->
                        <div class="custom-search d-flex justify-content-end">
                            <b-form-group>
                                <div class="d-flex align-items-center">
                                    <label class="mr-1">{{
                                        $t("message.seachLabel")
                                    }}</label>
                                    <b-form-input
                                        v-model="searchTerm"
                                        placeholder="Search"
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
                            v-if="props.column.field === 'product_photo'"
                            class="text-nowrap"
                        >
                            <b-img
                                :src="props.row.image"
                                rounded
                                fluid
                                id="product-img"
                                alt="product photo"
                                width="100%"
                                height="50%"
                                style="align-items: center"
                            />
                        </div>
                        <div
                            v-if="props.column.field === 'prices'"
                            class="text-nowrap"
                        >
                            <span class="text-nowrap"
                                >{{ setting.value }} {{ props.row.new_price }}</span                            >
                        </div>

                        <div
                            v-if="props.column.field === 'taxs'"
                            class="text-nowrap"
                        >
                            <span class="text-nowrap">{{ props.row.tax }}</span>
                        </div>

                        <div
                            v-if="props.column.field === 'order_id'"
                            class="text-nowrap"
                        >
                            <span class="text-nowrap">{{
                                props.row.order_id
                            }}</span>
                            <br />
                            <span class="text-nowrap">
                                <b-badge
                                    :variant="statusVariant(props.row.status)"
                                >
                                    {{ props.row.status }}
                                </b-badge></span
                            >
                        </div>

                        <!-- Column: Status -->
                        <!-- <span v-else-if="props.column.field === 'status'">
          <b-badge :variant="statusVariant(props.row.status)">
            {{ props.row.status }}
          </b-badge>
        </span> -->
                        <span v-else-if="props.column.field === 'f_status'">
                            <b-button
                                v-if="props.row.f_status === 1"
                                type="submit"
                                variant="outline-success"
                                class="mr-1"
                            >
                                {{ $t("active") }}
                            </b-button>
                            <b-button
                                v-if="props.row.f_status === 0"
                                type="submit"
                                variant="outline-warning"
                                class="mr-1"
                            >
                                {{ $t("inactive") }}
                            </b-button>
                        </span>

                        <span v-else-if="props.column.field === 'availablitys'">
                            <b-button
                                :id="'Available' + props.row.food_id"
                                v-if="
                                    props.row.stock_status == 0 &&
                                    props.row.f_status == 1
                                "
                                type="submit"
                                variant="outline-success"
                                class="mr-1"
                                
                            >
                                {{ $t("available") }}
                            </b-button>
                            <b-button
                                :id="'Sold_out_today' + props.row.food_id"
                                v-if="
                                    props.row.stock_status == 1 &&
                                    props.row.f_status == 1
                                "
                                type="submit"
                                variant="outline-warning"
                                class="mr-1"
                                
                            >
                                {{ $t("sold") }} {{ $t("out") }}
                                {{ $t("today") }}
                            </b-button>
                            <b-tooltip
                                :title="
                                    $t('set') +
                                    ' ' +
                                    $t('as') +
                                    ' ' +
                                    $t('Available') +
                                    ' ' +
                                    $t('sold')
                                "
                                :target="'Sold_out_today' + props.row.food_id"
                                placement="left"
                            />
                            <b-tooltip
                                :title="
                                    $t('set') +
                                    ' ' +
                                    $t('as') +
                                    ' ' +
                                    $t('sold') +
                                    ' ' +
                                    $t('out') +
                                    ' ' +
                                    $t('today')
                                "
                                :target="'Available' + props.row.food_id"
                                placement="left"
                            />
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
                                        name: 'edit_product',
                                        params: { id: props.row.food_id },
                                    })
                                "
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('edit') + ' ' + $t('product')"
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
                                        props.row.food_id,
                                        props.row.name
                                    )
                                "
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('delete') + ' ' + $t('product')"
                                :target="`invoice-row-delete-preview-icon`"
                            />
                        </span>

                        <span v-else-if="props.column.field === 'featured'">
                            <b-button
                                :id="'featured' + props.row.food_id"
                                v-if="props.row.featured == 0"
                                type="submit"
                                variant="outline-warning"
                                class="mr-1"
                            >
                                {{ $t("Not Featured") }}
                            </b-button>
                            <b-button
                                :id="'nfeatured' + props.row.food_id"
                                v-if="props.row.featured == 1"
                                type="submit"
                                variant="outline-success"
                                class="mr-1"
                            >
                                {{ $t(" Featured") }}
                            </b-button>
                            <b-tooltip
                                :title="$t('Remove From Featured')"
                                :target="'nfeatured' + props.row.food_id"
                                placement="left"
                            />
                            <b-tooltip
                                :title="$t('Set as Featured')"
                                :target="'featured' + props.row.food_id"
                                placement="left"
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

            <b-modal
                ref="my-modal"
                id="modal-code"
                ok-title="Submit"
                centered
                :title="$t('product') + ' ' + $t('upload')"
                @show="resetModal"
                @hidden="resetModal"
                @ok="upload_file"
            >
                <form ref="form" @submit.prevent="upload_file">
                    <b-form-group
                        :label="$t('file') + ' ' + $t('upload') + ':'"
                    >
                        <b-form-file
                            v-model="user_info.csv_file"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                        />
                    </b-form-group>
                </form>
            </b-modal>
        </b-col>
    </b-row>
</template>

<script>
import { BRow, BCol } from "bootstrap-vue";
import BCardCode from "@@core/components/b-card-code/BCardCode.vue";
import {
    BImg,
    BMedia,
    BAvatar,
    BBadge,
    BFormFile,
    BPagination,
    BButton,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BCard,
    BTooltip,
} from "bootstrap-vue";
import { VueGoodTable } from "vue-good-table";
import store from "@@/store/index";

import ToastificationContent from "@@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BRow,
        BCol,
        BCardCode,
        VueGoodTable,
        BAvatar,
        BBadge,
        BButton,
        BPagination,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BDropdown,
        BDropdownItem,
        BCard,
        BTooltip,
        BImg,
        BMedia,
        BFormFile,
    },

    created() {
        this.user_info.id = localStorage.id;
        this.$http.post("/store/product_list/"+ localStorage.id).then((res) => {
            this.rows = res.data.data;
            console.log("rows");
            console.log(this.rows);
            console.log(res.data.business);
            this.downlodPath = res.data.download_path;
            this.layout = res.data.business;
            this.Loading = false;

            if (this.layout == 2) {
                // this.$set(this.columns[3], "hidden", true);

                this.menu_col = true;
                // this.cate_col = false;
                // this.featured_col = true;
            } else {
                this.$set(this.columns[3], "hidden", true);
                this.menu_col = false;
                // this.cate_col = true;
                // this.featured_col = false;
            }
        });

        this.$http.get("/store/get_currency").then((res) => {
            this.setting = res.data;
        });
    },
    data() {
        return {
            publicPath: process.env.BASE_URL,
            pageLength: 10,
            dir: false,
            downlodPath: "",
            user_info: {},
            Loading: true,
            columns: [
                {
                    label: this.$t("name"),
                    field: "name",
                    tdClass: "names",
                },
                {
                    label: this.$t("Product Photo"),
                    field: "product_photo",
                },
                {
                    label: this.$t("Business Category"),
                    field: "business_name",
                },
                {
                    label: this.$t("menu") + " " + this.$t("name"),
                    field: "menu_name",
                    hidden: this.menu_col,
                },
                // {
                //     label: this.$t("category"),
                //     field: "category_name",
                //     hidden: this.cate_col,
                // },
                 {
                    label: this.$t("tax") + " " + this.$t("in") + "%",
                    field: "taxs",
                },
               
                {
                    label: this.$t("price"),
                    field: "prices",
                },

                {
                    label: this.$t("status"),
                    field: "f_status",
                },

                {
                    label: this.$t("availablity"),
                    field: "availablitys",
                    tdClass: "avail",
                },

                {
                    label: this.$t("Featured"),
                    field: "featured",
                },

                // {
                //     label: this.$t("action"),
                //     field: "action",
                //     tdClass: "widths",
                // },
            ],
            rows: [],
            searchTerm: "",
            setting: {},
            layout: "",
            menu_col: false,
            cate_col: false,
        };
    },
    computed: {
        statusVariant() {
            const statusColor = {
                1: "light-primary",
                2: "light-success",
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

    methods: {
        resetModal() {},
        changefeatured(id) {
            this.$http.get("/store/set_featured/" + id).then((res) => {
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
                    this.user_info.id = localStorage.id;
                    this.$http
                        .post("/store/product_list", this.user_info)
                        .then((res) => {
                            this.rows = res.data.data;
                            //   console.log(this.rows);
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
        upload() {
            this.$refs["my-modal"].show();
        },

        upload_file() {
            let data = new FormData();
            data.append("id", localStorage.id);
            data.append("csv_file", this.user_info.csv_file);

            this.$http.post("/store/upload_product_csv", data).then((res) => {
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
                    this.user_info.id = localStorage.id;
                    this.$http
                        .post("/store/product_list", this.user_info)
                        .then((res) => {
                            this.rows = res.data.data;
                            //   console.log(this.rows);
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

        generate() {
            this.user_info.id = localStorage.id;
            this.$http
                .post("/store/generate_product_csv", this.user_info)
                .then((res) => {
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
                        var down = (document.getElementById(
                            "download"
                        ).style.display = "block");

                        this.user_info.id = localStorage.id;
                        this.$http
                            .post("/store/product_list", this.user_info)
                            .then((res) => {
                                this.rows = res.data.data;
                                //   console.log(this.rows);
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

        showMsgBoxTwo(id, res_name) {
            this.boxTwo = "";
            this.$bvModal
                .msgBoxConfirm(
                    "Please confirm that you want to delete " + res_name + "",
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
                            .get("/store/delete_product/" + id)
                            .then((res) => {
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
                                    this.user_info.id = localStorage.id;
                                    this.$http
                                        .post(
                                            "/store/product_list",
                                            this.user_info
                                        )
                                        .then((res) => {
                                            this.rows = res.data.data;
                                            //   console.log(this.rows);
                                        });
                                }
                            });
                    }
                });
        },

        changedefault(id) {
            this.$http.get("/store/product-status-toggle/" + id).then((res) => {
                if (res.data.message === "Status Active") {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: res.data.message,
                            icon: "CheckIcon",
                            variant: "success",
                        },
                    });
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: res.data.message,
                            icon: "HeartIcon",
                            variant: "danger",
                        },
                    });
                }
                this.user_info.id = localStorage.id;
                this.$http
                    .post("/store/product_list", this.user_info)
                    .then((res) => {
                        this.rows = res.data.data;
                        //   console.log(this.rows);
                    });
            });
        },

        changedefaultAvailablity(id) {
            this.$http.get("/store/product-stock-toggle/" + id).then((res) => {
                if (res.data.message === "Product Available") {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: res.data.message,
                            icon: "CheckIcon",
                            variant: "success",
                        },
                    });
                } else {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: res.data.message,
                            icon: "HeartIcon",
                            variant: "danger",
                        },
                    });
                }
                this.user_info.id = localStorage.id;
                this.$http
                    .post("/store/product_list", this.user_info)
                    .then((res) => {
                        this.rows = res.data.data;
                        //   console.log(this.rows);
                    });
            });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";

.widths {
    min-width: 130px;
    text-align: center;
}

.avail {
    min-width: 200px;
    text-align: center;
}

.names {
    min-width: 200px;
    text-align: center;
}
</style>
