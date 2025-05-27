<template>
  <b-row>
    <b-col cols="12">
 <b-card>

    <!-- input search -->
    <div class="custom-search d-flex justify-content-end">
      <b-form-group>
        <div class="d-flex align-items-center">
          <label class="mr-1">{{$t("Search")}}</label>
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
        </span>  -->
        <span v-else-if="props.column.field === 'status'">
          <span v-if="props.row.status === 1">

              <b-badge :variant="statusVariant(props.row.status)">
                {{$t("Active")}}
              </b-badge>

          </span>
          <span v-else>

              <b-badge :variant="statusVariant(props.row.status)">
                {{$t("InActive")}}
              </b-badge>

          </span>

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
                <span>{{$t("Edit")}}</span>
              </b-dropdown-item>
              <b-dropdown-item>
                <feather-icon
                  icon="TrashIcon"
                  class="mr-50"
                />
                <span>{{$t("Delete")}}</span>
              </b-dropdown-item>
            </b-dropdown>
          </span>
        </span>
  <span
  v-else-if="props.column.field === 'rating'"
          class="text-nowrap"
        >
        <b-form-rating
      no-border
      v-model=props.row.rating
      readonly
      
      variant="warning"
      inline
      precision="2"
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
import BCardCode from '@@@core/components/b-card-code/BCardCode.vue'
import {
  BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem,BCard,BFormRating,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import store from '@@@/store/index'
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
    BCard,
    BFormRating,
  },
  data() {
    return {
     pageLength: 10,
      dir: false,
      codeColumnSearch,
      columns: [
        {
          label: 'Name',
          field: 'restaurant_name',
          
        },
         
        {
          label: 'Email',
          field: 'email',
          
        },
        {
          label: 'Address',
          field: 'address',
         
        },
        {
          label: 'Contact',
          field: 'contact',
         
        },
        {
          label: 'Rating',
          field: 'rating',
         
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
   if(localStorage.getItem('role') != 1){
      this.$router.push({ name: "misc-not-authorized" })
    }
    var ud = JSON.parse(localStorage.getItem('userData'))
    this.$http.get('/api/restaurant_list',ud)
      .then(res => { this.rows = res.data })
  },
}
</script>

<style lang="scss" >
@import '~@resources/scss/vue/libs/vue-good-table.scss';
</style>
