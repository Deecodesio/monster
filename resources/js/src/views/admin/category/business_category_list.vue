<template>
    <b-row>
      <b-col cols="12">
   <b-card>
  
    <b-row >
    <b-col md="6">
   <div class="custom-search d-flex justify-content-start">
          <b-button
              variant="primary"
              :to="{ name: 'business_category_add'}"
            >
              <i class="fa-solid fa-plus"></i>Add Category
            </b-button>
      </div>
  
   </b-col>
    <b-col md="6" >
    
   <!-- input search -->
      <div class="custom-search d-flex justify-content-end">
        <b-form-group>
          <div class="d-flex align-items-center">
            <label class="mr-1">{{ $t('message.seachLabel') }}</label>
            <b-form-input
              v-model="searchTerm"
              placeholder="Search"
              type="text"
              class="d-inline-block"
            />
          </div>
        </b-form-group>
      </div>
  
        <!-- input search -->
  
   </b-col>
   </b-row>
  
      <!-- table -->
      <vue-good-table
        :columns="columns"
        :line-numbers="true"
        :rows="rows"
        :rtl="direction"
        :search-options="{
          enabled: true,
          externalQuery: searchTerm }"
        :pagination-options="{
          enabled: true,
          perPage:pageLength
        }"
      >
        <template
          slot="table-row"
          slot-scope="props"
        >
  
          <!-- Column: Name -->
          <div
          v-if="props.column.field === 'images'"
          class="text-nowrap"
        >
      
         <b-img
             :src="props.row.image"
            rounded
            fluid
            id="profile-img"
            alt="profile photo"
           
          />
          <!-- <span class="text-nowrap">{{ setting.value }}</span> -->
          <!-- <b-avatar
            :src="props.row.banner_image"
            class="mx-1"
          /> -->
          <!-- <span class="text-nowrap">{{ props.row.fullName }}</span> -->
        </div>
  
           <div
            v-if="props.column.field === 'order_id'"
            class="text-nowrap"
          >
            <span class="text-nowrap">{{ props.row.order_id }}</span>
           <br>
            <span class="text-nowrap">  <b-badge :variant="statusVariant(props.row.status)">
              {{ props.row.status }}
            </b-badge></span>
          </div>
  
          <!-- Column: Status -->
          <!-- <span v-else-if="props.column.field === 'status'">
            <b-badge :variant="statusVariant(props.row.status)">
              {{ props.row.status }}
            </b-badge>
          </span> -->
           <span v-else-if="props.column.field === 'status'">
            <b-button
              v-if="props.row.status === 1"
              type="submit"
              variant="outline-success"
              class="mr-1"
              @click="changedefault(props.row.id)"
            >
              Active
            </b-button>
            <b-button
              v-if="props.row.status === 0"
              type="submit"
              variant="outline-warning"
              class="mr-1"
              @click="changedefault(props.row.id)"
            >
             InActive
            </b-button>
          </span>
  
  
          <!-- Column: Action -->
            <span v-else-if="props.column.field === 'action'">
             <feather-icon
              :id="`invoice-row-${props.row.id}-preview-icon`"
              icon="EditIcon"
              size="16"
              class="mx-1"
              cursor="pointer"
              @click="$router.push({ name: 'business_category_edit', params: { id: props.row.id }})"
            />
            <b-tooltip
              placement="left"
              title="Edit Category"
              :target="`invoice-row-${props.row.id}-preview-icon`"
            />
  
            <feather-icon
              :id="`invoice-row-delete-${props.row.id}-preview-icon`"
              icon="Trash2Icon"
              size="16"
              class="mx-1"
              cursor="pointer"
               @click="showMsgBoxTwo(props.row.id,props.row.category_name)"
            />
            <b-tooltip
              placement="left"
              title="Delete Category"
              :target="`invoice-row-delete-${props.row.id}-preview-icon`"
            />
          </span>
  
          <!-- Column: Common -->
          <span v-else>
            {{ props.formattedRow[props.column.field] }}
          </span>
        </template>
  
        <!-- pagination -->
        <template
          slot="pagination-bottom"
          slot-scope="props"
        >
          <div class="d-flex justify-content-between flex-wrap">
            <div class="d-flex align-items-center mb-0 mt-1">
              <span class="text-nowrap">
                Showing 1 to
              </span>
              <b-form-select
                v-model="pageLength"
                :options="['10','50','100']"
                class="mx-1"
                @input="(value)=>props.perPageChanged({currentPerPage:value})"
              />
              <span class="text-nowrap "> of {{ props.total }} entries </span>
            </div>
            <div>
              <b-pagination
                :value="1"
                :total-rows="props.total"
                :per-page="pageLength"
                first-number
                last-number
                align="right"
                prev-class="prev-item"
                next-class="next-item"
                class="mt-1 mb-0"
                @input="(value)=>props.pageChanged({currentPage:value})"
              >
                <template #prev-text>
                  <feather-icon
                    icon="ChevronLeftIcon"
                    size="18"
                  />
                </template>
                <template #next-text>
                  <feather-icon
                    icon="ChevronRightIcon"
                    size="18"
                  />
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
  import { BRow, BCol } from 'bootstrap-vue'
  import BCardCode from '@core/components/b-card-code/BCardCode.vue'
  import {
    BImg,BMedia, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
     BDropdown, BDropdownItem,BCard,BTooltip,BButton,
  } from 'bootstrap-vue'
  import { VueGoodTable } from 'vue-good-table'
  import store from '@/store/index'

  import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
  
  export default {
    components: {
      BRow,
      BCol,
      BCardCode,
      BButton,
      VueGoodTable,
      BAvatar,
      BBadge,
      BPagination,
      BFormGroup,
      BFormInput,
      BFormSelect,
      BDropdown,
      BDropdownItem,
      BCard,
      BTooltip,BImg,BMedia,
    },
    data() {
      return {
       pageLength: 10,
        dir: false,
        Loading:true,
       
        columns: [
          {
            label: 'Name',
            field: 'category_name',
           
          },
          {
            label: 'Business Type',
            field: 'business_name',
           
          },
          {
            label: 'Image',
            field: 'images',
          },
          {
            label: 'Status',
            field: 'status',
           
          },
          
          {
            label: 'Action',
            field: 'action',
          },
        ],
        rows: [],
        searchTerm: '',
      }
    },
    computed: {
      statusVariant() {
        const statusColor = {
         
          1       : 'light-success',
          2       : 'light-warning',
          3       : 'light-danger',
          4       : 'light-warning',
          5       : 'light-info',
          
        }
  
        return status => statusColor[status]
      },
      
      direction() {
        if (store.state.appConfig.isRTL) {
          // eslint-disable-next-line vue/no-side-effects-in-computed-properties
          this.dir = true
          return this.dir
        }
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.dir = false
        return this.dir
      },
    },
    created() {
     
      this.$http.get('/admin/business_category_lists')
        .then(res => { 
          this.rows = res.data; 
          this.Loading = false;
       
        })
    },
    methods: {
      
      showMsgBoxTwo(id,res_name) {
            
          this.boxTwo = ''
          this.$bvModal
            .msgBoxConfirm('Please confirm that you want to delete '+ res_name +'', {
              title: 'Please Confirm',
              size: 'sm',
              okVariant: 'primary',
              okTitle: 'Yes',
              cancelTitle: 'No',
              cancelVariant: 'outline-secondary',
              hideHeaderClose: false,
              centered: true,
            })
            .then(value => {
              if(value == true){
                          this.$http.get('/admin/delete_business_category/'+id)
                  .then(res => {    
                    
                    if(res.data.status == true){
                      this.$toast({
                                      component: ToastificationContent,
                                      position: 'bottom-right',
                                      props: {
                                        title: res.data.message,
                                        icon: 'CheckIcon',
                                        variant: 'success',
                                      },
                                    })
                                    this.$http.get('/admin/business_category_lists')
                                     .then(res => { this.rows = res.data
                                        this.Loading = false; }) 
                    }
                    else
                    {
                      this.$toast({
                                      component: ToastificationContent,
                                      position: 'bottom-right',
                                      props: {
                                        title: res.data.message,
                                        icon: 'CheckIcon',
                                        variant: 'warning',
                                      },
                                    })
  
  
                    }
                    
                      
                      })
              }
            })
        },
  
        changedefault(id){
         this.$http.get('/admin/business_category_status/'+id)
          .then(res => {   
            if(res.data.message === "Status Active")
               {
                this.$toast({
                              component: ToastificationContent,
                              position: 'bottom-right',
                              props: {
                                title: res.data.message,
                                icon: 'CheckIcon',
                                variant: 'success',
                              },
                            }) 
              
               }
               else{
                this.$toast({
                              component: ToastificationContent,
                              position: 'bottom-right',
                              props: {
                                title: res.data.message,
                                icon: 'HeartIcon',
                                variant: 'danger',
                              },
                            }) 
               }
             
             this.$http.get('/admin/business_category_lists')
        .then(res => { 
          
          this.rows = res.data
          this.Loading = false;
        
        })
              
              })
              
      },
  
  
  }
  }
  </script>
  
  <style lang="scss" >
  @import '~@resources/scss/vue/libs/vue-good-table.scss';
  @import '~@resources/scss/vue/pages/page-profile.scss';
#profile-img {
    
    width: 75px;
}
  </style>
  