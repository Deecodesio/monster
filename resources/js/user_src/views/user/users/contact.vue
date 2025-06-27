<template>
  <div class="mt-5">
    <div class="pt-4 text-center">
      <b-img src="/more1.jpg" fluid alt="Scan" />
    </div>

    <div class="container pt-1">
      <b-card>
        <h4 class="pb-2">Contact Us</h4>
        <b-row>
          <!-- Left: Form -->
         <b-col cols="12" lg="4">
            <div v-if="rows.length > 0 && rows[0].contact" v-html="rows[0].contact"></div>
            <div v-else class="text-muted text-center">Loading contact info or no data available.</div>
          </b-col>

          <!-- Right: Info Block -->
           <b-col cols="12" lg="8" class="pb-2">
            <ValidationObserver ref="simpleRules">
              <b-form @submit.prevent="validationForm">
                <b-row>
                  <!-- Name -->
                  <b-col md="6">
                    <b-form-group label="Name">
                      <ValidationProvider name="Name" rules="required" v-slot="{ errors }">
                        <b-form-input
                          v-model="restaurant.name"
                          :state="errors[0] ? false : null"
                          placeholder="Name"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </ValidationProvider>
                    </b-form-group>
                  </b-col>

                  <!-- Email -->
                  <b-col md="6">
                    <b-form-group label="Email Id">
                      <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                        <b-form-input
                          v-model="restaurant.email"
                          type="email"
                          :state="errors[0] ? false : null"
                          placeholder="Email"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </ValidationProvider>
                    </b-form-group>
                  </b-col>

                  <!-- Phone -->
                  <b-col md="6">
                    <b-form-group label="Mobile number">
                      <ValidationProvider name="Mobile number" rules="required" v-slot="{ errors }">
                        <b-form-input
                          v-model="restaurant.phone"
                          type="number"
                          :state="errors[0] ? false : null"
                          placeholder="Mobile number"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </ValidationProvider>
                    </b-form-group>
                  </b-col>

                  <!-- Reason (v-select) -->
                  <b-col md="6">
                    <b-form-group label="Reason">
                      <v-select
                        v-model="restaurant.reason"
                        :options="reasons"
                        label="reason"
                        :reduce="r => r.id"
                        placeholder="Select Reason"
                        :dir="direction"
                      />
                    </b-form-group>
                  </b-col>

                  <!-- Comments -->
                  <b-col cols="12">
                    <b-form-group label="Comments">
                      <b-form-textarea
                        v-model="restaurant.comments"
                        placeholder="Comments"
                        rows="3"
                      />
                    </b-form-group>
                  </b-col>

                  <!-- Submit Button -->
                  <b-col cols="12" class="pt-1  ">
                    <b-button variant="primary" type="submit">Submit</b-button>
                  </b-col>
                </b-row>
              </b-form>
            </ValidationObserver>
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>

<script>
import {
  BButton,
  BForm,
  BFormGroup,
  BFormInput,
  BRow,
  BCol,
  BCard,
  BFormTextarea,
  BImg,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import vSelect from "vue-select";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import store from '@@@/store'

export default {
  name: "ContactPage",
  components: {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BFormTextarea,
    BImg,
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  data() {
    return {
      rows: [],
      reasons: [],
      restaurant: {
        name: '',
        email: '',
        phone: '',
        reason: null,
        comments: ''
      },
    };
  },
  computed: {
    direction() {
      return store.state.appConfig?.isRTL ? 'rtl' : 'ltr';
    }
  },
  created() {
        store.commit('deliware_cart/UPDATE_FOOTER', false)

    this.$http.get("/getcontactreasons").then(res => {
      this.reasons = res.data || [];
      store.commit('deliware_cart/UPDATE_FOOTER', true);
    });

    this.$http.get("/fetch_contact").then(res => {
      console.log("Fetched contact content:", res.data); 
      this.rows = res.data?.data || [];
       store.commit('deliware_cart/UPDATE_FOOTER', true);
    });
  },
  methods: {
    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          this.$http.post("/contacts", this.restaurant).then(res => {
            if (res.data.status) {
              this.$toast({
                component: ToastificationContent,
                props: {
                  title: res.data.message,
                  icon: "BellIcon",
                  variant: "success",
                },
              });
            }
          });
        }
      });
    },
  },
};
</script>

<style>
.pt-10 {
  padding-top: 10rem !important;
}
</style>
