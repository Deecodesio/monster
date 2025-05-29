(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[56],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/home.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.map.js */ "./node_modules/core-js/modules/es.array.map.js");
/* harmony import */ var core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _top_banners_vue__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./top_banners.vue */ "./resources/js/user_src/views/user/business/top_banners.vue");
/* harmony import */ var _categories_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./categories.vue */ "./resources/js/user_src/views/user/business/categories.vue");
/* harmony import */ var _featured_vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./featured.vue */ "./resources/js/user_src/views/user/business/featured.vue");
/* harmony import */ var _views_user_home_download_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @@@/views/user/home/download.vue */ "./resources/js/user_src/views/user/home/download.vue");
/* harmony import */ var _views_user_static_pages_page1_vue__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @@@/views/user/static_pages/page1.vue */ "./resources/js/user_src/views/user/static_pages/page1.vue");
/* harmony import */ var _views_user_static_pages_page2_vue__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @@@/views/user/static_pages/page2.vue */ "./resources/js/user_src/views/user/static_pages/page2.vue");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @@@/store */ "./resources/js/user_src/store/index.js");
/* harmony import */ var _views_user_home_banner_vue__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @@@/views/user/home/banner.vue */ "./resources/js/user_src/views/user/home/banner.vue");



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//










/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCol"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BCard"],
    top_banners: _top_banners_vue__WEBPACK_IMPORTED_MODULE_4__["default"],
    categories: _categories_vue__WEBPACK_IMPORTED_MODULE_5__["default"],
    featured: _featured_vue__WEBPACK_IMPORTED_MODULE_6__["default"],
    download: _views_user_home_download_vue__WEBPACK_IMPORTED_MODULE_7__["default"],
    page1: _views_user_static_pages_page1_vue__WEBPACK_IMPORTED_MODULE_8__["default"],
    page2: _views_user_static_pages_page2_vue__WEBPACK_IMPORTED_MODULE_9__["default"],
    banner: _views_user_home_banner_vue__WEBPACK_IMPORTED_MODULE_11__["default"]
  },
  data: function data() {
    return {
      featured: [],
      recent: [],
      pages: [],
      lat: localStorage.getItem('latitude'),
      lng: localStorage.getItem('longitude'),
      business_id: localStorage.getItem('single_business_id'),
      isopen: 0,
      title1: "Popular Products",
      title2: "Best Seller",
      componentOrder: [],
      is_loading: false
    };
  },
  created: function created() {
    this.get_products();
  },
  computed: {
    orderedComponents: function orderedComponents() {
      var _this = this;
      return this.componentOrder.map(function (key) {
        var name = key.name;
        return _this.$options.components[name];
      });
    }
  },
  methods: {
    get_pages: function get_pages() {
      var _this2 = this;
      this.is_loading = true;
      this.$http.get('/get_pages').then(function (res) {
        _this2.pages = res.data.pages;
        for (var i = 0; i < _this2.pages.length; i++) {
          if (_this2.pages[i].is_editable == 1) {
            _this2.pages[i].name = "page1";
            // this.componentOrder.push(this.pages[i])
          } else {
            if (_this2.pages[i].name == "recent") {
              _this2.pages[i].name = "featured";
              _this2.pages[i].prop = _this2.recent;
              _this2.pages[i].title = _this2.title2;
            } else if (_this2.pages[i].name == "featured") {
              _this2.pages[i].prop = _this2.featured;
              _this2.pages[i].title = _this2.title1;
            } else {
              _this2.pages[i].prop = [];
              _this2.pages[i].title = "";
            }
            _this2.componentOrder.push(_this2.pages[i]);
          }
        }
        _this2.is_loading = false;
      });
    },
    get_products: function get_products() {
      var _this3 = this;
      _store__WEBPACK_IMPORTED_MODULE_10__["default"].commit('deliware_cart/UPDATE_FOOTER', false);
      console.log(this.lat);
      this.$http.get('/single_restaurant_products/' + this.lat + '/' + this.lng + '/' + this.business_id).then(function (res) {
        _this3.featured = res.data.featured;
        _this3.recent = res.data.recent;
        _this3.isopen = res.data.restaurant_details.is_open;
        _this3.get_pages();
        _store__WEBPACK_IMPORTED_MODULE_10__["default"].commit('deliware_cart/UPDATE_FOOTER', true);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/banner.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BForm"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCard"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCol"],
    BAvatar: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BAvatar"],
    BCardBody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCardBody"],
    BInputGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BInputGroup"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BFormInput"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCardText"],
    BInputGroupPrepend: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BInputGroupPrepend"],
    BTabs: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BTabs"],
    BTab: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BTab"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BImg"],
    BNav: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BNav"],
    BNavItem: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BNavItem"],
    BImgLazy: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BImgLazy"]
  },
  data: function data() {
    return {
      faqSearchQuery: '',
      faqData: {}
    };
  },
  watch: {
    faqSearchQuery: {
      immediate: true,
      handler: function handler() {
        this.fetchData();
      }
    }
  },
  methods: {
    fetchData: function fetchData() {
      var _this = this;
      this.$http.get('/faq/data', {
        params: {
          q: this.faqSearchQuery
        }
      }).then(function (res) {
        _this.faqData = res.data;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=script&lang=js":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=script&lang=js ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCol"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCard"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BImg"]
  },
  data: function data() {
    return {
      featured: [],
      recent: [],
      lat: localStorage.getItem('latitude'),
      lng: localStorage.getItem('longitude'),
      business_id: localStorage.getItem('single_business_id'),
      isopen: 0,
      title1: "Featured",
      title2: "Recent Products",
      page_content: "",
      image: "",
      layout_type: 1
    };
  },
  props: {
    prop2: {
      "default": function _default() {
        return [];
      }
    }
  },
  created: function created() {
    var _this = this;
    this.$http.get('/get_page_details/' + this.prop2).then(function (res) {
      _this.image = res.data.page_details.image;
      _this.layout_type = res.data.page_details.layout_type;
      _this.page_content = res.data.data[0].data;
    });
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "/*=========================================================================================\n    File Name: ext-component-swiper.scss\n    Description: swiper plugin scss.\n    ----------------------------------------------------------------------------------------\n    Item Name: Vuexy - Vuejs, React, Angular, HTML & Laravel Admin Dashboard Template\n    Author: PIXINVENT\n    Author URL: http://www.themeforest.net/user/pixinvent\n==========================================================================================*/\n/* Swiper css */\n/* ---------- */\n/* swiper slide shadow */\n[dir=ltr] .swiper-container .swiper-shadow {\n  box-shadow: 2px 8px 10px 0 rgba(25, 42, 70, 0.13) !important;\n}\n[dir=rtl] .swiper-container .swiper-shadow {\n  box-shadow: -2px 8px 10px 0 rgba(25, 42, 70, 0.13) !important;\n}\n.swiper-pagination .swiper-pagination-bullet:focus {\n  outline: none;\n}\n[dir] .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active {\n  background-color: #E01764;\n}\n[dir] .swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill {\n  background-color: #E01764;\n}\n.swiper-centered-slides.swiper-container .swiper-slide {\n  font-weight: 500;\n  height: auto;\n  width: auto !important;\n}\n[dir] .swiper-centered-slides.swiper-container .swiper-slide {\n  text-align: center;\n  background-color: #fff;\n  padding: 2rem 5.5rem;\n  cursor: pointer;\n}\n[dir] .swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active {\n  border: 2px solid #E01764;\n}\n.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active i,\n.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active svg {\n  color: #E01764;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n[dir] .swiper-centered-slides .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n.swiper-centered-slides .swiper-button-next,\n.swiper-centered-slides .swiper-button-prev {\n  height: 40px !important;\n  width: 40px !important;\n}\n.swiper-centered-slides .swiper-button-next:after,\n.swiper-centered-slides .swiper-button-prev:after {\n  height: 40px !important;\n  width: 40px !important;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after, [dir] .swiper-centered-slides .swiper-button-prev:after {\n  border-radius: 50%;\n  background-color: #E01764;\n  box-shadow: 0 2px 4px 0 rgba(34, 41, 47, 0.5) !important;\n  background-size: 24px !important;\n}\n[dir] .swiper-centered-slides.swiper-container-rtl .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n[dir] .swiper-centered-slides.swiper-container-rtl .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n.swiper-centered-slides-2.swiper-container .swiper-slide {\n  font-weight: 500;\n  height: auto;\n  width: auto !important;\n}\n[dir] .swiper-centered-slides-2.swiper-container .swiper-slide {\n  background-color: #f2f4f4;\n  cursor: pointer;\n}\n.swiper-centered-slides-2.swiper-container .swiper-slide.swiper-slide-active {\n  color: #fff;\n}\n[dir] .swiper-centered-slides-2.swiper-container .swiper-slide.swiper-slide-active {\n  background-color: #E01764 !important;\n  box-shadow: 0 3px 6px 0 rgba(224, 23, 100, 0.5) !important;\n}\n\n/* cube effect */\n.swiper-cube-effect.swiper-container {\n  width: 300px;\n}\n[dir] .swiper-cube-effect.swiper-container {\n  margin-top: -12px;\n}\n[dir=ltr] .swiper-cube-effect.swiper-container {\n  left: 50%;\n  margin-left: -150px;\n}\n[dir=rtl] .swiper-cube-effect.swiper-container {\n  right: 50%;\n  margin-right: -150px;\n}\n\n/* swiper coverflow slide width */\n.swiper-coverflow.swiper-container .swiper-slide {\n  width: 300px;\n}\n[dir] .gallery-thumbs {\n  padding: 10px 0;\n  background: #22292f;\n}\n.gallery-thumbs .swiper-slide {\n  opacity: 0.4;\n}\n.gallery-thumbs .swiper-slide-thumb-active {\n  opacity: 1;\n}\n[dir] .swiper-parallax .swiper-slide {\n  padding: 2.67rem 4rem;\n}\n.swiper-parallax .swiper-slide .title {\n  font-size: 1.07rem;\n}\n[dir] .swiper-parallax .swiper-slide .title {\n  padding: 0.5rem 0;\n}\n.swiper-parallax .swiper-slide .text {\n  font-size: 1rem;\n}\n.swiper-parallax .parallax-bg {\n  position: absolute;\n  width: 130%;\n}\n.swiper-virtual.swiper-container {\n  height: 300px;\n}\n.swiper-virtual.swiper-container .swiper-slide {\n  /* virtual slides  */\n  font-size: 1.5rem;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n[dir] .swiper-virtual.swiper-container .swiper-slide {\n  background-color: #eee;\n}\n.swiper-button-prev,\n.swiper-button-next,\n.swiper-container-rtl .swiper-button-prev,\n.swiper-container-rtl .swiper-button-next {\n  color: #fff;\n  width: 38px;\n  font-size: 2rem;\n}\n[dir] .swiper-button-prev, [dir] .swiper-button-next, [dir] .swiper-container-rtl .swiper-button-prev, [dir] .swiper-container-rtl .swiper-button-next {\n  background-image: none;\n}\n.swiper-button-prev:focus,\n.swiper-button-next:focus,\n.swiper-container-rtl .swiper-button-prev:focus,\n.swiper-container-rtl .swiper-button-next:focus {\n  outline: none;\n}\n.swiper-button-prev:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n[dir=ltr] .swiper-button-prev:after {\n  padding-right: 1px;\n}\n[dir=rtl] .swiper-button-prev:after {\n  padding-left: 1px;\n}\n.swiper-button-next:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n[dir=ltr] .swiper-button-next:after {\n  padding-right: 2px;\n}\n[dir=rtl] .swiper-button-next:after {\n  padding-left: 2px;\n}\n.swiper-container-rtl .swiper-button-prev:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-container-rtl .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n.swiper-container-rtl .swiper-button-next:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-container-rtl .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n@media only screen and (max-width: 768px) {\n.swiper-button-prev {\n    font-size: 1.286rem;\n    top: 55%;\n}\n.swiper-button-prev:after {\n    height: 28px;\n    width: 28px;\n}\n[dir] .swiper-button-prev:after {\n    background-size: 24px;\n}\n.swiper-button-next {\n    font-size: 1.286rem;\n    top: 55%;\n}\n[dir] .swiper-button-next:after {\n    background-size: 24px;\n}\n.swiper-centered-slides .swiper-button-next:after,\n.swiper-centered-slides .swiper-button-prev:after {\n    height: 28px;\n    width: 28px;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after, [dir] .swiper-centered-slides .swiper-button-prev:after {\n    background-size: 18px;\n}\n[dir] .swiper-parallax .swiper-slide {\n    padding: 1rem 1.2rem;\n}\n.swiper-parallax img {\n    height: 100% !important;\n}\n}\n@media only screen and (max-width: 576px) {\n[dir] .swiper-centered-slides.swiper-container .swiper-slide {\n    padding: 1.6rem 2.5rem;\n}\n.swiper-centered-slides.swiper-container .swiper-slide i,\n.swiper-centered-slides.swiper-container .swiper-slide svg {\n    height: 1.07rem !important;\n    width: 1.07rem !important;\n    font-size: 1.07rem !important;\n}\n.swiper-cube-effect.swiper-container {\n    width: 150px;\n}\n[dir=ltr] .swiper-cube-effect.swiper-container {\n    left: 70%;\n}\n[dir=rtl] .swiper-cube-effect.swiper-container {\n    right: 70%;\n}\n[dir] .swiper-parallax .swiper-slide {\n    padding: 1rem 1.3rem;\n}\n.swiper-virtual.swiper-container .swiper-slide {\n    font-size: 1rem;\n}\n}\n[dir] .dark-layout .swiper-container:not(.swiper-parallax) .swiper-slide {\n  background-color: #161d31;\n}\n[dir] .dark-layout .swiper-container.swiper-centered-slides .swiper-slide {\n  background-color: #283046;\n}\n.dark-layout .swiper-container.swiper-parallax .swiper-slide * {\n  color: #6e6b7b;\n}\n.swiper-slide.swiper-slide-active {\n  opacity: 1;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "[dir] .faq-search {\n  background-size: cover;\n  background-color: rgba(224, 23, 100, 0.12) !important;\n}\n[dir] .faq-search .faq-search-input .input-group:focus-within {\n  box-shadow: none;\n}\n[dir] .faq-contact .faq-contact-card {\n  background-color: rgba(186, 191, 199, 0.12);\n}\n@media (min-width: 992px) {\n[dir] .faq-search .card-body {\n    padding: 8rem !important;\n}\n}\n@media (min-width: 768px) and (max-width: 991.98px) {\n[dir] .faq-search .card-body {\n    padding: 6rem !important;\n}\n}\n@media (min-width: 768px) {\n.faq-search .faq-search-input .input-group {\n    width: 576px;\n}\n[dir] .faq-search .faq-search-input .input-group {\n    margin: 0 auto;\n}\n.faq-navigation {\n    height: 550px;\n}\n}\n.faq-navigation .nav.nav-pills {\n  height: 100%;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "/*=========================================================================================\n    File Name: ext-component-swiper.scss\n    Description: swiper plugin scss.\n    ----------------------------------------------------------------------------------------\n    Item Name: Vuexy - Vuejs, React, Angular, HTML & Laravel Admin Dashboard Template\n    Author: PIXINVENT\n    Author URL: http://www.themeforest.net/user/pixinvent\n==========================================================================================*/\n/* Swiper css */\n/* ---------- */\n/* swiper slide shadow */\n[dir=ltr] .swiper-container .swiper-shadow {\n  box-shadow: 2px 8px 10px 0 rgba(25, 42, 70, 0.13) !important;\n}\n[dir=rtl] .swiper-container .swiper-shadow {\n  box-shadow: -2px 8px 10px 0 rgba(25, 42, 70, 0.13) !important;\n}\n.swiper-pagination .swiper-pagination-bullet:focus {\n  outline: none;\n}\n[dir] .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active {\n  background-color: #E01764;\n}\n[dir] .swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill {\n  background-color: #E01764;\n}\n.swiper-centered-slides.swiper-container .swiper-slide {\n  font-weight: 500;\n  height: auto;\n  width: auto !important;\n}\n[dir] .swiper-centered-slides.swiper-container .swiper-slide {\n  text-align: center;\n  background-color: #fff;\n  padding: 2rem 5.5rem;\n  cursor: pointer;\n}\n[dir] .swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active {\n  border: 2px solid #E01764;\n}\n.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active i,\n.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active svg {\n  color: #E01764;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n[dir] .swiper-centered-slides .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n.swiper-centered-slides .swiper-button-next,\n.swiper-centered-slides .swiper-button-prev {\n  height: 40px !important;\n  width: 40px !important;\n}\n.swiper-centered-slides .swiper-button-next:after,\n.swiper-centered-slides .swiper-button-prev:after {\n  height: 40px !important;\n  width: 40px !important;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after, [dir] .swiper-centered-slides .swiper-button-prev:after {\n  border-radius: 50%;\n  background-color: #E01764;\n  box-shadow: 0 2px 4px 0 rgba(34, 41, 47, 0.5) !important;\n  background-size: 24px !important;\n}\n[dir] .swiper-centered-slides.swiper-container-rtl .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n[dir] .swiper-centered-slides.swiper-container-rtl .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n.swiper-centered-slides-2.swiper-container .swiper-slide {\n  font-weight: 500;\n  height: auto;\n  width: auto !important;\n}\n[dir] .swiper-centered-slides-2.swiper-container .swiper-slide {\n  background-color: #f2f4f4;\n  cursor: pointer;\n}\n.swiper-centered-slides-2.swiper-container .swiper-slide.swiper-slide-active {\n  color: #fff;\n}\n[dir] .swiper-centered-slides-2.swiper-container .swiper-slide.swiper-slide-active {\n  background-color: #E01764 !important;\n  box-shadow: 0 3px 6px 0 rgba(224, 23, 100, 0.5) !important;\n}\n\n/* cube effect */\n.swiper-cube-effect.swiper-container {\n  width: 300px;\n}\n[dir] .swiper-cube-effect.swiper-container {\n  margin-top: -12px;\n}\n[dir=ltr] .swiper-cube-effect.swiper-container {\n  left: 50%;\n  margin-left: -150px;\n}\n[dir=rtl] .swiper-cube-effect.swiper-container {\n  right: 50%;\n  margin-right: -150px;\n}\n\n/* swiper coverflow slide width */\n.swiper-coverflow.swiper-container .swiper-slide {\n  width: 300px;\n}\n[dir] .gallery-thumbs {\n  padding: 10px 0;\n  background: #22292f;\n}\n.gallery-thumbs .swiper-slide {\n  opacity: 0.4;\n}\n.gallery-thumbs .swiper-slide-thumb-active {\n  opacity: 1;\n}\n[dir] .swiper-parallax .swiper-slide {\n  padding: 2.67rem 4rem;\n}\n.swiper-parallax .swiper-slide .title {\n  font-size: 1.07rem;\n}\n[dir] .swiper-parallax .swiper-slide .title {\n  padding: 0.5rem 0;\n}\n.swiper-parallax .swiper-slide .text {\n  font-size: 1rem;\n}\n.swiper-parallax .parallax-bg {\n  position: absolute;\n  width: 130%;\n}\n.swiper-virtual.swiper-container {\n  height: 300px;\n}\n.swiper-virtual.swiper-container .swiper-slide {\n  /* virtual slides  */\n  font-size: 1.5rem;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n[dir] .swiper-virtual.swiper-container .swiper-slide {\n  background-color: #eee;\n}\n.swiper-button-prev,\n.swiper-button-next,\n.swiper-container-rtl .swiper-button-prev,\n.swiper-container-rtl .swiper-button-next {\n  color: #fff;\n  width: 38px;\n  font-size: 2rem;\n}\n[dir] .swiper-button-prev, [dir] .swiper-button-next, [dir] .swiper-container-rtl .swiper-button-prev, [dir] .swiper-container-rtl .swiper-button-next {\n  background-image: none;\n}\n.swiper-button-prev:focus,\n.swiper-button-next:focus,\n.swiper-container-rtl .swiper-button-prev:focus,\n.swiper-container-rtl .swiper-button-next:focus {\n  outline: none;\n}\n.swiper-button-prev:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n[dir=ltr] .swiper-button-prev:after {\n  padding-right: 1px;\n}\n[dir=rtl] .swiper-button-prev:after {\n  padding-left: 1px;\n}\n.swiper-button-next:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n[dir=ltr] .swiper-button-next:after {\n  padding-right: 2px;\n}\n[dir=rtl] .swiper-button-next:after {\n  padding-left: 2px;\n}\n.swiper-container-rtl .swiper-button-prev:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-container-rtl .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n.swiper-container-rtl .swiper-button-next:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-container-rtl .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n@media only screen and (max-width: 768px) {\n.swiper-button-prev {\n    font-size: 1.286rem;\n    top: 55%;\n}\n.swiper-button-prev:after {\n    height: 28px;\n    width: 28px;\n}\n[dir] .swiper-button-prev:after {\n    background-size: 24px;\n}\n.swiper-button-next {\n    font-size: 1.286rem;\n    top: 55%;\n}\n[dir] .swiper-button-next:after {\n    background-size: 24px;\n}\n.swiper-centered-slides .swiper-button-next:after,\n.swiper-centered-slides .swiper-button-prev:after {\n    height: 28px;\n    width: 28px;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after, [dir] .swiper-centered-slides .swiper-button-prev:after {\n    background-size: 18px;\n}\n[dir] .swiper-parallax .swiper-slide {\n    padding: 1rem 1.2rem;\n}\n.swiper-parallax img {\n    height: 100% !important;\n}\n}\n@media only screen and (max-width: 576px) {\n[dir] .swiper-centered-slides.swiper-container .swiper-slide {\n    padding: 1.6rem 2.5rem;\n}\n.swiper-centered-slides.swiper-container .swiper-slide i,\n.swiper-centered-slides.swiper-container .swiper-slide svg {\n    height: 1.07rem !important;\n    width: 1.07rem !important;\n    font-size: 1.07rem !important;\n}\n.swiper-cube-effect.swiper-container {\n    width: 150px;\n}\n[dir=ltr] .swiper-cube-effect.swiper-container {\n    left: 70%;\n}\n[dir=rtl] .swiper-cube-effect.swiper-container {\n    right: 70%;\n}\n[dir] .swiper-parallax .swiper-slide {\n    padding: 1rem 1.3rem;\n}\n.swiper-virtual.swiper-container .swiper-slide {\n    font-size: 1rem;\n}\n}\n[dir] .dark-layout .swiper-container:not(.swiper-parallax) .swiper-slide {\n  background-color: #161d31;\n}\n[dir] .dark-layout .swiper-container.swiper-centered-slides .swiper-slide {\n  background-color: #283046;\n}\n.dark-layout .swiper-container.swiper-parallax .swiper-slide * {\n  color: #6e6b7b;\n}\n.swiper-slide.swiper-slide-active {\n  opacity: 1;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.nav-link[data-v-2fc1290a] {\n  color: black;\n}\n.ht[data-v-2fc1290a] {\n  min-height: 600px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./home.vue?vue&type=style&index=0&id=4ced6408&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=template&id=4ced6408":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/home.vue?vue&type=template&id=4ced6408 ***!
  \************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-row",
    { staticStyle: { "margin-top": "110px" } },
    [
      _c("b-col", { attrs: { cols: "12" } }, [
        _vm.is_loading
          ? _c(
              "div",
              {
                staticClass: " mt-1 text-center",
                staticStyle: {
                  display: "flex",
                  "align-content": "space-around",
                  "justify-content": "space-between",
                  "flex-direction": "column",
                  "flex-wrap": "wrap",
                },
              },
              [
                _c("lottie-player", {
                  staticStyle: { width: "300px", height: "500px" },
                  attrs: {
                    src: "/animations/loading.json",
                    background: "#FFFDFD",
                    speed: "1",
                    loop: "",
                    autoplay: "",
                  },
                }),
              ],
              1
            )
          : _vm._e(),
        _vm._v(" "),
        !_vm.is_loading
          ? _c(
              "div",
              { staticClass: "container" },
              _vm._l(_vm.componentOrder, function (component) {
                return _c(component.name, {
                  key: component.name,
                  tag: "component",
                  attrs: {
                    prop: component.prop,
                    business_id: _vm.business_id,
                    isopen: _vm.isopen,
                    title: component.title,
                    prop2: component.id,
                  },
                })
              }),
              1
            )
          : _vm._e(),
      ]),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=template&id=2fc1290a&scoped=true":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/banner.vue?vue&type=template&id=2fc1290a&scoped=true ***!
  \**********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "section",
      { attrs: { id: "faq-search-filter " } },
      [
        _c(
          "b-card",
          { staticClass: "faq-search ", attrs: { "no-body": "" } },
          [
            _c("b-img-lazy", {
              staticClass: "rounded3",
              attrs: {
                src: "/banner.jpg",
                fluid: "",
                alt: "Scan",
                width: "1500",
                height: "347",
              },
            }),
          ],
          1
        ),
      ],
      1
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=template&id=8919fc30":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=template&id=8919fc30 ***!
  \*****************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "b-row",
    { staticStyle: { "margin-top": "80px" } },
    [
      _c("b-col", { attrs: { cols: "12" } }, [
        _c(
          "div",
          { staticClass: "container" },
          [
            _vm.layout_type == 2
              ? _c(
                  "b-row",
                  [
                    _c(
                      "b-col",
                      { attrs: { lg: "6", md: "6", sm: "12" } },
                      [
                        _c("b-img", {
                          attrs: { src: _vm.image, fluid: "", alt: "Scan" },
                        }),
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("b-col", { attrs: { lg: "6", md: "6", sm: "12" } }, [
                      _c("div", {
                        domProps: { innerHTML: _vm._s(_vm.page_content) },
                      }),
                    ]),
                  ],
                  1
                )
              : _c(
                  "b-row",
                  [
                    _c("b-col", { attrs: { lg: "6", md: "6", sm: "12" } }, [
                      _c("div", {
                        domProps: { innerHTML: _vm._s(_vm.page_content) },
                      }),
                    ]),
                    _vm._v(" "),
                    _c(
                      "b-col",
                      { attrs: { lg: "6", md: "6", sm: "12" } },
                      [
                        _c("b-img", {
                          attrs: { src: _vm.image, fluid: "", alt: "Scan" },
                        }),
                      ],
                      1
                    ),
                  ],
                  1
                ),
          ],
          1
        ),
      ]),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/user_src/views/user/business/home.vue":
/*!************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/home.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _home_vue_vue_type_template_id_4ced6408__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./home.vue?vue&type=template&id=4ced6408 */ "./resources/js/user_src/views/user/business/home.vue?vue&type=template&id=4ced6408");
/* harmony import */ var _home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./home.vue?vue&type=script&lang=js */ "./resources/js/user_src/views/user/business/home.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _home_vue_vue_type_style_index_0_id_4ced6408_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./home.vue?vue&type=style&index=0&id=4ced6408&lang=scss */ "./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _home_vue_vue_type_template_id_4ced6408__WEBPACK_IMPORTED_MODULE_0__["render"],
  _home_vue_vue_type_template_id_4ced6408__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/user_src/views/user/business/home.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/user_src/views/user/business/home.vue?vue&type=script&lang=js":
/*!************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/home.vue?vue&type=script&lang=js ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./home.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss ***!
  \*********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_style_index_0_id_4ced6408_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./home.vue?vue&type=style&index=0&id=4ced6408&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=style&index=0&id=4ced6408&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_style_index_0_id_4ced6408_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_style_index_0_id_4ced6408_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_style_index_0_id_4ced6408_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_style_index_0_id_4ced6408_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/business/home.vue?vue&type=template&id=4ced6408":
/*!******************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/home.vue?vue&type=template&id=4ced6408 ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_template_id_4ced6408__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./home.vue?vue&type=template&id=4ced6408 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/home.vue?vue&type=template&id=4ced6408");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_template_id_4ced6408__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_home_vue_vue_type_template_id_4ced6408__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/user_src/views/user/home/banner.vue":
/*!**********************************************************!*\
  !*** ./resources/js/user_src/views/user/home/banner.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _banner_vue_vue_type_template_id_2fc1290a_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./banner.vue?vue&type=template&id=2fc1290a&scoped=true */ "./resources/js/user_src/views/user/home/banner.vue?vue&type=template&id=2fc1290a&scoped=true");
/* harmony import */ var _banner_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./banner.vue?vue&type=script&lang=js */ "./resources/js/user_src/views/user/home/banner.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _banner_vue_vue_type_style_index_0_id_2fc1290a_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss */ "./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss");
/* harmony import */ var _banner_vue_vue_type_style_index_1_id_2fc1290a_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css */ "./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");







/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_4__["default"])(
  _banner_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _banner_vue_vue_type_template_id_2fc1290a_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"],
  _banner_vue_vue_type_template_id_2fc1290a_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "2fc1290a",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/user_src/views/user/home/banner.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/user_src/views/user/home/banner.vue?vue&type=script&lang=js":
/*!**********************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/banner.vue?vue&type=script&lang=js ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./banner.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss ***!
  \*******************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_0_id_2fc1290a_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=0&id=2fc1290a&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_0_id_2fc1290a_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_0_id_2fc1290a_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_0_id_2fc1290a_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_0_id_2fc1290a_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css ***!
  \******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_1_id_2fc1290a_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=style&index=1&id=2fc1290a&scoped=true&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_1_id_2fc1290a_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_1_id_2fc1290a_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_1_id_2fc1290a_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_style_index_1_id_2fc1290a_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/home/banner.vue?vue&type=template&id=2fc1290a&scoped=true":
/*!****************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/banner.vue?vue&type=template&id=2fc1290a&scoped=true ***!
  \****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_template_id_2fc1290a_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./banner.vue?vue&type=template&id=2fc1290a&scoped=true */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/banner.vue?vue&type=template&id=2fc1290a&scoped=true");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_template_id_2fc1290a_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_banner_vue_vue_type_template_id_2fc1290a_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/user_src/views/user/static_pages/page1.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/user_src/views/user/static_pages/page1.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _page1_vue_vue_type_template_id_8919fc30__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./page1.vue?vue&type=template&id=8919fc30 */ "./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=template&id=8919fc30");
/* harmony import */ var _page1_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./page1.vue?vue&type=script&lang=js */ "./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _page1_vue_vue_type_style_index_0_id_8919fc30_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss */ "./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _page1_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _page1_vue_vue_type_template_id_8919fc30__WEBPACK_IMPORTED_MODULE_0__["render"],
  _page1_vue_vue_type_template_id_8919fc30__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/user_src/views/user/static_pages/page1.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=script&lang=js":
/*!*****************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./page1.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss ***!
  \**************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_style_index_0_id_8919fc30_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=style&index=0&id=8919fc30&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_style_index_0_id_8919fc30_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_style_index_0_id_8919fc30_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_style_index_0_id_8919fc30_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_style_index_0_id_8919fc30_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=template&id=8919fc30":
/*!***********************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=template&id=8919fc30 ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_template_id_8919fc30__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./page1.vue?vue&type=template&id=8919fc30 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/static_pages/page1.vue?vue&type=template&id=8919fc30");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_template_id_8919fc30__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_page1_vue_vue_type_template_id_8919fc30__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);