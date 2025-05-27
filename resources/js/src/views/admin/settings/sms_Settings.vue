<template>
    
     <b-row>
        <b-col md="12">
      <b-card-text class="mb-0">
        {{$t('enable')}} {{$t('SMS')}}
      </b-card-text>
      <b-form-group>
        <!-- <input type="checkbox"  @change="enable_sms($event)" name="type"  id="enable"
        v-model="enable" class="custom-control-input" > -->
      <b-form-checkbox
        class="custom-control-success"
        name="check-button"
        switch
        @change="enable_sms($event)"
        id="enable"
        v-model="enable"  

      >
        <span class="switch-icon-left">
          <feather-icon icon="CheckIcon" />
        </span>
        <span class="switch-icon-right">
          <feather-icon icon="XIcon" />
        </span>
      </b-form-checkbox>
    </b-form-group>
    </b-col>
    <br>
    <b-col md="12" style="margin-top: 4%;">

    </b-col>
        <b-col md="6">
 <b-card id="sms" no-body class="border-primary">
 
<input type="radio" id="one"  name="sms_provider" hidden="hidden"  value="1" v-on:click="sms_provider"/>
<label for="one" style="margin: 3%;">
  <span class="d-flex justify-content-between flex-wrap mb-50">
                                <span class="fw-bolder"><strong>{{$t('MSG')}} 91</strong></span>
                                <i class="fa-solid fa-circle-check" style="color: green" id="msg_select"></i>
                              </span>
                          
                              <b-form @submit.prevent="add_msg">
                                <input type="hidden" name="type" v-model="brand.type" value="sms">
                                <input type="hidden" name="provider" v-model="brand.provider" id="provider" value="1">

                              <fieldset>
                               <div class="row">
                                <div class="col-md-12">  
                                
                                  <div class="form-group row">                        
                                    <label class="col-md-3">{{$t('template')}} {{$t('id')}}</label>                          
                                    <div class="col-md-9">
                                        <input type="text" name="template_id" v-model="brand.msg91_template_id" id="template_id" class="form-control" >
                                   </div>
                                </div>
                                
                                  <div class="form-group row">                        
                                    <label class="col-md-3"> {{$t('auth')}} {{$t('Key')}}</label>                          
                                    <div class="col-md-9"> 
                                        <input type="text" name="auth_key" v-model="brand.msg91_auth_key" id="auth_key" class="form-control" >
                                   </div>
                                </div>
                                <div class="form-group row">
                                 <label class="col-md-3"></label>
                                   <div class="col-md-9">
                                     <button type="submit" id="m_submit" class="btn btn-primary mr-1" style="padding: 10px 15px;">{{ $t('update') }} {{ $t('settings') }}</button> &nbsp;
                                    
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
 
<input type="radio" id="two" name="sms_provider" hidden="hidden" v-model="picked" value="2" v-on:click="sms_provider"/>
<label for="two" style="margin: 3%;">
  <span class="d-flex justify-content-between flex-wrap mb-50">
                                    <span class="fw-bolder"><strong>{{$t('Twillio')}}</strong></span>
                                <i class="fa-solid fa-circle-check" style="color: green" id="twillio_select"></i>
                                   
                                  </span>
                                
                                  <b-form @submit.prevent="add_msg">
                                    <input type="hidden" name="type" v-model="brand.type" value="sms">
                                <input type="hidden" name="provider" v-model="brand.provider" id="provider" value="2">

                                  <fieldset>
                                   <div class="row">
                                    <div class="col-md-12">  
                                    
                                      <div class="form-group row">                        
                                        <label class="col-md-3">{{$t('SID')}}</label>                          
                                        <div class="col-md-9">
                                            <input type="text" name="t_sid" v-model="brand.t_sid" id="t_sid" class="form-control" placeholder="TWILIO_SID" required="" >
                                       </div>
                                    </div>
                                    
                                      <div class="form-group row">                        
                                        <label class="col-md-3">{{$t('TOKEN')}}</label>                          
                                        <div class="col-md-9">
                                            <input type="text" name="t_token" v-model="brand.t_token" id="t_token" class="form-control" placeholder="TWILIO_TOKEN" required="" >
                                       </div>
                                    </div>
                                    <div class="form-group row">                        
                                        <label class="col-md-3">{{$t('FROM')}}</label>                          
                                        <div class="col-md-9">
                                            <input type="text" name="t_from" v-model="brand.t_from" id="t_from" class="form-control" placeholder="TWILIO_FROM" required="" >
                                       </div>
                                    </div>
                                    <div class="form-group row">
                                     <label class="col-md-3"></label>
                                       <div class="col-md-9">
                                         <button type="submit" id="t_submit" class="btn btn-primary mr-1" style="padding: 10px 15px;">{{ $t('update') }} {{ $t('settings') }}</button> &nbsp;
                                        
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
    BFormGroup, BFormInput, BFormCheckbox, BForm,BFormSelect, BCardText,BFormFile,
    BFormTextarea,BFormRadio,
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
       BForm,BFormSelect,
        BCardText,BFormFile,
        BFormTextarea,
        BFormRadio,
    },
    data() {
      return {
       
         selected: 1,
       
          brand: {},
          enable: false,
      }
    },
    created() {
      
      this.$http.get('/admin/settings/site')
      .then(res => {
       console.log(res.data.message);
            this.brand=res.data.message;

            if(process.env.MIX_IS_DEMO){
          
          this.brand.auth_key ="***************************"
          this.brand.template_id="***************************"
          this.brand.provider="***************************"
          this.brand.type="***************************"
       




        }
            if(this.brand.sms_enable == 1)
            { 
                document.getElementById('enable').checked = true;
               
            }
            else
            {    
                document.getElementById('enable').checked = false;
            
            }


           

  //  var hid =    document.querySelector('input[id="enable"]:checked').value;
    // alert(this.brand.sms_enable);
   if(this.brand.sms_enable == 0){
              document.getElementById("sms").style.display = "none";
              document.getElementById("sms1").style.display = "none";
     }
  if(this.brand.sms_enable == 1){
     document.getElementById("twillio_select").style.display = "none";
     document.getElementById("t_sid").disabled = true;
     document.getElementById("t_token").disabled = true;
     document.getElementById("t_from").disabled = true;
     document.getElementById("auth_key").disabled = false;
     document.getElementById("template_id").disabled = false;
     document.getElementById("t_submit").disabled = true;
     document.getElementById("m_submit").disabled = false;
     document.getElementById("customOptionsCheckableRadios1").checked = true;
     document.getElementById("customOptionsCheckableRadios2").checked = false;


  }else{
   document.getElementById("msg_select").style.display = "none";
     document.getElementById("auth_key").disabled = true;
     document.getElementById("template_id").disabled = true;
     document.getElementById("t_sid").disabled = false;
     document.getElementById("t_token").disabled = false;
     document.getElementById("t_from").disabled = false;
     document.getElementById("m_submit").disabled = true;
     document.getElementById("t_submit").disabled = false;
     document.getElementById("customOptionsCheckableRadios2").checked = true;
     document.getElementById("customOptionsCheckableRadios1").checked = false;



  }



         })


  
  },

  methods: {
    add_msg() {
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
                this.brand.provider =   document.querySelector('input[name="sms_provider"]:checked').value;
                this.brand.type   = "sms"; 
                // alert(document.getElementById("provider").value);
              this.$http
              .post("/admin/update-setting", this.brand)
                   .then(
                      (response => {
                          console.log(response);
                          if (response.data.status == true) {
                              this.$router.push({ name: "sms_settings" })
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
                      
                        
                        } )                  
                  )
                  .catch((error) => console.log(error))
                  .finally(() => (this.loading = false));
          },
           

            enable_sms()
            {
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
              var enable =  document.querySelector('input[id="enable"]:checked').value;
              // alert(enable);
             if(enable == true){
              document.getElementById("sms").style.display = "none";
              document.getElementById("sms1").style.display = "none";
             }
             else{
              document.getElementById("sms").style.display = "block";
              document.getElementById("sms1").style.display = "block";
            
             }
             this.brand.status_sms  =  this.brand.sms_enable;
              this.$http
              .post("/admin/enable-sms-toggle",this.brand)
                   .then(
                      (response => {
                          console.log(response);
                          if (response.data.status == true) {
                              this.$router.push({ name: "sms_settings" })


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
                      
                        
                        } )                  
                  )
                  .catch((error) => console.log(error))
                  .finally(() => (this.loading = false));

            },
            sms_provider()
            {
              var sms = document.querySelector('input[name="sms_provider"]:checked').value;
              // document.getElementsByName("sms_provider").value;
            // alert(sms);
                if(sms == "2")
                {
                  document.getElementById("msg_select").style.display = "none";
                  document.getElementById("twillio_select").style.display = "block";
                    document.getElementById("auth_key").disabled = true;
                    document.getElementById("template_id").disabled = true;
                    document.getElementById("t_sid").disabled = false;
                    document.getElementById("t_token").disabled = false;
                    document.getElementById("t_from").disabled = false;
                    document.getElementById("m_submit").disabled = true;
                    document.getElementById("t_submit").disabled = false;
                  


                }
                else{
                  document.getElementById("msg_select").style.display = "block";
                  document.getElementById("twillio_select").style.display = "none";
                    document.getElementById("t_sid").disabled = true;
                    document.getElementById("t_token").disabled = true;
                    document.getElementById("t_from").disabled = true;
                    document.getElementById("auth_key").disabled = false;
                    document.getElementById("template_id").disabled = false;
                    document.getElementById("t_submit").disabled = true;
                    document.getElementById("m_submit").disabled = false;
                


                }
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
  