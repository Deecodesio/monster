<template>
  
  <div class="nav-item nav-search">
    <div class=" align-content-center justify-content-between ">
              <vue-autosuggest
            :suggestions="filteredOptions"
            :limit="10"
            :input-props="{id:'autosuggest__input',class:'form-control', placeholder:'Search Restaurant or Product'}"
            @input="onInputChange"
          >
            <template slot-scope="{suggestion}">
              <span class="my-suggestion-item">{{ suggestion.item.text }}</span>
            </template>
          </vue-autosuggest>
    </div>
  </div>
    <!-- Icon -->

</template>

<script>
import {
  BFormInput, BLink, BImg, BAvatar, BRow, BCol, BInputGroup,
    BInputGroupPrepend,

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
  },
  data() {
    return {
      datasuggest: [],
      filteredOptions: [],
      limit: 10,
      selected: null,
    }
  },
  methods: {
    onInputChange(text) {
      if (text === '' || text === undefined) {
        return
      }
      var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');

    this.$http.get('/search_restaurants1/'+lat+'/'+lng+'/'+text)
      .then(res => { 
      
        this.datasuggest = res.data.data
       })
       
       this.filteredOptions = [{
        data: this.datasuggest,
      }]
     
    },
  },
}
</script>


<style lang="scss">
@import '~@resources/scss/vue/libs/vue-autosuggest.scss';
</style>
