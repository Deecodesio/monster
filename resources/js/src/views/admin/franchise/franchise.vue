<template>
    <b-row>
        <b-col cols="12">
            <b-card>
                <b-row>
                    <b-col md="6">
                        <div class="custom-search d-flex justify-content-start">
                            <b-button
                                variant="primary"
                                :to="{ name: 'add_franchise' }"
                            >
                                <i class="fa-solid fa-plus"></i>
                                {{ $t("Add Franchise") }}
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
                        ></div>

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
                                </b-badge>
                            </span>
                        </div>

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

                           <!-- Column: Pin code -->
            <span v-else-if="props.column.field === 'pin code'">
              {{ props.row.pincode }}
            </span>

              <span v-else-if="props.column.field === 'mobile no'">
              {{ props.row.phone_1 }}
            </span>

             <span v-else-if="props.column.field === 'mail id'">
              {{ props.row.email_id }}
            </span>

                        <!-- Column: Action -->
                        <span v-else-if="props.column.field === 'action'">
                            <!-- View Icon -->
                            <!-- <feather-icon
                                :id="`invoice-row-${props.row.id}-view-icon`"
                                icon="EyeIcon"
                                size="16"
                                class="mx-1"
                                cursor="pointer"
                                @click="viewRow(props.row)"
                            /> -->
                            <b-tooltip
                                placement="left"
                                :title="$t('view')"
                                :target="`invoice-row-${props.row.id}-view-icon`"
                            />

                            <!-- Edit Icon -->
                            <feather-icon
                                :id="`invoice-row-${props.row.id}-edit-icon`"
                                icon="EditIcon"
                                size="16"
                                class="mx-1"
                                cursor="pointer"
                                @click="
                                    $router.push({
                                        name: 'edit_franchise',
                                        params: { id: props.row.id },
                                    })
                                "
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('edit_country')"
                                :target="`invoice-row-${props.row.id}-edit-icon`"
                            />

                            <!-- Delete Icon -->
                            <feather-icon
                                :id="`invoice-row-${props.row.id}-delete-icon`"
                                icon="Trash2Icon"
                                size="16"
                                class="mx-1 text-danger"
                                cursor="pointer"
                                @click="deleteRow(props.row)"
                            />
                            <b-tooltip
                                placement="left"
                                :title="$t('delete')"
                                :target="`invoice-row-${props.row.id}-delete-icon`"
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
            pageLength: 10,
            dir: false,
            Loading: true,
            codeColumnSearch,
            columns: [
                //  {
                //   label: 'Logo',
                //   field: 'image',

                // },
                {
                    label: this.$t("name"),
                    field: "name",
                },
                {
                    label: this.$t("address"),
                    field: "address",
                },
                // {
                //     label: this.$t("date"),
                //     field: "date",
                // },
                {
                    label: this.$t("taluk"),
                    field: "taluk",
                },
                {
                    label: this.$t("District"),
                    field: "district",
                },
                {
                    label: this.$t("Pin Code"),
                    field: "pin code",
                },
                {
                    label: this.$t("Mobile No"),
                    field: "mobile no",
                },
                {
                    label: this.$t("Mail ID"),
                    field: "mail id",
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
    methods: {
        handleedit(id) {
            this.$router.push({
                name: "add_Brand",
                params: { id: data.item.id },
            });
        },
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
        
        this.$http.get("/api/franchises").then((res) => {
            this.rows = res.data.data;
            this.Loading = false;
        });
    },
    methods: {
        changedefault(id) {
            this.$http.get("/api/franchise_status/" + id).then((res) => {
                if (res.data.message === "Status Active") {
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

                this.$http.get("/admin/brands_list").then((res) => {
                    this.rows = res.data;
                });
            });
        },
        viewRow(row) {
            alert("View: " + JSON.stringify(row));
        },
        deleteRow(row) {
            this.$http
                .delete("/api/franchises/" + row.id)
                .then((res) => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: this.$t(res.data.message),
                            icon: "CheckIcon",
                            variant: "success",
                        },
                    });
                    this.$http.get("/api/franchises").then((res) => {
                        this.rows = res.data.data;
                    });
                })
                .catch((error) => {
                    this.$toast({
                        component: ToastificationContent,
                        position: "bottom-right",
                        props: {
                            title: this.$t("Failed to delete brand."),
                            icon: "AlertTriangleIcon",
                            variant: "danger",
                        },
                    });
                    console.error(error);
                });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";
@import "~@resources/scss/vue/pages/page-profile.scss";

#brand-img {
    width: 75px;
}
</style>
