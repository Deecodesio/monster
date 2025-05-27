<template>
  <b-row>
    <b-col cols="12">
 <b-card >

    <!-- input search -->
    <div class="custom-search d-flex justify-content-end">
      <b-form-group>
        <div class="d-flex align-items-center">
          <label class="mr-1">Search</label>
          <b-form-input
            v-model="searchTerm"
            placeholder="Search"
            type="text"
            class="d-inline-block"
          />
        </div>
      </b-form-group>
    </div>

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
          v-if="props.column.field === 'fullName'"
          class="text-nowrap"
        >
          <b-avatar
            :src="props.row.avatar"
            class="mx-1"
          />
          <span class="text-nowrap">{{ props.row.fullName }}</span>
        </div>

        <div
          v-if="props.column.field === 'bill_amounts'"
          class="text-nowrap"
        >
          <span class="text-nowrap " > {{setting.value}} {{ props.row.bill_amount }}</span>
         
         <br>
       
        </div>

        <!-- <div
          v-if="props.column.field === 'delivery_type'"
          class="text-nowrap"
        >
         <span v-if="props.row.delivery_type === 1">
                <b-badge :variant="statusVariant(props.row.status)">
           Delivery
          </b-badge>
          </span>
          <span v-else>
                 <b-badge :variant="statusVariant(props.row.status)">
            Takeaway
          </b-badge>
          </span>
        </div> -->

         <div
          v-if="props.column.field === 'order_id'"
          class="text-nowrap"
        >
          <span class="text-nowrap">{{ props.row.order_id }}</span>
         <br>
      
        </div>


        <span v-else-if="props.column.field === 'delivery_type'">
          <!-- <b-badge :variant="statusVariant(props.row.is_cod)">
            {{ props.row.is_cod }}
          </b-badge> -->

            <span v-if="props.row.delivery_type === 1">
                <b-badge :variant="statusVariant(props.row.delivery_type)">
           Delivery
          </b-badge>
          </span>
          <span v-else>
                 <b-badge :variant="statusVariant(props.row.delivery_type)">
            Takeaway
          </b-badge>
          </span>
        </span>


        <!-- Column: Status -->
        <span v-else-if="props.column.field === 'is_cod'">
          <!-- <b-badge :variant="statusVariant(props.row.is_cod)">
            {{ props.row.is_cod }}
          </b-badge> -->

            <span v-if="props.row.is_cod === 1">
                <b-badge :variant="statusVariant(props.row.is_cod)">
           COD
          </b-badge>
          </span>
          <span v-else>
                 <b-badge :variant="statusVariant(props.row.is_cod)">
            Online
          </b-badge>
          </span>
        </span>
       <span v-else-if="props.column.field === 'view'">
            <feather-icon
            :id="`invoice-row-${props.row.is_cod}-preview-icon`"
            icon="EyeIcon"
            size="16"
            class="mx-1"
            @click="$router.push({ name: 'view_order', params: { id: props.row.id }})"
            cursor="pointer"

          />
          <b-tooltip
            title="View Order" placement="left"
            :target="`invoice-row-${props.row.is_cod}-preview-icon`"
          />
        </span>
        <!-- Column: Action -->
        <span v-else-if="props.column.field === 'action'">
          <span>
            <b-dropdown
              variant="link"
              toggle-class="text-decoration-none"
              no-caret
            >
              <template v-slot:button-content>
                <feather-icon
                  icon="MoreVerticalIcon"
                  size="16"
                  class="text-body align-middle mr-25"
                />
              </template>
              <b-dropdown-item>
                <feather-icon
                  icon="Edit2Icon"
                  class="mr-50"
                />
                <span>Edit</span>
              </b-dropdown-item>
              <b-dropdown-item>
                <feather-icon
                  icon="TrashIcon"
                  class="mr-50"
                />
                <span @click="handleDelete(props.row.id)">Delete</span>
              </b-dropdown-item>
            </b-dropdown>
          </span>
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
      <div slot="emptystate" style="text-align: center;"  v-if="Loading">
      
      <b-img src="/admin_loader.svg" fluid alt="Scan"  />

  </div>
    </vue-good-table>

    <template #code>
      {{ codeColumnSearch }}
    </template>
  </b-card>

    </b-col>
  </b-row>
</template>

<script>

import { BRow, BCol } from 'bootstrap-vue'
import BCardCode from '@@core/components/b-card-code/BCardCode.vue'
import {
  BImg,BMedia,BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem,BButton,BCard,BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@@/store/index'
import { codeColumnSearch } from './code'
export default {
  components: {
    BRow,
    BCol,
    BCardCode,
    VueGoodTable,
    BAvatar,
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BButton,
    BCard,
    BTooltip,BImg,BMedia,
  },
  data() {
    return {
      user_info:{},
     pageLength: 10,
     setting:{},
      dir: false,
      Loading:true,
      codeColumnSearch,
      columns: [
        {
          label: 'Order Id',
          field: 'order_id',
          class: 'text-nowrap',
        },
         {
          label: 'View Order',
          field: 'view',
     
        },
         
        {
          label: 'Customer Name',
          field: 'user_name',
         
        },
        {
          label: 'Order Type',
          field: 'delivery_type',
          
        },
        {
          label: 'Delivery People',
          field: 'rider_name',
          class: 'text-nowrap',
        },
        {
          label: 'Restaurant',
          field: 'restaurant_name',
          
        },
         {
          label: 'Date',
          field: 'ordered_time',
         
        },
        {
          label: 'Address',
          field: 'delivery_address',
         
        },
         {
          label: 'Payment Mode',
          field: 'is_cod',
          
        },
        {
          label: 'Total',
          field: 'bill_amounts',
          
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
  methods:{
    handleDelete(id) {
     
      this.$http
                .get("/store/new_update",data)
                .then(
                  (response => {
                        console.log(response);
                        if (response.data.status == true) {
                            this.$router.push({ name: "reason_list" })
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
  computed: {
    
    statusVariant() {
      const statusColor = {
       
        1       : 'light-primary',
        2       : 'light-success',
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
  
    this.user_info.id=   localStorage.id;
    this.$http.post('/store/Orders_list/'+this.$route.name+'/'+this.user_info.id)
      .then(res => {
       
         this.rows = res.data; 
         this.Loading =false;
         })

         this.$http.get('/admin/get_currency')
      .then(res => {
       
         this.setting = res.data 
       
         
         })
      
  },
  
  
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>
