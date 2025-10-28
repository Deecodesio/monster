import { useUtils as useI18nUtils } from '@core/libs/i18n'
import { useUtils as useAclUtils } from '@core/libs/acl'

const { t } = useI18nUtils()
const { canViewVerticalNavMenuHeader } = useAclUtils()

export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  render(h) {
    console.log('Header item:', this.item.header)
    const colorHeaders = ['Banner', 'Product Management', 'Location Management', 'Order Management', 'Payout', 'Payouts', 'Reports', 'Delivery Management', 'Vehicle Management', 'Carrier Type', 'Document Management', 'Cancellation Reasons', 'Blogs', 'Promocodes', 'Brands', 'Sizes', 'Content Management', 'Settings', 'Job opening']
    const isColored = colorHeaders.includes(this.item.header)
    console.log('Is colored:', isColored, 'for header:', this.item.header)
    const span = h('span', {
      style: isColored ? 'color: #e91e63 !important; font-weight: bold !important; font-size: 1.35rem !important; line-height: 100% !important;' : ''
    }, t(this.item.header))
    const icon = h('feather-icon', { props: { icon: 'MoreHorizontalIcon', size: '18' } })
    if (canViewVerticalNavMenuHeader(this.item)) {
      return h('li', { class: 'navigation-header text-truncate' }, [span, icon])
    }
    return h()
  },
}
