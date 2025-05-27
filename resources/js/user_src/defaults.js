export default {
    namespaced: true,
    state: {
      currency: (() => {
        const currency = localStorage.getItem('currency') ? localStorage.getItem('currency') : '$'
        return currency
      })(),
      decimal: (() => {
        const decimal = localStorage.getItem('decimal') ? localStorage.getItem('decimal') : 2
        return decimal
      })(),
      delimiter: (() => {
        const delimiter = localStorage.getItem('delimiter') ? localStorage.getItem('delimiter') : ','
        return delimiter
      })(),
      address: (() => {
        const address = localStorage.getItem('address') ? localStorage.getItem('address') : 'Select Address'
        return address
      })(),
      tax: (() => {
        const tax = localStorage.getItem('IS_TAX_INCLUSIVE') ? localStorage.getItem('IS_TAX_INCLUSIVE') : '1'
        return tax
      })(),
      username: (() => {
        const username = JSON.parse(localStorage.getItem('webuserData'))
        return username
      })(),
      profile_img :(()=>{
        const profile_img = JSON.parse(localStorage.getItem('webuserData'))
        return profile_img
      }),

      delivery_address: (() => {
        const userData = localStorage.getItem('address')
        return userData
      })(),
      wallet: (() => {
        const currency = localStorage.getItem('wallet1') ? localStorage.getItem('wallet1') : 1
        return currency
      })(),
    },
    getters: {},
    mutations: {
      UPDATE_D_ADDRESS(state, count) {
        state.delivery_address = count
      },

      UPDATE_USER(state, count) {
        state.username = count
      },
    },
    
   
  
  }