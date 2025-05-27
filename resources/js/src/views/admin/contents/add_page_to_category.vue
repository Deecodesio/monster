<template>
    <div>
        <b-card>
            <h4>
                {{ menu_name }}
            </h4>
            <div class="mt-5">
                <b-row>
                    <b-col md="6">
                        <h6 class="text-primary font-weight-bold mb-2">
                            {{ $t('Pages In') }} {{ menu_name }}
                        </h6>
                        <draggable :list="list1" tag="ul" group="people" class="list-group list-group-flush cursor-move">
                            <b-list-group-item v-for="(listItem, index) in list1" :key="index" tag="li">
                                <div class="d-flex">
                                    <b-avatar :text="listItem.name.slice(0, 2)" />
                                    <div class="ml-25">
                                        <b-card-text class="mb-0 font-weight-bold">
                                            {{ listItem.name }}
                                        </b-card-text>
                                        <small>{{ listItem.email }}</small>
                                    </div>
                                </div>
                            </b-list-group-item>
                        </draggable>
                    </b-col>
                    <b-col md="6" class="mt-sm-2 mt-md-0">
                        <h6 class="text-primary font-weight-bold mb-2">
                            {{ $t('Available Pages') }}
                        </h6>
                        <draggable :list="pages" tag="ul" group="people" class="list-group list-group-flush cursor-move">
                            <b-list-group-item v-for="(listItem, index) in pages" :key="index" tag="li">
                                <div class="d-flex">
                                    <b-avatar :text="listItem.name.slice(0, 2)" />
                                    <div class="ml-25">
                                        <b-card-text class="mb-0 font-weight-bold">
                                            {{ listItem.name }}
                                        </b-card-text>
                                        <small>{{ listItem.email }}</small>
                                    </div>
                                </div>
                            </b-list-group-item>
                        </draggable>
                    </b-col>
                </b-row>
            </div>
            <div class="mt-5">
                <b-button variant="primary" type="submit" @click="save_pages">
                    {{ $t('Save') }}
                </b-button>
            </div>
        </b-card>
    </div>
</template>
<script>
import { BListGroupItem, BAvatar, BButton, BCardText, BForm, BBadge, BTooltip, BPagination, BRow, BCol, BCard, BFormGroup, BImg, BFormInput, BFormSelect, BDropdown } from 'bootstrap-vue'
import draggable from 'vuedraggable'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import store from '@/store/index'

export default {
    components: {
        draggable,
        BListGroupItem,
        BAvatar, BButton, BCardText, BForm, BBadge, BTooltip, BPagination, BRow, BCol, BCard, BFormGroup, BImg, BFormInput, BFormSelect, BDropdown
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
            menu_name: '',
            pages: {},
            list1: [],
            list2: [],

        }
    },
    created() {
        this.get_header_menu()
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
        save_pages() {
            let pages = this.list1

            let data = new FormData();
            data.append('pages', JSON.stringify(pages));
            data.append('cat', this.$route.params.id);
            this.$http.post('/admin/save_page_category', data)
                .then(res => {
                    if (res.data.status) {
                        this.poptoast("Success", res.data.message)
                    }
                })
        },
        get_header_menu() {
            this.$http.get('/admin/fetch_header_menu/' + this.$route.params.id)
                .then(res => {
                    console.log(res)

                    this.menu_name = res.data.header_category.name
                    this.pages = res.data.pages
                    this.list1 = res.data.in_pages
                })
        },
        poptoast(variant, message) {
            this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                    title: message,
                    icon: "CheckIcon",
                    variant: variant,
                },
            })
        }

    },
}
</script>
  

  