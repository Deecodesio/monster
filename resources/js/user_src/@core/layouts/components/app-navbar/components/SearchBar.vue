<!-- <template>
    <div class=" align-content-center justify-content-between  pt-1">
        <vue-autosuggest v-model="query" :suggestions="suggestions" :input-props="inputProps"
            :section-configs="sectionConfigs" :render-suggestion="renderSuggestion" :limit="10" @input="fetchResults" />
    </div>
</template> -->
<template>
  <div class="align-content-center justify-content-between pt-1">
    <vue-autosuggest
      v-model="query"
      :suggestions="suggestions"
      :input-props="inputProps"
      :section-configs="sectionConfigs"
      :render-suggestion="renderSuggestion"
      :limit="10"
      @input="fetchResults"
      class="custom-autosuggest"
    />
  </div>
</template>


<script>
import {
    BFormInput, BLink, BImg, BAvatar, BRow, BCol, BInputGroup,
    BInputGroupPrepend, BCardText,

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
            datasuggest3: [],
            query: '',
            suggestions: [],
            selected: null,
            inputProps: {
                id: 'autosuggest__input',
                class: 'form-control ht-6',
                placeholder: this.$t('Hungry ? Search Meats'),
            },
            sectionConfigs: {
                products: {
                    limit: 20,
                    label: 'Products',
                    onSelected: selected => {
                        this.selected = selected.item
                        this.$router.push({ name: 'product', params: { slug: selected.item.slug } });

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
            this.$http.get('/search_restaurants1/' + lat + '/' + lng + '/' + text)
                .then(res => {
                    this.datasuggest = res.data.data
                    for (let index = 0; index < this.datasuggest.length; index++) {
                        var str = this.datasuggest[index].name
                        this.datasuggest[index].slug = this.datasuggest[index].slug;
                    }
                    this.datasuggest2 = res.data.food_list
                    for (let index = 0; index < this.datasuggest2.length; index++) {
                        console.log(this.datasuggest2);
                        var str = this.datasuggest2[index].name
                        this.datasuggest2[index].slug = this.datasuggest2[index].slug;
                    }
                    this.datasuggest3 = res.data.cat_list
                    for (let index = 0; index < this.datasuggest3.length; index++) {
                        var str = this.datasuggest3[index].name
                        this.datasuggest3[index].slug = this.datasuggest[index].slug;
                    }
                })
            const filteredDevelopers = this.datasuggest
            const filteredPages = this.datasuggest2
            const filteredPages2 = this.datasuggest3
            this.suggestions.push({ name: 'restaurants', data: filteredDevelopers })
            this.suggestions.push({ name: 'products', data: filteredPages })
            this.suggestions.push({ name: 'category', data: filteredPages2 })
        },
        renderSuggestion(suggestion) {
            // if (suggestion.name === 'restaurants') {
            //   const dev = suggestion.item
            //   return (
            //     <div class="d-flex align-items-center">
            //       <b-avatar src={dev.img} class="mr-50"></b-avatar>
            //       <div class="detail">
            //         <b-card-text class="mb-0">{dev.name}</b-card-text>
            //       </div>
            //     </div>
            //   )
            // }
            if (suggestion.name === 'products') {
                const page = suggestion.item
                return (
                    <div class="d-flex align-items-center">
                        <b-avatar src={page.img} class="mr-50"></b-avatar>
                        <div class="detail">
                            <b-card-text class="mb-0">{page.name}</b-card-text>
                            {/* <small class="text-muted">{page.restaurant_name}</small> */}
                        </div>
                    </div>
                )
            }
            if (suggestion.name === 'category') {
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
.ht-6 {
    min-height: 35px !important;
}

[dir] .form-control {
    padding: 0.438rem 1rem;
    background-color: #fff;
    background-clip: padding-box;
    border: 3px solid #26323870 !important;
    border-radius: 8px;
    -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
 
 
.custom-autosuggest input {
  width: 100%;
  padding: 10px 14px;
  font-size: 16px;
  color: #2f2f2f;
  outline: none;
}

 
.custom-autosuggest input::placeholder {
  color: #7e8489;
  font-weight: 400;
}

 
.custom-autosuggest input:focus {
  box-shadow: none;
}
 

</style>
