<template>
  <b-card
    v-if="data"
    class="card-transaction"
   
  >
    <b-card-header>
      <b-card-title>{{$t('recent')}} {{$t('orders')}}</b-card-title>

  
    </b-card-header>

    <b-card-body>
      <div
        v-for="transaction in data"
        :key="transaction.mode"
        class="transaction-item"
      >
        <b-media no-body style="cursor:pointer" @click="$router.push({ name: 'view_order', params: { id: transaction.order_id }})">
          <b-media-aside>
            <b-avatar
              rounded
              size="42"
              :variant="transaction.avatarVariant"
            >
              <feather-icon
                size="18"
                :icon="transaction.avatar"
              />
            </b-avatar>
          </b-media-aside>
          <b-media-body>
            <h6 class="transaction-title">
              {{ transaction.mode }}
            </h6>
            <small>{{ $t(transaction.types) }}</small>
          </b-media-body>
        </b-media>
        <div
          class="font-weight-bolder"
          :class="transaction.deduction ? 'text-danger':'text-success'"
        >
          {{ transaction.payment }}
        </div>
      </div>
    </b-card-body>
  </b-card>
</template>

<script>
import {
  BCard, BCardHeader, BCardTitle, BCardBody, BMediaBody, BMedia, BMediaAside, BAvatar, BDropdown, BDropdownItem,
} from 'bootstrap-vue'

export default {
  components: {
    BCard,
    BCardHeader,
    BCardTitle,
    BCardBody,
    BMediaBody,
    BMedia,
    BMediaAside,
    BAvatar,
    BDropdown,
    BDropdownItem,
  },
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
}
</script>
