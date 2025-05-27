<template>
    <div>
        <h6 class="text-primary font-weight-bold mb-2">
            <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="outline-success" class="mt-0 mt-md-2"
                :to="{ name: 'add_section' }" id="add">
                <feather-icon icon="PlusIcon" class="mr-25" />
                <span>{{ $t('add') }} {{ $t('new') }} {{ $t('Section') }}</span>
            </b-button>
        </h6>
        <b-form @submit.prevent="sortMenu">
            <draggable v-model="list" class="list-group list-group-flush cursor-move" tag="ul">
                <b-list-group-item v-for="listItem in list" :key="listItem.id" tag="li" :data-id="listItem.id">
                    <div class="d-flex">
                        <b-avatar :text="listItem.name.slice(0, 2)" />
                        <div class="ml-25">
                            <b-card-text class="font-weight-bold mb-0">
                                {{ listItem.name }}
                                <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="flat-primary" class="btn-icon"
                                    @click="$router.push({ name: 'edit_page', params: { id: listItem.id } })"
                                    v-if="listItem.is_editable == 1">
                                    <feather-icon icon="EditIcon" cursor="pointer" style="color: #6e6b7b;" />
                                </b-button>
                            </b-card-text>
                        </div>
                    </div>
                </b-list-group-item>
            </draggable>
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mt-1"
                style="margin-bottom: 2%;">
                {{ $t('save') }}
            </b-button>
        </b-form>
    </div>
</template>
<script>
import { BListGroupItem, BAvatar, BButton, BCardText, BForm } from 'bootstrap-vue'
import draggable from 'vuedraggable'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
export default {
    components: {
        draggable,
        BListGroupItem,
        BAvatar, BButton, BCardText, BForm
    },
    directives: {
        Ripple,
    },
    data() {
        return {
            list: [],
            cate: {},
        }
    },
    created() {
        this.$http.get('/admin/get_pages')
            .then(res => {
                this.list = res.data.pages
            })
    },
    methods: {
        sortMenu() {
            const CONTENEUR_TOPICS = document.getElementsByClassName('list-group-flush');
            let TOPICS;
            console.log(CONTENEUR_TOPICS)
            if (CONTENEUR_TOPICS.length > 0) {
                TOPICS = CONTENEUR_TOPICS[0].children;
                let result = Array.from(TOPICS).map(el => el.dataset.id);
                this.cate.categoryRanks = result;
            }
            this.$http
                .post("/admin/page_sort_save", this.cate)
                .then
                (response => {
                    if (response.data.status == true) {
                        this.list = response.data.data
                        this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                                title: this.$t(response.data.message),
                                icon: 'CheckIcon',
                                variant: 'success',
                            }
                        })
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: 'bottom-right',
                            props: {
                                title: this.$t(response.data.message),
                                icon: 'CheckIcon',
                                variant: 'danger',
                            }
                        })
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
        popToast(response, icon, variant) {
            this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                    title: this.$t(response.data.message),
                    icon: icon,
                    variant: variant,
                },
            })
        },
    },
}
</script>
  
<style>
.list-group-item {
    transition: all 1s
}
</style>