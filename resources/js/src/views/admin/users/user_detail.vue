<template>
    <b-tabs
        vertical
        content-class="col-12 col-md-9 mt-1 mt-md-0"
        pills
        nav-wrapper-class="col-md-3 col-12"
        nav-class="nav-left"
    >
        <b-tab active>
            <template #title>
                <feather-icon icon="UserIcon" size="18" class="mr-50" />
                <span class="font-weight-bold">{{$t('General')}}</span>
            </template>

            <b-card>
                <!-- media -->
                <b-media no-body>
                    <b-media-aside>
                        
                            <b-img
                               
                                rounded
                                :src="user_detail.avatar"
                                height="80"
                            />
                       
                    </b-media-aside>
                </b-media>
              
                <b-form class="mt-2">
                    <b-row>
                        <b-col sm="6">
                            <b-form-group
                                label="Username"
                                label-for="account-username"
                            >
                                <b-form-input
                                    placeholder="Username"
                                    name="username"
                                    v-model="user_detail.name"
                                    plaintext
                                />
                            </b-form-group>
                        </b-col>

                        <b-col sm="6">
                            <b-form-group
                                label="E-mail"
                                label-for="account-e-mail"
                            >
                                <b-form-input
                                    name="email"
                                    placeholder="Email"
                                    v-model="user_detail.email"
                                    plaintext
                                />
                            </b-form-group>
                        </b-col>
                        <b-col sm="6">
                            <b-form-group label="Phone" label-for="phone">
                                <b-form-input
                                    name="phone"
                                    placeholder="phone"
                                    v-model="user_detail.phone"
                                    plaintext
                                />
                            </b-form-group>
                        </b-col>

                        <b-col sm="6">
                            <b-form-group
                                label="Wallet Balance"
                                label-for="wallet"
                            >
                                <b-form-input
                                    name="wallet"
                                    placeholder="wallet"
                                    v-model="user_detail.wallet_amount"
                                    plaintext
                                />
                            </b-form-group>
                        </b-col>

                        <b-col sm="6">
                            <b-form-group
                                label="Created At"
                                label-for="Created"
                            >
                                <b-form-input
                                    name="Created"
                                    placeholder="Created"
                                    v-model="user_detail.create"
                                    plaintext
                                />
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-form>
            </b-card>
        </b-tab>

        <b-tab>
            <template #title>
                <feather-icon icon="ArchiveIcon" size="18" class="mr-50" />
                <span class="font-weight-bold">{{$t('Orders')}}</span>
            </template>
            <b-card>
                <h4>{{$t('Total Orders')}} : {{ totalRows }}</h4>
                <b-col cols="12">
                    <b-table
                        responsive="sm"
                        :items="orders"
                        :fields="fields"
                        :per-page="perPage"
                        :current-page="currentPage"
                        selectable
                        @row-selected="onRowSelected"
                    >
                        <template #cell(index)="data">
                            {{ data.index + 1 }}
                        </template>
                        <template #cell(bill_amount)="data">
                            {{ setting.currency_symbol }} {{ data.value }} 
                        </template>
                        <template #cell(status)="data">
                            <b-badge :variant="status[1][data.value]">
                                {{ status[0][data.value] }}
                            </b-badge>
                        </template>
                        <template #cell(create)="data">
                            {{ data.value }}
                        </template>
                    </b-table>
                </b-col>
                <b-col cols="12">
                    <b-row>
                        <b-col cols="6">
                            <b-form-group class="mb-0">
                                <label class="d-inline-block text-sm-left mr-50"
                                    >{{$t('Per page')}}</label
                                >
                                <b-form-select
                                    id="perPageSelect"
                                    v-model="perPage"
                                    size="sm"
                                    :options="pageOptions"
                                    class="w-50"
                                />
                            </b-form-group>
                        </b-col>
                        <b-col cols="6">
                            <b-pagination
                                v-model="currentPage"
                                :total-rows="totalRows"
                                :per-page="perPage"
                                align="center"
                                size="sm"
                                class="my-0"
                            />
                        </b-col>
                    </b-row>
                </b-col>
            </b-card>
        </b-tab>

        <!-- <b-tab>
            <template #title>
                <feather-icon icon="InfoIcon" size="18" class="mr-50" />
                <span class="font-weight-bold">Wallet</span>
            </template>
        </b-tab> -->
    </b-tabs>
</template>

<script>
import { BTabs, BTab } from "bootstrap-vue";
import {
    BFormFile,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
    BPagination,
    BFormSelect,
    BBadge,
} from "bootstrap-vue";
import { BTable } from "bootstrap-vue";
import store from '@/store/index'

export default {
    components: {
        BTabs,
        BTab,
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
        BTable,
        BPagination,
        BFormSelect,
        BBadge,
    },
    data() {
        return {
            options: {},
            user_detail: {},
            orders: {},
            wallet: {},
            setting: {},
            perPage: 10,
            pageOptions: [10, 15, 20],
            totalRows: 10,
            currentPage: 1,
            demo: false,
            userData: JSON.parse(localStorage.getItem('userData')),

            fields: [
                "index",
                { key: "order_id", label: this.$t("Order Id") },
                { key: "restaurant_name", label: this.$t("Store") },
                { key: "status", label: this.$t("Order Status") },
                { key: "bill_amount", label: this.$t("Total") },
                { key: "create", label: this.$t("Ordered On") },

            ],
            status: [
                {
                    0: this.$t("New"),
                    1: this.$t("Processing"),
                    2: this.$t("Processing"),
                    3: this.$t("Pickup"),
                    4: this.$t("Pickup"),
                    5: this.$t("Pickup"),
                    6: this.$t("Delivered"),
                    7: this.$t("Delivered"),
                    9: this.$t("Cancelled"),
                    10: this.$t("Cancelled"),
                    11: this.$t("Cancelled"),
                    12: this.$t("Cancelled"),
                    13: this.$t("Cancelled"),
                    14: this.$t("Cancelled"),
                    "-2": this.$t("Failed"),
                    "-1": this.$t("Abandon"),
                },
                {
                    0: "light-primary",
                    1: "light-warning",
                    2: "light-warning",
                    3: "light-warning",
                    4: "light-warning",
                    5: "light-warning",
                    6: "light-success",
                    7: "light-success",
                    9: "light-danger",
                    10: "light-danger",
                    11: "light-danger",
                    12: "light-danger",
                    13: "light-danger",
                },
            ],
        };
    },
    beforeCreate() {
        
        this.$http
            .get("/admin/user_data/" + this.$route.params.id)
            .then((res) => {
                this.user_detail = res.data.user_detail;
                this.orders = res.data.orders;
                this.wallet = res.data.wallet;
                this.totalRows = res.data.orders.length;
            });

        this.$http.get("/admin/get_currency").then((res) => {
            this.setting = res.data;
        });
    },
   
    created() {
    this.$http.get('/admin/check_demo/' + this.userData.email)
      .then(res => {
        if (res.data.demo == true) {
          this.demo = true
          console.log(this.user_detail);
          this.user_detail.phone = "**********"
          this.user_detail.email = "**********"

        }
      })
   
  },
    methods: {
    onRowSelected(items) {
      console.log(items[0].id)
      this.$router.push({ name: 'view_order', params: { id: items[0].id }});

    },
    
  },
};
</script>
