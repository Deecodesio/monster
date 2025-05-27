<template>
    <div>
        <div v-if="!view_order_page">
            <b-card class="pad0">
                <!-- <b-col
            v-if="!informationData.length"
            xxl="12"
            lg="12"
            md="12"
            cols="12" class="pad0"
        >
                <h4>{{$t("No Orders Found")}}</h4>
   
        </b-col> -->
                <div v-if="!informationData.length" class="text-center"
                    style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
                    <lottie-player src="/animations/empty-box.json" background="#FFFDFD" speed="1"
                        style="width: 300px; height: 300px" loop autoplay></lottie-player>
                    <h4>{{ $t("No Orders Found") }}</h4>
                </div>

                <b-col xxl="12" lg="12" md="12" cols="12" v-for="(data, index) in informationData" :key="index"
                    class="pt-3 ">

                    <div class="" style="border-bottom:1px solid #e6e6e6; border-radius: 0px;">
                        <b-row>
                            <b-col cols="12" md="12" lg="2" sm="12" class="">


                                <b-img class="bor" :src="data.restaurant_image" blank-color="#ccc" width="100" />

                            </b-col>

                            <b-col cols="12" md="12" lg="9">
                                <h4>
                                    {{ data.restaurant_name }}
                                    <b-badge :variant="statusVariant(2)">
                                        {{ data.layout_name }}
                                    </b-badge>
                                </h4>
                                <div style="display: flex;" v-if="data.layout == 3">

                                    <b-card-text class="textp">
                                        <h5 style="font-weight: 900;">
                                            {{ $t("Pickup Location") }}
                                        </h5>
                                        <feather-icon icon="MapPinIcon" /> {{ data.pickup_address }}
                                    </b-card-text>


                                    <b-card-text class="textp">
                                        <h5 style="font-weight: 900;">
                                            {{ $t("Drop Location") }}
                                        </h5>
                                        <feather-icon icon="MapPinIcon" /> {{ data.delivery_address }}
                                    </b-card-text>

                                </div>

                                <b-card-text class="textp" v-else>
                                    <feather-icon icon="MapPinIcon" /> {{ data.delivery_address }}
                                </b-card-text>
                                <b-card-text class="textp">
                                    <feather-icon icon="ArchiveIcon" /> {{ $t("Order Id") }}: {{ data.order_id }}
                                </b-card-text>
                                <b-card-text class="textp">
                                    <feather-icon icon="ClockIcon" /> {{ data.ordered_on }}
                                </b-card-text>
                                <b-card-text class="textp">
                                    <feather-icon icon="DollarSignIcon" />
                                    <span v-if="data.is_cod === 1">
                                        {{ $t("Cash-on-Delivery") }}
                                    </span>
                                    <span v-else>
                                        {{ $t("Online Payment") }}
                                    </span>
                                    <h4 style="padding: 30px 0px">{{ $t("Total Paid") }} :{{ data.bill_amount }} </h4>
                                </b-card-text>
                            </b-col>

                            <b-col cols="12" md="12" lg="2">



                            </b-col>
                            <b-col cols="12" md="12" lg="9" class="pad0">
                                <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-success"
                                    class="mb-2" @click="view_order(data.request_id)">
                                    {{ $t("View") }} {{ $t("Order") }}
                                </b-button>

                                <!-- <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-danger" class="mb-2"
                  @click="cancel_modal(data.request_id)" v-if="data.status < 5">
                  {{ $t("Cancel") }} {{ $t("Order") }}
                </b-button> -->
                                <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-info" class="mb-2"
                                    @click="$router.push({ name: 'pickupdrop_trackorder', params: { id: data.request_id } })"
                                    v-if="data.layout == 3">
                                    {{ $t("Track") }} {{ $t("Order") }}
                                </b-button>
                                <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="outline-info" class="mb-2"
                                    @click="$router.push({ name: 'trackorder', params: { id: data.request_id } })"
                                    v-else>
                                    {{ $t("Track") }} {{ $t("Order") }}
                                </b-button>
                            </b-col>


                        </b-row>
                    </div>

                </b-col>

            </b-card>



        </div>
        <div v-if="view_order_page">

            <section class="invoice-preview-wrapper">

                <!-- Alert: No item found -->
                <b-alert variant="danger" :show="req_details === undefined">
                    <h4 class="alert-heading">
                        {{ $t("Error fetching Order details") }}

                    </h4>

                </b-alert>

                <b-row v-if="req_details" class="invoice-preview">

                    <!-- Col: Left (Invoice Container) -->
                    <b-col cols="12" xl="9" md="8">
                        <b-card no-body class="invoice-preview-card">
                            <!-- Header -->
                            <b-card-body class="invoice-padding pb-0">

                                <div
                                    class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">

                                    <!-- Header: Left Content -->
                                    <div>
                                        <div class="logo-wrapper">
                                            <!-- <logo /> -->
                                            <h3 class="text-primary invoice-logo">
                                                Monster Foods
                                            </h3>
                                        </div>
                                        <p class="card-text mb-25">
                                            {{ $t("Order Status") }} : {{ order_status }}
                                        </p>
                                        <p class="card-text mb-25">
                                            {{ $t("Ordered On") }} : {{ req_details.ordered_time }}
                                        </p>
                                    </div>

                                    <!-- Header: Right Content -->
                                    <div class="mt-md-0 mt-2">
                                        <h4 class="invoice-title">
                                            {{ $t("Order Id") }}
                                            <span class="invoice-number">#{{ req_details.order_id }}</span>
                                        </h4>
                                        <div class="invoice-date-wrapper">
                                            <!-- <p class="invoice-date-title">
                Ordered Time
              </p>
              <p class="invoice-date">
               {{ req_details.created_at }}
              </p> -->
                                        </div>

                                    </div>
                                </div>
                            </b-card-body>

                            <!-- Spacer -->
                            <hr class="hrcolor">

                            <!-- Invoice Client & Payment Details -->
                            <b-card-body v-if="req_details" class="invoice-padding pt-0">
                                <b-row class="invoice-spacing">

                                    <!-- Col: Invoice To -->
                                    <b-col cols="12" xl="6" class="p-0">
                                        <h6 class="mb-2">
                                            {{ $t("Customer Details") }}:

                                        </h6>
                                        <h6 class="mb-25">
                                            {{ req_details.user_name }}

                                        </h6>
                                        <p class="card-text mb-25">
                                            {{ req_details.delivery_address }}
                                        </p>

                                        <p class="card-text mb-25">
                                            {{ req_details.user_phone }}
                                        </p>
                                        <p class="card-text mb-0">
                                            {{ req_details.user_email }}
                                        </p>
                                    </b-col>

                                    <!-- Col: Payment Details -->
                                    <!-- <b-col xl="6" cols="12" class="p-0">
                    <h6 class="mb-2">
                      {{ $t("Restaurant Details") }}:
                    </h6>
                    <h6 class="mb-25">
                      {{ req_details.restaurant_name }}

                    </h6>
                    <p class="card-text mb-25">
                      {{ req_details.address }}
                    </p>

                    <p class="card-text mb-25">
                      +{{ req_details.contact_country_calling_code }}{{ req_details.contact }}
                    </p>
                    <p class="card-text mb-0">
                      {{ req_details.restaurants_email }}
                    </p>
                  </b-col> -->
                                </b-row>
                            </b-card-body>

                            <!-- Invoice Description: Table -->
                            <b-table-lite responsive :items="food_details"
                                :fields="['Name', 'description', 'quantity', 'price']">
                                <template #cell(taskDescription)="data">
                                    <b-card-text class="font-weight-bold mb-25">
                                        {{ data.item.Name }}
                                    </b-card-text>
                                    <b-card-text class="text-nowrap">
                                        {{ data.item.description }}
                                    </b-card-text>
                                </template>
                            </b-table-lite>

                            <!-- Invoice Description: Total -->
                            <b-card-body class="invoice-padding pb-0">
                                <b-row>
                                    <b-col cols="12" md="6" class="mt-md-0 mt-3" order="2" order-md="1">
                                        <b-card-text class="mb-0">
                                            <span class="font-weight-bold"></span>
                                            <span class="ml-75"></span>
                                        </b-card-text>
                                    </b-col>

                                    <!-- Col: Total -->
                                    <b-col cols="12" md="6" class="mt-md-6 d-flex justify-content-end mb-2" order="1"
                                        order-md="2">
                                        <div class="invoice-total-wrapper">
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Item Total") }}:
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{ req_details.item_total }}
                                                </p>
                                            </div>
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Packing Charge") }}
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{
                                                    req_details.restaurant_packaging_charge }}

                                                </p>
                                            </div>
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Delivery Fee") }}
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{
                                                    req_details.delivery_charge }}

                                                </p>
                                            </div>
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Tax") }}:
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{ req_details.tax }}

                                                </p>
                                            </div>
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Tips") }}:
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{ req_details.tips }}

                                                </p>
                                            </div>
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Total Discount") }}:
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{
                                                    req_details.offer_discount }}

                                                </p>
                                            </div>
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Wallet") }}(-):
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{ req_details.wallet_amount
                                                    }}

                                                </p>
                                            </div>
                                            <hr class="my-50 hrcolor">
                                            <div class="invoice-total-item">
                                                <p class="invoice-total-title">
                                                    {{ $t("Total") }}:
                                                </p>
                                                <p class="invoice-total-amount">
                                                    {{ $store.state['defaults'].currency }} {{ req_details.bill_amount
                                                    }}

                                                </p>
                                            </div>
                                        </div>
                                    </b-col>
                                </b-row>
                            </b-card-body>

                            <!-- Spacer -->
                            <!-- <hr class=" hrcolor invoice-spacing"> -->

                            <!-- Note -->
                            <!-- <b-card-body class="invoice-padding pt-0">
        <span class="font-weight-bold">Note: </span>
        <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance
          projects. Thank You!</span>
      </b-card-body> -->
                        </b-card>
                    </b-col>

                    <!-- Right Col: Card -->
                    <b-col cols="12" md="4" xl="3" class="invoice-actions">
                        <b-card>



                            <!-- Button: DOwnload -->
                            <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary"
                                class="mb-75" block>
                                {{ $t("Download") }}
                            </b-button>

                            <!-- Button: Print -->
                            <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary"
                                class="mb-75" block @click="printInvoice">
                                {{ $t("Print") }}
                            </b-button>

                            <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary"
                                class="mb-75" block @click="goback">
                                {{ $t("Back") }}
                            </b-button>


                        </b-card>
                    </b-col>
                </b-row>

            </section>


        </div>

        <b-modal id="modal-center" centered :title="$t('Cancel Order')" ok-only :ok-title="$t('Cancel Order')"
            ref="cancel-modal" @ok="reason_add">
            <b-card-text>
                <b-form-group>
                    <v-select v-model="cselected" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" label="title"
                        :options="coption" />
                </b-form-group>
            </b-card-text>
        </b-modal>

        <b-modal id="modal-center2" centered :title="$t('Rate Order')" ok-only :ok-title="$t('Rate Order')"
            ref="rate-modal" @ok="add_rating">
            <b-card-text>
                <b-col cols="12">
                    <h4> {{ $t("Rate Your Food") }} - {{ rest }}</h4>

                    <b-form-rating v-model="value" show-value inline variant="warning" style="width:100%" />
                    <h4 class="mt-3"> {{ $t("Your Suggestions For") }} - {{ rest }}</h4>
                    <b-form-textarea v-model="feedback" id="textarea-default" :placeholder="$t('Restaurant Feedback')"
                        rows="6" />
                </b-col>
                <b-col cols="12">
                    <h4 class="mt-3"> {{ $t("Rate Your Delivery Person") }}</h4>

                    <b-form-rating v-model="value1" show-value inline variant="warning" style="width:100%" />
                </b-col>
            </b-card-text>
        </b-modal>
    </div>
</template>

<script>

import { BButton, BForm, BFormGroup, BBadge, BFormInput, BRow, BCol, BCard, BFormTextarea, BCardText, BMedia, BImg, BModal, BAlert, BLink, BCardBody, BTableLite, VBModal } from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import Cleave from 'vue-cleave-component'

// eslint-disable-next-line import/no-extraneous-dependencies
import 'cleave.js/dist/addons/cleave-phone.us'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
    components: {
        BButton,
        BForm,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BCard,
        BFormTextarea,
        vSelect,
        flatPickr,
        Cleave,
        BCardText,
        BMedia,
        BImg, BBadge,
        BModal, BAlert, BLink, BCardBody, BTableLite,

    },
    computed: {
        statusVariant() {
            const statusColor = {

                1: 'light-primary',
                2: 'light-success',
                3: 'light-danger',
                4: 'light-warning',
                5: 'light-info',

            }

            return status => statusColor[status]
        },
    },
    directives: {
        'b-modal': VBModal,
        Ripple,
    },
    props: {
        informationData: {
            type: Object,
            default: () => { },
        },
        view_order_page: {
            type: Boolean,
            default: false,
        },
        order_status: {
            type: Object,

        },
        food_details: {
            type: Object,

        },
        req_details: {
            type: Object,

        },
        coption: [],
        cselected: '',
        cancel_id: '',
        rate_id: '',
        value: 0,
        value1: 0,
        feedback: '',
        rest: ''
    },
    data() {
        return {
            countryOption: ['USA', 'India', 'Canada'],
            localOptions: JSON.parse(JSON.stringify(this.informationData)),
            clevePhone: {
                phone: true,
                phoneRegionCode: 'US',
            },
            layout: '',



        }
    },
    methods: {

        rate_modal(req, rest) {
            this.rate_id = req
            this.rest = rest

            this.$refs['rate-modal'].show()
        },
        add_rating() {
            let data = new FormData();
            data.append('restaurant_rating', this.value)
            data.append('request_id', this.rate_id)
            data.append('delivery_boy_rating', this.value1)
            data.append('restaurant_feedback', this.feedback)


            this.$http.post('/order_ratings', data)
                .then(res => {
                    if (res.data.status == true) {
                        var lat = localStorage.getItem('latitude');
                        var lng = localStorage.getItem('longitude');
                        var user = JSON.parse(localStorage.getItem('webuserData'))

                        this.$http.get('/get_profile/' + user.id + '/' + lat + '/' + lng)
                            .then(res => {
                                this.informationData = res.data.past_orders
                            })
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: res.data.message,
                                icon: 'CheckIcon',
                                variant: 'success',
                            },
                        })
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            props: {
                                title: res.data.message,
                                icon: 'CheckIcon',
                                variant: 'danger',
                            },
                        })
                    }

                })
        },

        cancel_modal(request_id) {
            this.cancel_id = request_id
            this.$http.get('/get_cancellation_reasons')
                .then(res => {
                    this.coption = res.data.reasons
                })
            this.$refs['cancel-modal'].show()

        },

        reason_add() {
            let data = new FormData();
            data.append('detail', this.cselected)
            data.append('request_id', this.cancel_id)
            this.$http.post('/order_cancel', data)
                .then(res => {

                    var lat = localStorage.getItem('latitude');
                    var lng = localStorage.getItem('longitude');
                    var user = JSON.parse(localStorage.getItem('webuserData'))
                    this.$http.get('/get_profile/' + user.id + '/' + lat + '/' + lng)
                        .then(res => {


                            this.informationData = res.data.cur_orders

                        })
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: res.data.message,
                            icon: 'CheckIcon',
                            variant: 'success',
                        },
                    })
                })
        },

        cancel_modal(request_id) {
            this.cancel_id = request_id
            this.$http.get('/get_cancellation_reasons')
                .then(res => {
                    this.coption = res.data.reasons
                })
            this.$refs['cancel-modal'].show()

        },
        printInvoice() {
            window.print()
        },
        resetForm() {
            this.localOptions = JSON.parse(JSON.stringify(this.informationData))
        },
        view_order(id) {

            this.view_order_page = true
            this.$http.get('/view_order/' + id)
                .then(res => {

                    this.req_details = res.data.message


                    this.food_details = res.data.food
                    this.layout = res.data.layout

                    this.order_status = res.data.order_status

                    const rootEle = document.documentElement
                    rootEle.scrollTo({
                        top: 0,
                        behavior: 'smooth',
                    })
                    this.view_order_page = true

                })
        },
        goback() {
            this.view_order_page = false
        }
    },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/libs/vue-flatpicker.scss';
@import "~@resources/scss/base/pages/app-invoice.scss";
</style>

<style lang="scss">
@media print {

    // Global Styles
    body {
        background-color: transparent !important;
    }

    nav.header-navbar {
        display: none;
    }

    .main-menu {
        display: none;
    }

    .header-navbar-shadow {
        display: none !important;
    }

    .content.app-content {
        margin-left: 0;
        padding-top: 2rem !important;
    }

    footer.footer {
        display: none;
    }

    .card {
        background-color: transparent;
        box-shadow: none;
    }

    .customizer-toggle {
        display: none !important;
    }

    // Invoice Specific Styles
    .invoice-preview-wrapper {
        .row.invoice-preview {
            .col-md-8 {
                max-width: 100%;
                flex-grow: 1;
            }

            .invoice-preview-card {
                .card-body:nth-of-type(2) {
                    .row {
                        >.col-12 {
                            max-width: 50% !important;
                        }

                        .col-12:nth-child(2) {
                            display: flex;
                            align-items: flex-start;
                            justify-content: flex-end;
                            margin-top: 0 !important;
                        }
                    }
                }
            }
        }

        // Action Right Col
        .invoice-actions {
            display: none;
        }
    }
}

.hr {
    border-bottom: 1px solid #e6e6e6 !important;
}
</style>
