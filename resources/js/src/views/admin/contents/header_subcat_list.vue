<template>
    <b-row>
        <b-col cols="12">
            <b-card>
                <b-row>
                    <b-col md="6">
                        <div class="custom-search d-flex justify-content-start">
                            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" @click="open_Add_modal"
                                variant="outline-primary">
                                <feather-icon icon="PlusIcon" class="mr-25" />
                                <span> {{ $t('add') }} {{ $t('new') }} {{ $t('Sub Category') }}</span>
                            </b-button>
                            <b-modal id="modal-1" :title="$t('Add New Header Sub Category')" ok-only ok-title="Submit"
                                ref="add_modal" @ok="validationForm">
                                <validation-observer ref="simpleRules">
                                    <b-form>
                                        <b-col md="12">
                                            <b-form-group :label="$t('Header Sub Category Name')">
                                                <validation-provider #default="{ errors }" name="First Name"
                                                    rules="required">
                                                    <b-form-input v-model="subcategory.name"
                                                        :state="errors.length > 0 ? false : null"
                                                        placeholder="Header Sub Category Name" />
                                                    <small class="text-danger">{{ errors[0] }}</small>
                                                </validation-provider>
                                            </b-form-group>
                                        </b-col>
                                        <b-col md="12">
                                            <b-form-group :label="$t('Header Category')">
                                                <validation-provider #default="{ errors }" name="Header Category"
                                                    rules="required">
                                                    <v-select v-model="subcategory.category"
                                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                                        :options="categories_list" label="name" :reduce="sel => sel.id" />
                                                    <small class="text-danger">{{ errors[0] }}</small>
                                                </validation-provider>
                                            </b-form-group>
                                        </b-col>
                                    </b-form>
                                </validation-observer>
                            </b-modal>
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
                <vue-good-table :columns="columns" :line-numbers="true" :rows="rows" :rtl="direction" :search-options="{
                    enabled: true,
                    externalQuery: searchTerm
                }" :pagination-options="{
    enabled: true,
    perPage: pageLength
}">
                    <template slot="table-row" slot-scope="props">


                        <span v-if="props.column.field === 'status'">

                            <b-button v-if="props.row.status === 1" type="submit" variant="outline-success" class="mr-1"
                                @click="change_status(props.row.id)">
                                {{ $t('active') }}
                            </b-button>
                            <b-button v-if="props.row.status === 0" type="submit" variant="outline-warning" class="mr-1"
                                @click="change_status(props.row.id)">
                                {{ $t('inactive') }}
                            </b-button>
                        </span>

                        <span v-else-if="props.column.field === 'action'">
                            <feather-icon :id="`invoice-row-${props.row.id}-preview-icon`" icon="EditIcon" size="16"
                                class="mx-1" cursor="pointer"
                                @click="edit_category(props.row.id, props.row.name, props.row.header_category)" />
                            <b-tooltip title="Edit Subcategory" placement="left"
                                :target="`invoice-row-${props.row.id}-preview-icon`" />




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
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email } from '@validations'
import vSelect from 'vue-select'


export default {
    components: {
        draggable,
        BListGroupItem,
        BAvatar, BButton, BCardText, BForm, VueGoodTable, BBadge, BTooltip, BPagination, BRow, BCol, BCard, BFormGroup, BImg, BFormInput, BFormSelect, BDropdown, ValidationProvider,
        ValidationObserver, vSelect
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
                    label: this.$t('Sub category name'),
                    field: 'header_sub_category_name',
                },
                {
                    label: this.$t('category name'),
                    field: 'header_category_name',
                },
                {
                    label: this.$t('Status'),
                    field: 'status',
                },
                {
                    label: this.$t('action'),
                    field: 'action',
                },
            ],
            rows: [],
            searchTerm: '',
            subcategory: {
                name: '',
                id: '',
                category: ''
            },
            categories_list: []
        }
    },
    created() {
        this.get_header_subcategories()
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
        edit_category(id, name, cat) {
            this.get_header_categories()

            this.$refs["add_modal"].show();
            this.subcategory.name = name
            this.subcategory.id = id
            this.subcategory.category = cat

        },
        get_header_categories() {
            this.$http.get('/admin/get_header_categories')
                .then(res => {
                    this.categories_list = res.data.categories
                })
        },
        validationForm() {
            this.$refs.simpleRules.validate().then(success => {
                if (success) {
                    this.$http.post('/admin/add_new_header_subcategory', this.subcategory)
                        .then(
                            response => {
                                this.$toast({
                                    component: ToastificationContent,
                                    position: 'bottom-right',
                                    props: {
                                        title: this.$t(response.data.message),
                                        icon: "CheckIcon",
                                        variant: "success",
                                    },
                                })
                                this.get_header_subcategories()

                            }
                        )
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                }
            })
        },
        open_Add_modal() {
            this.$refs["add_modal"].show();
            this.subcategory.name = ""
            this.subcategory.id = ""
            this.subcategory.category = ""


            this.get_header_categories()

        },
        get_header_subcategories() {
            this.$http.get('/admin/get_header_subcategories')
                .then(res => {
                    this.rows = res.data.subs
                })

        },
        change_status(id) {
            this.$http.get('/admin/change_header_subcat_status/' + id)
                .then(res => {
                    if (res.data.status == true) {
                        this.get_header_subcategories();
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
  