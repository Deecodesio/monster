<template>
  <b-row>
    <b-col md="6">

      <b-card>
        <b-row>
          <b-col md="12">
            <h3> {{ $t('Store') }} {{ $t('full') }} {{ $t('details') }} :</h3>
          </b-col>

        </b-row>
        <b-row>

          <!-- Right Col: Table -->
          <b-col cols="12" xl="12">
            <table class="mt-2 mt-xl-0 w-100  table table-bordered">
              <tr>
                <th class="pb-50 design">
                  <feather-icon icon="UserIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('Store') }} {{ $t('name') }}:</span>
                </th>
                <td class="pb-50" v-if="restaurant.restaurant_name">
                  {{ restaurant.restaurant_name }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>
              <tr>
                <th class="pb-50 design">
                  <feather-icon icon="CheckIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('email') }}:</span>
                </th>
                <td class="pb-50 text-capitalize" v-if="restaurant.email">
                  {{ restaurant.email }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>
              <tr>
                <th class="pb-50 design">
                  <feather-icon icon="StarIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('city') }}:</span>
                </th>
                <td class="pb-50 text-capitalize" v-if="restaurant.city">
                  {{ restaurant.city }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>
              <tr>
                <th class="pb-50 design">
                  <feather-icon icon="FlagIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('area') }}:</span>
                </th>
                <td class="pb-50" v-if="restaurant.area">
                  {{ restaurant.area }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>
              <tr>
                <th class="design">
                  <feather-icon icon="PhoneIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('phone') }} {{ $t('number') }}:</span>
                </th>
                <td v-if="restaurant.phone">
                  {{ restaurant.phone }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>
            </table>
          </b-col>


        </b-row>
      </b-card>


    </b-col>
    <b-col md="6">
      <b-card>
        <b-row>
          <b-col md="12">
            <h3> {{ $t('commission') }} {{ $t('details') }}:</h3>
          </b-col>

        </b-row>
        <b-row>

          <!-- Right Col: Table -->
          <b-col cols="12" xl="12">
            <table class="mt-2 mt-xl-0 w-100  table table-bordered">
              <tr>
                <th class="pb-50 design">
                  <feather-icon icon="UserIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('admin') }} {{ $t('commision') }} %</span>
                </th>
                <td class="pb-50" v-if="restaurant.admin_commision">
                  {{ restaurant.admin_commision }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>
              <tr>
                <th class="pb-50 design">
                  <feather-icon icon="CheckIcon" class="mr-75" />
                  <span class="font-weight-bold">{{ $t('Store') }} {{ $t('delivery') }} {{ $t('charge') }}</span>
                </th>
                <td class="pb-50 text-capitalize" v-if="restaurant.restaurant_delivery_charge">
                  {{ restaurant.restaurant_delivery_charge }}
                </td>
                <td class="pb-50" v-else>
                  {{ $t('not available') }}
                </td>
              </tr>

            </table>
          </b-col>


        </b-row>
      </b-card>

    </b-col>




    <b-col md="12">

      <b-row>
        <b-col md="12">
          <h3> {{ $t('Store') }} {{ $t('hours') }}</h3>
        </b-col>
      </b-row>
      <b-row>

        <!-- Right Col: Table -->
        <b-col cols="12" xl="12">
          <table class="mt-2 mt-xl-0 w-100  table table-bordered">
            <tr>
              <th>
                <h3>{{ $t('Store') }} {{ $t('timing') }} {{ $t('weekdays') }} </h3>
              </th>
              <td></td>
            </tr>
            <tr>
              <th class="pb-50 design">
                <feather-icon icon="UserIcon" class="mr-75" />
                <span class="font-weight-bold"> {{ $t('Store') }} {{ $t('opens') }} </span>
              </th>
              <td class="pb-50" v-if="restaurant.opening_time">
                {{ restaurant.opening_time }}
              </td>
              <td class="pb-50" v-else>
                {{ $t('not available') }}
              </td>
            </tr>
            <tr>
              <th class="pb-50 design">
                <feather-icon icon="CheckIcon" class="mr-75" />
                <span class="font-weight-bold">{{ $t('Store') }} {{ $t('closes') }}</span>
              </th>
              <td class="pb-50 text-capitalize" v-if="restaurant.closing_time">
                {{ restaurant.closing_time }}
              </td>
              <td class="pb-50" v-else>
                {{ $t('not available') }}
              </td>
            </tr>


            <tr>
              <th>
                <h3>{{ $t('Store') }} {{ $t('timing') }} {{ $t('weekends') }} </h3>
              </th>
              <td></td>
            </tr>
            <tr>
              <th class="pb-50 design">
                <feather-icon icon="UserIcon" class="mr-75" />
                <span class="font-weight-bold"> {{ $t('Store') }} {{ $t('opens') }}</span>
              </th>
              <td class="pb-50" v-if="restaurant.weekend_opening_time">
                {{ restaurant.weekend_opening_time }}
              </td>
              <td class="pb-50" v-else>
                {{ $t('not available') }}
              </td>
            </tr>
            <tr>
              <th class="pb-50 design">
                <feather-icon icon="CheckIcon" class="mr-75" />
                <span class="font-weight-bold"> {{ $t('Store') }} {{ $t('closes') }}</span>
              </th>
              <td class="pb-50 text-capitalize" v-if="restaurant.weekend_closing_time">
                {{ restaurant.weekend_closing_time }}
              </td>
              <td class="pb-50" v-else>
                {{ $t('not available') }}
              </td>
            </tr>

            <tr>
              <th class="pb-50 design">
                <feather-icon icon="CheckIcon" class="mr-75" />
                <span class="font-weight-bold">{{ $t('estimated') }} {{ $t('delivery') }} {{ $t('Time') }}</span>
              </th>
              <td class="pb-50 text-capitalize" v-if="restaurant.estimated_delivery_time">
                {{ restaurant.estimated_delivery_time }}
              </td>
              <td class="pb-50" v-else>
                {{ $t('not available') }}
              </td>
            </tr>

          </table>
        </b-col>


      </b-row>
    </b-col>
  </b-row>



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
      restaurant: {},

      rows: [],
      searchTerm: '',
    }
  },

  created() {
    if (this.$route.params.id) {
      this.$http.get('/admin/restaurant_view/' + this.$route.params.id)
        .then(res => {
          this.restaurant = res.data;
          this.restaurant.city = this.restaurant.city_list.city;
          this.restaurant.area = this.restaurant.area.area;

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
.design {

  background-color: #e9e9e9;
}
</style>
