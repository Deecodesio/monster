import mock from '@@/@fake-db/mock'
/* eslint-disable global-require */
const data = {
  congratulations: {
    name: 'John',
    saleToday: '48900',
  },
  statisticsItems: [
    {
      icon: 'TrendingUpIcon',
      color: 'light-primary',
      title: '230k',
      subtitle: 'Sales',
      customClass: 'mb-2 mb-xl-0',
    },
    {
      icon: 'UserIcon',
      color: 'light-info',
      title: '8.549k',
      subtitle: 'Customers',
      customClass: 'mb-2 mb-xl-0',
    },
    {
      icon: 'BoxIcon',
      color: 'light-danger',
      title: '1.423k',
      subtitle: 'Products',
      customClass: 'mb-2 mb-sm-0',
    },
    {
      icon: 'DollarSignIcon',
      color: 'light-success',
      title: '$9745',
      subtitle: 'Revenue',
      customClass: '',
    },
  ],
  statisticsOrder: {
    series: [
      {
        name: '2020',
        data: [45, 85, 65, 45, 65],
      },
    ],
  },
  statisticsProfit: {
    series: [
      {
        data: [0, 20, 5, 30, 15, 45],
      },
    ],
  },
  earningsChart: {
    series: [53, 16, 31],
  },
  revenue: {
    years: ['2020', '2019', '2018'],
    price: '25,852',
    budget: '56,800',
    revenueReport: {
      series: [
        {
          name: 'Earning',
          data: [95, 177, 284, 256, 105, 63, 168, 218, 72],
        },
        {
          name: 'Expense',
          data: [-145, -80, -60, -180, -100, -60, -85, -75, -100],
        },
      ],
    },
    budgetChart: {
      series: [
        {
          data: [61, 48, 69, 52, 60, 40, 79, 60, 59, 43, 62],
        },
        {
          data: [20, 10, 30, 15, 23, 0, 25, 15, 20, 5, 27],
        },
      ],
    },
  },
  companyTable: [
    {
      avatarImg: require('@@/assets/images/icons/toolbox.svg'),
      title: 'Dixons',
      subtitle: 'meguc@ruj.io',
      avatarIcon: 'MonitorIcon',
      avatarColor: 'light-primary',
      avatarTitle: 'Technology',
      viewTitle: '23.4k',
      viewsub: 'in 24 hours',
      revenue: '891.2',
      sales: '68',
      loss: true,
    },
    {
      avatarImg: require('@@/assets/images/icons/parachute.svg'),
      title: 'Motels',
      subtitle: 'vecav@hodzi.co.uk',
      avatarIcon: 'CoffeeIcon',
      avatarColor: 'light-success',
      avatarTitle: 'Grocery',
      viewTitle: '78k',
      viewsub: 'in 2 days',
      revenue: '668.51',
      sales: '97',
      loss: false,
    },
    {
      avatarImg: require('@@/assets/images/icons/brush.svg'),
      title: 'Zipcar',
      subtitle: 'davcilse@is.gov',
      avatarIcon: 'WatchIcon',
      avatarColor: 'light-warning',
      avatarTitle: 'Fashion',
      viewTitle: '162',
      viewsub: 'in 5 days',
      revenue: '522.29',
      sales: '62',
      loss: false,
    },
    {
      avatarImg: require('@@/assets/images/icons/star.svg'),
      title: 'Owning',
      subtitle: 'us@cuhil.gov',
      avatarIcon: 'MonitorIcon',
      avatarColor: 'light-primary',
      avatarTitle: 'Technology',
      viewTitle: '214',
      viewsub: 'in 24 hours',
      revenue: '291.01',
      sales: '88',
      loss: false,
    },
    {
      avatarImg: require('@@/assets/images/icons/book.svg'),
      title: 'Cafés',
      subtitle: 'pudais@jife.com',
      avatarIcon: 'CoffeeIcon',
      avatarColor: 'light-success',
      avatarTitle: 'Grocery',
      viewTitle: '208',
      viewsub: 'in 1 week',
      revenue: '783.93',
      sales: '16',
      loss: true,
    },
    {
      avatarImg: require('@@/assets/images/icons/rocket.svg'),
      title: 'Kmart',
      subtitle: 'bipri@cawiw.com',
      avatarIcon: 'WatchIcon',
      avatarColor: 'light-warning',
      avatarTitle: 'Fashion',
      viewTitle: '990',
      viewsub: 'in 1 month',
      revenue: '780.05',
      sales: '78',
      loss: false,
    },
    {
      avatarImg: require('@@/assets/images/icons/speaker.svg'),
      title: 'Payers',
      subtitle: 'luk@izug.io',
      avatarIcon: 'WatchIcon',
      avatarColor: 'light-warning',
      avatarTitle: 'Fashion',
      viewTitle: '12.9k',
      viewsub: 'in 12 hours',
      revenue: '531.49',
      sales: '42',
      loss: false,
    },
  ],
  meetup: {
    mediaData: [
      { avatar: 'CalendarIcon', title: 'Sat, May 25, 2020', subtitle: '10:AM to 6:PM' },
      { avatar: 'MapPinIcon', title: 'Central Park', subtitle: 'Manhattan, New york City' },
    ],
    avatars: [
      { avatar: require('@@/assets/images/portrait/small/avatar-s-9.jpg'), fullName: 'Billy Hopkins' },
      { avatar: require('@@/assets/images/portrait/small/avatar-s-6.jpg'), fullName: 'Amy Carson' },
      { avatar: require('@@/assets/images/portrait/small/avatar-s-8.jpg'), fullName: 'Brandon Miles' },
      { avatar: require('@@/assets/images/portrait/small/avatar-s-7.jpg'), fullName: 'Daisy Weber' },
      { avatar: require('@@/assets/images/portrait/small/avatar-s-20.jpg'), fullName: 'Jenny Looper' },
    ],
  },
  goalOverview: {
    completed: '786,617',
    inProgress: '13,561',
    series: [83],
  },
  transactionData: [
    {
      mode: 'Wallet',
      types: 'Starbucks',
      avatar: 'PocketIcon',
      avatarVariant: 'light-primary',
      payment: '-$74',
      deduction: true,
    },
    {
      mode: 'Bank Transfer',
      types: 'Add Money',
      avatar: 'CheckIcon',
      avatarVariant: 'light-success',
      payment: '+$480',
      deduction: false,
    },
    {
      mode: 'Paypal',
      types: 'Add Money',
      avatar: 'DollarSignIcon',
      avatarVariant: 'light-danger',
      payment: '+$480',
      deduction: false,
    },
    {
      mode: 'Mastercard',
      types: 'Ordered Food',
      avatar: 'CreditCardIcon',
      avatarVariant: 'light-warning',
      payment: '-$23',
      deduction: true,
    },
    {
      mode: 'Transfer',
      types: 'Refund',
      avatar: 'TrendingUpIcon',
      avatarVariant: 'light-info',
      payment: '+$98',
      deduction: false,
    },
  ],
}
/* eslint-disable global-require */
mock.onGet('/ecommerce/data').reply(() => [200, data])
