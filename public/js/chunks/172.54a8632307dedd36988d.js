(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[172],{

/***/ "./node_modules/@babel/runtime/helpers/esm/createForOfIteratorHelper.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/createForOfIteratorHelper.js ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _createForOfIteratorHelper; });
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");

function _createForOfIteratorHelper(o, allowArrayLike) {
  var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"];
  if (!it) {
    if (Array.isArray(o) || (it = Object(_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o)) || allowArrayLike && o && typeof o.length === "number") {
      if (it) o = it;
      var i = 0;
      var F = function F() {};
      return {
        s: F,
        n: function n() {
          if (i >= o.length) return {
            done: true
          };
          return {
            done: false,
            value: o[i++]
          };
        },
        e: function e(_e) {
          throw _e;
        },
        f: F
      };
    }
    throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }
  var normalCompletion = true,
    didErr = false,
    err;
  return {
    s: function s() {
      it = it.call(o);
    },
    n: function n() {
      var step = it.next();
      normalCompletion = step.done;
      return step;
    },
    e: function e(_e2) {
      didErr = true;
      err = _e2;
    },
    f: function f() {
      try {
        if (!normalCompletion && it["return"] != null) it["return"]();
      } finally {
        if (didErr) throw err;
      }
    }
  };
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************************************/
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
    BAvatar: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BAvatar"]
  },
  props: {
    variant: {
      type: String,
      "default": 'primary'
    },
    icon: {
      type: String,
      "default": null
    },
    title: {
      type: String,
      "default": null
    },
    text: {
      type: String,
      "default": null
    },
    hideClose: {
      type: Boolean,
      "default": false
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=script&lang=js":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=script&lang=js ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_createForOfIteratorHelper_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/createForOfIteratorHelper.js */ "./node_modules/@babel/runtime/helpers/esm/createForOfIteratorHelper.js");
/* harmony import */ var core_js_modules_es_array_find_index_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.find-index.js */ "./node_modules/core-js/modules/es.array.find-index.js");
/* harmony import */ var core_js_modules_es_array_find_index_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_find_index_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.array.join.js */ "./node_modules/core-js/modules/es.array.join.js");
/* harmony import */ var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.array.map.js */ "./node_modules/core-js/modules/es.array.map.js");
/* harmony import */ var core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_map_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.array.reduce.js */ "./node_modules/core-js/modules/es.array.reduce.js");
/* harmony import */ var core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_reduce_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_array_splice_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.array.splice.js */ "./node_modules/core-js/modules/es.array.splice.js");
/* harmony import */ var core_js_modules_es_array_splice_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_splice_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var core_js_modules_es_json_stringify_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! core-js/modules/es.json.stringify.js */ "./node_modules/core-js/modules/es.json.stringify.js");
/* harmony import */ var core_js_modules_es_json_stringify_js__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_json_stringify_js__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var core_js_modules_es_number_to_fixed_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! core-js/modules/es.number.to-fixed.js */ "./node_modules/core-js/modules/es.number.to-fixed.js");
/* harmony import */ var core_js_modules_es_number_to_fixed_js__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_number_to_fixed_js__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! core-js/modules/es.object.keys.js */ "./node_modules/core-js/modules/es.object.keys.js");
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var core_js_modules_es_string_replace_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! core-js/modules/es.string.replace.js */ "./node_modules/core-js/modules/es.string.replace.js");
/* harmony import */ var core_js_modules_es_string_replace_js__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_replace_js__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var vue_slider_component__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! vue-slider-component */ "./node_modules/vue-slider-component/dist/vue-slider-component.umd.min.js");
/* harmony import */ var vue_slider_component__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(vue_slider_component__WEBPACK_IMPORTED_MODULE_16__);
/* harmony import */ var _vueuse_core__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! @vueuse/core */ "./node_modules/@vueuse/core/dist/index.esm.js");
/* harmony import */ var _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! @core/components/toastification/ToastificationContent.vue */ "./resources/js/src/@core/components/toastification/ToastificationContent.vue");
/* harmony import */ var vue_ripple_directive__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! vue-ripple-directive */ "./node_modules/vue-ripple-directive/src/ripple.js");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! @@/store */ "./resources/js/store_src/store/index.js");
/* harmony import */ var vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! vue-awesome-swiper */ "./node_modules/vue-awesome-swiper/dist/vue-awesome-swiper.js");
/* harmony import */ var vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_21___default = /*#__PURE__*/__webpack_require__.n(vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_21__);
/* harmony import */ var swiper_css_swiper_css__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! swiper/css/swiper.css */ "./node_modules/swiper/css/swiper.css");
/* harmony import */ var swiper_css_swiper_css__WEBPACK_IMPORTED_MODULE_22___default = /*#__PURE__*/__webpack_require__.n(swiper_css_swiper_css__WEBPACK_IMPORTED_MODULE_22__);
/* harmony import */ var vue_autosuggest__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! vue-autosuggest */ "./node_modules/vue-autosuggest/dist/vue-autosuggest.esm.js");
/* harmony import */ var vue_perfect_scrollbar__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! vue-perfect-scrollbar */ "./node_modules/vue-perfect-scrollbar/dist/index.js");
/* harmony import */ var vue_perfect_scrollbar__WEBPACK_IMPORTED_MODULE_24___default = /*#__PURE__*/__webpack_require__.n(vue_perfect_scrollbar__WEBPACK_IMPORTED_MODULE_24__);
/* harmony import */ var vee_validate__WEBPACK_IMPORTED_MODULE_25__ = __webpack_require__(/*! vee-validate */ "./node_modules/vee-validate/dist/vee-validate.esm.js");
/* harmony import */ var _validations__WEBPACK_IMPORTED_MODULE_26__ = __webpack_require__(/*! @validations */ "./resources/js/src/@core/utils/validations/validations.js");















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
    ValidationProvider: vee_validate__WEBPACK_IMPORTED_MODULE_25__["ValidationProvider"],
    ValidationObserver: vee_validate__WEBPACK_IMPORTED_MODULE_25__["ValidationObserver"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BButton"],
    BForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BForm"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCard"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCol"],
    BAvatar: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BAvatar"],
    BCardBody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCardBody"],
    BInputGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BInputGroup"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormInput"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCardText"],
    BInputGroupPrepend: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BInputGroupPrepend"],
    BTabs: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BTabs"],
    BTab: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BTab"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BImg"],
    BNav: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BNav"],
    BNavItem: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BNavItem"],
    BBadge: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BBadge"],
    VueSlider: vue_slider_component__WEBPACK_IMPORTED_MODULE_16___default.a,
    BMedia: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BMedia"],
    BModal: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BModal"],
    VBModal: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["VBModal"],
    BFormSpinbutton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormSpinbutton"],
    VueAutosuggest: vue_autosuggest__WEBPACK_IMPORTED_MODULE_23__["VueAutosuggest"],
    Swiper: vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_21__["Swiper"],
    SwiperSlide: vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_21__["SwiperSlide"],
    BLink: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BLink"],
    VuePerfectScrollbar: vue_perfect_scrollbar__WEBPACK_IMPORTED_MODULE_24___default.a,
    required: _validations__WEBPACK_IMPORTED_MODULE_26__["required"],
    confirmed: _validations__WEBPACK_IMPORTED_MODULE_26__["confirmed"],
    password: _validations__WEBPACK_IMPORTED_MODULE_26__["password"],
    email: _validations__WEBPACK_IMPORTED_MODULE_26__["email"],
    min: _validations__WEBPACK_IMPORTED_MODULE_26__["min"],
    integer: _validations__WEBPACK_IMPORTED_MODULE_26__["integer"],
    url: _validations__WEBPACK_IMPORTED_MODULE_26__["url"],
    alpha: _validations__WEBPACK_IMPORTED_MODULE_26__["alpha"],
    between: _validations__WEBPACK_IMPORTED_MODULE_26__["between"],
    digits: _validations__WEBPACK_IMPORTED_MODULE_26__["digits"],
    length: _validations__WEBPACK_IMPORTED_MODULE_26__["length"],
    alphaDash: _validations__WEBPACK_IMPORTED_MODULE_26__["alphaDash"],
    BFormGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormGroup"]
  },
  data: function data() {
    var _this = this;
    return {
      rest_id: localStorage.id,
      order_details: {},
      rows: {},
      rows_detail: {},
      rows_detail2: {},
      isLoading: true,
      isfav: false,
      address: "",
      cart: JSON.parse(localStorage.getItem("store_cart")),
      add_ons: {},
      food_quantity: {},
      itemid: "",
      selected: {},
      quantity: [],
      cart_items: [],
      promocodes: {},
      top_selling: {},
      isopen: true,
      modal_image: "",
      modal_title: "",
      setting: {},
      ctax: 0,
      cdc: 0,
      ctips: 0,
      cbill: 0,
      cdiscount: 0,
      cdelivery_charge: 0,
      cpackaging_charge: 0,
      citem_total: 0,
      pos_customer: -1,
      payment_type: 1,
      order_type: 1,
      business_type: {},
      datasuggest: [],
      datasuggest2: [],
      cust: {},
      query: '',
      query1: '',
      suggestions: [],
      suggestions1: [],
      selected1: null,
      selected2: null,
      inputProps: {
        id: 'autosuggest__input',
        "class": 'form-control ht-6',
        placeholder: "Search Products "
      },
      sectionConfigs: {
        products: {
          limit: 20,
          label: 'Products',
          onSelected: function onSelected(selected1) {
            _this.selected1 = selected1.item;
            _this.go_to_product(selected1.item);
          }
        }
      },
      inputProps1: {
        id: 'autosuggest__input',
        "class": 'form-control ht-6',
        placeholder: "Search Customer "
      },
      sectionConfigs1: {
        Users: {
          limit: 20,
          label: 'Users',
          onSelected: function onSelected(selected2) {
            _this.selected2 = selected2.item;
            _this.go_to_user(selected2.item);
          }
        }
      },
      limit: 10,
      swiperOptions: {
        breakpoints: {
          320: {
            slidesPerView: 1.2,
            spaceBetween: 30
          },
          420: {
            slidesPerView: 2.2,
            spaceBetween: 30
          },
          768: {
            slidesPerView: 3.2,
            spaceBetween: 30
          },
          1200: {
            slidesPerView: 4.2,
            spaceBetween: 30
          }
        },
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev"
        }
      }
    };
  },
  mounted: function mounted() {},
  directives: {
    "b-modal": bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["VBModal"],
    Ripple: vue_ripple_directive__WEBPACK_IMPORTED_MODULE_19__["default"]
  },
  filters: {
    price_format_final: function price_format_final(price) {
      var number = price,
        decimals = 2,
        dec_point = ".",
        thousands_sep = '';
      number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
      var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
        dec = typeof dec_point === 'undefined' ? '.' : dec_point,
        s = '',
        toFixedFix = function toFixedFix(n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
        };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
    }
  },
  methods: {
    add_customer: function add_customer() {
      var _this2 = this;
      this.$refs.simpleRules.validate().then(function (success) {
        if (success) {
          _this2.$http.post("/store/add_customer", _this2.cust).then(function (response) {
            if (response.data.status == true) {
              _this2.pos_customer = response.data.data.id;
              _this2.query1 = response.data.data.name;
              _this2.$toast({
                component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_18__["default"],
                position: "bottom-right",
                props: {
                  title: response.data.message,
                  icon: "UserIcon",
                  variant: "success"
                }
              });
              _this2.$refs['my-modal'].hide();
            } else {
              _this2.$toast({
                component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_18__["default"],
                position: "bottom-right",
                props: {
                  title: response.data.message,
                  icon: "UserIcon",
                  variant: "danger"
                }
              });
            }
          })["catch"](function (error) {
            return console.log(error);
          })["finally"](function () {
            return _this2.loading = false;
          });
        }
      });
    },
    showModal: function showModal() {
      this.$refs['my-modal'].show();
    },
    o_type_sel: function o_type_sel(otype) {
      this.order_type = otype;
      if (otype == 1) {
        this.cpackaging_charge = 0;
        document.getElementById("dineinp").classList.add("tipsactive1");
        document.getElementById("takeawayp").classList.remove("tipsactive1");
        this.loadcart();
      }
      if (otype == 2) {
        document.getElementById("takeawayp").classList.add("tipsactive1");
        document.getElementById("dineinp").classList.remove("tipsactive1");
        this.loadcart();
      }
    },
    payment_type_sel: function payment_type_sel(ptype) {
      this.payment_type = ptype;
      if (ptype == 1) {
        document.getElementById("cashp").classList.add("tipsactive1");
        document.getElementById("walletp").classList.remove("tipsactive1");
      }
      if (ptype == 2) {
        document.getElementById("walletp").classList.add("tipsactive1");
        document.getElementById("cashp").classList.remove("tipsactive1");
      }
    },
    go_to_user: function go_to_user(user) {
      this.pos_customer = user.id;
    },
    go_to_product: function go_to_product(product) {
      var _this3 = this;
      this.$http.get('/store/search_products_from_name/' + this.rest_id + '/' + product.name).then(function (res) {
        _this3.rows_detail = res.data.items;
        _this3.loadcart();
      });
    },
    scrollMeTo: function scrollMeTo(refName, id) {
      var _this4 = this;
      this.isLoading = true;
      if (refName == 0) {
        this.$http.get("/store/get_food_list_by_menu_pos/" + this.rest_id).then(function (res) {
          _this4.rows_detail = res.data.items;
          _this4.rows_detail2 = res.data.items;
          _this4.isLoading = false;
          var li = document.querySelectorAll('.side div');
          li.forEach(function (li) {
            li.classList.remove("active");
          });
          _this4.isLoading = false;
          var box = document.querySelector('[id="' + refName + '"]');
          box.classList.add("active");
          var rootEle = document.documentElement;
          rootEle.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
      } else {
        this.$http.get('/get_food_list_by_menu_id/' + refName + '/' + this.rest_id).then(function (res) {
          _this4.rows_detail = res.data.items;
          var li = document.querySelectorAll('.side div');
          li.forEach(function (li) {
            li.classList.remove("active");
          });
          _this4.isLoading = false;
          var box = document.querySelector('[id="' + _this4.rows_detail[0].menu_name + '"]');
          box.classList.add("active");
          var rootEle = document.documentElement;
          rootEle.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
      }
    },
    fetchResults: function fetchResults(text) {
      var _this5 = this;
      var query = this.query;
      if (query) {
        this.suggestions = [];
      }
      this.$http.get('/store/search_products/' + this.rest_id + '/' + text).then(function (res) {
        _this5.datasuggest2 = res.data.food_list;
      });
      var filteredPages = this.datasuggest2;
      this.suggestions.push({
        name: 'products',
        data: filteredPages
      });
    },
    renderSuggestion: function renderSuggestion(suggestion) {
      var h = this.$createElement;
      if (suggestion.name === 'products') {
        var page = suggestion.item;
        return h("div", {
          "class": "d-flex align-items-center"
        }, [h("b-avatar", {
          "attrs": {
            "src": page.img
          },
          "class": "mr-50"
        }), h("div", {
          "class": "detail"
        }, [h("b-card-text", {
          "class": "mb-0"
        }, [page.name]), h("small", {
          "class": "text-muted"
        }, [page.restaurant_name])])]);
      }
      return false;
    },
    fetchResults1: function fetchResults1(text) {
      var _this6 = this;
      var query1 = this.query1;
      if (query1) {
        this.suggestions1 = [];
      }
      this.$http.get('/store/search_users/' + text).then(function (res) {
        _this6.datasuggest = res.data.user_list;
      });
      var filteredPages1 = this.datasuggest;
      this.suggestions1.push({
        name: 'Users',
        data: filteredPages1
      });
    },
    renderSuggestion1: function renderSuggestion1(suggestion1) {
      var h = this.$createElement;
      if (suggestion1.name === 'Users') {
        var page1 = suggestion1.item;
        return h("div", {
          "class": "d-flex align-items-center"
        }, [h("b-avatar", {
          "attrs": {
            "src": page1.img
          },
          "class": "mr-50"
        }), h("div", {
          "class": "detail"
        }, [h("b-card-text", {
          "class": "mb-0"
        }, [page1.name]), h("small", {
          "class": "text-muted"
        }, [page1.phone])])]);
      }
      return false;
    },
    create_order: function create_order() {
      var _this7 = this;
      localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
      var cart = JSON.parse(localStorage.getItem("store_cart"));
      if (cart.length === 0) return alert('No items in the cart');
      var item_total = cart.reduce(function (o, c, i) {
        return cart[i].quantity * (cart[i].price + cart[i].addonsPrice) + o;
      }, 0);
      var data = new FormData();
      data.append('user_id', this.pos_customer);
      data.append('restaurant_id', this.rest_id);
      data.append('food_id', cart.map(function (item) {
        return item.id;
      }).join(','));
      data.append('food_price', cart.map(function (item) {
        return item.price;
      }).join(','));
      data.append('food_qty', cart.map(function (item) {
        return item.quantity;
      }).join(','));
      data.append('item_total', item_total);
      data.append('addons', cart.map(function (item) {
        return item.addons;
      }).join('-'));
      data.append('sizes', cart.map(function (item) {
        return item.sizes;
      }).join('-'));
      data.append('paid_type', this.payment_type);
      data.append('gst', parseFloat(this.ctax));
      data.append('tips', 0);
      data.append('offer_discount', parseFloat(this.cdiscount));
      data.append('delivery_charge', 0);
      data.append('restaurant_packaging_charge', parseFloat(this.cpackaging_charge));
      data.append('bill_amount', parseFloat(this.cbill));
      data.append('device_type', 'WEB');
      data.append('is_cod', 1);
      this.$http.post('/store/create_pos', data).then(function (res) {
        console.log(res);
        console.log(res.data.data.order_id);
        console.log(res.data.message);
        if (res.data.status == true) {
          _this7.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_18__["default"],
            position: "bottom-right",
            props: {
              title: res.data.message,
              icon: "UserIcon",
              variant: "success"
            }
          });
          _this7.$router.push({
            name: 'view_order',
            params: {
              id: res.data.data.order_id
            }
          });
        } else {
          _this7.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_18__["default"],
            position: "bottom-right",
            props: {
              title: res.data.message,
              icon: "UserIcon",
              variant: "danger"
            }
          });
        }
        console.log(res);
      });
    },
    open_image: function open_image(image, title) {
      this.$refs["image-modal"].show();
      this.modal_image = image;
      this.modal_title = title;
    },
    addcart: function addcart(price, item_tax, name, food_id, is_veg, add_ons, food_quantity, food_image) {
      localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
      this.itemid = food_id;
      var id = food_id,
        restaurant = this.rows[0].id,
        isveg = is_veg,
        restaurant_name = this.rows[0].name,
        restaurant_address = this.rows[0].address,
        restaurant_image = this.rows[0].image,
        taxperc = parseFloat(item_tax),
        cart = JSON.parse(localStorage.getItem("store_cart"));
      price = parseFloat(price);
      this.selected.id = id;
      this.selected.restaurant = restaurant;
      this.selected.isveg = isveg;
      this.selected.restaurant_name = restaurant_name;
      this.selected.restaurant_address = restaurant_address;
      this.selected.restaurant_image = restaurant_image;
      this.selected.taxperc = taxperc;
      this.selected.price = price;
      this.selected.add_ons = add_ons;
      this.selected.food_quantity = food_quantity;
      this.selected.food_image = food_image;
      this.selected.name = name;
      if (add_ons.length > 0 || food_quantity.length > 0) {
        this.add_ons = add_ons;
        this.food_quantity = food_quantity;
        this.$refs["addon-modal"].show();
      } else {
        if (cart.length && cart[0].restaurant != restaurant) {
          var ok = confirm("The items previously will be deleted");
          if (ok) {
            cart = [];
          } else return;
        }
        var foodIndex = cart.findIndex(function (food, index) {
          return food.id == id;
        });
        if (foodIndex !== -1) {
          cart[foodIndex].quantity++;
          cart[foodIndex].addons += " ";
          cart[foodIndex].sizes += " ";
        } else {
          cart.push({
            id: id,
            name: name,
            isveg: isveg,
            price: parseFloat(parseFloat(price).toFixed(2)),
            quantity: 1,
            restaurant: restaurant,
            addons: "",
            sizes: "",
            addonsName: "",
            addonsPrice: 0,
            sizeName: "",
            restaurant_name: restaurant_name,
            restaurant_address: restaurant_address,
            restaurant_image: restaurant_image,
            sizePrice: 0,
            taxperc: taxperc,
            food_image: food_image
          });
        }
        localStorage.setItem("store_cart", JSON.stringify(cart));
        var cartn = JSON.parse(localStorage.getItem("store_cart"));
        this.loadcart();
      }
    },
    number_format: function number_format(number, decimals, dec_point, thousands_sep) {
      // Strip all characters but numerical ones.
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function toFixedFix(n, prec) {
          var k = Math.pow(10, prec);
          return "" + Math.round(n * k) / k;
        };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
      }
      return s.join(dec);
    },
    loadcart: function loadcart() {
      localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
      var DELIVERY_CHARGE = 0;
      var offer_discount = parseFloat(this.rows[0].offer_discount);
      var packaging_charge = parseFloat(this.rows[0].packaging_charge);
      var cart = JSON.parse(localStorage.getItem("store_cart"));
      var tips = parseFloat(this.order_details.tax);
      var total_price = 0;
      var total_item = 0;
      var tot_tax = 0;
      var is_tax = parseFloat(this.rows[0].tax);
      var tot_amt = 0;
      var item_amount_total = 0;
      var restaurant_packaging_charge = 0;
      cart.forEach(function (item, i) {
        if (document.querySelectorAll('[id="food_control_' + item.id + 'r"]')) {
          var elms = document.querySelectorAll('[id="food_control_' + item.id + 'r"]');
          for (var i = 0; i < elms.length; i++) {
            elms[i].style.display = "block";
          }
          var elms2 = document.querySelectorAll('[id="food_add_' + item.id + 'r"]');
          for (var i = 0; i < elms2.length; i++) {
            elms2[i].style.display = "none";
          }
          var elms3 = document.querySelectorAll('[id="food_qty_' + item.id + 'r"]');
          var elms4 = document.querySelectorAll('[id="food_qty_' + item.id + 'c"]');
          for (var i = 0; i < elms3.length; i++) {
            elms3[i].value = item.quantity;
          }
          for (var i = 0; i < elms4.length; i++) {
            elms4[i].value = item.quantity;
          }
        }
        total_price += parseFloat((item.quantity * (item.price + item.addonsPrice)).toFixed(2));
        total_item++;
        var thisAmount = parseFloat(((item.price + item.addonsPrice) * item.quantity).toFixed(2));
        tot_tax += parseFloat(thisAmount / 100 * item.taxperc);
        localStorage.setItem("tot_tax", tot_tax);
        if (is_tax == 0) {
          tot_amt = total_price + tot_tax;
        } else {
          tot_amt = total_price;
        }
        item_amount_total += thisAmount;
      });
      if (this.order_type == 1) {
        restaurant_packaging_charge = 0;
      } else {
        restaurant_packaging_charge = parseFloat(item_amount_total / 100 * packaging_charge);
      }
      var delivery_charge_calc = 0;
      var tips = 0;
      total_price = tot_amt === 0 ? tot_amt : (tot_amt + restaurant_packaging_charge + DELIVERY_CHARGE).toFixed(2);
      var bill = {
        item_total: tot_amt,
        packaging_charge: restaurant_packaging_charge,
        delivery_charge: 0,
        discount: offer_discount,
        bill: total_price,
        tips: 0,
        dc: 0,
        tax: tot_tax
      };
      this.citem_total = tot_amt;
      this.cpackaging_charge = restaurant_packaging_charge;
      console.log(this.cpackaging_charge);
      this.cdelivery_charge = DELIVERY_CHARGE;
      this.cdiscount = offer_discount;
      this.cbill = total_price;
      this.ctips = tips;
      this.cdc = DELIVERY_CHARGE;
      this.ctax = tot_tax;
      this.cart_items = JSON.parse(localStorage.getItem("store_cart"));
      // localStorage.setItem("DC", JSON.stringify(DELIVERY_CHARGE));

      // var offer_discount = parseFloat(this.rows[0].offer_discount);
      // var DELIVERY_CHARGE_TYPE = this.rows[0].delivery_charge_type;
      // localStorage.setItem("DELIVERY_CHARGE_TYPE", DELIVERY_CHARGE_TYPE);

      // var packaging_charge = parseFloat(this.rows[0].packaging_charge);
      // localStorage.setItem("RES_PACK_CHARGE", packaging_charge);

      // var DELIVERY_CHARGE_BASEDON = this.rows[0].DeliveryChargeBasedOn;
      // localStorage.setItem(
      //     "DC_BON",
      //     JSON.stringify(DELIVERY_CHARGE_BASEDON)
      // );

      // localStorage.setItem("IS_TAX_INCLUSIVE", this.rows[0].tax);

      // localStorage.setItem("cart", localStorage.getItem("store_cart") || "[]");
      // let cart = JSON.parse(localStorage.getItem("store_cart"));
      // var lat = localStorage.getItem("latitude");
      // var lng = localStorage.getItem("longitude");
      // if (localStorage.getItem("webuserData")) {
      //     this.$http
      //         .get(
      //             "/get_checkout_details/" +
      //                 cart[0].restaurant +
      //                 "/" +
      //                 lat +
      //                 "/" +
      //                 lng +
      //                 "/" +
      //                 this.userData.id
      //         )
      //         .then((res) => {

      //             if (res.data.status == true) {
      //                 localStorage.setItem("DISTANCE", res.data.distance);
      //             } else {
      //                 this.$toast({
      //                     component: ToastificationContent,
      //                     position: "bottom-right",
      //                     props: {
      //                         title: res.data.message,
      //                         icon: "UserIcon",
      //                         variant: "danger",
      //                     },
      //                 });
      //             }
      //         });
      // }
      // var tips = 0;

      // let total_price = 0;
      // let total_item = 0;
      // let tot_tax = 0;
      // let is_tax = localStorage.getItem("IS_TAX_INCLUSIVE");
      // let tot_amt = 0;
      // let item_amount_total = 0;
      // let restaurant_packaging_charge = 0;
      // if (packaging_charge == 0) {
      //     packaging_charge = JSON.parse(
      //         localStorage.getItem("RES_PACK_CHARGE")
      //     );
      // }
      // if (cart.length === 0) {
      // } else {
      //     cart.forEach(function (item, i) {
      //         if (
      //             document.querySelectorAll(
      //                 '[id="food_control_' + item.id + 'r"]'
      //             )
      //         ) {
      //             var elms = document.querySelectorAll(
      //                 '[id="food_control_' + item.id + 'r"]'
      //             );

      //             for (var i = 0; i < elms.length; i++) {
      //                 elms[i].style.display = "block";
      //             }
      //             var elms2 = document.querySelectorAll(
      //                 '[id="food_add_' + item.id + 'r"]'
      //             );
      //             for (var i = 0; i < elms2.length; i++) {
      //                 elms2[i].style.display = "none";
      //             }

      //             var elms3 = document.querySelectorAll(
      //                 '[id="food_qty_' + item.id + 'r"]'
      //             );
      //             for (var i = 0; i < elms3.length; i++) {
      //                 elms3[i].value = item.quantity;
      //             }
      //             // document.getElementById('food_control_' + item.id + 'r').style.display = 'block';
      //             // document.getElementById('food_add_' + item.id+'r').style.display = 'none';

      //             // document.getElementById('food_qty_' + item.id+'r').value = item.quantity;
      //         }

      //         total_price += parseFloat(
      //             (
      //                 item.quantity *
      //                 (item.price + item.addonsPrice)
      //             ).toFixed(2)
      //         );
      //         total_item++;
      //         let thisAmount = parseFloat(
      //             (
      //                 (item.price + item.addonsPrice) *
      //                 item.quantity
      //             ).toFixed(2)
      //         );
      //         tot_tax += parseFloat((thisAmount / 100) * item.taxperc);
      //         localStorage.setItem("tot_tax", tot_tax);
      //         if (is_tax == 0) {
      //             tot_amt = total_price + tot_tax;
      //         } else {
      //             tot_amt = total_price;
      //         }

      //         item_amount_total += thisAmount;
      //     });
      //     restaurant_packaging_charge = parseFloat(
      //         (item_amount_total / 100) * packaging_charge
      //     );
      //     var dc = 1;
      //     if (dc == 1) {
      //         var delivery_charge_calc =
      //             this.calc_delivery_charge(tot_amt);
      //     } else {
      //         var delivery_charge_calc = 0;
      //         var tips = 0;
      //     }
      //     var offer_discount = 0;
      //     total_price =
      //         tot_amt === 0
      //             ? tot_amt
      //             : (
      //                   tot_amt +
      //                   restaurant_packaging_charge +
      //                   delivery_charge_calc
      //               ).toFixed(2);
      //     localStorage.setItem("tot_amt", total_price);
      //     // var bill = {'item_total':tot_amt,'packaging_charge':restaurant_packaging_charge,'delivery_charge':delivery_charge_calc,'discount':0,'bill':total_price};
      //     var bill = {
      //         item_total: tot_amt,
      //         packaging_charge: restaurant_packaging_charge,
      //         delivery_charge: delivery_charge_calc,
      //         discount: offer_discount,
      //         bill: total_price,
      //         tips: tips,
      //         dc: dc,
      //         tax: tot_tax,
      //     };
      //     localStorage.setItem("bill", bill);
      //     store.commit("deliware_cart/UPDATE_CART_TOTAL", bill);
      //     this.loadcoupon();
      // }
    },
    calc_delivery_charge: function calc_delivery_charge(total_price) {
      var DC = JSON.parse(localStorage.getItem("DC"));
      if (DC) {
        var delivery_charge = typeof DELIVERY_CHARGE !== "undefined" ? DELIVERY_CHARGE : DC;
      } else {
        var delivery_charge = [];
      }
      var DELIVERY_CHARGE_TYPE = JSON.parse(localStorage.getItem("DELIVERY_CHARGE_TYPE"));
      var delivery_charge_type = parseFloat(typeof DELIVERY_CHARGE_TYPE !== "undefined" ? DELIVERY_CHARGE_TYPE : 0);
      var delivery_charge_calc = 0; // Assigned 0 because if the list is empty loop will not execute.
      var deliver_charge_mode = JSON.parse(localStorage.getItem("DC_BON"));
      var deliver_distance = localStorage.getItem("DISTANCE");
      var total = 0;
      var tot = 0;
      var delivery_charge_calc1 = 0;
      var delivery_charge_calc2 = 0;
      var delivery_charge_calc3 = 0;
      var dc_rate = 0;
      var dc_total = 0;
      var _iterator = Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_createForOfIteratorHelper_js__WEBPACK_IMPORTED_MODULE_0__["default"])(delivery_charge),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var each_delivery_charge = _step.value;
          // Total price should be greater than minimum AND (less than maximum or maximum can be null(infinity))

          //price
          /*if (deliver_charge_mode == 1 &&
          total_price >= each_delivery_charge.Minimum &&
          (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum) && delivery_charge_type == 1 removed this dctype
          ) */
          if (deliver_charge_mode == 1) {
            if (total_price >= each_delivery_charge.Minimum && (each_delivery_charge.Maximum == null || total_price <= each_delivery_charge.Maximum)) {
              delivery_charge_calc = parseFloat(each_delivery_charge.Value);
              delivery_charge_type = each_delivery_charge.Type;
            }
          }
          //km
          if (deliver_charge_mode == 2) {
            //loop deliver charge type

            var dc = deliver_distance;
            //var dc = deliver_distance - each_delivery_charge.Maximum;
            if (dc >= each_delivery_charge.Minimum && (each_delivery_charge.Maximum == null || dc <= each_delivery_charge.Maximum) && each_delivery_charge.Type == 1) {
              var delivery_charge_calc1 = parseFloat(each_delivery_charge.Value);
              delivery_charge_calc3 += delivery_charge_calc1;
              delivery_charge_type = each_delivery_charge.Type;
            }
            for (var i = 1; i <= deliver_distance; i++) {
              if (i <= each_delivery_charge.Maximum && i >= each_delivery_charge.Minimum && each_delivery_charge.Type == 3) {
                var total = each_delivery_charge.Value * 1;
                tot += total;
                var delivery_charge_calc2 = parseFloat(tot);
              } else {
                if (i == each_delivery_charge.Maximum) {
                  var dc_rate = each_delivery_charge.Value;
                }
              }
              var dckm_rate = parseFloat(dc_rate) + parseFloat(delivery_charge_calc2);
            }
            // calculat tot have diff type
            if (dckm_rate) {
              var delivery_charge_calc = parseFloat(delivery_charge_calc3) + parseFloat(dckm_rate);
            } else {
              var delivery_charge_calc = parseFloat(delivery_charge_calc3);
            }
            dc_total += delivery_charge_calc;
          }
        }

        //If calculation type is percentage, then calculate the percentage
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      if (deliver_charge_mode == 1 && delivery_charge_type == 2) {
        delivery_charge_calc = parseFloat(total_price / 100 * delivery_charge_calc);
      }
      delivery_charge_calc = parseFloat(parseFloat(delivery_charge_calc).toFixed(2));
      return delivery_charge_calc;
    },
    addCustomizedProduct: function addCustomizedProduct(selected) {
      localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
      var id = selected.id,
        restaurant = selected.restaurant,
        isveg = selected.isveg,
        restaurant_name = selected.restaurant_name,
        restaurant_address = selected.restaurant_address,
        restaurant_image = selected.restaurant_image,
        taxperc = selected.taxperc,
        name = selected.name,
        food_image = selected.food_image,
        cart = JSON.parse(localStorage.getItem("store_cart"));
      var price = parseFloat(selected.price);
      var selectedSize = undefined;
      var selectedSizeName = undefined;
      var selectedSizePrice = "";
      if (document.querySelector('input[name="sizesOfItem_' + id + '"]:checked')) {
        selectedSize = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').value;
        selectedSizeName = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').getAttribute("data-name");
        selectedSizePrice = document.querySelector('input[name="sizesOfItem_' + id + '"]:checked').getAttribute("data-price");
      }
      selectedSize = selectedSize == undefined ? "" : selectedSize;
      selectedSizePrice = isNaN(selectedSizePrice) ? 0 : selectedSizePrice;
      if (selectedSizePrice > 0) {
        price = selectedSizePrice;
      }
      var selectedAddons = [];
      var selectedAddonsName = [];
      var selectedAddonsPrice = 0;
      document.querySelectorAll('input[name="addonsOfItem_' + id + '"]:checked').forEach(function (index, value) {
        selectedAddons.push(index.value);
        selectedAddonsName.push(index.getAttribute("data-name"));
        selectedAddonsPrice += parseFloat(index.getAttribute("data-price"));
      });
      var selectedAddonsString = selectedAddons.join(",");
      selectedAddonsName = selectedAddonsName.join(", ");
      selectedAddonsPrice = parseFloat(selectedAddonsPrice);
      if (cart.length && cart[0].restaurant != restaurant) {
        var ok = confirm("The items previously will be deleted");
        if (ok) {
          cart = [];
          _store__WEBPACK_IMPORTED_MODULE_20__["default"].commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", _store__WEBPACK_IMPORTED_MODULE_20__["default"].state["deliware_cart"].cartItemsCount - 1);
        } else return;
      }
      if (cart.length != 0) {
        if (typeof selectedSizeName == "undefined" && selectedAddonsName != "") {
          var food_isPresent = cart.some(function (el) {
            return el.id === id;
          });
          var food_addon_isPresent = cart.some(function (el) {
            return el.addonsName === selectedAddonsName;
          });
          if (food_isPresent === true && food_addon_isPresent === true) {
            cart = localStorage.getItem("store_cart");
            cart = JSON.parse(cart);
            for (var i = 0; i < cart.length; i++) {
              if (cart[i].addonsName === selectedAddonsName && cart[i].id === id) {
                cart[i].quantity = cart[i].quantity + 1;
                break;
              }
            }
          } else {
            cart.push({
              id: id,
              name: name,
              isveg: isveg,
              price: parseFloat(parseFloat(price).toFixed(2)),
              quantity: 1,
              restaurant: restaurant,
              addons: selectedAddonsString,
              sizes: selectedSize,
              addonsName: selectedAddonsName,
              addonsPrice: selectedAddonsPrice,
              sizeName: selectedSizeName,
              restaurant_name: restaurant_name,
              restaurant_address: restaurant_address,
              restaurant_image: restaurant_image,
              sizePrice: selectedSizePrice,
              taxperc: taxperc,
              food_image: food_image
            });
          }
        } else {
          if (selectedSizeName != "" && selectedAddonsName != "") {
            var _food_isPresent = cart.some(function (el) {
              return el.id === id;
            });
            var _food_addon_isPresent = cart.some(function (el) {
              return el.addonsName === selectedAddonsName;
            });
            var food_size_isPresent = cart.some(function (el) {
              return el.sizeName === selectedSizeName;
            });
            if (_food_isPresent === true && _food_addon_isPresent === true && food_size_isPresent == true) {
              cart = localStorage.getItem("store_cart");
              cart = JSON.parse(cart);
              for (var i = 0; i < cart.length; i++) {
                var temp_loop_count = i + 1;
                if (cart[i].addonsName === selectedAddonsName && cart[i].sizeName === selectedSizeName && cart[i].id === id) {
                  cart[i].quantity = cart[i].quantity + 1;
                  break;
                }
                if (temp_loop_count == cart.length) {
                  cart.push({
                    id: id,
                    name: name,
                    isveg: isveg,
                    price: parseFloat(parseFloat(price).toFixed(2)),
                    quantity: 1,
                    restaurant: restaurant,
                    addons: selectedAddonsString,
                    sizes: selectedSize,
                    addonsName: selectedAddonsName,
                    addonsPrice: selectedAddonsPrice,
                    sizeName: selectedSizeName,
                    restaurant_name: restaurant_name,
                    restaurant_address: restaurant_address,
                    restaurant_image: restaurant_image,
                    sizePrice: selectedSizePrice,
                    taxperc: taxperc,
                    food_image: food_image
                  });
                  break;
                }
              }
            } else {
              cart.push({
                id: id,
                name: name,
                isveg: isveg,
                price: parseFloat(parseFloat(price).toFixed(2)),
                quantity: 1,
                restaurant: restaurant,
                addons: selectedAddonsString,
                sizes: selectedSize,
                addonsName: selectedAddonsName,
                addonsPrice: selectedAddonsPrice,
                sizeName: selectedSizeName,
                restaurant_name: restaurant_name,
                restaurant_address: restaurant_address,
                restaurant_image: restaurant_image,
                sizePrice: selectedSizePrice,
                taxperc: taxperc,
                food_image: food_image
              });
            }
          }
          if (selectedSizeName != "" && selectedAddonsName === "") {
            var _food_isPresent2 = cart.some(function (el) {
              return el.id === id;
            });
            var _food_size_isPresent = cart.some(function (el) {
              return el.sizeName === selectedSizeName;
            });
            if (_food_isPresent2 === true && _food_size_isPresent == true) {
              cart = localStorage.getItem("store_cart");
              cart = JSON.parse(cart);
              for (var i = 0; i < cart.length; i++) {
                var _temp_loop_count = i + 1;
                if (cart[i].addonsName === selectedAddonsName && cart[i].sizeName === selectedSizeName && cart[i].id === id) {
                  cart[i].quantity = cart[i].quantity + 1;
                  break;
                }
                if (_temp_loop_count == cart.length) {
                  cart.push({
                    id: id,
                    name: name,
                    isveg: isveg,
                    price: parseFloat(parseFloat(price).toFixed(2)),
                    quantity: 1,
                    restaurant: restaurant,
                    addons: selectedAddonsString,
                    sizes: selectedSize,
                    addonsName: selectedAddonsName,
                    addonsPrice: selectedAddonsPrice,
                    sizeName: selectedSizeName,
                    restaurant_name: restaurant_name,
                    restaurant_address: restaurant_address,
                    restaurant_image: restaurant_image,
                    sizePrice: selectedSizePrice,
                    taxperc: taxperc,
                    food_image: food_image
                  });
                  break;
                }
              }
            } else {
              cart.push({
                id: id,
                name: name,
                isveg: isveg,
                price: parseFloat(parseFloat(price).toFixed(2)),
                quantity: 1,
                restaurant: restaurant,
                addons: selectedAddonsString,
                sizes: selectedSize,
                addonsName: selectedAddonsName,
                addonsPrice: selectedAddonsPrice,
                sizeName: selectedSizeName,
                restaurant_name: restaurant_name,
                restaurant_address: restaurant_address,
                restaurant_image: restaurant_image,
                sizePrice: selectedSizePrice,
                taxperc: taxperc,
                food_image: food_image
              });
            }
          }
        }
      } else {
        cart.push({
          id: id,
          name: name,
          isveg: isveg,
          price: parseFloat(parseFloat(price).toFixed(2)),
          quantity: 1,
          restaurant: restaurant,
          addons: selectedAddonsString,
          sizes: selectedSize,
          addonsName: selectedAddonsName,
          addonsPrice: selectedAddonsPrice,
          sizeName: selectedSizeName,
          restaurant_name: restaurant_name,
          restaurant_address: restaurant_address,
          restaurant_image: restaurant_image,
          sizePrice: selectedSizePrice,
          taxperc: taxperc,
          food_image: food_image
        });
      }
      localStorage.setItem("store_cart", JSON.stringify(cart));
      var cartn = JSON.parse(localStorage.getItem("store_cart"));
      _store__WEBPACK_IMPORTED_MODULE_20__["default"].commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", cartn.length);
      _store__WEBPACK_IMPORTED_MODULE_20__["default"].commit("deliware_cart/UPDATE_CART_ITEMS", cartn);
      this.$refs["addon-modal"].hide();
      this.loadcart();
    },
    removefromcart: function removefromcart(price, item_tax, name, food_id, is_veg, add_ons, food_quantity, food_image) {
      localStorage.setItem("store_cart", localStorage.getItem("store_cart") || "[]");
      this.itemid = food_id;
      var id = food_id,
        cart = JSON.parse(localStorage.getItem("store_cart"));
      price = parseFloat(price);
      var foodIndex = cart.findIndex(function (food, index) {
        return food.id == id;
      });
      if (foodIndex !== -1) {
        if (cart[foodIndex].quantity > 0) cart[foodIndex].quantity--;
        if (cart[foodIndex].quantity <= 0) {
          cart.splice(foodIndex, 1);
          var elms = document.querySelectorAll('[id="food_control_' + id + 'r"]');
          for (var i = 0; i < elms.length; i++) {
            elms[i].style.display = "none";
          }
          var elms2 = document.querySelectorAll('[id="food_add_' + id + 'r"]');
          for (var i = 0; i < elms2.length; i++) {
            elms2[i].style.display = "inline-block";
          }

          // document.getElementById('food_control_' + id +'r').style.display = 'none';
          // document.getElementById('food_add_' + id +'r').style.display = 'inline-block';
        }
      }
      localStorage.setItem("store_cart", JSON.stringify(cart));
      var cartn = JSON.parse(localStorage.getItem("store_cart"));
      _store__WEBPACK_IMPORTED_MODULE_20__["default"].commit("deliware_cart/UPDATE_CART_ITEMS_COUNT", cartn.length);
      _store__WEBPACK_IMPORTED_MODULE_20__["default"].commit("deliware_cart/UPDATE_CART_ITEMS", cartn);
      this.loadcart();
    }
  },
  created: function created() {
    var _this8 = this;
    this.$store.commit("verticalMenu/UPDATE_VERTICAL_MENU_COLLAPSED", true);
    this.$http.get("/store/get_currency").then(function (res) {
      _this8.setting = res.data;
    });
    localStorage.removeItem("store_cart");
    this.$http.get('/store/single_restaurant/' + this.rest_id).then(function (res) {
      _this8.rows = res.data.restaurants;
      if (_this8.rows[0].is_open != 1) {
        _this8.isopen = false;
      }
      _this8.promocodes = res.data.promocodes;
      console.log(_this8.rows);
    });
    this.$http.get("/store/get_food_list_by_menu_pos/" + this.rest_id).then(function (res) {
      _this8.rows_detail = res.data.items;
      _this8.business_type = res.data.business_type;
      _this8.rows_detail2 = res.data.items;
      _this8.isLoading = false;
    });
  },
  destroyed: function destroyed() {
    this.$store.commit("verticalMenu/UPDATE_VERTICAL_MENU_COLLAPSED", false);
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".toastification-close-icon[data-v-7834b2fa],\n.toastification-title[data-v-7834b2fa] {\n  line-height: 26px;\n}\n.toastification-title[data-v-7834b2fa] {\n  color: inherit;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".autosuggest__results-container {\n  position: relative;\n  z-index: 999;\n}\n.autosuggest__results-container .autosuggest__results {\n  position: absolute;\n  width: 100%;\n  overflow-y: auto;\n  max-height: 40vh;\n}\n[dir] .autosuggest__results-container .autosuggest__results {\n  background-color: #fff;\n  margin-top: 1rem;\n  border-radius: 0.5rem;\n  box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.11), 0 5px 15px 0 rgba(0, 0, 0, 0.08) !important;\n}\n[dir] .autosuggest__results-container .autosuggest__results ul {\n  margin-bottom: 0;\n}\n[dir=ltr] .autosuggest__results-container .autosuggest__results ul {\n  padding-left: 0;\n}\n[dir=rtl] .autosuggest__results-container .autosuggest__results ul {\n  padding-right: 0;\n}\n.autosuggest__results-container .autosuggest__results ul li {\n  list-style: none;\n}\n[dir] .autosuggest__results-container .autosuggest__results ul li {\n  padding: 0.75rem 1rem;\n}\n[dir] .autosuggest__results-container .autosuggest__results ul li:hover, [dir] .autosuggest__results-container .autosuggest__results ul li.autosuggest__results-item--highlighted {\n  background: #ededed;\n}\n.autosuggest__results-container .autosuggest__results ul li .detail {\n  line-height: 0.5;\n}\n.autosuggest__results-container .autosuggest__results ul .autosuggest__results-before {\n  color: #E01764;\n  font-weight: 600;\n}\nbody.dark-layout .autosuggest__results-container .autosuggest__results {\n  color: #b4b7bd;\n}\n[dir] body.dark-layout .autosuggest__results-container .autosuggest__results {\n  background-color: #283046;\n}\n[dir] body.dark-layout .autosuggest__results-container .autosuggest__results .autosuggest__results-item--highlighted, [dir] body.dark-layout .autosuggest__results-container .autosuggest__results .autosuggest__results-item:hover, [dir] body.dark-layout .autosuggest__results-container .autosuggest__results .autosuggest__results-before:hover {\n  background-color: #242b3d;\n}\nbody.dark-layout .autosuggest__results-container .autosuggest__results .my-suggestion-item {\n  color: #b4b7bd;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "[dir] .faq-search {\n  background-size: cover;\n  background-color: rgba(224, 23, 100, 0.12) !important;\n}\n[dir] .faq-search .faq-search-input .input-group:focus-within {\n  box-shadow: none;\n}\n[dir] .faq-contact .faq-contact-card {\n  background-color: rgba(186, 191, 199, 0.12);\n}\n@media (min-width: 992px) {\n[dir] .faq-search .card-body {\n    padding: 8rem !important;\n}\n}\n@media (min-width: 768px) and (max-width: 991.98px) {\n[dir] .faq-search .card-body {\n    padding: 6rem !important;\n}\n}\n@media (min-width: 768px) {\n.faq-search .faq-search-input .input-group {\n    width: 576px;\n}\n[dir] .faq-search .faq-search-input .input-group {\n    margin: 0 auto;\n}\n.faq-navigation {\n    height: 550px;\n}\n}\n.faq-navigation .nav.nav-pills {\n  height: 100%;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "/* Set the theme color of the component */\n/* import theme style */\n/* component style */\n.vue-slider-disabled {\n  opacity: 0.5;\n}\n[dir] .vue-slider-disabled {\n  cursor: not-allowed;\n}\n\n/* rail style */\n[dir] .vue-slider-rail {\n  background-color: rgba(224, 23, 100, 0.12);\n  border-radius: 15px;\n}\n\n/* process style */\n[dir] .vue-slider-process {\n  background-color: #E01764;\n  border-radius: 15px;\n}\n\n/* mark style */\n.vue-slider-mark {\n  z-index: 4;\n}\n.vue-slider-mark:first-child .vue-slider-mark-step, .vue-slider-mark:last-child .vue-slider-mark-step {\n  display: none;\n}\n.vue-slider-mark-step {\n  width: 100%;\n  height: 100%;\n}\n[dir] .vue-slider-mark-step {\n  border-radius: 50%;\n  background-color: rgba(0, 0, 0, 0.16);\n}\n.vue-slider-mark-label {\n  font-size: 14px;\n  white-space: nowrap;\n}\n\n/* dot style */\n.vue-slider-dot-handle {\n  width: 100%;\n  height: 100%;\n  box-sizing: border-box;\n}\n[dir] .vue-slider-dot-handle {\n  cursor: pointer;\n  border-radius: 50%;\n  background-color: #fff;\n  box-shadow: none;\n}\n[dir] .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(224, 23, 100, 0.36);\n}\n[dir] .vue-slider-dot-handle-disabled {\n  cursor: not-allowed;\n  background-color: #ccc;\n}\n.vue-slider-dot-tooltip-inner {\n  font-size: 14px;\n  white-space: nowrap;\n  min-width: 20px;\n  color: #fff;\n  box-sizing: content-box;\n}\n[dir] .vue-slider-dot-tooltip-inner {\n  padding: 2px 5px;\n  text-align: center;\n  border-radius: 5px;\n  border-color: #E01764;\n  background-color: #E01764;\n}\n.vue-slider-dot-tooltip-inner::after {\n  content: \"\";\n  position: absolute;\n}\n.vue-slider-dot-tooltip-inner-top::after {\n  top: 100%;\n  height: 0;\n  width: 0;\n}\n[dir] .vue-slider-dot-tooltip-inner-top::after {\n  border-color: transparent;\n  border-style: solid;\n  border-width: 5px;\n  border-top-color: inherit;\n}\n[dir=ltr] .vue-slider-dot-tooltip-inner-top::after {\n  left: 50%;\n  transform: translate(-50%, 0);\n}\n[dir=rtl] .vue-slider-dot-tooltip-inner-top::after {\n  right: 50%;\n  transform: translate(50%, 0);\n}\n.vue-slider-dot-tooltip-inner-bottom::after {\n  bottom: 100%;\n  height: 0;\n  width: 0;\n}\n[dir] .vue-slider-dot-tooltip-inner-bottom::after {\n  border-color: transparent;\n  border-style: solid;\n  border-width: 5px;\n  border-bottom-color: inherit;\n}\n[dir=ltr] .vue-slider-dot-tooltip-inner-bottom::after {\n  left: 50%;\n  transform: translate(-50%, 0);\n}\n[dir=rtl] .vue-slider-dot-tooltip-inner-bottom::after {\n  right: 50%;\n  transform: translate(50%, 0);\n}\n.vue-slider-dot-tooltip-inner-left::after {\n  top: 50%;\n  height: 0;\n  width: 0;\n}\n[dir] .vue-slider-dot-tooltip-inner-left::after {\n  transform: translate(0, -50%);\n  border-color: transparent;\n  border-style: solid;\n  border-width: 5px;\n}\n[dir=ltr] .vue-slider-dot-tooltip-inner-left::after {\n  left: 100%;\n  border-left-color: inherit;\n}\n[dir=rtl] .vue-slider-dot-tooltip-inner-left::after {\n  right: 100%;\n  border-right-color: inherit;\n}\n.vue-slider-dot-tooltip-inner-right::after {\n  top: 50%;\n  height: 0;\n  width: 0;\n}\n[dir] .vue-slider-dot-tooltip-inner-right::after {\n  transform: translate(0, -50%);\n  border-color: transparent;\n  border-style: solid;\n  border-width: 5px;\n}\n[dir=ltr] .vue-slider-dot-tooltip-inner-right::after {\n  right: 100%;\n  border-right-color: inherit;\n}\n[dir=rtl] .vue-slider-dot-tooltip-inner-right::after {\n  left: 100%;\n  border-left-color: inherit;\n}\n.vue-slider-dot-tooltip-wrapper {\n  opacity: 0;\n  transition: all 0.3s;\n}\n.vue-slider-dot-tooltip-wrapper-show {\n  opacity: 1;\n}\n[dir] .vue-slider-dot-handle {\n  border: 2px solid #E01764;\n}\n[dir] .vue-slider-dot-tooltip-inner {\n  background-color: #E01764;\n  border-color: #E01764;\n}\n\n/* Generate:\n*  Border color according to event class\n*/\n[dir] .vue-slider-white .vue-slider-dot-handle {\n  border: 2px solid #ffffff;\n}\n[dir] .vue-slider-white .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(255, 255, 255, 0.36);\n}\n[dir] .vue-slider-white .vue-slider-process {\n  background-color: #ffffff;\n}\n[dir] .vue-slider-white .vue-slider-rail {\n  background-color: rgba(255, 255, 255, 0.12);\n}\n[dir] .vue-slider-white .vue-slider-dot-tooltip-inner {\n  background-color: #ffffff;\n  border-color: #ffffff;\n}\n[dir] .vue-slider-black .vue-slider-dot-handle {\n  border: 2px solid #000000;\n}\n[dir] .vue-slider-black .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(0, 0, 0, 0.36);\n}\n[dir] .vue-slider-black .vue-slider-process {\n  background-color: #000000;\n}\n[dir] .vue-slider-black .vue-slider-rail {\n  background-color: rgba(0, 0, 0, 0.12);\n}\n[dir] .vue-slider-black .vue-slider-dot-tooltip-inner {\n  background-color: #000000;\n  border-color: #000000;\n}\n[dir] .vue-slider-dark .vue-slider-dot-handle {\n  border: 2px solid #4b4b4b;\n}\n[dir] .vue-slider-dark .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(75, 75, 75, 0.36);\n}\n[dir] .vue-slider-dark .vue-slider-process {\n  background-color: #4b4b4b;\n}\n[dir] .vue-slider-dark .vue-slider-rail {\n  background-color: rgba(75, 75, 75, 0.12);\n}\n[dir] .vue-slider-dark .vue-slider-dot-tooltip-inner {\n  background-color: #4b4b4b;\n  border-color: #4b4b4b;\n}\n[dir] .vue-slider-light .vue-slider-dot-handle {\n  border: 2px solid #f6f6f6;\n}\n[dir] .vue-slider-light .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(246, 246, 246, 0.36);\n}\n[dir] .vue-slider-light .vue-slider-process {\n  background-color: #f6f6f6;\n}\n[dir] .vue-slider-light .vue-slider-rail {\n  background-color: rgba(246, 246, 246, 0.12);\n}\n[dir] .vue-slider-light .vue-slider-dot-tooltip-inner {\n  background-color: #f6f6f6;\n  border-color: #f6f6f6;\n}\n[dir] .vue-slider-primary .vue-slider-dot-handle {\n  border: 2px solid #E01764;\n}\n[dir] .vue-slider-primary .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(224, 23, 100, 0.36);\n}\n[dir] .vue-slider-primary .vue-slider-process {\n  background-color: #E01764;\n}\n[dir] .vue-slider-primary .vue-slider-rail {\n  background-color: rgba(224, 23, 100, 0.12);\n}\n[dir] .vue-slider-primary .vue-slider-dot-tooltip-inner {\n  background-color: #E01764;\n  border-color: #E01764;\n}\n[dir] .vue-slider-secondary .vue-slider-dot-handle {\n  border: 2px solid #FFCD04;\n}\n[dir] .vue-slider-secondary .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(255, 205, 4, 0.36);\n}\n[dir] .vue-slider-secondary .vue-slider-process {\n  background-color: #FFCD04;\n}\n[dir] .vue-slider-secondary .vue-slider-rail {\n  background-color: rgba(255, 205, 4, 0.12);\n}\n[dir] .vue-slider-secondary .vue-slider-dot-tooltip-inner {\n  background-color: #FFCD04;\n  border-color: #FFCD04;\n}\n[dir] .vue-slider-success .vue-slider-dot-handle {\n  border: 2px solid #28c76f;\n}\n[dir] .vue-slider-success .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(40, 199, 111, 0.36);\n}\n[dir] .vue-slider-success .vue-slider-process {\n  background-color: #28c76f;\n}\n[dir] .vue-slider-success .vue-slider-rail {\n  background-color: rgba(40, 199, 111, 0.12);\n}\n[dir] .vue-slider-success .vue-slider-dot-tooltip-inner {\n  background-color: #28c76f;\n  border-color: #28c76f;\n}\n[dir] .vue-slider-info .vue-slider-dot-handle {\n  border: 2px solid #00cfe8;\n}\n[dir] .vue-slider-info .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(0, 207, 232, 0.36);\n}\n[dir] .vue-slider-info .vue-slider-process {\n  background-color: #00cfe8;\n}\n[dir] .vue-slider-info .vue-slider-rail {\n  background-color: rgba(0, 207, 232, 0.12);\n}\n[dir] .vue-slider-info .vue-slider-dot-tooltip-inner {\n  background-color: #00cfe8;\n  border-color: #00cfe8;\n}\n[dir] .vue-slider-warning .vue-slider-dot-handle {\n  border: 2px solid #ff9f43;\n}\n[dir] .vue-slider-warning .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(255, 159, 67, 0.36);\n}\n[dir] .vue-slider-warning .vue-slider-process {\n  background-color: #ff9f43;\n}\n[dir] .vue-slider-warning .vue-slider-rail {\n  background-color: rgba(255, 159, 67, 0.12);\n}\n[dir] .vue-slider-warning .vue-slider-dot-tooltip-inner {\n  background-color: #ff9f43;\n  border-color: #ff9f43;\n}\n[dir] .vue-slider-danger .vue-slider-dot-handle {\n  border: 2px solid #ea5455;\n}\n[dir] .vue-slider-danger .vue-slider-dot-handle-focus {\n  box-shadow: 0px 0px 1px 2px rgba(234, 84, 85, 0.36);\n}\n[dir] .vue-slider-danger .vue-slider-process {\n  background-color: #ea5455;\n}\n[dir] .vue-slider-danger .vue-slider-rail {\n  background-color: rgba(234, 84, 85, 0.12);\n}\n[dir] .vue-slider-danger .vue-slider-dot-tooltip-inner {\n  background-color: #ea5455;\n  border-color: #ea5455;\n}\n[dir] body.dark-layout .vue-slider-dot-handle {\n  background-color: #161d31;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".swiper-slide[data-v-2de8c2a0] {\n  width: 20%;\n  height: 100%;\n}\n[dir] .rounded3[data-v-2de8c2a0] {\n  cursor: pointer;\n}\n[dir] .categories-radio-group[data-v-2de8c2a0]  > .custom-control, [dir] .brands-radio-group[data-v-2de8c2a0]  > .custom-control, [dir] .price-range-defined-radio-group[data-v-2de8c2a0]  > .custom-control {\n  margin-bottom: 0.75rem;\n}\n.count-number-input[data-v-2de8c2a0] {\n  width: 40px;\n  color: #282828;\n  height: 24px;\n  vertical-align: bottom;\n}\n[dir] .count-number-input[data-v-2de8c2a0] {\n  text-align: center;\n  margin: 0 -4px;\n  background: #f4fffd;\n  border: 1px solid #82868b !important;\n  border-radius: 0px;\n}\n.b-sidebar[data-v-2de8c2a0] {\n  min-width: 420px !important;\n}\n[dir] .btn-secondary[data-v-2de8c2a0] {\n  border-color: #fff !important;\n  background-color: transparent !important;\n}\n[dir] .cart-bord[data-v-2de8c2a0] {\n  border-top: 1px solid #e6e6e6;\n  padding: 20px 0px;\n  border-bottom: 1px solid #e6e6e6;\n}\n[dir] .sidebar-shop[data-v-2de8c2a0] {\n  cursor: pointer;\n}\n[dir] .bor[data-v-2de8c2a0] {\n  border-radius: 10px;\n}\n.side[data-v-2de8c2a0] {\n  line-height: 3;\n}\n[dir] .side[data-v-2de8c2a0] {\n  cursor: pointer;\n}\n[dir=ltr] .side[data-v-2de8c2a0] {\n  padding: 0px 12px 0px 0px;\n}\n[dir=rtl] .side[data-v-2de8c2a0] {\n  padding: 0px 0px 0px 12px;\n}\n.s-border[data-v-2de8c2a0] {\n  min-height: 400px;\n}\n[dir=ltr] .s-border[data-v-2de8c2a0] {\n  border-right: 2px solid #e6e6e6;\n}\n[dir=rtl] .s-border[data-v-2de8c2a0] {\n  border-left: 2px solid #e6e6e6;\n}\n.active[data-v-2de8c2a0] {\n  font-weight: bold;\n}\n[dir] .active[data-v-2de8c2a0] {\n  background-color: #f4fffd;\n}\n[dir=ltr] .active[data-v-2de8c2a0] {\n  border-right: 4px solid rgba(0, 160, 130, 0.7);\n  padding: 0px 30px 0px 0px;\n}\n[dir=rtl] .active[data-v-2de8c2a0] {\n  border-left: 4px solid rgba(0, 160, 130, 0.7);\n  padding: 0px 0px 0px 30px;\n}\n.restaurant-detailed-header[data-v-2de8c2a0] {\n  bottom: 0;\n  position: absolute;\n}\n[dir] .restaurant-detailed-header[data-v-2de8c2a0] {\n  padding: 65px 0 17px;\n  margin-bottom: 40px;\n}\n[dir=ltr] .restaurant-detailed-header[data-v-2de8c2a0] {\n  left: 0;\n  right: 0;\n}\n[dir=rtl] .restaurant-detailed-header[data-v-2de8c2a0] {\n  right: 0;\n  left: 0;\n}\n[dir] .bg-color[data-v-2de8c2a0] {\n  background-color: #000 !important;\n}\n.overlay[data-v-2de8c2a0] {\n  /* position: absolute; */\n  opacity: 0.5;\n}\n[dir] .overlay[data-v-2de8c2a0] {\n  background-color: #000;\n}\n.mborderw[data-v-2de8c2a0] {\n  font-family: sans-serif;\n  color: #fff;\n}\n[dir] .mborderw[data-v-2de8c2a0] {\n  padding: 10px;\n  border-radius: 30px !important;\n  border: 2px solid #fff;\n  margin: 0px 5px;\n  text-align: center;\n}\n.mborderw2[data-v-2de8c2a0] {\n  width: 40px;\n  font-family: sans-serif;\n  color: #fff;\n}\n[dir] .mborderw2[data-v-2de8c2a0] {\n  padding: 10px;\n  border-radius: 30px !important;\n  border: 2px solid #fff;\n  margin: 0px 5px;\n  text-align: center;\n}\n[dir] .navbar-sticky .app-content[data-v-2de8c2a0] {\n  padding: 0px;\n}\n[dir] .pad0 .card-body[data-v-2de8c2a0] {\n  padding: 0rem !important;\n}\n.sticky-top3[data-v-2de8c2a0] {\n  position: -webkit-sticky;\n  position: sticky;\n  z-index: 0;\n  top: 100px;\n  overflow-y: scroll;\n  /*max-height: 600px;\n  margin-bottom: 50px;*/\n}\n.textp[data-v-2de8c2a0] {\n  overflow: hidden;\n  text-overflow: ellipsis;\n  display: -webkit-box;\n  -webkit-line-clamp: 2;\n  /* number of lines to show */\n  line-clamp: 2;\n  -webkit-box-orient: vertical;\n  min-height: 36px;\n}\n.textp2[data-v-2de8c2a0] {\n  color: #e6e6e6;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  display: -webkit-box;\n  -webkit-line-clamp: 2;\n  /* number of lines to show */\n  line-clamp: 2;\n  -webkit-box-orient: vertical;\n}\n[dir] .btn-icon[data-v-2de8c2a0] {\n  padding: 5px;\n}\n.btn-scroll-to-top[data-v-2de8c2a0] {\n  color: #000;\n  display: inline-block;\n}\n[dir] .btn-scroll-to-top[data-v-2de8c2a0] {\n  background-color: #ffbd00 !important;\n  border-radius: 100px;\n  border: 1px solid #ffbd00 !important;\n  padding: 20px;\n}\n.swiper-button-prev[data-v-2de8c2a0],\n.swiper-button-next[data-v-2de8c2a0] {\n  color: #00a082;\n}\n.skin-2 .num-in[data-v-2de8c2a0] {\n  height: 30px;\n  width: 80px;\n}\n[dir] .skin-2 .num-in[data-v-2de8c2a0] {\n  background: #ffffff;\n  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.15);\n  border-radius: 25px;\n}\n[dir=ltr] .skin-2 .num-in[data-v-2de8c2a0] {\n  float: left;\n}\n[dir=rtl] .skin-2 .num-in[data-v-2de8c2a0] {\n  float: right;\n}\n.skin-2 .num-in span[data-v-2de8c2a0] {\n  width: 40%;\n  display: block;\n  height: 30px;\n  position: relative;\n}\n[dir=ltr] .skin-2 .num-in span[data-v-2de8c2a0] {\n  float: left;\n}\n[dir=rtl] .skin-2 .num-in span[data-v-2de8c2a0] {\n  float: right;\n}\n.skin-2 .num-in span[data-v-2de8c2a0]:before,\n.skin-2 .num-in span[data-v-2de8c2a0]:after {\n  content: \"\";\n  position: absolute;\n  height: 2px;\n  width: 10px;\n  top: 50%;\n}\n[dir] .skin-2 .num-in span[data-v-2de8c2a0]:before, [dir] .skin-2 .num-in span[data-v-2de8c2a0]:after {\n  background-color: #667780;\n  margin-top: -1px;\n}\n[dir=ltr] .skin-2 .num-in span[data-v-2de8c2a0]:before, [dir=ltr] .skin-2 .num-in span[data-v-2de8c2a0]:after {\n  left: 50%;\n  margin-left: -5px;\n}\n[dir=rtl] .skin-2 .num-in span[data-v-2de8c2a0]:before, [dir=rtl] .skin-2 .num-in span[data-v-2de8c2a0]:after {\n  right: 50%;\n  margin-right: -5px;\n}\n[dir=ltr] .skin-2 .num-in span.plus[data-v-2de8c2a0]:after {\n  transform: rotate(90deg);\n}\n[dir=rtl] .skin-2 .num-in span.plus[data-v-2de8c2a0]:after {\n  transform: rotate(-90deg);\n}\n.skin-2 .num-in input[data-v-2de8c2a0] {\n  width: 20%;\n  height: 30px;\n}\n[dir] .skin-2 .num-in input[data-v-2de8c2a0] {\n  border: none;\n  text-align: center;\n}\n[dir=ltr] .skin-2 .num-in input[data-v-2de8c2a0] {\n  float: left;\n}\n[dir=rtl] .skin-2 .num-in input[data-v-2de8c2a0] {\n  float: right;\n}\n[dir=ltr] .pad0[data-v-2de8c2a0] {\n  padding-left: 0px;\n  padding-right: 0px;\n}\n[dir=rtl] .pad0[data-v-2de8c2a0] {\n  padding-right: 0px;\n  padding-left: 0px;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.nav-link[data-v-2de8c2a0] {\n    color: black;\n}\n.text-yellow[data-v-2de8c2a0] {\n    color: #ffbd00;\n}\n.text-white[data-v-2de8c2a0] {\n    color: white;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.swiper-button-next[data-v-2de8c2a0],\n.swiper-container-rtl .swiper-button-prev[data-v-2de8c2a0] {\n    /* top: -30px; \n    margin-top: -68px;*/\n    /* z-index: 999999; */\n    top: 45%;\n}\n[dir] .swiper-button-next[data-v-2de8c2a0], [dir] .swiper-container-rtl .swiper-button-prev[data-v-2de8c2a0] {\n    background-color: #f4fffd90;\n    border-radius: 10px;\n    border: 2px solid #00a082;\n}\n[dir=ltr] .swiper-button-next[data-v-2de8c2a0], [dir=ltr] .swiper-container-rtl .swiper-button-prev[data-v-2de8c2a0] {\n    right: 10px;\n    left: auto;\n}\n[dir=rtl] .swiper-button-next[data-v-2de8c2a0], [dir=rtl] .swiper-container-rtl .swiper-button-prev[data-v-2de8c2a0] {\n    left: 10px;\n    right: auto;\n}\n.swiper-button-prev[data-v-2de8c2a0],\n.swiper-container-rtl .swiper-button-next[data-v-2de8c2a0] {\n    top: 45%;\n}\n[dir] .swiper-button-prev[data-v-2de8c2a0], [dir] .swiper-container-rtl .swiper-button-next[data-v-2de8c2a0] {\n    background-color: #f4fffd90;\n    border-radius: 10px;\n    border: 2px solid #00a082;\n}\n[dir=ltr] .swiper-button-prev[data-v-2de8c2a0], [dir=ltr] .swiper-container-rtl .swiper-button-next[data-v-2de8c2a0] {\n    left: 10px;\n    right: auto;\n}\n[dir=rtl] .swiper-button-prev[data-v-2de8c2a0], [dir=rtl] .swiper-container-rtl .swiper-button-next[data-v-2de8c2a0] {\n    right: 10px;\n    left: auto;\n}\n[dir] .tipsactive1[data-v-2de8c2a0]{\n  border-color: #ffbd00 !important;\n  background-color: #ffbd00 !important;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true":
/*!************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true ***!
  \************************************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "toastification" }, [
    _c(
      "div",
      { staticClass: "d-flex align-items-start" },
      [
        _c(
          "b-avatar",
          {
            staticClass: "mr-75 flex-shrink-0",
            attrs: { variant: _vm.variant, size: "1.8rem" },
          },
          [_c("feather-icon", { attrs: { icon: _vm.icon, size: "15" } })],
          1
        ),
        _vm._v(" "),
        _c("div", { staticClass: "d-flex flex-grow-1" }, [
          _c("div", [
            _vm.title
              ? _c("h5", {
                  staticClass: "mb-0 font-weight-bolder toastification-title",
                  class: "text-" + _vm.variant,
                  domProps: { textContent: _vm._s(_vm.title) },
                })
              : _vm._e(),
            _vm._v(" "),
            _vm.text
              ? _c("small", {
                  staticClass: "d-inline-block text-body",
                  domProps: { textContent: _vm._s(_vm.text) },
                })
              : _vm._e(),
          ]),
          _vm._v(" "),
          _c(
            "span",
            {
              staticClass: "cursor-pointer toastification-close-icon ml-auto ",
              on: {
                click: function ($event) {
                  return _vm.$emit("close-toast")
                },
              },
            },
            [
              !_vm.hideClose
                ? _c("feather-icon", {
                    staticClass: "text-body",
                    attrs: { icon: "XIcon" },
                  })
                : _vm._e(),
            ],
            1
          ),
        ]),
      ],
      1
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=template&id=2de8c2a0&scoped=true":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=template&id=2de8c2a0&scoped=true ***!
  \********************************************************************************************************************************************************************************************************************************************/
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
    "div",
    [
      _c(
        "b-row",
        [
          _c("b-col", { attrs: { lg: "2", md: "2", cols: "12" } }, [
            _c(
              "div",
              { staticClass: "text-r side sticky-top3 dsp" },
              [
                _c(
                  "div",
                  {
                    attrs: { id: "0" },
                    on: {
                      click: function ($event) {
                        return _vm.scrollMeTo(0, 0)
                      },
                    },
                  },
                  [_vm._v("All")]
                ),
                _vm._v(" "),
                _vm._l(_vm.rows_detail2, function (data2, index) {
                  return _c(
                    "div",
                    {
                      key: index,
                      attrs: { id: data2.menu_name },
                      on: {
                        click: function ($event) {
                          return _vm.scrollMeTo(data2.menu_id, _vm.rows[0].id)
                        },
                      },
                    },
                    [
                      _vm._v(
                        "\n                    " +
                          _vm._s(data2.menu_name) +
                          "\n                "
                      ),
                    ]
                  )
                }),
              ],
              2
            ),
          ]),
          _vm._v(" "),
          _c(
            "b-col",
            { attrs: { lg: "6", md: "6", cols: "12" } },
            [
              _c("vue-autosuggest", {
                attrs: {
                  suggestions: _vm.suggestions,
                  "input-props": _vm.inputProps,
                  "section-configs": _vm.sectionConfigs,
                  "render-suggestion": _vm.renderSuggestion,
                  limit: 10,
                },
                on: { input: _vm.fetchResults },
                model: {
                  value: _vm.query,
                  callback: function ($$v) {
                    _vm.query = $$v
                  },
                  expression: "query",
                },
              }),
              _vm._v(" "),
              _vm.isLoading
                ? _c(
                    "div",
                    { staticClass: "ecommerce-searchbar mt-1" },
                    [
                      _c(
                        "b-row",
                        [
                          _c(
                            "b-col",
                            { attrs: { cols: "2" } },
                            [
                              _c("b-img", {
                                attrs: {
                                  src: "/loader.svg",
                                  fluid: "",
                                  alt: "Scan",
                                },
                              }),
                            ],
                            1
                          ),
                        ],
                        1
                      ),
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              !_vm.isLoading
                ? _c(
                    "div",
                    { staticClass: "ecommerce-searchbar mt-1" },
                    [
                      _c(
                        "b-row",
                        _vm._l(_vm.rows_detail, function (data2, index2) {
                          return _c(
                            "b-col",
                            {
                              key: index2,
                              attrs: { cols: "12", id: "products" },
                            },
                            [
                              _c("h3", [_vm._v(_vm._s(data2.menu_name))]),
                              _vm._v(" "),
                              _c(
                                "div",
                                { attrs: { id: data2.menu_name } },
                                [
                                  _c(
                                    "b-row",
                                    _vm._l(
                                      data2.items,
                                      function (data3, index3) {
                                        return _c(
                                          "b-col",
                                          {
                                            key: index3,
                                            staticClass: "col-xxl-4",
                                            attrs: {
                                              xxl: "4",
                                              lg: "6",
                                              md: "12",
                                              cols: "12",
                                            },
                                          },
                                          [
                                            _c(
                                              "b-card",
                                              { staticClass: "rounded3 " },
                                              [
                                                _c(
                                                  "b-row",
                                                  [
                                                    _c(
                                                      "b-col",
                                                      {
                                                        attrs: {
                                                          cols: "5",
                                                          md: "4",
                                                          lg: "5",
                                                          xxl: "3",
                                                        },
                                                      },
                                                      [
                                                        _c("b-img", {
                                                          staticClass: "bor",
                                                          attrs: {
                                                            src: data3.image,
                                                            "blank-color":
                                                              "#ccc",
                                                            width: "80",
                                                          },
                                                          on: {
                                                            click: function (
                                                              $event
                                                            ) {
                                                              return _vm.open_image(
                                                                data3.image,
                                                                data3.name
                                                              )
                                                            },
                                                          },
                                                        }),
                                                      ],
                                                      1
                                                    ),
                                                    _vm._v(" "),
                                                    _c(
                                                      "b-col",
                                                      {
                                                        attrs: {
                                                          cols: "7",
                                                          md: "8",
                                                          lg: "7",
                                                          xxl: "9",
                                                        },
                                                      },
                                                      [
                                                        _c(
                                                          "h5",
                                                          {
                                                            staticClass:
                                                              "media-heading",
                                                            staticStyle: {
                                                              height: "40px",
                                                            },
                                                          },
                                                          [
                                                            data3.is_veg
                                                              ? _c(
                                                                  "div",
                                                                  [
                                                                    _vm._v(
                                                                      "\n                                                    " +
                                                                        _vm._s(
                                                                          data3.name
                                                                        ) +
                                                                        "\n                                                    "
                                                                    ),
                                                                    _c(
                                                                      "b-img",
                                                                      {
                                                                        staticStyle:
                                                                          {
                                                                            width:
                                                                              "12px",
                                                                          },
                                                                        attrs: {
                                                                          src: "/veg.svg",
                                                                          fluid:
                                                                            "",
                                                                          alt: "Scan",
                                                                        },
                                                                      }
                                                                    ),
                                                                  ],
                                                                  1
                                                                )
                                                              : _c(
                                                                  "div",
                                                                  [
                                                                    _vm._v(
                                                                      "\n                                                    " +
                                                                        _vm._s(
                                                                          data3.name
                                                                        ) +
                                                                        "\n                                                    "
                                                                    ),
                                                                    _c(
                                                                      "b-img",
                                                                      {
                                                                        staticStyle:
                                                                          {
                                                                            width:
                                                                              "12px",
                                                                          },
                                                                        attrs: {
                                                                          src: "/non_veg.svg",
                                                                          fluid:
                                                                            "",
                                                                          alt: "Scan",
                                                                        },
                                                                      }
                                                                    ),
                                                                  ],
                                                                  1
                                                                ),
                                                          ]
                                                        ),
                                                        _vm._v(" "),
                                                        _vm.isopen
                                                          ? _c(
                                                              "b-col",
                                                              {
                                                                staticClass:
                                                                  "text-r pad0",
                                                                attrs: {
                                                                  cols: "12",
                                                                },
                                                              },
                                                              [
                                                                _c(
                                                                  "h4",
                                                                  {
                                                                    staticClass:
                                                                      "media-heading text-primary",
                                                                  },
                                                                  [
                                                                    _vm._v(
                                                                      _vm._s(
                                                                        _vm
                                                                          .setting
                                                                          .value
                                                                      ) +
                                                                        " " +
                                                                        _vm._s(
                                                                          data3.price
                                                                        )
                                                                    ),
                                                                  ]
                                                                ),
                                                                _vm._v(" "),
                                                                _c(
                                                                  "b-button",
                                                                  {
                                                                    directives:
                                                                      [
                                                                        {
                                                                          name: "ripple",
                                                                          rawName:
                                                                            "v-ripple.400",
                                                                          value:
                                                                            "rgba(113, 102, 240, 0.15)",
                                                                          expression:
                                                                            " 'rgba(113, 102, 240, 0.15)'",
                                                                          modifiers:
                                                                            {
                                                                              400: true,
                                                                            },
                                                                        },
                                                                      ],
                                                                    staticClass:
                                                                      "btn-icon rounded-circle add_to_cart",
                                                                    attrs: {
                                                                      variant:
                                                                        "outline-primary",
                                                                      id:
                                                                        "food_add_" +
                                                                        data3.food_id +
                                                                        "r",
                                                                      "data-price":
                                                                        data3.price,
                                                                    },
                                                                    on: {
                                                                      click:
                                                                        function (
                                                                          $event
                                                                        ) {
                                                                          return _vm.addcart(
                                                                            data3.price,
                                                                            data3.item_tax,
                                                                            data3.name,
                                                                            data3.food_id,
                                                                            data3.is_veg,
                                                                            data3.add_ons,
                                                                            data3.food_quantity,
                                                                            data3.image
                                                                          )
                                                                        },
                                                                    },
                                                                  },
                                                                  [
                                                                    _c(
                                                                      "feather-icon",
                                                                      {
                                                                        attrs: {
                                                                          icon: "PlusIcon",
                                                                        },
                                                                      }
                                                                    ),
                                                                  ],
                                                                  1
                                                                ),
                                                                _vm._v(" "),
                                                                _c(
                                                                  "div",
                                                                  {
                                                                    staticClass:
                                                                      "num-block skin-2",
                                                                    staticStyle:
                                                                      {
                                                                        display:
                                                                          "none",
                                                                      },
                                                                    attrs: {
                                                                      id:
                                                                        "food_control_" +
                                                                        data3.food_id +
                                                                        "r",
                                                                    },
                                                                  },
                                                                  [
                                                                    _c(
                                                                      "div",
                                                                      {
                                                                        staticClass:
                                                                          "num-in",
                                                                      },
                                                                      [
                                                                        _c(
                                                                          "span",
                                                                          {
                                                                            staticClass:
                                                                              "minus dis",
                                                                            attrs:
                                                                              {
                                                                                id:
                                                                                  "food_minus_" +
                                                                                  data3.food_id,
                                                                              },
                                                                            on: {
                                                                              click:
                                                                                function (
                                                                                  $event
                                                                                ) {
                                                                                  return _vm.removefromcart(
                                                                                    data3.price,
                                                                                    data3.item_tax,
                                                                                    data3.name,
                                                                                    data3.food_id,
                                                                                    data3.is_veg,
                                                                                    data3.add_ons,
                                                                                    data3.food_quantity,
                                                                                    data3.image
                                                                                  )
                                                                                },
                                                                            },
                                                                          }
                                                                        ),
                                                                        _vm._v(
                                                                          " "
                                                                        ),
                                                                        _c(
                                                                          "input",
                                                                          {
                                                                            staticClass:
                                                                              "in-num",
                                                                            attrs:
                                                                              {
                                                                                type: "text",
                                                                                value:
                                                                                  "1",
                                                                                readonly:
                                                                                  "",
                                                                                id:
                                                                                  "food_qty_" +
                                                                                  data3.food_id +
                                                                                  "r",
                                                                              },
                                                                          }
                                                                        ),
                                                                        _vm._v(
                                                                          " "
                                                                        ),
                                                                        _c(
                                                                          "span",
                                                                          {
                                                                            staticClass:
                                                                              "plus",
                                                                            attrs:
                                                                              {
                                                                                id:
                                                                                  "food_plus_" +
                                                                                  data3.food_id,
                                                                              },
                                                                            on: {
                                                                              click:
                                                                                function (
                                                                                  $event
                                                                                ) {
                                                                                  return _vm.addcart(
                                                                                    data3.price,
                                                                                    data3.item_tax,
                                                                                    data3.name,
                                                                                    data3.food_id,
                                                                                    data3.is_veg,
                                                                                    data3.add_ons,
                                                                                    data3.food_quantity,
                                                                                    data3.image
                                                                                  )
                                                                                },
                                                                            },
                                                                          }
                                                                        ),
                                                                      ]
                                                                    ),
                                                                  ]
                                                                ),
                                                              ],
                                                              1
                                                            )
                                                          : _vm._e(),
                                                      ],
                                                      1
                                                    ),
                                                  ],
                                                  1
                                                ),
                                              ],
                                              1
                                            ),
                                          ],
                                          1
                                        )
                                      }
                                    ),
                                    1
                                  ),
                                ],
                                1
                              ),
                            ]
                          )
                        }),
                        1
                      ),
                    ],
                    1
                  )
                : _vm._e(),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-col",
            { attrs: { lg: "4", md: "4", cols: "12" } },
            [
              _c("h3", [_vm._v(_vm._s(_vm.$t("Customer")))]),
              _vm._v(" "),
              _c("vue-autosuggest", {
                attrs: {
                  suggestions: _vm.suggestions1,
                  "input-props": _vm.inputProps1,
                  "section-configs": _vm.sectionConfigs1,
                  "render-suggestion": _vm.renderSuggestion1,
                  limit: 10,
                },
                on: { input: _vm.fetchResults1 },
                model: {
                  value: _vm.query1,
                  callback: function ($$v) {
                    _vm.query1 = $$v
                  },
                  expression: "query1",
                },
              }),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticStyle: {
                    border: "2px solid #e6e6e6",
                    padding: "10px",
                    margin: "20px 0px",
                    "border-radius": "10px",
                    cursor: "pointer",
                  },
                  on: { click: _vm.showModal },
                },
                [
                  _c("i", {
                    staticClass: "fa-solid fa-circle-plus",
                    staticStyle: { "font-size": "12px" },
                  }),
                  _vm._v(" "),
                  _c("span", { staticStyle: { "font-size": "12px" } }, [
                    _vm._v(
                      _vm._s(_vm.$t("Add")) +
                        " " +
                        _vm._s(_vm.$t("New")) +
                        " " +
                        _vm._s(_vm.$t("User"))
                    ),
                  ]),
                ]
              ),
              _vm._v(" "),
              _c("b-card", { staticClass: "rounded3" }, [
                _c(
                  "div",
                  { staticClass: "text-r side sticky-top3 dsp" },
                  [
                    _c("h3", [_vm._v(_vm._s(_vm.$t("Cart")))]),
                    _vm._v(" "),
                    _vm._l(_vm.cart_items, function (data, index) {
                      return _c(
                        "div",
                        { key: index },
                        [
                          _c(
                            "b-row",
                            [
                              _c(
                                "b-col",
                                {
                                  staticStyle: { padding: "1.25rem" },
                                  attrs: { cols: "12" },
                                },
                                [
                                  _c(
                                    "b-media",
                                    {
                                      attrs: { "vertical-align": "top" },
                                      scopedSlots: _vm._u(
                                        [
                                          data.food_image
                                            ? {
                                                key: "aside",
                                                fn: function () {
                                                  return [
                                                    _c("b-img", {
                                                      staticClass: "bor",
                                                      attrs: {
                                                        src: data.food_image,
                                                        "blank-color": "#ccc",
                                                        width: "40",
                                                      },
                                                    }),
                                                  ]
                                                },
                                                proxy: true,
                                              }
                                            : null,
                                        ],
                                        null,
                                        true
                                      ),
                                    },
                                    [
                                      _vm._v(" "),
                                      _c(
                                        "h5",
                                        {
                                          staticClass: "media-heading",
                                          staticStyle: { height: "40px" },
                                        },
                                        [
                                          data.isveg
                                            ? _c(
                                                "div",
                                                [
                                                  _c("b-img", {
                                                    staticStyle: {
                                                      width: "12px",
                                                    },
                                                    attrs: {
                                                      src: "/veg.svg",
                                                      fluid: "",
                                                      alt: "Scan",
                                                    },
                                                  }),
                                                  _vm._v(
                                                    "\n                                        " +
                                                      _vm._s(data.name) +
                                                      "\n                                    "
                                                  ),
                                                ],
                                                1
                                              )
                                            : _c(
                                                "div",
                                                [
                                                  _c("b-img", {
                                                    staticStyle: {
                                                      width: "12px",
                                                    },
                                                    attrs: {
                                                      src: "/non_veg.svg",
                                                      fluid: "",
                                                      alt: "Scan",
                                                    },
                                                  }),
                                                  _vm._v(
                                                    "\n                                        " +
                                                      _vm._s(data.name) +
                                                      "\n                                    "
                                                  ),
                                                ],
                                                1
                                              ),
                                        ]
                                      ),
                                      _vm._v(" "),
                                      data.addonsName
                                        ? _c(
                                            "b-card-text",
                                            { staticClass: "mb-0 textp" },
                                            [
                                              _vm._v(
                                                "\n                                    " +
                                                  _vm._s(_vm.$t("Addons")) +
                                                  " :\n                                    " +
                                                  _vm._s(data.addonsName) +
                                                  "\n                                "
                                              ),
                                            ]
                                          )
                                        : _vm._e(),
                                      _vm._v(" "),
                                      data.sizeName
                                        ? _c(
                                            "b-card-text",
                                            { staticClass: "mb-0 textp" },
                                            [
                                              _vm._v(
                                                "\n                                    " +
                                                  _vm._s(_vm.$t("Size")) +
                                                  " : " +
                                                  _vm._s(data.sizeName) +
                                                  "\n                                "
                                              ),
                                            ]
                                          )
                                        : _vm._e(),
                                      _vm._v(" "),
                                      _c(
                                        "b-row",
                                        { staticClass: "pt-0" },
                                        [
                                          _c(
                                            "b-col",
                                            { attrs: { cols: "12" } },
                                            [
                                              _c(
                                                "h4",
                                                {
                                                  staticClass:
                                                    "media-heading text-primary",
                                                },
                                                [
                                                  _vm._v(
                                                    "\n                                            " +
                                                      _vm._s(
                                                        _vm.setting.value
                                                      ) +
                                                      "\n                                            " +
                                                      _vm._s(
                                                        data.price +
                                                          data.addonsPrice
                                                      ) +
                                                      "\n                                        "
                                                  ),
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c("div", [
                                                _c(
                                                  "div",
                                                  {
                                                    staticClass:
                                                      "num-block skin-2",
                                                    attrs: {
                                                      id:
                                                        "food_control_" +
                                                        data.id +
                                                        "c",
                                                    },
                                                  },
                                                  [
                                                    _c(
                                                      "div",
                                                      { staticClass: "num-in" },
                                                      [
                                                        _c("span", {
                                                          staticClass:
                                                            "minus dis",
                                                          attrs: {
                                                            id:
                                                              "food_minus_" +
                                                              data.id,
                                                          },
                                                          on: {
                                                            click: function (
                                                              $event
                                                            ) {
                                                              return _vm.removefromcart(
                                                                data.price,
                                                                data.taxperc,
                                                                data.name,
                                                                data.id,
                                                                data.isveg,
                                                                data.addons,
                                                                data.sizes,
                                                                data.food_image,
                                                                index
                                                              )
                                                            },
                                                          },
                                                        }),
                                                        _vm._v(" "),
                                                        _c("input", {
                                                          staticClass: "in-num",
                                                          attrs: {
                                                            type: "text",
                                                            value: "1",
                                                            readonly: "",
                                                            id:
                                                              "food_qty_" +
                                                              data.id +
                                                              "c",
                                                          },
                                                        }),
                                                        _vm._v(" "),
                                                        _c("span", {
                                                          staticClass: "plus",
                                                          attrs: {
                                                            id:
                                                              "food_plus_" +
                                                              data.id,
                                                          },
                                                          on: {
                                                            click: function (
                                                              $event
                                                            ) {
                                                              return _vm.addcart(
                                                                data.price,
                                                                data.taxperc,
                                                                data.name,
                                                                data.id,
                                                                data.isveg,
                                                                data.addons,
                                                                data.sizes,
                                                                data.food_image,
                                                                index
                                                              )
                                                            },
                                                          },
                                                        }),
                                                      ]
                                                    ),
                                                  ]
                                                ),
                                              ]),
                                            ]
                                          ),
                                        ],
                                        1
                                      ),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              ),
                            ],
                            1
                          ),
                        ],
                        1
                      )
                    }),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "pt-1 cart-bord",
                        staticStyle: { "margin-bottom": "20px" },
                        attrs: { id: "bill" },
                      },
                      [
                        _c("h4", [_vm._v(_vm._s(_vm.$t("Bill Details")))]),
                        _vm._v(" "),
                        _c("table", { attrs: { width: "100%" } }, [
                          _c("tr", [
                            _c("td", [_vm._v(_vm._s(_vm.$t("Item Total")))]),
                            _vm._v(" "),
                            _c("td", [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(_vm.setting.value) +
                                  "\n                                " +
                                  _vm._s(
                                    _vm._f("price_format_final")(
                                      _vm.citem_total
                                    )
                                  ) +
                                  "\n                            "
                              ),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("td", [
                              _vm._v(_vm._s(_vm.$t("Packaging Charge"))),
                            ]),
                            _vm._v(" "),
                            _c("td", [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(_vm.setting.value) +
                                  "\n                                " +
                                  _vm._s(
                                    _vm._f("price_format_final")(
                                      _vm.cpackaging_charge
                                    )
                                  ) +
                                  "\n                            "
                              ),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("td", [_vm._v(_vm._s(_vm.$t("Discount")))]),
                            _vm._v(" "),
                            _c("td", [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(_vm.setting.value) +
                                  "\n                                " +
                                  _vm._s(
                                    _vm._f("price_format_final")(_vm.cdiscount)
                                  ) +
                                  "\n                            "
                              ),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c("tr", { attrs: { id: "tips_bill" } }, [
                            _c("td", [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(_vm.$t("Tax")) +
                                  "\n                                \n                            "
                              ),
                            ]),
                            _vm._v(" "),
                            _c("td", [
                              _vm._v(
                                "\n                                " +
                                  _vm._s(_vm.setting.value) +
                                  "\n                                " +
                                  _vm._s(
                                    _vm._f("price_format_final")(_vm.ctax)
                                  ) +
                                  " \n                            "
                              ),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c("tr", [
                            _c("td", [
                              _c("b", [
                                _vm._v(_vm._s(_vm.$t("Bill Total")) + " "),
                              ]),
                            ]),
                            _vm._v(" "),
                            _c("td", [
                              _c("b", [
                                _vm._v(
                                  "\n                                    " +
                                    _vm._s(_vm.setting.value) +
                                    "\n                                    " +
                                    _vm._s(
                                      _vm._f("price_format_final")(_vm.cbill)
                                    ) +
                                    "\n                                    "
                                ),
                              ]),
                            ]),
                          ]),
                        ]),
                      ]
                    ),
                    _vm._v(" "),
                    _vm.business_type == 1
                      ? _c(
                          "div",
                          {
                            staticStyle: { "margin-bottom": "20px" },
                            attrs: { id: "order_type" },
                          },
                          [
                            _c("h4", [_vm._v(_vm._s(_vm.$t("Order Type")))]),
                            _vm._v(" "),
                            _c(
                              "label",
                              {
                                staticClass:
                                  "btn btn-outline-secondary tiplabel tiphide  deli f-size-0 tipsactive1",
                                attrs: { id: "dineinp", type: "button" },
                              },
                              [
                                _c("input", {
                                  staticClass: "checktips",
                                  attrs: {
                                    type: "radio",
                                    hidden: "",
                                    name: "d_type",
                                    id: "delivery",
                                    value: "1",
                                    checked: "",
                                  },
                                  on: {
                                    change: function ($event) {
                                      return _vm.o_type_sel(1)
                                    },
                                  },
                                }),
                                _c(
                                  "span",
                                  { staticStyle: { color: "#282828" } },
                                  [
                                    _c("i", {
                                      staticClass: "fa-solid fa-utensils",
                                    }),
                                    _vm._v(" " + _vm._s(_vm.$t("Dine In"))),
                                  ]
                                ),
                              ]
                            ),
                            _vm._v(" "),
                            _c(
                              "label",
                              {
                                staticClass:
                                  "btn btn-outline-secondary tiplabel tiphide take f-size-0 ",
                                attrs: { id: "takeawayp", type: "button" },
                              },
                              [
                                _c("input", {
                                  staticClass: "checktips",
                                  attrs: {
                                    type: "radio",
                                    hidden: "",
                                    name: "d_type",
                                    id: "takeaway",
                                    value: "2",
                                  },
                                  on: {
                                    change: function ($event) {
                                      return _vm.o_type_sel(2)
                                    },
                                  },
                                }),
                                _c(
                                  "span",
                                  { staticStyle: { color: "#282828" } },
                                  [
                                    _c("i", {
                                      staticClass: "fa-solid fa-person-walking",
                                    }),
                                    _vm._v(" " + _vm._s(_vm.$t("Take Away"))),
                                  ]
                                ),
                              ]
                            ),
                          ]
                        )
                      : _vm._e(),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "cart-bord",
                        staticStyle: { "margin-bottom": "20px" },
                        attrs: { id: "payment_type" },
                      },
                      [
                        _c("h4", [_vm._v(_vm._s(_vm.$t("Payment Type")))]),
                        _vm._v(" "),
                        _c(
                          "label",
                          {
                            staticClass:
                              "btn btn-outline-secondary tiplabel tiphide  deli f-size-0 tipsactive1",
                            attrs: { id: "cashp", type: "button" },
                          },
                          [
                            _c("input", {
                              staticClass: "checktips",
                              attrs: {
                                type: "radio",
                                hidden: "",
                                name: "d_type",
                                id: "delivery",
                                value: "1",
                                checked: "",
                              },
                              on: {
                                change: function ($event) {
                                  return _vm.payment_type_sel(1)
                                },
                              },
                            }),
                            _c("span", { staticStyle: { color: "#282828" } }, [
                              _c("i", {
                                staticClass: "fa-solid fa-money-bill",
                              }),
                              _vm._v(" " + _vm._s(_vm.$t("Cash"))),
                            ]),
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "label",
                          {
                            staticClass:
                              "btn btn-outline-secondary tiplabel tiphide take f-size-0 ",
                            attrs: { id: "walletp", type: "button" },
                          },
                          [
                            _c("input", {
                              staticClass: "checktips",
                              attrs: {
                                type: "radio",
                                hidden: "",
                                name: "d_type",
                                id: "takeaway",
                                value: "2",
                              },
                              on: {
                                change: function ($event) {
                                  return _vm.payment_type_sel(2)
                                },
                              },
                            }),
                            _c("span", { staticStyle: { color: "#282828" } }, [
                              _c("i", { staticClass: "fa-solid fa-wallet" }),
                              _vm._v(" " + _vm._s(_vm.$t("Wallet"))),
                            ]),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "b-button",
                      {
                        directives: [
                          {
                            name: "ripple",
                            rawName: "v-ripple.400",
                            value: "rgba(255, 255, 255, 0.15)",
                            expression: "'rgba(255, 255, 255, 0.15)'",
                            modifiers: { 400: true },
                          },
                        ],
                        staticStyle: { width: "100%" },
                        attrs: { variant: "primary" },
                        on: {
                          click: function ($event) {
                            return _vm.create_order()
                          },
                        },
                      },
                      [
                        _vm._v(
                          "\n                    Create Order\n                "
                        ),
                      ]
                    ),
                  ],
                  2
                ),
              ]),
            ],
            1
          ),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "addon-modal",
          attrs: {
            id: "modal-addons",
            "ok-only": "",
            "ok-variant": "success",
            "ok-title": "Accept",
            "modal-class": "modal-success",
            centered: "",
            "hide-footer": "",
            title: _vm.$t("Customize"),
          },
        },
        [
          _c(
            "div",
            { staticClass: "text-center", attrs: { id: "addonsModalBody" } },
            [
              _vm.add_ons.length
                ? _c(
                    "table",
                    { attrs: { width: "80%" } },
                    [
                      _c("tr", [
                        _c("td", { staticStyle: { "font-size": "16px" } }, [
                          _c("b", [_vm._v(_vm._s(_vm.$t("Addons")))]),
                        ]),
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.add_ons, function (data4, index4) {
                        return _c("tr", { key: index4 }, [
                          _c(
                            "td",
                            { attrs: { width: "20%", ALIGN: "center" } },
                            [
                              _c("input", {
                                attrs: {
                                  type: "checkbox",
                                  name: "addonsOfItem_" + _vm.itemid,
                                  id: "addon_" + _vm.itemid + "_" + data4.id,
                                  "data-name": data4.name,
                                  "data-price": data4.price,
                                },
                                domProps: { value: data4.id },
                              }),
                            ]
                          ),
                          _vm._v(" "),
                          _c("td", [
                            _c(
                              "label",
                              {
                                staticStyle: { "font-size": "16px" },
                                attrs: {
                                  for: "addon_" + _vm.itemid + "_" + data4.id,
                                },
                              },
                              [_vm._v(" " + _vm._s(data4.name) + " ")]
                            ),
                          ]),
                          _vm._v(" "),
                          _c("td", [
                            _c(
                              "label",
                              {
                                staticStyle: { "font-size": "16px" },
                                attrs: {
                                  for: "addon_" + _vm.itemid + "_" + data4.id,
                                },
                              },
                              [
                                _vm._v(
                                  "     " +
                                    _vm._s(_vm.setting.value) +
                                    " " +
                                    _vm._s(data4.price) +
                                    " "
                                ),
                              ]
                            ),
                          ]),
                        ])
                      }),
                    ],
                    2
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.food_quantity.length
                ? _c(
                    "table",
                    {
                      staticStyle: { "margin-top": "30px" },
                      attrs: { width: "80%" },
                    },
                    [
                      _c("tr", [
                        _c("td", { staticStyle: { "font-size": "16px" } }, [
                          _c("b", [_vm._v(_vm._s(_vm.$t("Sizes")))]),
                          _c("br"),
                        ]),
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.food_quantity, function (data5, index5) {
                        return _c("tr", { key: index5 }, [
                          data5.is_default
                            ? _c("td", { attrs: { width: "20%" } }, [
                                _c("input", {
                                  attrs: {
                                    type: "radio",
                                    name: "sizesOfItem_" + _vm.itemid,
                                    id: "size_" + _vm.itemid + "_" + data5.id,
                                    "data-name": data5.name,
                                    "data-price": data5.price,
                                    checked: "",
                                  },
                                }),
                              ])
                            : _c(
                                "td",
                                { attrs: { width: "20%", ALIGN: "center" } },
                                [
                                  _c("input", {
                                    attrs: {
                                      type: "radio",
                                      name: "sizesOfItem_" + _vm.itemid,
                                      id: "size_" + _vm.itemid + "_" + data5.id,
                                      "data-name": data5.name,
                                      "data-price": data5.price,
                                    },
                                  }),
                                ]
                              ),
                          _vm._v(" "),
                          _c("td", [
                            _c(
                              "label",
                              {
                                staticStyle: { "font-size": "16px" },
                                attrs: {
                                  for: "size_" + _vm.itemid + "_" + data5.id,
                                },
                              },
                              [_vm._v(" " + _vm._s(data5.name) + " ")]
                            ),
                          ]),
                          _vm._v(" "),
                          _c("td", [
                            _c(
                              "label",
                              {
                                staticStyle: { "font-size": "16px" },
                                attrs: {
                                  for: "size_" + _vm.itemid + "_" + data5.id,
                                },
                              },
                              [
                                _vm._v(
                                  "    " +
                                    _vm._s(_vm.setting.value) +
                                    " " +
                                    _vm._s(data5.price) +
                                    " "
                                ),
                              ]
                            ),
                          ]),
                        ])
                      }),
                    ],
                    2
                  )
                : _vm._e(),
              _vm._v(" "),
              _c(
                "b-button",
                {
                  staticClass: "mt-2",
                  staticStyle: { width: "100%" },
                  attrs: { variant: "primary", id: "addCustomizedProduct" },
                  on: {
                    click: function ($event) {
                      return _vm.addCustomizedProduct(_vm.selected)
                    },
                  },
                },
                [_vm._v(_vm._s(_vm.$t("Add")))]
              ),
            ],
            1
          ),
        ]
      ),
      _vm._v(" "),
      _c(
        "b-modal",
        {
          ref: "my-modal",
          attrs: {
            id: "modal-login",
            "cancel-variant": "outline-secondary",
            "ok-title": "Login",
            "cancel-title": "Close",
            centered: "",
            title: "Add Customer",
            "hide-footer": "",
          },
        },
        [
          _c(
            "validation-observer",
            { ref: "simpleRules" },
            [
              _c(
                "b-form",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        [
                          _c("label", [_vm._v("Name")]),
                          _vm._v(" "),
                          _c("validation-provider", {
                            attrs: { rules: "required", name: "First Name" },
                            scopedSlots: _vm._u([
                              {
                                key: "default",
                                fn: function (ref) {
                                  var errors = ref.errors
                                  return [
                                    _c("b-form-input", {
                                      attrs: {
                                        state: errors.length > 0 ? false : null,
                                        placeholder: "Name",
                                      },
                                      model: {
                                        value: _vm.cust.name,
                                        callback: function ($$v) {
                                          _vm.$set(_vm.cust, "name", $$v)
                                        },
                                        expression: "cust.name",
                                      },
                                    }),
                                    _vm._v(" "),
                                    _c(
                                      "small",
                                      { staticClass: "text-danger" },
                                      [_vm._v(_vm._s(errors[0]))]
                                    ),
                                  ]
                                },
                              },
                            ]),
                          }),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        [
                          _c("label", [_vm._v("Phone Number")]),
                          _vm._v(" "),
                          _c("validation-provider", {
                            attrs: {
                              rules: "required|integer",
                              name: "Number",
                            },
                            scopedSlots: _vm._u([
                              {
                                key: "default",
                                fn: function (ref) {
                                  var errors = ref.errors
                                  return [
                                    _c("b-form-input", {
                                      attrs: {
                                        state: errors.length > 0 ? false : null,
                                        placeholder: "Phone Number",
                                      },
                                      model: {
                                        value: _vm.cust.phone,
                                        callback: function ($$v) {
                                          _vm.$set(_vm.cust, "phone", $$v)
                                        },
                                        expression: "cust.phone",
                                      },
                                    }),
                                    _vm._v(" "),
                                    _c(
                                      "small",
                                      { staticClass: "text-danger" },
                                      [_vm._v(_vm._s(errors[0]))]
                                    ),
                                  ]
                                },
                              },
                            ]),
                          }),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { md: "12" } },
                    [
                      _c(
                        "b-form-group",
                        [
                          _c("label", [_vm._v("Email")]),
                          _vm._v(" "),
                          _c("validation-provider", {
                            attrs: { name: "Email", rules: "required|email" },
                            scopedSlots: _vm._u([
                              {
                                key: "default",
                                fn: function (ref) {
                                  var errors = ref.errors
                                  return [
                                    _c("b-form-input", {
                                      attrs: {
                                        state: errors.length > 0 ? false : null,
                                        placeholder: "Email",
                                      },
                                      model: {
                                        value: _vm.cust.email,
                                        callback: function ($$v) {
                                          _vm.$set(_vm.cust, "email", $$v)
                                        },
                                        expression: "cust.email",
                                      },
                                    }),
                                    _vm._v(" "),
                                    _c(
                                      "small",
                                      { staticClass: "text-danger" },
                                      [_vm._v(_vm._s(errors[0]))]
                                    ),
                                  ]
                                },
                              },
                            ]),
                          }),
                        ],
                        1
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-col",
                    { attrs: { cols: "12" } },
                    [
                      _c(
                        "b-button",
                        {
                          attrs: { variant: "primary", type: "submit" },
                          on: {
                            click: function ($event) {
                              $event.preventDefault()
                              return _vm.add_customer($event)
                            },
                          },
                        },
                        [_vm._v("\n        Submit\n      ")]
                      ),
                    ],
                    1
                  ),
                ],
                1
              ),
            ],
            1
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/@core/components/toastification/ToastificationContent.vue":
/*!************************************************************************************!*\
  !*** ./resources/js/src/@core/components/toastification/ToastificationContent.vue ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ToastificationContent_vue_vue_type_template_id_7834b2fa_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true */ "./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true");
/* harmony import */ var _ToastificationContent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ToastificationContent.vue?vue&type=script&lang=js */ "./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _ToastificationContent_vue_vue_type_style_index_0_id_7834b2fa_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true */ "./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ToastificationContent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _ToastificationContent_vue_vue_type_template_id_7834b2fa_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"],
  _ToastificationContent_vue_vue_type_template_id_7834b2fa_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "7834b2fa",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/@core/components/toastification/ToastificationContent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=script&lang=js":
/*!************************************************************************************************************!*\
  !*** ./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=script&lang=js ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ToastificationContent.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true":
/*!*********************************************************************************************************************************************!*\
  !*** ./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true ***!
  \*********************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_style_index_0_id_7834b2fa_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=style&index=0&id=7834b2fa&lang=scss&scoped=true");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_style_index_0_id_7834b2fa_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_style_index_0_id_7834b2fa_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_style_index_0_id_7834b2fa_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_style_index_0_id_7834b2fa_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true":
/*!******************************************************************************************************************************!*\
  !*** ./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true ***!
  \******************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_template_id_7834b2fa_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/components/toastification/ToastificationContent.vue?vue&type=template&id=7834b2fa&scoped=true");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_template_id_7834b2fa_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ToastificationContent_vue_vue_type_template_id_7834b2fa_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/@core/utils/validations/validations.js":
/*!*****************************************************************!*\
  !*** ./resources/js/src/@core/utils/validations/validations.js ***!
  \*****************************************************************/
/*! exports provided: required, email, min, confirmed, regex, between, alpha, integer, digits, alphaDash, alphaNum, length, positive, credit, password, url */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "required", function() { return required; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "email", function() { return email; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "min", function() { return min; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "confirmed", function() { return confirmed; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "regex", function() { return regex; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "between", function() { return between; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "alpha", function() { return alpha; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "integer", function() { return integer; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "digits", function() { return digits; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "alphaDash", function() { return alphaDash; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "alphaNum", function() { return alphaNum; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "length", function() { return length; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "positive", function() { return positive; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "credit", function() { return credit; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "password", function() { return password; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "url", function() { return url; });
/* harmony import */ var vee_validate__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vee-validate */ "./node_modules/vee-validate/dist/vee-validate.esm.js");
/* harmony import */ var vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vee-validate/dist/rules */ "./node_modules/vee-validate/dist/rules.js");
/* harmony import */ var vee_validate_dist_locale_ar_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vee-validate/dist/locale/ar.json */ "./node_modules/vee-validate/dist/locale/ar.json");
var vee_validate_dist_locale_ar_json__WEBPACK_IMPORTED_MODULE_2___namespace = /*#__PURE__*/__webpack_require__.t(/*! vee-validate/dist/locale/ar.json */ "./node_modules/vee-validate/dist/locale/ar.json", 1);
/* harmony import */ var vee_validate_dist_locale_en_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vee-validate/dist/locale/en.json */ "./node_modules/vee-validate/dist/locale/en.json");
var vee_validate_dist_locale_en_json__WEBPACK_IMPORTED_MODULE_3___namespace = /*#__PURE__*/__webpack_require__.t(/*! vee-validate/dist/locale/en.json */ "./node_modules/vee-validate/dist/locale/en.json", 1);
/* harmony import */ var _validators__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./validators */ "./resources/js/src/@core/utils/validations/validators.js");





// eslint-disable-next-line object-curly-newline


// ////////////////////////////////////////////////////////
// General
// ////////////////////////////////////////////////////////

var required = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('required', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["required"]);
var email = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('email', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["email"]);
var min = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('min', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["min"]);
var confirmed = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('confirmed', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["confirmed"]);
var regex = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('regex', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["regex"]);
var between = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('between', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["between"]);
var alpha = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('alpha', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["alpha"]);
var integer = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('integer', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["integer"]);
var digits = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('digits', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["digits"]);
var alphaDash = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('alpha-dash', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["alpha_dash"]);
var alphaNum = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('alpha-num', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["alpha_num"]);
var length = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('length', vee_validate_dist_rules__WEBPACK_IMPORTED_MODULE_1__["length"]);
var positive = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('positive', {
  validate: _validators__WEBPACK_IMPORTED_MODULE_4__["validatorPositive"],
  message: 'Please enter positive number!'
});
var credit = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('credit-card', {
  validate: _validators__WEBPACK_IMPORTED_MODULE_4__["validatorCreditCard"],
  message: 'It is not valid credit card!'
});
var password = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('password', {
  validate: _validators__WEBPACK_IMPORTED_MODULE_4__["validatorPassword"],
  message: 'Your {_field_} must contain at least one uppercase, one lowercase, one special character and one digit'
});
var url = Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["extend"])('url', {
  validate: _validators__WEBPACK_IMPORTED_MODULE_4__["validatorUrlValidator"],
  message: 'URL is invalid'
});

// Install English and Arabic localizations.
Object(vee_validate__WEBPACK_IMPORTED_MODULE_0__["localize"])({
  en: {
    messages: vee_validate_dist_locale_en_json__WEBPACK_IMPORTED_MODULE_3__.messages,
    names: {
      email: 'Email',
      password: 'Password'
    },
    fields: {
      password: {
        min: '{_field_} is too short, you want to get hacked?'
      }
    }
  },
  ar: {
    messages: vee_validate_dist_locale_ar_json__WEBPACK_IMPORTED_MODULE_2__.messages,
    names: {
      email: 'البريد الإلكتروني',
      password: 'كلمة السر'
    },
    fields: {
      password: {
        min: 'كلمة السر قصيرة جداً سيتم اختراقك'
      }
    }
  }
});
// ////////////////////////////////////////////////////////
// NOTE:
// Quasar validation for reference only
// Remove this note once development is finished and make sure to
// to convert all of them in veevalidate version
// ////////////////////////////////////////////////////////

// export const required = (val) => {
//   return (val && val.length > 0) || '*Field is required'
// }

// export const required_obj = (obj) => {
//   if (obj === null || obj === undefined) return '*Field is required'
//   return (Object.entries(obj).length > 0 && obj.constructor === Object) || '*Field is required'
// }

// export const no_blank_spaces_arr = (arr) => {
//   return arr.every(val => (val.trim() && val.trim().length > 0)) || 'Blank Spaces are not allowed'
// }

// export const url = val => {
//   // If blank return
//   if (val === undefined || val === null || val.length === 0) return true

//   // Used
//   // https://stackoverflow.com/questions/4314741/url-regex-validation

//   // Other
//   // https://stackoverflow.com/questions/5717093/check-if-a-javascript-string-is-a-url
//   // https://www.w3resource.com/javascript-exercises/javascript-regexp-exercise-9.php
//   // https://www.geeksforgeeks.org/how-to-validate-url-using-regular-expression-in-javascript/

//   /* eslint-disable no-useless-escape */
//   const re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/
//   /* eslint-enable no-useless-escape */
//   return re.test(val) || 'URL is invalid'
// }

// export const date = val => {

//   // If blank return
//   if (val === undefined || val === null || val.length === 0) return true

//   // https://github.com/quasarframework/quasar/blob/dev/ui/src/utils/patterns.js
//   return /^-?[\d]+\/[0-1]\d\/[0-3]\d$/.test(val) || 'Date is invalid'
// }

// export const max = (val, max) => {

//   // If blank return
//   if (val === undefined || val === null) return true

//   return val.length <= max || `More than ${max} characters are not allowed`
// }

// export const max_arr = (val, max) => {
//   return val.length <= max || `More than ${max} values are not allowed`
// }

// export const min = (val, min) => {

//   // If blank return
//   if (val === undefined || val === null || val.length === 0) return true

//   return val.length >= min || `Minimum ${min} characters are required`
// }

// export const num_range = (val, min, max) => {

//   // If blank return
//   if (val === undefined || val === null || val.length === 0) return true

//   const msg = 'Value is invalid'
//   if (min === null) return val <= max || msg
//   else if (max === null) return val >= min || msg
//   else return (val >= min && val <= max) || msg
// }

/***/ }),

/***/ "./resources/js/src/@core/utils/validations/validators.js":
/*!****************************************************************!*\
  !*** ./resources/js/src/@core/utils/validations/validators.js ***!
  \****************************************************************/
/*! exports provided: validatorPositive, validatorPassword, validatorCreditCard, validatorUrlValidator */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "validatorPositive", function() { return validatorPositive; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "validatorPassword", function() { return validatorPassword; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "validatorCreditCard", function() { return validatorCreditCard; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "validatorUrlValidator", function() { return validatorUrlValidator; });
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_regexp_test_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.regexp.test.js */ "./node_modules/core-js/modules/es.regexp.test.js");
/* harmony import */ var core_js_modules_es_regexp_test_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_test_js__WEBPACK_IMPORTED_MODULE_1__);


var validatorPositive = function validatorPositive(value) {
  if (value >= 0) {
    return true;
  }
  return false;
};
var validatorPassword = function validatorPassword(password) {
  /* eslint-disable no-useless-escape */
  var regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()]).{8,}/;
  /* eslint-enable no-useless-escape */
  var validPassword = regExp.test(password);
  return validPassword;
};
var validatorCreditCard = function validatorCreditCard(creditnum) {
  /* eslint-disable no-useless-escape */
  var cRegExp = /^(?:3[47][0-9]{13})$/;
  /* eslint-enable no-useless-escape */
  var validCreditCard = cRegExp.test(creditnum);
  return validCreditCard;
};
var validatorUrlValidator = function validatorUrlValidator(val) {
  if (val === undefined || val === null || val.length === 0) {
    return true;
  }
  /* eslint-disable no-useless-escape */
  var re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
  /* eslint-enable no-useless-escape */
  return re.test(val);
};

/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue":
/*!********************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _pos_vue_vue_type_template_id_2de8c2a0_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./pos.vue?vue&type=template&id=2de8c2a0&scoped=true */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=template&id=2de8c2a0&scoped=true");
/* harmony import */ var _pos_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./pos.vue?vue&type=script&lang=js */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _pos_vue_vue_type_style_index_0_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss");
/* harmony import */ var _pos_vue_vue_type_style_index_1_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss");
/* harmony import */ var _pos_vue_vue_type_style_index_2_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css");
/* harmony import */ var _pos_vue_vue_type_style_index_3_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss");
/* harmony import */ var _pos_vue_vue_type_style_index_4_id_2de8c2a0_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true");
/* harmony import */ var _pos_vue_vue_type_style_index_5_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css */ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");











/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_8__["default"])(
  _pos_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _pos_vue_vue_type_template_id_2de8c2a0_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"],
  _pos_vue_vue_type_template_id_2de8c2a0_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "2de8c2a0",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/store_src/views/store/ordermanagement/pos.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=script&lang=js":
/*!********************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=script&lang=js ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_0_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=0&id=2de8c2a0&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_0_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_0_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_0_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_0_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_1_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=1&id=2de8c2a0&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_1_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_1_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_1_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_1_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css ***!
  \****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_2_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=2&id=2de8c2a0&scoped=true&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_2_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_2_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_2_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_2_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_3_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=3&id=2de8c2a0&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_3_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_3_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_3_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_3_id_2de8c2a0_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true ***!
  \*****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_4_id_2de8c2a0_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=4&id=2de8c2a0&lang=scss&scoped=true");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_4_id_2de8c2a0_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_4_id_2de8c2a0_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_4_id_2de8c2a0_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_4_id_2de8c2a0_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css ***!
  \****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_5_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=style&index=5&id=2de8c2a0&scoped=true&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_5_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_5_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_5_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_style_index_5_id_2de8c2a0_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=template&id=2de8c2a0&scoped=true":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=template&id=2de8c2a0&scoped=true ***!
  \**************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_template_id_2de8c2a0_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./pos.vue?vue&type=template&id=2de8c2a0&scoped=true */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/store/ordermanagement/pos.vue?vue&type=template&id=2de8c2a0&scoped=true");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_template_id_2de8c2a0_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_pos_vue_vue_type_template_id_2de8c2a0_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);