<template>
  <b-card
    
  >
    <b-form @submit.prevent="promocode_add">
      <b-row>
        <b-col md="6">
          <b-form-group
            label="Title *"
          >
           <validation-provider
                  #default="{ errors }"
                  name="Title"
                  rules="required"
                >
                 
                 
            <b-form-input
              id="mc-last-name"
              placeholder="Title"
                v-model="promocode.title"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group
            label="Description *"
            
          >
           <validation-provider
                  #default="{ errors }"
                  name="Description"
                  rules="required"
                >
            <b-form-input
              id="mc-last-name"
              placeholder="Description"
                v-model="promocode.description"
            />
            <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Promo Code *"
           
          >
          <validation-provider
                  #default="{ errors }"
                  name="PromoCode"
                  rules="required"
                >
            <b-form-input
              id="mc-last-name"
              placeholder="PromoCode"
                v-model="promocode.promo_code"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Offer Type*"
            
          >
          
            <b-form-select
            
        v-model="promocode.offer_type"
         v-on:change="isHidden = !isHidden"
          >
        <option  value="1" >Amount</option>
        <option  value="0"  >Percent</option>
        </b-form-select>
          
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Maximum amount *"
            
            v-if="!isHidden"
          >
           <validation-provider
                  #default="{ errors }"
                  name="Maximum_amount"
                  rules="required"
                >
            <b-form-input
            
              id="Maximum_amount"
              placeholder="Maximum Amount"
                v-model="promocode.max_amount"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Minimum Order value *"
           
             v-if="!isHidden"
          >
           <validation-provider
                  #default="{ errors }"
                  name="Minimum Order value"
                  rules="required"
                >
            <b-form-input
          
              id="Minimum_Order_value"
              placeholder="Minimum Order Value"
                v-model="promocode.min_amount"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Discount *"
          
          >
           <validation-provider
                  #default="{ errors }"
                  name="Discount"
                  rules="required"
                >
            <b-form-input
              id="mc-last-name"
              placeholder="Discount"
                v-model="promocode.discount"
            />
              <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Usage Limit Per Coupon *"
           
          >
           <validation-provider
                  #default="{ errors }"
                  name="Usage Limit Per Coupon"
                  rules="required"
                >
            <b-form-input
              id="mc-last-name"
              placeholder="Enter Per Coupon"
                v-model="promocode.total_use"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Usage Limit Per User*"
           
          >
          <validation-provider
                  #default="{ errors }"
                  name="Usage Limit Per User"
                  rules="required"
                >
            <b-form-input
              id="mc-last-name"
              placeholder="Enter Per User"
                v-model="promocode.use_per_customer"
            />
             <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Available From *"
           
          >
           <validation-provider
                  #default="{ errors }"
                  name="Available From"
                  rules="required"
                >
             <b-form-datepicker
          id="example-datepicker"
           v-model="promocode.available_from"
         class="mb-1"
         />
         <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Expiry Date *"
           
          >
          <validation-provider
                  #default="{ errors }"
                  name="Expiry Date"
                  rules="required"
                >
          <b-form-datepicker
          id="example-datepicker1"
           v-model="promocode.valid_till"
         class="mb-1"
         />
         <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
            
          </b-form-group>
        </b-col>
        
        <b-col md="6">
          <b-form-group
            label="Status"
           
          >
            <b-form-select
        v-model="promocode.status"
        :options="options_status"
      />
          </b-form-group>
        </b-col>
       
        
       

       
</b-row>
        <!-- submit and reset -->
        <b-col>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            class="mr-1"
          >
           Save
          </b-button>
         
        </b-col>
      
    </b-form>

   
  </b-card>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BCard,  BRow, BCol,BFormSelect,BFormRadioGroup,BFormDatepicker,  BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email } from '@validations'
export default {
  components: {
    BCardCode,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    BFormRadioGroup, 
    BFormDatepicker,
     ValidationProvider,
    ValidationObserver,
    BCard,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      promocode: {},
      selected_status: null,
      options_status: [
        { value: null, text: '--Status--', disabled: true },
        { value: 1, text: 'Active' },
        { value: 0, text: 'Inactive' },
      ],
    //   selected_offer: 1,
    //   options_offer: [
    //     { value: 1, text: 'Amount' },
    //     { value: 0, text: 'Percent' },
    //   ],
       
    isHidden:  true
    
    }
  },
   methods: {
        promocode_add() {
          
            this.$http
                .post("/api/add_to_promocode", this.promocode)
                .then(
                    (response => {
                        console.log(response);
                        if (response.data.status == true) {
                            this.$router.push({ name: "promocode_list" })
                        } else {

                             this.popToast(response) 
                        }
                    
                      
                      } ) 
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
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
   
}
</script>
