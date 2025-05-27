<template>
    <div class="container" style="margin-top:155px;">
        <b-row>
            <b-col cols="12" lg="4"></b-col>
            <b-col cols="12" lg="4">
                <b-card>
                    <p class="f-size-2 text-center pb-1">Payment Gateway</p>
                    <table width="100%" style="margin-bottom:40px;">
                        <tr>
                            <td>{{ $t("Item Total") }}</td>
                            <td>{{ $store.state['defaults'].currency }} {{ rows.item_total }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t("Packaging Charge") }}</td>
                            <td>{{ $store.state['defaults'].currency }} {{ rows.restaurant_packaging_charge }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t("Discount") }}</td>
                            <td>{{ $store.state['defaults'].currency }} {{ rows.offer_discount }}</td>
                        </tr>
                        <tr id="dcharge_bill">
                            <td>{{ $t("Delivery Charge") }}</td>
                            <td>{{ $store.state['defaults'].currency }} {{ rows.delivery_charge }}</td>
                        </tr>
                        <tr id="tips_bill">
                            <td>{{ $t("Tips") }}</td>
                            <td>{{ $store.state['defaults'].currency }} {{ rows.tips }}</td>
                        </tr>
                        <tr id="tips_bill">
                            <td>{{ $t("Tax") }} </td>
                            <td>{{ $store.state['defaults'].currency }} {{ rows.tax }}</td>
                        </tr>
                        <tr>
                            <td>
                                <p>{{ $t("Bill Total") }} </p>
                            </td>
                            <td>
                                {{ $store.state['defaults'].currency }} {{ rows.bill_amount }}
                            </td>
                        </tr>


                    </table>

                    <b-col cols="12" class="pad0">
                        <div>
                            <button @click="makePayment" class="btn btn-success">Pay</button>
                        </div>
                    </b-col>
                </b-card>

            </b-col>
            <b-col cols="4" lg="4"></b-col>
        </b-row>

    </div>
</template>

<script>
import { BModal, BButton, VBModal, BAlert, BCard, BFormRadioGroup, BFormGroup, BRow, BCol } from 'bootstrap-vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { BTabs, BTab } from 'bootstrap-vue'
import { StripeCheckout } from '@vue-stripe/vue-stripe';
import { StripeElementPayment } from '@vue-stripe/vue-stripe';
import Ripple from 'vue-ripple-directive'

export default {
    components: {
        BButton,
        BModal,
        BAlert,
        BCard,
        BFormRadioGroup, BFormGroup, BTabs, BTab, BRow, BCol, StripeCheckout, StripeElementPayment,
    },
    mounted() {
        this.generatePaymentIntent();
    },
    data() {

        return {
            option: {},

            rows: {},
            loading: false,


            script: 'https://checkout.razorpay.com/v1/checkout.js'

        }
    },
    methods: {

        async loadRazorPay() {
            return new Promise(resolve => {
                const script = document.createElement('script')
                script.src = this.script
                script.onload = () => {
                    resolve(true)
                }
                script.onerror = () => {
                    resolve(false)
                }
                document.body.appendChild(script)
            })
        },


        generatePaymentIntent() {

            this.$http.post('/pay_for_order/' + this.$route.params.id)
                .then(res => {


                    this.rows = res.data.data


                })
        },
        pay() {
            this.$refs.paymentRef.submit();
        },
        makePayment: function (ev) {
            ev.preventDefault();
            this.$http.get('/pay_for_razor/' + this.$route.params.id)
                .then(res => {

                    this.order_id = res.data.order.id;
                    //  alert(res.data.razor);
                    //Initiation of Razorpay PG
                    var newThis = this;
                    var rzp1 = new Razorpay({
                        key: res.data.razor_key,
                        amount: res.data.razor,
                        name: res.data.user.name,
                        currency: res.data.currency.currency_code,
                        description: "New Order",
                        // redirect: true,
                        // callback_url: window.location.origin+'/razorpaypayment',
                        // callback_method: "GET",
                        image: res.data.logo,
                        order_id: res.data.razor_id,

                        handler: function (response) {
                            // this.r_razorpay_payment_id =response.razorpay_payment_id;
                            // this.r_razorpay_order_id =response.razorpay_order_id;
                            // this.r_razorpay_signature =response.razorpay_signature;
                            // alert(this.r_razorpay_payment_id);
                            // alert(this.r_razorpay_order_id);
                            // alert(this.r_razorpay_signature);
                            newThis.razorpay(response);

                        },
                        prefill: {
                            name: res.data.user.name,
                            email: res.data.user.email,
                            phone: res.data.user.phone,
                        },
                        notes: {
                            address: ""
                        },
                        theme: {
                            color: "#00ffff"
                        },


                        //  callback_method: "POST",

                    });
                    rzp1.open();
                })
                .catch(err => {
                    console.log("ERR", err);
                });

        },

        razorpay(response) {

            let data = new FormData();
            data.append('razorpay_payment_id', response.razorpay_payment_id);
            data.append('razorpay_order_id', response.razorpay_order_id);
            data.append('razorpay_signature', response.razorpay_signature);
            data.append('order_id', this.$route.params.id);



            console.log(data);
            this.$http.post('/razorpaypayment', data)
                .then(res => {
                    this.rows = res.data
                    this.$router.push({ name: 'trackorder', params: { id: res.data.order_id } });

                })
        },
    },
    created() {




    },


}
</script>

<style scoped>
.pt-10 {
    padding-top: 10rem !important;
}

/* Variables */
* {
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 16px;
    -webkit-font-smoothing: antialiased;
    display: flex;
    justify-content: center;
    align-content: center;
    height: 100vh;
    width: 100vw;
}

form {
    width: 30vw;
    min-width: 500px;
    align-self: center;
    box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
        0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
    border-radius: 7px;
    padding: 40px;
}

.hidden {
    display: none;
}

#payment-message {
    color: rgb(105, 115, 134);
    font-size: 16px;
    line-height: 20px;
    padding-top: 12px;
    text-align: center;
}

#payment-element {
    margin-bottom: 24px;
}

/* Buttons and links */
button {
    background: #5469d4;
    font-family: Arial, sans-serif;
    color: #ffffff;
    border-radius: 4px;
    border: 0;
    padding: 12px 16px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: block;
    transition: all 0.2s ease;
    box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
    width: 100%;
}

button:hover {
    filter: contrast(115%);
}

button:disabled {
    opacity: 0.5;
    cursor: default;
}

/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
    border-radius: 50%;
}

.spinner {
    color: #ffffff;
    font-size: 22px;
    text-indent: -99999px;
    margin: 0px auto;
    position: relative;
    width: 20px;
    height: 20px;
    box-shadow: inset 0 0 0 2px;
    -webkit-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
}

.spinner:before,
.spinner:after {
    position: absolute;
    content: "";
}

.spinner:before {
    width: 10.4px;
    height: 20.4px;
    background: #5469d4;
    border-radius: 20.4px 0 0 20.4px;
    top: -0.2px;
    left: -0.2px;
    -webkit-transform-origin: 10.4px 10.2px;
    transform-origin: 10.4px 10.2px;
    -webkit-animation: loading 2s infinite ease 1.5s;
    animation: loading 2s infinite ease 1.5s;
}

.spinner:after {
    width: 10.4px;
    height: 10.2px;
    background: #5469d4;
    border-radius: 0 10.2px 10.2px 0;
    top: -0.1px;
    left: 10.2px;
    -webkit-transform-origin: 0px 10.2px;
    transform-origin: 0px 10.2px;
    -webkit-animation: loading 2s infinite ease;
    animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes loading {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@media only screen and (max-width: 600px) {
    form {
        width: 80vw;
        min-width: initial;
    }
}
</style>
