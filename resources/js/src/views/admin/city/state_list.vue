<template>
  <b-row>
    <b-col cols="12">
 <b-card>

 <b-row >
  <b-col md="6">
 <div class="custom-search d-flex justify-content-start">
        <b-button
            variant="primary"
            :to="{ name: 'add_state'}"
          >
           <i class="fa-solid fa-plus"></i> {{$t('add state')}}
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
            :placeholder="$t('message.seachLabel')"
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
          <span v-if="props.row.status === 1">

              <b-badge :variant="statusVariant(props.row.status)">
                {{ $t('active') }}
              </b-badge>

          </span>
          <span v-else>

              <b-badge :variant="statusVariant(props.row.status)">
                {{ $t('inactive') }}
              </b-badge>

          </span>

        </span>

        <!-- Column: Action -->
        <span v-else-if="props.column.field === 'action'">
           <feather-icon
            :id="`invoice-row-${props.row.id}-preview-icon`"
            icon="EditIcon"
            size="16"
            class="mx-1"
            cursor="pointer"
            @click="$router.push({ name: 'add_state', params: { id: props.row.id }})"
          />
          <b-tooltip
            placement="left"
            :title="$t('edit state')"
            :target="`invoice-row-${props.row.id}-preview-icon`"
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
              {{ $t('message.pagelength') }}
            </span>
            <b-form-select
              v-model="pageLength"
              :options="['10','50','100']"
              class="mx-1"
              @input="(value)=>props.perPageChanged({currentPerPage:value})"
            />
            <span class="text-nowrap "> {{ $t('message.of') }} {{ props.total }} {{ $t('message.pageText2') }}
                </span>
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
      
      <b-img src="/admin_loader.svg" fluid alt="Scan"  />

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
  
BImg,BMedia,BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem,BCard,BButton,BSpinner,BTooltip,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@/store/index'
import { codeColumnSearch } from './code'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
    BCard,
    BButton,BSpinner,BTooltip,
BImg,BMedia,
  },
  data() {
    return {
     pageLength: 10,
      dir: false,
      Loading:true,
      codeColumnSearch,
      columns: [
        {
          label: this.$t('state'),
          field: 'state',
          
        },
         
        {
          label: this.$t('country id'),
          field: 'country',
          
        },
        
    
        {
          label: this.$t('action'),
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
  
    this.$http.get('/admin/state_list')
      .then(res => {
        
        this.rows = res.data;
        this.Loading = false;
       })
  },
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>
