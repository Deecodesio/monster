<template>
  <b-card
  >
    <b-form @submit.prevent="addProduct">
      <b-row>
        <b-col md="6">
          <b-form-group
            label="Name"
            label-for="mc-first-name"
          >
         
            <b-form-input
              id="name"
              placeholder="Name"
              v-model="product.name"
            />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group
            label="Featured Image"
            label-for="mc-first-name"
          >
           <p>
          Note: Please upload Image size 252x152 for featured Product
          </p>
          </b-form-group>
        </b-col>

         <b-col md="6">
              <b-form-group
                label="Description"
                
              >
                <b-form-textarea
                  v-model="product.description"
                  id="textarea-default"
                   placeholder="Textarea"
                   rows="6"
                    />
              </b-form-group>
            </b-col>

               <b-col md="6">
              <b-form-group
                label="Description"
                
              >
                 <b-img
            :src="url1? url1:require('@/assets/images/banner/banner-34.jpg')"
            rounded
            fluid
            id="profile-img"
            alt="profile photo"
            @click="$refs.onFileChange1.click()"
          />
           <b-form-file
      v-model="product.image"
      placeholder="Choose a file or drop it here..."
      drop-placeholder="Drop file here..."
        @change="onFileChange1"
    />
              </b-form-group>
            </b-col>
<b-col md="6">
          <b-form-group
            label="Category "
            label-for="mc-first-name"
          >
         
            <v-select
         v-model="product.category"
        :options="this.category"
          label="category_name"
          :reduce="sel => sel.id" 
            placeholder="Select Category"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />
          </b-form-group>
        </b-col>
         <b-col md="6">
          <b-form-group
            label="Price"
            label-for="mc-first-name"
          >
         
            <b-form-input
              id="Price"
              placeholder="Price"
              v-model="product.price"
            />
          </b-form-group>
        </b-col>

         </b-col>
         <b-col md="6">
          <b-form-group
            label="Pure Veg"
            label-for="mc-first-name"
          >
         
       <b-form-radio-group
      v-model="product.food_type"
      :options="options_veg"
      class="demo-inline-spacing mb-1"
      value-field="value"
      text-field="text"
    />
          </b-form-group>
        </b-col>

        <b-col md="6">
          <b-form-group
            label="Tax In % *"
            label-for="mc-first-name"
          >
         
            <b-form-input
              id="tax"
              placeholder="Tax"
              v-model="product.tax"
            />
          </b-form-group>
        </b-col>

         <b-col md="6">
          <b-form-group
            label="Status"
          >
             <v-select
         v-model="product.status"
        :options="options_status"
          label="text"
          :reduce="sel => sel.value" 
            placeholder="Select status"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />
          </b-form-group>
        </b-col>

         <b-col md="6">
              <b-form-group
                label="Addons *"
              > 
              <b-form-checkbox 
              v-model="addon_name"
            @change="cuisinesMethod"
             v-for="addon in this.addons"
          :key="addon.id"
          :value="addon.id"
          name="flavour-3a">{{ addon.name }}</b-form-checkbox>
             </b-form-checkbox-group>
           
                
              </b-form-group>
            </b-col>

             <b-col md="6">
          <b-form-group
            label="Status"
          >
             <v-select
         v-model="product.stockstatus"
        :options="options_stock"
          label="text"
          :reduce="sel => sel.value" 
            placeholder="Select status"
           :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
        />
          </b-form-group>
        </b-col>




                <b-col md="12">
           <b-form-group label="Food Quantity">
      <!-- prop `add-on - change` is needed to enable adding tags vie the `change` event -->
      <b-form-tags
        v-model="value"
        size="lg"
        add-on-change
        no-outer-focus
      >
        <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
          <ul
            v-if="tags.length > 0"
            class="list-inline d-inline-block mb-1"
          >
            <li
              v-for="(tag, index) in tags"
              :key="tag"
              class="list-inline-item"
            >
            
            
              <b-form-tag
                :title="tag"
                :disabled="disabled"
                variant="danger"
                :id="'tag_'+tag"
                v-model="inputText"
                @remove="removeTag(tag);showModal(index);"
              >
                {{ tag }}
              </b-form-tag>
            </li>
          </ul>

           
          <b-form-select
           placeholder="Select Food Quantity"
            :disabled="disabled || availableOptions.length === 0"
            :options="availableOptions"
            v-on="inputHandlers"
            @input="repeateAgain()"
          >
            <template v-slot:first>
              <!-- This is required to prevent bugs with Safari -->
              <option
                disabled
                value=""
              >
                Choose a tag...
              </option>
            </template>
          </b-form-select>
        </template>
      </b-form-tags>
    </b-form-group>
        </b-col>
        

        <b-col md=12>
        <b-form-group>
        <b-form
        ref="form"
        :style="{height: trHeight}"
        class="repeater-form"
        @submit.prevent="repeateAgain"

      >

        <!-- Row Loop -->
        <b-row
          v-for="(item, index) in items"
          :id="index"
          :key="index"
          ref="row"
        >

          <!-- Item Name -->
         <b-col md="4">
            <b-form-group
             
            
            > 
            
           <b-form-input
            :id="'food_price'+index"
                 placeholder="price"
                   
                  />
            </b-form-group>
          </b-col>

          <!-- Cost -->
          <b-col md="4">
            <b-form-group
             
            >
               <b-form-input
                :id="'food_des'+index"
                 placeholder="description"
                 
                  />
            </b-form-group>
           
          </b-col>

          <!-- Remove Button -->
           <b-col
            lg="2"
            md="3"
            class="mb-50"
          >

           <b-form-group
            >
           
                 <input type="radio"   v-model="product.food_quantity_default"  :name="'food_quantity_default'" > Make default
                  <b-button
              v-ripple.400="'rgba(234, 84, 85, 0.15)'"
              variant="outline-danger"
              class="mt-0 mt-md-2"
              @remove="removeItem(index)"
              hidden="hidden"
            >
              <feather-icon
                icon="XIcon"
                class="mr-25"
              />
              <span>Delete</span>
            </b-button>
            </b-form-group>
          
          </b-col>
          <b-col cols="12">
            <hr>
          </b-col>
          
        </b-row>

      </b-form>
        </b-form-group>
    </b-col>
      
   

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
      </b-row>
    </b-form>
  </b-card>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormGroup,BImg,BMedia,BFormTags, BFormTag,  
   BFormRadioGroup , BAvatar,BFormInput, BFormCheckbox,BFormTextarea, BForm, BButton,BFormSelect,
    BCardText,BCard,BFormFile,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'

export default {
  components: {
    BCardCode,
    BRow,
    BMedia,
    BAvatar,
    BFormTags,
    BFormTag,  
    BImg,
    BFormTextarea,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BFormRadioGroup,
    BButton,
    BFormSelect,
    BCardText,
    BCard,
    vSelect,
    BFormFile,

  },
  computed: {
    availableOptions() {
      return this.options.filter(opt => this.value.indexOf(opt) === -1)
    },
  },
    mounted() {
    this.initTrHeight()
  },
   destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  directives: {
    Ripple,
  },
  data() {
    return {  
        product: {},
        rows: [],
        user_info:{},
        category:[],
        options_veg:[

        { text: 'No', value: 0, },
        { text: 'Yes', value: 1, },
        ],
         options_status:[

        { text: 'Enable', value: 1, },
        { text: 'Disable', value: 0, },
        ],
        options_stock:[
        { text: 'In Stock', value: 1, },
        { text: 'Out Of Stock', value: 0, },

        ],
inputText: '',
        addons:[],
        addon_name:[],

         options: [],
    value: [],
    
    items: [],
    url1:null,

    }
  },
  methods: {
        addProduct() {
          let product1 = new FormData(); 
             product1.append('res_id', localStorage.id);
            product1.append('name', this.product.name);
            product1.append('description', this.product.description);
            product1.append('category', this.product.category);
             product1.append('menu', this.product.menu);
            product1.append('status', this.product.status);
            product1.append('stockstatus', this.product.stockstatus);
             product1.append('price', this.product.price);
            product1.append('tax', this.product.tax);
            product1.append('food_type', this.product.food_type);
             product1.append('image', this.product.image);
               product1.append('food_quantity', this.product.food_quantity);
             product1.append('add_ons[]', this.product.add_ons);
             
               this.options.forEach(function(number) {

                // alert(number);
            //  if(number.document_for == 2)
            //  {
            //    data.append('document['+number.id +'][document]', document.getElementById('document'+number.id).files[0]);
              
            //     if(number.expiry_date_needed == 1)
            //     {
            //       data.append('document['+number.id +'][date]', document.getElementById('date'+number.id).value);
                
            //     }
              
            // }
            
            
                    var val =  document.getElementById('tag_'+number+'__taglabel_').innerHTML;
                    console.log(val);

             });
            //    if(number.name == document.getElementById('tag_'+number).value)
            // {
            //    product1.append('food_quantity_default['+number.id +']', this.product.food_quantity_default);
            // }
            
            //  product1.append('food_quantity_price['+number.id +']', this.product.food_quantity_price);
            //    product1.append('food_quantity_desc['+number.id +']', this.product.food_quantity_desc);
            
           
            this.$http
                .post("/store/store_addons")
                 .then(
                    (response => {
                        console.log(response);
                        if (response.data.status == true) {
                            this.$router.push({ name: "addons_list" })
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

    cuisinesMethod(){
       this.product.addons =   this.addon_name
  },


      repeateAgain() {
       
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      
      })
        // alert( this.nextTodoId)
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
        // alert(index);
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },
  onChange()
  {
    alert("hi");
  },
  onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
     reader.onload = () => {
     this.url1 = reader.result;
      };
       
   
    },

    showModal(index)
    {
      
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)

    },
    },
    created() {
    if(this.$route.params.id){
      this.$http.get('/store/edit_addons/'+this.$route.params.id)
      .then(res => {
       console.log(res);
            this.brand=res.data.message;
         })
    }
 
     this.$http.get('/store/category_list')
      .then(res => {
          this.category = res.data
         console.log(this.category)
          })

     this.user_info.id = localStorage.id;
      this.$http.post('/store/addons_list',this.user_info)
      .then(res => {
          this.addons = res.data
       
          })

           this.$http.get('/store/food_quantity')
         .then(res => {
        var names =[];
         res.data.forEach(function(number) {
               names.push(number.name); 
         })
         this.options = names;
         console.log( this.options);
          })


   
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';

#profile-img {
    height: 8.92rem;
    width: 8.92rem;
}
</style>

