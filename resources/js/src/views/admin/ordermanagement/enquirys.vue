<template>
    <b-row>
        <b-col cols="12">
            <b-card>

                <!-- input search -->
                <div class="custom-search d-flex justify-content-end">
                    <b-form-group>
                        <div class="d-flex align-items-center">
                            <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                            <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text"
                                class="d-inline-block" />
                        </div>
                    </b-form-group>
                </div>

                <!-- table -->
                <vue-good-table :columns="columns" :line-numbers="true" :rows="rows" :rtl="direction" :search-options="{
                    enabled: true,
                    externalQuery: searchTerm
                }" :pagination-options="{
    enabled: true,
    perPage: pageLength
}">
                    <template slot="table-row" slot-scope="props">
                        <a id="link" hidden="hidden" target="_blank" href="/store/new"></a>
                        <!-- Column: Name -->
                        <div v-if="props.column.field === 'user_names'" class="text-nowrap">

                            <span class="text-nowrap" v-if="props.row.device_type === 'android'"><i
                                    :id="'andriod' + props.row.restaurant_id" class="fa-brands fa-android"
                                    aria-hidden="true"></i> {{
                                        props.row.name
                                    }}</span>
                            <span class="text-nowrap" v-else-if="props.row.device_type === 'ios'"><i
                                    :id="'ios' + props.row.restaurant_id" class="fa-brands fa-apple" aria-hidden="true"></i>
                                {{
                                    props.row.name
                                }}</span>
                            <span class="text-nowrap" v-else><i :id="'web' + props.row.restaurant_id"
                                    class="fa-solid fa-desktop" aria-hidden="true"></i> {{ props.row.name }}</span>
                            <br>
                            <span class="text-nowrap">{{ props.row.phone }}</span>
                            <b-tooltip v-b-tooltip.hover.v-warning title="Android"
                                :target="'andriod' + props.row.restaurant_id" />
                            <b-tooltip v-b-tooltip.hover.v-warning title="IOS" :target="'ios' + props.row.restaurant_id" />
                            <b-tooltip v-b-tooltip.hover.v-warning title="WEB" :target="'web' + props.row.restaurant_id" />
                        </div>



                        <span v-else-if="props.column.field === 'view'">
                            <feather-icon :id="`invoice-row-${props.row.restaurant_id}-preview-icon`" icon="EyeIcon"
                                size="16" class="mx-1"
                                @click="$router.push({ name: 'view_enquiry', params: { id: props.row.enquiry_id } })"
                                cursor="pointer" />
                            <b-tooltip :title="$t('view') + ' ' + $t('order')" placement="left"
                                :target="`invoice-row-${props.row.restaurant_id}-preview-icon`" />
                        </span>


                        <span v-else>
                            {{ props.formattedRow[props.column.field] }}
                        </span>
                    </template>

                    <!-- pagination -->
                    <template slot="pagination-bottom" slot-scope="props">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-0 mt-1">
                                <span class="text-nowrap">
                                    {{ $t('message.pagelength') }}
                                </span>
                                <b-form-select v-model="pageLength" :options="['10', '50', '100']" class="mx-1"
                                    @input="(value) => props.perPageChanged({ currentPerPage: value })" />
                                <span class="text-nowrap "> {{ $t('message.of') }} {{ props.total }} {{
                                    $t('message.pageText2') }}
                                </span>
                            </div>
                            <div>
                                <b-pagination :value="1" :total-rows="props.total" :per-page="pageLength" first-number
                                    last-number align="right" prev-class="prev-item" next-class="next-item"
                                    class="mt-1 mb-0" @input="(value) => props.pageChanged({ currentPage: value })">
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
                    <div slot="emptystate" style="text-align: center;" v-if="Loading">

                        <b-img src="/admin_loader.svg" fluid alt="Scan" />

                    </div>
                </vue-good-table>


            </b-card>

        </b-col>




        <b-modal id="modal-1" :data-id="this.to_id" ref="my-modal" cancel-variant="outline-secondary" cancel-title="Close"
            :title="$t('refund')" hide-footer>
            <a class="btn btn-success" @click="refund_w(to_id)"> {{ $t('refund') }} {{ $t('to') }} {{ $t('wallet') }}</a>
            <a class="btn btn-success" @click="refund(to_id)">{{ $t('refund') }} {{ $t('to') }} {{ $t('source_account')
            }}</a>
        </b-modal>
    </b-row>
</template>
  
<script>

import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@core/components/b-card-code/BCardCode.vue'
import {
    VBTooltip, BModal, VBModal, BAlert, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
    BDropdown, BDropdownItem, BButton, BCard, BTooltip, BImg, BMedia,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'
import { codeColumnSearch } from './code'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
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
        BButton,
        BCard,
        BTooltip,
        BModal,
        VBModal,
        BAlert,
        VBTooltip,
        BImg, BMedia,
    },
    data() {
        return {
            pageLength: 10,
            dir: false,
            codeColumnSearch,
            to_id: '',
            Loading: true,
            setting: {},
            columns: [
                {
                    label: this.$t('customer name'),
                    field: 'user_names',
                    class: 'text-nowrap',

                },
                {
                    label: this.$t('view order'),
                    field: 'view',
                    tdClass: 'align',

                },

                {
                    label: this.$t('Enquired On'),
                    field: 'created_at',
                    tdClass: 'text-nowrap',
                },
                {
                    label: this.$t('address'),
                    field: 'delivery_address',
                    tdClass: 'text-nowraps',
                },

            ],
            rows: [],
            searchTerm: '',
        }
    },
    directives: {
        'b-modal': VBModal,
        Ripple,
    },
    methods: {

        popToast(response) {

            const h = this.$createElement
            const vNodesMsg = h('p', { class: ['text-center', 'mb-0'] }, [
                response.data.message
            ])
            this.$bvToast.toast([vNodesMsg], {
                solid: false,
                variant: 'info',
            })
        },



    },
    computed: {

        statusVariant() {
            const statusColor = {

                1: 'light-success',
                2: 'light-warning',
                3: 'light-danger',
                0: 'light-warning',
                5: 'light-info',

            }

            return status => statusColor[status]
        },

        direction() {
            if (store.state.appConfig.isRTL) {
                // eslint-disable-next-line vue/no-side-effects-in-computed-properties
                this.dir = true
                return this.dir
            }
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.dir = false
            return this.dir
        },
    },
    created() {

        var ad = localStorage.admin_email
        this.$http.get('/admin/fetch_enquirys')
            .then(res => {

                this.rows = res.data;
                console.log(this.rows);
                this.Loading = false;
                if (process.env.MIX_IS_DEMO) {
                    for (let i = 0; i < this.rows.length; i++) {
                        this.rows[i].phone = "**********"
                        this.rows[i].email = "**********"
                        this.rows[i].contact = "**********"

                    }
                }

            })
        window.setInterval(() => {

            var ad = localStorage.admin_email
            this.$http.get('/admin/Orders_list/' + this.$route.name + '/' + ad)
                .then(res => {

                    this.rows = res.data;
                    this.Loading = false;
                    if (process.env.MIX_IS_DEMO) {
                        for (let i = 0; i < this.rows.length; i++) {
                            this.rows[i].phone = "**********"
                            this.rows[i].email = "**********"
                            this.rows[i].contact = "**********"

                        }
                    }

                })
        }, 10 * 1000);
        this.$http.get('/admin/get_currency')
            .then(res => {

                this.setting = res.data
                //  console.log(this.setting);

            })

    },


}
</script>
  
<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';

.font-weight-bold {
    font-weight: 900 !important;
}

.text-nowraps {
    min-width: 300px;
}

table.vgt-table td {
    vertical-align: middle;
    color: #6e6b7b;
}

.align {
    text-align: center;
}
</style>
  