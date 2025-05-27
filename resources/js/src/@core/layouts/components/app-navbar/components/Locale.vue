<template>
  <b-nav-item-dropdown
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
  </b-nav-item-dropdown>
</template>

<script>
import { BNavItemDropdown, BDropdownItem, BImg } from 'bootstrap-vue'
import store from "@/store";

export default {
  components: {
    BNavItemDropdown,
    BDropdownItem,
    BImg,
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
      localStorage.setItem('lnags_admin', lang);
     
      this.$http.get('/admin/setlocale/'+lang)
              .then(res => {
               
                window.location.reload();
              })
    }
  },
  created() {
    this.$http.get('/admin/setlocale_lang')
              .then(res => {
                this.$http.get('/admin/setlocale/'+localStorage.getItem('lnags_admin'))
              .then(res => {
               
                
              })
     
      if(localStorage.getItem('lnags_admin'))
      {
        this.$i18n.locale =  localStorage.getItem('lnags_admin');  
        if("ar" == localStorage.getItem('lnags_admin')){
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
        img: require('@/assets/images/flags/en.png'),
        name: 'English',
      },
      {
        locale: 'du',
        img: require('@/assets/images/flags/du.png'),
        name: 'Dutch',
      },
      {
        locale: 'fr',
        img: require('@/assets/images/flags/fr.png'),
        name: 'French',
      },
      
      {
        locale: 'pt',
        img: require('@/assets/images/flags/pt.png'),
        name: 'Portuguese',
      },
      {
        locale: 'ar',
        img: require('@/assets/images/flags/ar.png'),
        name: 'Arabic',
      },
      {
        locale: 'es',
        img: require('@/assets/images/flags/es.png'),
        name: 'Spanish',
      },
      {
        locale: 'de',
        img: require('@/assets/images/flags/de.png'),
        name: 'German',
      },
      {
        locale: 'sw',
        img: require('@/assets/images/flags/sw.png'),
        name: 'Swedish',
      },
      {
        locale: 'vie',
        img: require('@/assets/images/flags/vi.png'),
        name: 'Vietnamese',
      },
      {
        locale: 'it',
        img: require('@/assets/images/flags/it.png'),
        name: 'Italian',
      },
    ]
    /* eslint-disable global-require */

    return {
      locales,
    }
  },
}
</script>

<style>

</style>
