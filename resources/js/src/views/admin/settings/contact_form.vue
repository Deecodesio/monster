<template>
    <div>
        <div>
        
            <b-form
                ref="form"
                :style="{ height: trHeight }"
                class="repeater-form"
                @submit.prevent="repeateAgain"
            >
                <b-row
                    v-for="(item, index) in items"
                    :id="item.id"
                    :key="item.id"
                    ref="row"
                >
                    <b-col md="4">
                        <b-form-group :label="$t('Reason')" label-for="item-name">
                            <b-form-input
                                id="item-name"
                                type="text"
                                :value="item.reason"
                                v-model="item.reason"
                            />
                        </b-form-group>
                    </b-col>

                    <b-col md="5">
                        <b-form-group :label="$t('Mail Id')" label-for="cost">
                            <b-form-tags
                                v-model="rest_signup[index]"
                                input-id="tags-validation"
                                :input-attrs="{
                                    'aria-describedby': 'tags-validation-help',
                                }"
                                :state="state"
                                :duplicate-tag-text="$t('Duplicate Emailid')"
                                :invalid-tag-text="$t('Invalid Email ID')"
                                :tag-validator="tagValidator"
                                separator=" "
                                :placeholder="$t('Enter Email Ids')"
                                add-button-variant="outline-primary"
                            />
                        </b-form-group>
                    </b-col>

                    <b-col lg="2" md="3" class="mb-50">
                        <b-button
                            v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                            variant="outline-danger"
                            class="mt-0 mt-md-2"
                            @click="removeItem(index)"
                        >
                            <feather-icon icon="XIcon" class="mr-25" />
                            <span>{{$t('Delete')}}</span>
                        </b-button>
                    </b-col>
                    <b-col cols="12">
                        <hr />
                    </b-col>
                </b-row>
            </b-form>
        </div>
        <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            @click="repeateAgain"
        >
            <feather-icon icon="PlusIcon" class="mr-25" />
            <span>{{$t('Add New Reason')}}</span>
        </b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1" @click="save_cont()">
                    {{ $t('save') }}
        </b-button>
    </div>
</template>

<script>
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
    BFormTags,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import { heightTransition } from "@core/mixins/ui/transition";
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
        BFormTags,
    },
    directives: {
        Ripple,
    },
    mixins: [heightTransition],
    data() {
        return {
            selected: 1,
            userData: JSON.parse(localStorage.getItem("userData")),
            brand: {},
            demo: false,
            newordermail: [],
            cancelledordermail: [],
            failedordermail: [],
            rest_signup: [],
            rest_signup1: [],
            product: {},
            items: [
                {
                    id: 1,
                    selected: "male",
                    selected1: "designer",
                    prevHeight: 0,
                },
            ],
            nextTodoId: 2,

            dirty: false,
        };
    },
    computed: {
        state() {
        
        var st = false
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.newordermail))
            {
            st = true
            // return true
            }else{
            st = false
            // return false
            }
        return this.dirty ? st : null
        },
    },
    watch: {
        tags() {
        
        this.dirty = true
        },
    },

    mounted() {
        // this.initTrHeight();
    },
    destroyed() {
        // window.removeEventListener("resize", this.initTrHeight);
    },
    created() {
      
        // window.addEventListener("resize", this.initTrHeight);
        this.$http.get("/admin/get_contact_reasons").then((res) => {
            console.log(res)
            this.items = res.data.contact;
            for(var i=0;i<this.items.length;i++){
                this.rest_signup[i]=this.items[i].mailid
            }
         
        })
        this.$http
            .get("/admin/check_demo/" + this.userData.email)
            .then((res) => {
                if (res.data.demo == true) {
                    this.demo = true;
                }
            });

           

    },
    methods: {
        save_cont(){
            // console.log(this.items)
            // console.log(this.rest_signup)
            var fmails=[]
            for(var i=0;i<this.rest_signup.length;i++){
                var mails=[]
                for(var j=0;j<this.rest_signup[i].length;j++){
                    console.log(mails[0])
                        mails.push(this.rest_signup[i][j])
                }
                fmails.push(JSON.stringify(mails))
            }

            // var reas =[];
            // for(var i=0;i<this.items;i++){
            //     reas.push(this.items[i])
            // }
            let data = new FormData();
            data.append('reasons',  JSON.stringify(this.items));
            data.append('mails', JSON.stringify(fmails));
            this.$http
                .post("/admin/update_contact", data)
                .then(
                (response => {

                    console.log(response)
                    this.$toast({
                        component: ToastificationContent,
                        position: 'bottom-right',
                        props: {
                        title: this.$t(response.data.message),
                        icon: 'CheckIcon',
                        variant: 'primary',
                        },
                    })
                })
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));

        },
        tagValidator(tag) {
     
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(tag))
            {
                return true
            }else{
                return false
            }
            
        },
        repeateAgain() {
            this.items.push({
                id: (this.nextTodoId += this.nextTodoId),
            });

            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        // initTrHeight() {
        //     this.trSetHeight(null);
        //     this.$nextTick(() => {
        //         this.trSetHeight(this.$refs.form.scrollHeight);
        //     });
        // },
    },
};
</script>
<style lang="scss" scoped>
.repeater-form {
    overflow: hidden;
    transition: 0.35s height;
}
.b-form-tags-button {
    background-color: #390053 !important;
  
}
</style>
