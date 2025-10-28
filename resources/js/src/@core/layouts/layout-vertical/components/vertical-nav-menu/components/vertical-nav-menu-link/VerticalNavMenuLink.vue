<template>
  <li
    v-if="canViewVerticalNavMenuLink(item)"
    class="nav-item"
    :class="{
      'active': isActive,
      'disabled': item.disabled
    }"
  >
    <b-link
      v-bind="linkProps"
      class="d-flex align-items-center"
    >
      <!-- <feather-icon :icon="item.icon || 'CircleIcon'" /> -->
      <feather-icon v-if="item.icon" :icon="item.icon" />
      <span class="menu-title text-truncate" :style="shouldApplyColor(item.title) ? { color: '#e91e63', fontWeight: 'bold', fontSize: '1.35rem', lineHeight: '100%' } : {}">{{ t(item.title) }}</span>
      <b-badge
        v-if="item.tag"
        pill
        :variant="item.tagVariant || 'primary'"
        class="mr-1 ml-auto"
      >
        {{ item.tag }}
      </b-badge>
    </b-link>
  </li>
</template>

<script>
import { useUtils as useAclUtils } from '@core/libs/acl'
import { BLink, BBadge } from 'bootstrap-vue'
import { useUtils as useI18nUtils } from '@core/libs/i18n'
import useVerticalNavMenuLink from './useVerticalNavMenuLink'
import mixinVerticalNavMenuLink from './mixinVerticalNavMenuLink'

export default {
  components: {
    BLink,
    BBadge,
  },
  mixins: [mixinVerticalNavMenuLink],
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const { isActive, linkProps, updateIsActive } = useVerticalNavMenuLink(props.item)
    const { t } = useI18nUtils()
    const { canViewVerticalNavMenuLink } = useAclUtils()

    const shouldApplyColor = (title) => {
      const colorTitles = ['Banner', 'Product Management', 'Location Management', 'Order Management', 'Payout', 'Payouts', 'Reports', 'Delivery Management', 'Vehicle Management', 'Carrier Type', 'Document Management', 'Cancellation Reasons', 'Blogs', 'Promocodes', 'Brands', 'Sizes', 'Content Management', 'Settings', 'Job opening']
      return colorTitles.includes(title)
    }

    return {
      isActive,
      linkProps,
      updateIsActive,
      shouldApplyColor,

      // ACL
      canViewVerticalNavMenuLink,

      // i18n
      t,
    }
  },

}
</script>
