<template>
  <b-card
    
  >
    <b-form @submit.prevent="role_add">
      <b-row>
        <b-col md="12" v-if="setting.lang.value == 1">

<b-tabs>
    <b-tab active  @click="onTabChange(1)" >
      <template #title>
          <i class="fa fa-language" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">{{ setting.primary.value }}</span>
      </template>
    </b-tab>

    <!-- Tab: Information -->
    <b-tab @click="onTabChange(2)">
      <template #title>
          <i class="fa fa-language" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">{{ setting.secondary.value }}</span>
      </template>
    </b-tab>

  </b-tabs>
</b-col>
<b-col md="6" v-if="!isHidden">
          <b-form-group
            :label="$t('new')+' '+$t('role')+' '+$t('name')+'*'"
           
          >
            <b-form-input
              id="mc-last-name"
              :placeholder="$t('role')+' '+$t('name')"
                v-model="role.role_name"
            />
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden">
          <b-form-group
            :label="$t('new')+' '+$t('role')+' '+$t('name')+'*'"
           
          >
            <b-form-input
              id="mc-last-name"
              :placeholder="$t('role')+' '+$t('name')"
                v-model="role.second_role_name"
            />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group
            :label="$t('account')+' '+$t('type')+'*'"
           
          >
              <v-select
          id="type"
          v-model="role.similar"
          :options="options_status"
          label="text"
          :reduce="sel => sel.value" 
            :placeholder="$t('role')+' '+$t('type')"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
           @input="onChange($event)"

        />
          </b-form-group>
        </b-col>
   
         <b-col md="6">
          <b-form-group
            :label="$t('Duplicate Role of')"
            
          >
        
           <v-select
          id="role_name"
         v-model="role.role_type"
        :options="this.state"
          label="role_name"
          :reduce="sel => sel.id" 
          :placeholder="$t('role')+' '+$t('name')"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            @input="onChange1($event)"
        />
           
          </b-form-group>
        </b-col>
       
        <b-col md="6">
         
        </b-col>
        <b-col md="6">
            <b-form-group
            :label="$t('permissions') + '*'" 
            /> 
          <table class="table ">
  <thead >
    <tr>
      <th scope="col">{{ $t('name') }}</th>
      <th scope="col">{{ $t('view') }}</th>
      <th scope="col">{{ $t('edit') }}</th>
    </tr>
  </thead>
  <tbody v-for="(name, index) in permissons_name" :key="index">
    <tr>
    
      <td>{{name.text}}</td>
      <td> <b-form-checkbox 
           :id="name.value+'-view'"
           v-model="Permissions" 
          :key="name.value+'-view'"
          :value="name.value+'-view'"
          name="flavour-3a"></b-form-checkbox></td>
      <td><b-form-checkbox 
             :id="name.value+'-edit'"
            v-model="Permissions" 
          :key="name.value+'-edit'"
          :value="name.value+'-edit'"
          name="flavour-3a"></b-form-checkbox></td>
    </tr>
  
  </tbody>
</table>

         


        </b-col>

      <b-col md="6">
         
        </b-col>
        <!-- submit and reset -->
        <b-col md="6"  style="text-align: right">
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            type="submit"
            variant="primary"
            class="mr-1 justify-content-end"
           
          >
         {{$t('save')}}
          </b-button>
         
        </b-col>
      </b-row>
    </b-form>

   
  </b-card>

</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol,BFormSelect,BFormRadioGroup,BTooltip,  BFormGroup, BFormInput,
   BFormCheckbox, BForm, BButton,BCard,BTable,BCardHeader,BTabs, BTab,
  BCardTitle,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'



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
    BCard,
    vSelect,
    BTooltip,
    BTable,BCardHeader,
  BCardTitle,BTabs, BTab,
  },
  directives: {
    Ripple,
  },
  data() {
    
    return {
      role: {},
      setting:{},
      isHidden: false,
      role_type:[
      { value: "MODULE",  },
        { value: "VIEW",  },
        { value: "EDIT",  },

      ],
      Permissions:[],
      selected: null,
      options: [
        { value: null, text: '--Document For--' , disabled: true},
        { value: 1, text: this.$t('driver') },
        { value: 2, text: this.$t('Store') },
      ],
      selected_status: null,
      options_status: [
       
      { value: 1, text: this.$t('Admin privileges') },
        { value: 2, text: this.$t('Store privileges') },
      ],
    selected_radio: 'No',
      options_radio: [
      { text: this.$t('yes'), value: 1 },
        { text: this.$t('no'), value: 2 },
       
      ],
      state:[],
      privileges:[],
      permissons_name : [
        { text:  this.$t('dashboard') ,value:'dashboard',values:'dashboard'},
        { text: this.$t('order'),value:'order' ,values:'order'},
        { text: this.$t('Store'),value:'restaurant',values:'restaurant'},
        { text: this.$t('city'),value:'city',values:'city'},
        { text:  this.$t('vehicle'),value:'vehicle',values:'vehicle'},
        { text: this.$t('driver') ,value:'driver',values:'driver'},
        { text: this.$t('document') ,value:'document',values:'document'},
        { text: this.$t('promocode'),value:'promocodes',values:'promocodes'},
        { text: this.$t('User'),value:'user',values:'user'},
        { text: this.$t('cuisines') ,value:'cuisines',values:'cuisines'},
        { text: this.$t('category') ,value:'category',values:'category'},
        { text: this.$t('reports') ,value:'report',values:'report'}, 
        { text: this.$t('carrier') ,value:'carrier',values:'carrier'},
        { text: this.$t('package') ,value:'package',values:'package'}, 
    ],
     permissons_view : [
      {
        id: 'view',
        name: 'View',
       
      },

      {
        id: 'edit',
        name: 'Edit',
       
      },
    ],
    permissons_edit : [
      {
        id: 'edit',
        name: 'Edit',
       
      },
    ],
  
    
    }
  },
  
   watch: {
    // whenever question changes, this function will run
    question(newQuestion, oldQuestion) {
      if (newQuestion.includes('?')) {
        this.getAnswer()
      }
    }
  },
  created() {
      this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
      
    },
   methods: {
    onTabChange(id) {

        if (id == 1) {
          this.isHidden = false;
        }
        else {
          this.isHidden = true;
        }
        

      },
         role_add() {
        let checkboxes = document.querySelectorAll('input[class="custom-control-input"]:checked');
            let values = [];
            checkboxes.forEach((checkbox) => {
                values.push(checkbox.value);
            });
            this.role.privilege =   values;
          let data = new FormData(); 
            data.append('role_name', this.role.role_name ? this.role.role_name : '');
            data.append('second_role_name', this.role.second_role_name ? this.role.second_role_name : '');

            data.append('role_type', this.role.similar);
            data.append('privilege', this.role.privilege);
          


            this.$http
                .post("/admin/role_add", data)
                .then(

                  (response => {
                       
                        if (response.data.status == true) {
                            this.$router.push({ name: "roles_list" })
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

          onChange(event) {
        
           this.$http.get('/admin/get_roles_byid/'+this.role.similar)
          .then(res => {
       
          this.state = res.data
        
          })

         
    },
     onChange1(event) {
    
      
           this.$http.get('/admin/role_privilege/'+this.role.role_type)
          .then(res => {
       
          this.privileges = res.data
           var uncheck=document.getElementsByTagName('input');
       
            for(var i=0;i<uncheck.length;i++)
           {
            if(uncheck[i].type=='checkbox')
            {
              uncheck[i].checked=false;
              
            }
          }
       this.privileges.forEach(function(element) {
           
        
           document.getElementById(element.name).checked = true;
          
        });
            
               
          })
            
        },
        
},

}
</script>
