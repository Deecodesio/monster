<template>

  <b-row>


    <b-col md="6">
      <b-card id="sms" no-body class="border-primary">

        <input class="custom-option-item-check" type="checkbox" hidden="hidden" name="payment_gateway_stripe" value="0"
          id="customOptionsCheckableRadios1" v-on:click="payment_stripe">
        <label class="custom-option-item p-1" for="customOptionsCheckableRadios1">
          <span class="d-flex justify-content-between flex-wrap mb-50">
            <span class="fw-bolder"><strong>{{ $t('Stripe') }}</strong></span>
            <i class="fa-solid fa-circle-check" style="color: green" id="msg_select"></i>

          </span>
          <b-form @submit.prevent="add_payment" id="stripe">
            <input type="hidden" name="type" value="payment_gateway">
            <input type="hidden" name="payment" id="payment" value="1">

            <fieldset>
              <div class="row">
                <div class="col-md-12">

                  <div class="form-group row">
                    <label class="col-md-3">{{ $t('Stripe') }} {{ $t('API') }} {{ $t('Key') }} {{ $t('PK') }} </label>
                    <div class="col-md-9">
                      <input type="text" name="stripe_pk_key" v-model="brand.stripe_pk_key" id="stripe_pk_key"
                        class="form-control" placeholder="Stripe API Key PK" required="">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3">{{ $t('Stripe') }} {{ $t('API') }} {{ $t('Key') }} {{ $t('SK') }}</label>
                    <div class="col-md-9">
                      <input type="text" name="stripe_sk_key" v-model="brand.stripe_sk_key" id="stripe_sk_key"
                        class="form-control" placeholder="Stripe API Key SK" required="">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3">{{ $t('Stripe') }} {{ $t('version') }} </label>
                    <div class="col-md-9">
                      <input type="text" name="stripe_version" v-model="brand.stripe_version" id="stripe_version"
                        class="form-control" placeholder="Stripe Version" required="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3"></label>
                    <div class="col-md-9">
                      <button type="submit" id="m_submit" class="btn btn-primary mr-1"
                        style="padding: 10px 15px;">{{ $t('update') }} {{ $t('settings') }}</button> &nbsp;

                    </div>
                  </div>

                </div>
              </div>
            </fieldset>
          </b-form>
        </label>

      </b-card>
    </b-col>




    <b-col md="6">
      <b-card id="sms1" no-body class="border-primary">
        <input class="custom-option-item-check" hidden="hidden" type="checkbox" name="payment_gateway_razorpay"
          value="0" id="customOptionsCheckableRadios2" v-on:click="payment_razorpay">
        <label class="custom-option-item p-1" for="customOptionsCheckableRadios2">
          <span class="d-flex justify-content-between flex-wrap mb-50">
            <span class="fw-bolder"><strong>{{ $t('razorpay') }}</strong></span>
            <i class="fa-solid fa-circle-check" style="color: green" id="twillio_select"></i>

          </span>
          <b-form @submit.prevent="add_payment" id="razorpay">
            <input type="hidden" name="type" value="payment_gateway">
            <input type="hidden" name="payment" id="payment" value="2">

            <fieldset>
              <div class="row">
                <div class="col-md-12">

                  <div class="form-group row">
                    <label class="col-md-3">{{ $t('razorpay') }} {{ $t('Key') }}</label>
                    <div class="col-md-9">
                      <input type="text" name="razor_key" v-model="brand.razor_key" id="razor_key" class="form-control"
                        placeholder="Razor Key" required="">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3">{{ $t('razorpay') }} {{ $t('secret') }}</label>
                    <div class="col-md-9">
                      <input type="text" name="razor_secret" v-model="brand.razor_secret" id="razor_secret"
                        class="form-control" placeholder="Razor Secret" required="">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3"></label>
                    <div class="col-md-9">
                      <button type="submit" id="t_submit" class="btn btn-primary mr-1"
                        style="padding: 10px 15px;">{{ $t('update') }} {{ $t('settings') }} </button> &nbsp;

                    </div>
                  </div>

                </div>
              </div>

            </fieldset>
          </b-form>
        </label>


      </b-card>
    </b-col>
  </b-row>
</template>
 
<script>
import {
  BCard, BCardHeader, BCardBody, BBadge, BButton, BRow, BCol, BAlert, BLink,
  BFormGroup, BFormInput, BFormCheckbox, BForm, BFormSelect, BCardText, BFormFile,
  BFormTextarea, BFormRadio,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


export default {
  directives: {
    Ripple,
  },

  components: {
    BCard,
    BCardHeader,
    BCardBody,
    BBadge,
    BButton,
    BRow, BCol,
    BAlert, BLink,
    BFormCheckbox,
    BForm, BFormSelect,
    BCardText, BFormFile,
    BFormTextarea,
    BFormRadio,
  },
  data() {
    return {

      selected: 1,

      brand: {},

    }
  },
  created() {

    this.$http.get('/admin/settings/site')
      .then(res => {
        console.log(res.data.message);
        this.brand = res.data.message;



        var razorpay = this.brand.razorpay;
        var stripe = this.brand.stripe_status;
        if(process.env.MIX_IS_DEMO){
          
          this.brand.razor_secret ="***************************"
          this.brand.razor_key="***************************"
          this.brand.stripe_version="***************************"
          this.brand.stripe_sk_key="***************************"
          this.brand.stripe_pk_key="***************************"

       




        }
        console.log(stripe);

        if (razorpay == "1") {
          document.getElementById("twillio_select").style.display = "block";
          document.getElementById("customOptionsCheckableRadios1").value = 1;


        }
        else {

          document.getElementById("twillio_select").style.display = "none";
          document.getElementById("t_submit").disabled = true;
          var form = document.getElementById("razorpay");
          var elements = form.elements;
          for (var i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
          }

        }


        if (stripe == "1") {
          document.getElementById("msg_select").style.display = "block";
          document.getElementById("customOptionsCheckableRadios1").value = 1;
        }
        else {
          document.getElementById("msg_select").style.display = "none";
          document.getElementById("m_submit").disabled = true;
          var form = document.getElementById("stripe");
          var elements = form.elements;
          for (var i = 0, len = elements.length; i < len; ++i) {
            elements[i].readOnly = true;
          }


        }


      })



  },

  methods: {
    add_payment() {
      if(process.env.MIX_IS_DEMO){
          
          this.$toast({
                  component: ToastificationContent,
                  position: 'bottom-right',
                  props: {
                    title: this.$t("Settings Cannot be Changed in Demo Mode"),
                    icon: 'CheckIcon',
                    variant: 'danger',
                  },
                })
  
                return false
  
  
          }
      this.brand.stripe_status = document.getElementById("customOptionsCheckableRadios1").value;
      this.brand.razorpay = document.getElementById("customOptionsCheckableRadios2").value;

      this.brand.type = "payment_gateway";

      this.$http
        .post("/admin/update-setting", this.brand)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "payment_settings" })
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })
            } else {

              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'warning',
                },
              })
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },


    payment_razorpay() {
      var sms = document.querySelector('input[name="payment_gateway_razorpay"]:checked').value;

      if (sms == "1") {
        document.getElementById("twillio_select").style.display = "none";
        document.getElementById("customOptionsCheckableRadios2").value = 0;
        document.getElementById("t_submit").disabled = true;
        var form = document.getElementById("razorpay");
        var elements = form.elements;
        for (var i = 0, len = elements.length; i < len; ++i) {
          elements[i].readOnly = true;
        }
      }
      else {
        document.getElementById("twillio_select").style.display = "block";
        document.getElementById("customOptionsCheckableRadios2").value = 1;
        document.getElementById("t_submit").disabled = false;
        var form = document.getElementById("razorpay");
        var elements = form.elements;
        for (var i = 0, len = elements.length; i < len; ++i) {
          elements[i].readOnly = false;
        }

      }

    },


    payment_stripe() {
      var sms = document.querySelector('input[name="payment_gateway_stripe"]:checked').value;
      // document.getElementsByName("sms_provider").value;

      if (sms == "1") {
        document.getElementById("msg_select").style.display = "none";
        document.getElementById("customOptionsCheckableRadios1").value = 0;
        document.getElementById("m_submit").disabled = true;
        var form = document.getElementById("stripe");
        var elements = form.elements;
        for (var i = 0, len = elements.length; i < len; ++i) {
          elements[i].readOnly = true;
        }
      }
      else {
        document.getElementById("msg_select").style.display = "block";
        document.getElementById("customOptionsCheckableRadios1").value = 1;
        document.getElementById("m_submit").disabled = false;
        var form = document.getElementById("stripe");
        var elements = form.elements;
        for (var i = 0, len = elements.length; i < len; ++i) {
          elements[i].readOnly = false;
        }

      }
      // if(sms == "1")
      // {

      //   document.getElementById("msg_select").style.display = "none";

      //   document.getElementById("t_submit").disabled = false;
      //        var form = document.getElementById("razorpay");
      //     var elements = form.elements;
      //     for (var i = 0, len = elements.length; i < len; ++i) {
      //         elements[i].readOnly = false;
      //     }

      // }



      // else{
      //     document.getElementById("msg_select").style.display = "none";
      //   // document.getElementById("twillio_select").style.display = "none";
      //   //   document.getElementById("t_submit").disabled = true;
      //   //   document.getElementById("m_submit").disabled = false;

      //     // var form = document.getElementById("stripe");
      //     // var elements = form.elements;
      //     // for (var i = 0, len = elements.length; i < len; ++i) {
      //     //     elements[i].readOnly = false;
      //     // }

      //     // var form = document.getElementById("razorpay");
      //     // var elements = form.elements;
      //     // for (var i = 0, len = elements.length; i < len; ++i) {
      //     //     elements[i].readOnly = true;
      //     // }

      // }
    }
  },
}
</script>

 
 <!-- <style>
 *,
*:before,
*:after{
   box-sizing: border-box;
   padding: 0;
   margin: 0;
}
body{
   background-color: #f5f7ff;
   font-family: 'Poppins',sans-serif;
   color: #2c2c51;
   height: 100vh;
   width: 100vw;
   display: flex;
   align-items: center;
   justify-content: space-around;
}
.card{
   height: 150px;
   width: 200px;
   position: relative;
}
input[type="radio"]{
   -webkit-appearance: none;
   appearance: none;
   background-color: white;
   height: 100%;
   width: 100%;
   border-radius: 10px;
   position: absolute;
   box-shadow: 7px 7px 15px rgba(2,28,53,0.08);
   cursor: pointer;
   outline: none;
}
input[type="radio"]:before{
   content: "";
   position: absolute;
   height: 22px;
   width: 22px;
   background-color: #f9fafd;
   border: 1px solid #e2e6f3;
   border-radius: 50%;
   top: 35px;
   right: 20px;
}
input[type="radio"]:after{
   content: "";
   position: absolute;
   height: 13px;
   width: 13px;
   background-color: transparent;
   border-radius: 50%;
   top: 39.5px;
   right: 24.5px;
}
label{
   position: absolute;
   margin: 20px;
   cursor: pointer;
}
h5{
   font-weight: 600;
   font-size: 18px;
   letter-spacing: 0.5px;
   margin: 15px 0 20px 0;
}
h2{
   font-size: 28px;
}
span{
   font-weight: 400;
   font-size: 18px;
   color: #7b7b93;
}
input[type="radio"]:hover{
   transform: scale(1.05);
}
input[type="radio"]:checked{
   border: 3px solid #478bfb;
}
input[type="radio"]:checked:after{
   background-color: #478bfb;
}


 </style> -->
 