<template>
  
    <div class=" align-content-center justify-content-between  pt-2">
      
      <vue-autosuggest
      

      v-model="query"
            :suggestions="suggestions"
      :input-props="inputProps"
      :section-configs="sectionConfigs"
      :render-suggestion="renderSuggestion"
      :limit="10"
      @input="fetchResults"
     
    />
  
    </div>
    <!-- Icon -->

</template>

<script>
import {
  BFormInput, BLink, BImg, BAvatar, BRow, BCol, BInputGroup,
    BInputGroupPrepend,BCardText,

} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { VueAutosuggest } from 'vue-autosuggest'

export default {
  components: {
    BFormInput,
    BLink,
    BImg,
    BAvatar,
    VuePerfectScrollbar,
    BRow,
    BCol, 
    BInputGroup,
    BInputGroupPrepend,
    VueAutosuggest,
    BCardText,
  },
  data() {
    return {
      datasuggest: [],
      datasuggest2: [],

      query: '',
      suggestions: [],
      selected: null,
      inputProps: {
        id: 'autosuggest__input',
        class: 'form-control ht-5',
        placeholder: "Search Products or Restaurants",
      },
      sectionConfigs: {
        restaurants: {
          limit: 20,
          label: 'Restaurants',
          onSelected: selected => {
            this.selected = selected.item

          },
        },
        products: {
          limit: 20,
          label: 'Products',
          onSelected: selected => {
            this.selected = selected.item
          },
        },
      },
      limit: 10,
    }
  },
  methods: {
    
    fetchResults(text) {
      const { query } = this
      if (query) {
        this.suggestions = []
      }
      var lat = localStorage.getItem('latitude');
      var lng = localStorage.getItem('longitude');

      this.$http.get('/search_restaurants1/'+lat+'/'+lng+'/'+text)
        .then(res => { 
          this.datasuggest = res.data.data
          this.datasuggest2 = res.data.food_list

        })

       
      const filteredDevelopers = this.datasuggest
      const filteredPages = this.datasuggest2
      this.suggestions.push({ name: 'restaurants', data: filteredDevelopers })
      this.suggestions.push({ name: 'products', data: filteredPages })
     
    },
    renderSuggestion(suggestion) {
      if (suggestion.name === 'restaurants') {
        const dev = suggestion.item
        return (
          <div class="d-flex align-items-center">
            <b-avatar src={dev.img} class="mr-50"></b-avatar>
            <div class="detail">
              <b-card-text class="mb-0">{dev.name}</b-card-text>
            </div>
          </div>
        )
      }
      if (suggestion.name === 'products') {
        const page = suggestion.item
        return (
          <div class="d-flex align-items-center">
             <b-avatar src={page.img} class="mr-50"></b-avatar>
            <div class="detail">
              <b-card-text class="mb-0">{page.name}</b-card-text>
              <small class="text-muted">{page.restaurant_name}</small>
            </div>
          </div>
        )
      }
      return false
    },
  },
}
</script>


<style lang="scss">
@import '~@resources/scss/vue/libs/vue-autosuggest.scss';
</style>
<style lang="scss">
.ht-5{
  min-height: 45px !important;
}
</style>