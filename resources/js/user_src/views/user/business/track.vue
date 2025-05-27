<template>
    <div>
        <banner />
        <div class="container">
            <b-row>
                <b-col cols="12" lg="3">
                </b-col>
                <b-col cols="12" lg="6">
                    <b-card>
                        <validation-observer ref="simpleRules">
                            <b-form>
                                <b-row>
                                    <b-col md="12">
                                        <b-form-group>
                                            <validation-provider #default="{ errors }" name="Order Id" rules="required">
                                                <b-form-input v-model="order_id" :state="errors.length > 0 ? false : null"
                                                    placeholder="Enter Order ID" />
                                                <small class="text-danger">{{ errors[0] }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col cols="12">
                                        <b-button variant="primary" type="submit" @click.prevent="validationForm">
                                            Track Order
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </b-form>
                        </validation-observer>
                    </b-card>

                    <b-card v-if="is_fetched">
                        <section class="timeline_area section_padding_130">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-8 col-lg-6">
                                        <div class="section_heading text-center">
                                            <h6>Order ID : {{ order_detail.order_id }}</h6>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="apland-timeline-area">
                                            <div class="single-timeline-area" v-for="(data, index) in tracking_details"
                                                :key="index">
                                                <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s"
                                                    style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                                    <p>{{ data.created_at_time }}</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="single-timeline-content d-flex wow fadeInLeft"
                                                            data-wow-delay="0.3s"
                                                            style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInLeft;">
                                                            <div class="timeline-icon"><i class="fa fa-address-card"
                                                                    aria-hidden="true"></i></div>
                                                            <div class="timeline-text">
                                                                <h6>{{ data.detail }}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>
   
<script>
import {
    BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import banner from '@@@/views/user/users/banner.vue'


export default {
    components: {
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
        ValidationProvider,
        ValidationObserver,
        banner
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            rows: {},
            banner: [],
            order_id: '',
            is_loading: false,
            is_fetched: false,
            tracking_details: [],
            order_detail: {}
        }
    },
    created() {
        this.$http.get('/business_banner/' + localStorage.bussiness_id)
            .then(res => {
                this.banner = res.data[0];
            })
    },
    methods: {
        validationForm() {
            this.$refs.simpleRules.validate().then(success => {
                if (success) {
                    this.is_loading = true
                    this.is_fetched = false


                    let data = new FormData();
                    data.append('order_id', this.order_id);
                    this.$http.post('/track_user_order', data)
                        .then(res => {
                            console.log(res);
                            if (!res.data.status) {
                                this.$swal({
                                    title: 'Oops!',
                                    text: res.data.message,
                                    icon: 'error',
                                    customClass: {
                                        confirmButton: 'btn btn-primary',
                                    },
                                    buttonsStyling: false,
                                })
                            } else {
                                this.is_fetched = true

                                this.tracking_details = res.data.tracking_detail
                                this.order_detail = res.data.order_status[0]
                            }
                            this.is_loading = false

                        })
                }
            })
        },
    },
}
</script>
<style lang="scss">
@import '~@resources/scss/base/bootstrap-extended/_variables.scss';

.pt-10 {
    padding-top: 10rem !important;
}

timeline_area {
    position: relative;
    z-index: 1;
}

.single-timeline-area {
    position: relative;
    z-index: 1;
    padding-left: 180px;
}

@media only screen and (max-width: 575px) {
    .single-timeline-area {
        padding-left: 100px;
    }
}

.single-timeline-area .timeline-date {
    position: absolute;
    width: 180px;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -ms-grid-row-align: center;
    align-items: center;
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    padding-right: 60px;
}

@media only screen and (max-width: 575px) {
    .single-timeline-area .timeline-date {
        width: 100px;
    }
}

.single-timeline-area .timeline-date::after {
    position: absolute;
    width: 3px;
    height: 100%;
    content: "";
    background-color: #ebebeb;
    top: 0;
    right: 30px;
    z-index: 1;
}

.single-timeline-area .timeline-date::before {
    position: absolute;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background-color: $primary;
    content: "";
    top: 50%;
    right: 26px;
    z-index: 5;
    margin-top: -5.5px;
}

.single-timeline-area .timeline-date p {
    margin-bottom: 0;
    color: #020710;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 500;
}

.single-timeline-area .single-timeline-content {
    position: relative;
    z-index: 1;
    padding: 30px 30px 25px;
    border-radius: 6px;
    margin-bottom: 15px;
    margin-top: 15px;
    -webkit-box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
    box-shadow: 0 0.25rem 1rem 0 rgba(47, 91, 234, 0.125);
    border: 1px solid #ebebeb;
}

@media only screen and (max-width: 575px) {
    .single-timeline-area .single-timeline-content {
        padding: 20px;
    }
}

.single-timeline-area .single-timeline-content .timeline-icon {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    width: 30px;
    height: 30px;
    background-color: $primary;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 30px;
    flex: 0 0 30px;
    text-align: center;
    max-width: 30px;
    border-radius: 50%;
    margin-right: 15px;
}

.single-timeline-area .single-timeline-content .timeline-icon i {
    color: #ffffff;
    line-height: 30px;
}

.single-timeline-area .single-timeline-content .timeline-text h6 {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
}

.single-timeline-area .single-timeline-content .timeline-text p {
    font-size: 13px;
    margin-bottom: 0;
}

.single-timeline-area .single-timeline-content:hover .timeline-icon,
.single-timeline-area .single-timeline-content:focus .timeline-icon {
    background-color: #020710;
}

.single-timeline-area .single-timeline-content:hover .timeline-text h6,
.single-timeline-area .single-timeline-content:focus .timeline-text h6 {
    color: #3f43fd;
}
</style>
       