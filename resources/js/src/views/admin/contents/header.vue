<template>
    <b-row>
        <b-col cols="12">
            <b-card>
                <b-row>
                    <b-col md="6">
                        <div class="custom-search d-flex justify-content-start">
                            <b-button variant="primary" :to="{ name: 'add_header_page' }">
                                <i class="fa-solid fa-plus"></i> {{ $t('add') }} {{ $t('new') }} {{ $t('Page') }}
                            </b-button>
                        </div>
                    </b-col>
                    <b-col md="6">
                        <div class="custom-search d-flex justify-content-end">
                            <b-form-group>
                                <div class="d-flex align-items-center">
                                    <label class="mr-1">{{ $t('message.seachLabel') }}</label>
                                    <b-form-input v-model="searchTerm" :placeholder="$t('message.seachLabel')" type="text"
                                        class="d-inline-block" />
                                </div>
                            </b-form-group>
                        </div>
                    </b-col>
                </b-row>
                <vue-good-table :columns="columns" :line-numbers="true" :rows="header_pages" :rtl="direction"
                    :search-options="{
                        enabled: true,
                        externalQuery: searchTerm
                    }" :pagination-options="{
    enabled: true,
    perPage: pageLength
}">
                    <template slot="table-row" slot-scope="props">


                        <span v-if="props.column.field === 'page_status'">

                            <b-button v-if="props.row.page_status === 1" type="submit" variant="outline-success"
                                class="mr-1" @click="change_status(props.row.pid)">
                                {{ $t('active') }}
                            </b-button>
                            <b-button v-if="props.row.page_status === 0" type="submit" variant="outline-warning"
                                class="mr-1" @click="change_status(props.row.pid)">
                                {{ $t('inactive') }}
                            </b-button>
                        </span>

                        <span v-else-if="props.column.field === 'action'">
                            <feather-icon :id="`invoice-row-${props.row.pid}-preview-icon`" icon="EditIcon" size="16"
                                class="mx-1" cursor="pointer"
                                @click="$router.push({ name: 'edit_header_page', params: { id: props.row.pid } })" />
                            <b-tooltip title="Edit Page" placement="left"
                                :target="`invoice-row-${props.row.pid}-preview-icon`" />

                            <feather-icon :id="`invoice-row_view-${props.row.pid}-preview-icon`" icon="EyeIcon" size="16"
                                class="mx-1" cursor="pointer"
                                @click="$router.push({ name: 'view_header_page', params: { id: props.row.pid } })" />
                            <b-tooltip title="View Page" placement="left"
                                :target="`invoice-row_view-${props.row.pid}-preview-icon`" />




                        </span>
                        <span v-else>
                            {{ props.formattedRow[props.column.field] }}
                        </span>
                    </template>
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
    </b-row>
</template>
<script>
import { BListGroupItem, BAvatar, BButton, BCardText, BForm, BBadge, BTooltip, BPagination, BRow, BCol, BCard, BFormGroup, BImg, BFormInput, BFormSelect, BDropdown } from 'bootstrap-vue'
import draggable from 'vuedraggable'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
// import VueDragTree from 'vue-drag-tree'
// import 'vue-drag-tree/dist/vue-drag-tree.min.css'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'

export default {
    components: {
        draggable,
        BListGroupItem,
        BAvatar, BButton, BCardText, BForm, VueGoodTable, BBadge, BTooltip, BPagination, BRow, BCol, BCard, BFormGroup, BImg, BFormInput, BFormSelect, BDropdown
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            header_pages: [],
            pageLength: 10,
            dir: false,
            Loading: true,
            columns: [

                {
                    label: this.$t('Page name'),
                    field: 'page_name',
                },

                {
                    label: this.$t('Menu'),
                    field: 'header_category_name',
                },
                // {
                //     label: this.$t('Sub Category'),
                //     field: 'header_sub_category_name',
                // },
                {
                    label: this.$t('Status'),
                    field: 'page_status',
                },
                {
                    label: this.$t('action'),
                    field: 'action',
                },
            ],
            rows: [],
            searchTerm: '',
        }
    },
    created() {
        this.get_pages()
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
        direction() {
            if (store.state.appConfig.isRTL) {
                this.dir = true
                return this.dir
            }
            this.dir = false
            return this.dir
        },
    },
    methods: {
        get_pages() {
            this.$http.get('/admin/get_header_pages')
                .then(res => {
                    this.header_pages = res.data.header_pages
                })
        },
        change_status(id) {
            this.$http.get('/admin/change_page_status/' + id)
                .then(res => {
                    if (res.data.status == true) {
                        this.get_pages();
                        this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                                title: res.data.message,
                                icon: 'CheckIcon',
                                variant: 'success',
                            },
                        })
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                                title: res.data.message,
                                icon: 'CheckIcon',
                                variant: 'danger',
                            },
                        })
                    }

                })
        }
    },
}
</script>
  
<style>
.list-group-item {
    transition: all 1s
}
</style>
 
<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>
  