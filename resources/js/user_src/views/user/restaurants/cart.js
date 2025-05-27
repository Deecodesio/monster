import axios from '@@@axios'

export default {
  namespaced: true,
  state: {
    cartItemsCount: (() => {
      const cart = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')).length : 0
      return cart
    })(),
    cart_items: (() => {
      const cartitems = localStorage.getItem('cart') ? JSON.parse(localStorage.getItem('cart')) : []
      return cartitems
    })(),
    cart_total: (() => {
      const carttotal = localStorage.getItem('bill')
      return carttotal
    })(),
    cart_Details: (() => {
      const cartdetails = localStorage.getItem('CART_DETAILS')
      return cartdetails
    })(),
    total_Details: (() => {
      const totaldetails = localStorage.getItem('TOTAL')
      return totaldetails
    })(),
    pharmacy: (() => {
      const pharmacydetails = localStorage.getItem('pharmacy_file')
      return pharmacydetails
    })(),
    show_footer: (() => {
      const footer = true
      return footer
    })(),

  },
  getters: {

  },
  mutations: {
    UPDATE_CART_ITEMS_COUNT(state, count) {
      state.cartItemsCount = count
    },
    UPDATE_CART_ITEMS(state, count) {
      state.cart_items = count
    },
    UPDATE_CART_TOTAL(state, count) {
      state.cart_total = count
    },
    UPDATE_CART_DETAILS(state, count) {
      state.cart_Details = count
    },
    UPDATE_TOTAl_DETAILS(state, count) {
      state.total_Details = count
    },
    UPDATE_PHARMACY_FILE(state, count) {
      state.pharmacy = count
    },
    UPDATE_FOOTER(state, count) {
      state.show_footer = count
    },
  },

}