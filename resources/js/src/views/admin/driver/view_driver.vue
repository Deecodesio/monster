<template>
  <b-card>


    <b-row>
      <b-col md="6">
        <h3> {{ $t('driver') }} {{ $t('details') }}</h3>
      </b-col>

    </b-row>

    <b-row>

      <!-- Right Col: Table -->
      <b-col cols="12" xl="6">
        <table class="mt-2 mt-xl-0 w-100 table table-bordered">
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="UserIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('name') }} </span>
            </th>
            <td class="pb-50" v-if="driver">
              {{ driver.name }}
            </td>
            <td class="pb-50" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="CheckIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('id') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="driver">
              {{ driver.id }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="StarIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('email') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="driver">
              {{ driver.email }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="FlagIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('phone') }} {{ $t('number') }}</span>
            </th>
            <td class="pb-50 " v-if="driver">
              {{ driver.phone }}
            </td>
            <td class="pb-50 " v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="design">
              <feather-icon icon="PhoneIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('address') }}</span>
            </th>
            <td v-if="details">
              {{ details.address_line_1 }}
              {{ details.address_line_2 }}
            </td>
            <td v-else>
              {{ $t('not available') }}
            </td>
          </tr>
        </table>
      </b-col>

      <!-- Right Col: Table -->
      <b-col cols="12" xl="6">
        <table class="mt-2 mt-xl-0 w-100 table table-bordered">
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="UserIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('city') }}</span>
            </th>
            <td class="pb-50" v-if="details">
              {{ details.city }}
            </td>
            <td class="pb-50" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="CheckIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('state') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="details">
              {{ details.state_province }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="StarIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('country') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="details">
              {{ details.country }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="FlagIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('zip') }} {{ $t('code') }}</span>
            </th>
            <td class="pb-50" v-if="details">
              {{ details.zip_code }}
            </td>
            <td class="pb-50 " v-else>
              {{ $t('not available') }}
            </td>
          </tr>

        </table>
      </b-col>
    </b-row>
    <br>
    <br>


    <b-row>
      <b-col md="6">
        <h3> {{ $t('driver') }} {{ $t('details') }}</h3>
      </b-col>

    </b-row>

    <b-row>

      <!-- Right Col: Table -->
      <b-col cols="12" xl="6">
        <table class="mt-2 mt-xl-0 w-100 table table-bordered">
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="UserIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('account') }} {{ $t('name') }}</span>
            </th>
            <td class="pb-50" v-if="details">
              {{ details.account_name }}
            </td>
            <td class="pb-50 " v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="CheckIcon" class="mr-75" />
              <span class="font-weight-bold"> {{ $t('account') }} {{ $t('number') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="details">
              {{ details.account_number }}
            </td>
            <td class="pb-50 " v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="StarIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('account') }} {{ $t('address') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="details">
              {{ details.account_address }}
            </td>
            <td class="pb-50" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="FlagIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('swift') }} {{ $t('code') }}</span>
            </th>
            <td class="pb-50" v-if="details">
              {{ details.swift_code }}
            </td>
            <td class="pb-50 " v-else>
              {{ $t('not available') }}
            </td>
          </tr>

        </table>
      </b-col>

      <!-- Right Col: Table -->
      <b-col cols="12" xl="6">
        <table class="mt-2 mt-xl-0 w-100 table table-bordered">
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="UserIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('bank') }} {{ $t('name') }}</span>
            </th>
            <td class="pb-50" v-if="details">
              {{ details.bank_name }}
            </td>
            <td class="pb-50" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="CheckIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('branch') }} {{ $t('name') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="details">
              {{ details.branch_name }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="StarIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('branch') }} {{ $t('address') }}</span>
            </th>
            <td class="pb-50 text-capitalize" v-if="details">
              {{ details.branch_address }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="FlagIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('routing') }} {{ $t('number') }} </span>
            </th>
            <td class="pb-50" v-if="details">
              {{ details.routing_no }}
            </td>
            <td class="pb-50 text-capitalize" v-else>
              {{ $t('not available') }}
            </td>
          </tr>

        </table>
      </b-col>
    </b-row>
    <br>
    <br>

    <b-row>
      <b-col md="6">
        <h3> {{ $t('document') }}</h3>
      </b-col>

    </b-row>

    <b-row>

      <!-- Right Col: Table -->
      <b-col cols="12" xl="6">
        <table class="mt-2 mt-xl-0 w-100 table table-bordered" v-for="item in document">
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="UserIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('license') }} {{ $t('front') }}</span>
            </th>
            <td class="pb-50 ">
              {{ item.document }}
            </td>
          </tr>
          <tr>
            <th class="pb-50 design">
              <feather-icon icon="CheckIcon" class="mr-75" />
              <span class="font-weight-bold">{{ $t('expiry') }} {{ $t('date') }}</span>
            </th>
            <td class="pb-50 text-capitalize">
              {{ item.expiry_date }}
            </td>
          </tr>
        </table>
      </b-col>
    </b-row>
  </b-card>
</template>
  
<script>
import {
  BCard, BButton, BAvatar, BRow, BCol,
} from 'bootstrap-vue'
import { avatarText } from '@core/utils/filter'
//   import useUsersList from '../users-list/useUsersList'

export default {
  components: {
    BCard, BButton, BRow, BCol, BAvatar,
  },

  data() {
    return {
      pageLength: 10,
      dir: false,
      driver: {},
      details: {},
      document: {},
      rows: [],
      searchTerm: '',
    }
  },

  created() {
    if (this.$route.params.id) {
      this.$http.get('/admin/view_delivery_boy/' + this.$route.params.id)
        .then(res => {

          this.driver = res.data.driver;
          this.details = res.data.details;
          this.document = res.data.document;

        })
    }
  },
  // setup() {
  //   const { resolveUserRoleVariant } = useUsersList()
  //   return {
  //     avatarText,
  //     resolveUserRoleVariant,
  //   }
  // },
}
</script>
  
<style>
[dir] .design {
    background-color: #ffffff;
}
[dir] .dark-layout .design {
    background-color: #283046;
}
</style>
  