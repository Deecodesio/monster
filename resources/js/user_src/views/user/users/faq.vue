<template>
    <div class="mt-5 ">
      <!-- faq search section -->
      <section id="faq-search-filter">
        <b-card
          no-body
          class="faq-search "
          style="border-radius:0px;"
          :style="{backgroundImage:`url(${require('@@@/assets/images/banner/more1.jpg')})`}"
        >
          <b-card-body class="text-center">
            <h2 class="text-primary">
              {{$t("Let's answer some questions")}}
            </h2>
            <b-card-text class="mb-2" style="color:#e6e6e6;">
              {{$t("or choose a category to quickly find the help you need")}} 
            </b-card-text>
  
            <!-- form -->
            <b-form class="faq-search-input">
              <b-input-group class="input-group-merge">
                <b-input-group-prepend is-text>
                  <feather-icon icon="SearchIcon" />
                </b-input-group-prepend>
                <b-form-input
                  id="searchbar"
                  v-model="faqSearchQuery"
                  placeholder="Search faq..."
                />
              </b-input-group>
            </b-form>
            <!-- form -->
          </b-card-body>
        </b-card>
      </section>
      <!--/ faq search section -->
  
      <!-- frequently asked questions tabs pills -->
      <section id="faq-tabs" class="container">
        <b-tabs
          vertical
          content-class="col-12 col-md-8 col-lg-9"
          pills
          nav-wrapper-class="faq-navigation col-md-4 col-lg-3 col-12"
          nav-class="nav-left"
        >
  
          <!-- payment tab -->
          <b-tab
            v-for="(categoryObj, categoryName, index) in faqData"
            :key="categoryName"
            :active="index"
          >
  
            <!-- title -->
            <template #title>
              <feather-icon
                :icon="categoryObj.icon"
                size="18"
                class="mr-1"
              />
              <span class="font-weight-bold">{{ categoryObj.title }}</span>
            </template>
  
            <faq-question-answer :options="categoryObj" />
          </b-tab>
          <!--/ payment tab -->
  
          <!-- sitting lady image -->
          <template #tabs-end>
            <b-img
              fluid
              :src="require('@/assets/images/illustration/faq-illustrations.svg')"
              class="d-none d-md-block mt-auto"
            />
          </template>
          <!--/ sitting lady image -->
        </b-tabs>
      </section>
      <!--/ frequently asked questions tabs pills -->
  
      <!-- contact us -->
      <section class="faq-contact">
        <b-row class="mt-5 pt-75 text-center">
          <b-col cols="12">
            <h2>{{$t("You still have a question?")}}</h2>
            <b-card-text class="mb-3">
              {{$t("If you cannot find a question in our FAQ, you can always contact us. We will answer to you shortly!")}}
            </b-card-text>
          </b-col>
          <b-col sm="6">
            <b-card
              class="shadow-none py-1 faq-contact-card"
            >
              <b-avatar
                size="42"
                rounded
                variant="light-primary"
                class="mb-2"
              >
                <feather-icon
                  icon="PhoneCallIcon"
                  size="18"
                />
              </b-avatar>
              <h4>{{site_contact}}</h4>
              <span class="text-body">{{$t("We are always happy to help!")}}</span>
            </b-card>
          </b-col>
          <b-col sm="6">
            <b-card
              class="shadow-none py-1 faq-contact-card"
            >
              <b-avatar
                size="42"
                rounded
                variant="light-primary"
                class="mb-2"
              >
                <feather-icon
                  icon="MailIcon"
                  size="18"
                />
              </b-avatar>
              <h4>{{site_email}}</h4>
              <span class="text-body">{{$t("Best way to get answer faster!")}}</span>
            </b-card>
          </b-col>
        </b-row>
      </section>
      <!--/ contact us -->
    </div>
  </template>
  
  <script>
  import {
    BCard,
    BCardBody,
    BForm,
    BInputGroup,
    BFormInput,
    BCardText,
    BInputGroupPrepend,
    BTabs,
    BTab,
    BImg,
    BRow,
    BCol,
    BAvatar,
  } from 'bootstrap-vue'
  import FaqQuestionAnswer from './FaqQuestionAnswer.vue'
  
  export default {
    components: {
      BForm,
      BCard,
      BRow,
      BCol,
      BAvatar,
      BCardBody,
      BInputGroup,
      BFormInput,
      BCardText,
      BInputGroupPrepend,
      BTabs,
      BTab,
      BImg,
      FaqQuestionAnswer,
    },
    data() {
      return {
        faqSearchQuery: '',
        faqData: {},
        site_email:'',
        site_contact:'',
      }
    },
    watch: {
      faqSearchQuery: {
        immediate: true,
        handler() {
          this.fetchData()
        },
      },
    },
    methods: {
      fetchData() {
        this.$http.get('/faqs', { params: { q: this.faqSearchQuery } }).then(res => {
          this.faqData = res.data.data
        })
      },
    },
    created(){
        this.$http.get('/get_site_logo').then(res => {
          this.site_contact = res.data.site_contact
          this.site_email = res.data.site_email

        })
    }
  }
  </script>
  
  <style lang="scss">
  @import '~@resources/scss/vue/pages/page-faq.scss';
  </style>
  