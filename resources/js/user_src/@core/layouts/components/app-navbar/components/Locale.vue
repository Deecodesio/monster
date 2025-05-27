<template>
  <b-dropdown
    id="dropdown-grouped"
    variant="link"
    class="dropdown-language"
    right
  >
    <template #button-content>
      <b-img
        :src="currentLocale.img"
        height="14px"
        width="22px"
        :alt="currentLocale.locale"
      />
      <span class="ml-50 text-body">{{ currentLocale.name }}</span>
    </template>
    <b-dropdown-item
      v-for="localeObj in locales"
      :key="localeObj.locale"
      @click="changelang(localeObj.locale)"

    >
      <b-img
        :src="localeObj.img"
        height="14px"
        width="22px"
        :alt="localeObj.locale"
      />
      <span class="ml-50">{{ localeObj.name }}</span>
    </b-dropdown-item>
  </b-dropdown>
 
</template>

<script>
import { BNavItemDropdown, BDropdownItem, BImg ,BDropdown} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'

import store from "@@@/store";

export default {
  components: {
    BNavItemDropdown,
    BDropdownItem,
    BImg,
    BDropdown,
  },
  directives: {
    Ripple,
  },
  // ! Need to move this computed property to comp function once we get to Vue 3
  computed: {
    currentLocale() {
      return this.locales.find(l => l.locale === this.$i18n.locale)
    },
  },
  methods:{
    changelang(lang){
      if(lang == "ar"){
        store.commit('appConfig/TOGGLE_RTL', true)
      }else{
        if(store.state['appConfig'].layout.isRTL){
          store.commit('appConfig/TOGGLE_RTL', false)
        }
        
      }
      this. $i18n.locale = lang
      localStorage.setItem('lnags', lang);
      this.$http.get('/setlocale/'+lang)
              .then(res => {
                window.location.reload();
                
              })

    }
  },
  created() {
    this.$http.get('/setlocale_lang')
              .then(res => {
        
                this.$http.get('/setlocale/'+localStorage.getItem('lnags'))
              .then(res => {
                
                
              })
      if(localStorage.getItem('lnags'))
      {
        this.$i18n.locale =  localStorage.getItem('lnags');  
        if("ar" == localStorage.getItem('lnags')){
        store.commit('appConfig/TOGGLE_RTL', true)
      }else{
        if(store.state['appConfig'].layout.isRTL){
          store.commit('appConfig/TOGGLE_RTL', false)
        }
       
      }
      }
         
              })
  },
  setup() {
    /* eslint-disable global-require */
    const locales = [
      {
        locale: 'en',
        img: require('@@@/assets/images/flags/en.png'),
        name: 'English',
      },
      {
        locale: 'fr',
        img: require('@@@/assets/images/flags/fr.png'),
        name: 'French',
      },
      {
        locale: 'de',
        img: require('@@@/assets/images/flags/de.png'),
        name: 'German',
      },
      {
        locale: 'pt',
        img: require('@@@/assets/images/flags/pt.png'),
        name: 'Portuguese',
      },
      {
        locale: 'du',
        img: require('@@@/assets/images/flags/du.png'),
        name: 'Dutch',
      },
      {
        locale: 'ar',
        img: require('@@@/assets/images/flags/ar.png'),
        name: 'Arabic',
      },
      {
        locale: 'sp',
        img: require('@@@/assets/images/flags/sp.png'),
        name: 'Spanish',
      },
      {
        locale: 'it',
        img: require('@@@/assets/images/flags/it.png'),
        name: 'Italian',
      },
      {
        locale: 'vi',
        img: require('@@@/assets/images/flags/vi.png'),
        name: 'Vietnamese ',
      },
      {
        locale: 'sw',
        img: require('@/assets/images/flags/sw.png'),
        name: 'Swedish',
      },
    ]
    /* eslint-disable global-require */

    return {
      locales,
    }
  },
}
</script>


