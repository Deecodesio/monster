(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[240],{

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

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=script&lang=js":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=script&lang=js ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/defineProperty.js */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.join.js */ "./node_modules/core-js/modules/es.array.join.js");
/* harmony import */ var core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_join_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var core_js_modules_es_number_constructor_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.number.constructor.js */ "./node_modules/core-js/modules/es.number.constructor.js");
/* harmony import */ var core_js_modules_es_number_constructor_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_number_constructor_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
/* harmony import */ var core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_for_each_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var vue_form_wizard__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! vue-form-wizard */ "./node_modules/vue-form-wizard/dist/vue-form-wizard.js");
/* harmony import */ var vue_form_wizard__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(vue_form_wizard__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! vue-select */ "./node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var vee_validate__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! vee-validate */ "./node_modules/vee-validate/dist/vee-validate.esm.js");
/* harmony import */ var vue_form_wizard_dist_vue_form_wizard_min_css__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! vue-form-wizard/dist/vue-form-wizard.min.css */ "./node_modules/vue-form-wizard/dist/vue-form-wizard.min.css");
/* harmony import */ var vue_form_wizard_dist_vue_form_wizard_min_css__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(vue_form_wizard_dist_vue_form_wizard_min_css__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _core_components_b_card_code__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @core/components/b-card-code */ "./resources/js/src/@core/components/b-card-code/index.js");
/* harmony import */ var _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @core/components/toastification/ToastificationContent.vue */ "./resources/js/src/@core/components/toastification/ToastificationContent.vue");
/* harmony import */ var vue_phone_number_input__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! vue-phone-number-input */ "./node_modules/vue-phone-number-input/dist/vue-phone-number-input.common.js");
/* harmony import */ var vue_phone_number_input__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(vue_phone_number_input__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var vue_phone_number_input_dist_vue_phone_number_input_css__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! vue-phone-number-input/dist/vue-phone-number-input.css */ "./node_modules/vue-phone-number-input/dist/vue-phone-number-input.css");
/* harmony import */ var vue_phone_number_input_dist_vue_phone_number_input_css__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(vue_phone_number_input_dist_vue_phone_number_input_css__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _validations__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! @validations */ "./resources/js/src/@core/utils/validations/validations.js");
/* harmony import */ var _vue_composition_api__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! @vue/composition-api */ "./node_modules/@vue/composition-api/dist/vue-composition-api.mjs");







//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//





// dropdown

//form validation//






/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    ValidationProvider: vee_validate__WEBPACK_IMPORTED_MODULE_9__["ValidationProvider"],
    ValidationObserver: vee_validate__WEBPACK_IMPORTED_MODULE_9__["ValidationObserver"],
    FormWizard: vue_form_wizard__WEBPACK_IMPORTED_MODULE_7__["FormWizard"],
    TabContent: vue_form_wizard__WEBPACK_IMPORTED_MODULE_7__["TabContent"],
    BForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BForm"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCol"],
    BFormGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormGroup"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormInput"],
    vSelect: vue_select__WEBPACK_IMPORTED_MODULE_8___default.a,
    BFormInvalidFeedback: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormInvalidFeedback"],
    BFormDatepicker: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormDatepicker"],
    // eslint-disable-next-line vue/no-unused-components
    ToastificationContent: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
    BFormSelect: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormSelect"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCardText"],
    BCardCode: _core_components_b_card_code__WEBPACK_IMPORTED_MODULE_11__["default"],
    BFormFile: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormFile"],
    BFormTextarea: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormTextarea"],
    BFormCheckboxGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormCheckboxGroup"],
    BFormCheckbox: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormCheckbox"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BImg"],
    BMedia: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BMedia"],
    BFormRadio: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormRadio"],
    BModal: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BModal"],
    VBModal: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["VBModal"],
    VuePhoneNumberInput: vue_phone_number_input__WEBPACK_IMPORTED_MODULE_13___default.a,
    BTab: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BTab"],
    BTabs: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BTabs"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BCard"],
    BAlert: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BAlert"],
    BLink: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BLink"],
    BFormRadioGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BFormRadioGroup"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BButton"],
    BDropdownForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BDropdownForm"],
    BDropdownDivider: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BDropdownDivider"],
    BDropdownText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BDropdownText"],
    BListGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BListGroup"],
    BListGroupItem: bootstrap_vue__WEBPACK_IMPORTED_MODULE_15__["BListGroupItem"]
  },
  created: function created() {
    var _this = this;
    this.$http.get('/admin/get_lang').then(function (res) {
      _this.setting = res.data;
    });
    this.$http.get('/admin/res_list').then(function (res) {
      _this.rows = res.data;
    });
    this.$http.get('/admin/vehicles_list_drive').then(function (res) {
      _this.vehicles = res.data;
    });
    this.$http.get('/admin/city_list').then(function (res) {
      _this.cities = res.data;
    });
    this.$http.get('/admin/area_list').then(function (res) {
      _this.areas = res.data;
    });
    this.$http.get('/admin/document_view').then(function (res) {
      _this.document_view = res.data;
    });
    this.$http.get('/admin/layout_driver').then(function (res) {
      _this.services = res.data;
    });
    this.$http.get('/admin/country_list').then(function (res) {
      _this.country = res.data;
    });
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_delivery_partner/' + this.$route.params.id).then(function (res) {
        _this.driver = res.data.data;
        _this.driver_tab = res.data.data;
        _this.driver.vehicle_name = res.data.data.vehicle_name;
        if (res.data.data.city == 0) {
          _this.driver.city = '';
        } else {
          _this.driver.city = res.data.data.city;
        }
        _this.formattedNumber = _this.driver.phone;
        _this.phone_country_code = _this.driver.country_code;
        _this.phone_country_calling_code = _this.driver.country_calling_code;
        _this.driver.password = _this.driver.org_password;
        _this.driver.cpassword = _this.driver.org_password;
        if (_this.driver.services.length > 0) {
          _this.driver.services.forEach(function (element) {
            document.getElementById(element).checked = true;
          });
        }
        document.getElementById('driver_id').value = res.data.data.id;
        _this.document = res.data.document;
        if (_this.document.length > 0) {
          _this.document.forEach(function (element) {
            // var img = document.querySelector('[id="' + 'document_img'+element.document_id + '"]') ;

            //     img.setAttribute("src",element.document);
            document.getElementById('document_img' + element.document_id).src = element.document;
            // document.getElementById('license_expirys'+element.id).src = element.document;
            // document.getElementById('license_expirys'+element.document_id).value = element.expiry_date;
          });
        }
        _this.citys.state = res.data.message.state_id;
      });
    }
  },
  data: function data() {
    return {
      url1: null,
      driver: {},
      driver_tab: {},
      driver_doc: {},
      rows: [],
      vehicles: [],
      cities: [],
      areas: [],
      license: [],
      licenses: [],
      document: [],
      doc_id: [],
      exp_id: [],
      driver_id: [],
      document_view: [],
      res_id: [],
      selected_res: "",
      selected_vehicle: "",
      selected_city: "",
      selected_area: null,
      modal_image: '',
      formattedNumber: '',
      country_calling_code: '',
      country_code: '',
      options: [{
        value: 1,
        text: this.$t('active')
      }, {
        value: 2,
        text: this.$t('inactive')
      }, {
        value: 3,
        text: this.$t('new')
      }],
      online_radio: [{
        text: 'Male',
        value: 1
      }, {
        text: 'Female',
        value: 0
      }],
      services: [],
      license_expiry: {},
      setting: {},
      isHidden: false,
      citys: {},
      isHidden1: false,
      isHidden2: false,
      isHidden3: false,
      country: [],
      areas1: {},
      state: [],
      citys_country: {},
      citys_state: {},
      options_status1: [{
        value: "1",
        text: this.$t('active')
      }, {
        value: "2",
        text: this.$t('inactive')
      }],
      options_status: [{
        value: "1",
        text: this.$t('active')
      }, {
        value: "2",
        text: this.$t('inactive')
      }]
    };
  },
  methods: Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_0__["default"])(Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_0__["default"])(Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_0__["default"])({
    onTabChange: function onTabChange(id) {
      if (id == 1) {
        this.isHidden = false;
      } else {
        this.isHidden = true;
      }
    },
    open_citymodal: function open_citymodal() {
      this.$refs["city-modal"].show();
      this.$refs["area-modal"].hide();
    },
    open_areamodal: function open_areamodal() {
      this.$refs["area-modal"].show();
      var timer = setInterval(function () {
        var element = document.getElementById("gmap");
        var mapoptions = {
          zoom: 18,
          disableDefaultUI: true
        };
        var map = new google.maps.Map(element, mapoptions);
        var lati = document.getElementById('latitude_area').value;
        var _long = document.getElementById('longitude_area').value;
        var myLatlng = new google.maps.LatLng(Number(lati), Number(_long));
        var geocoder = new google.maps.Geocoder();
        var input = document.getElementById('searchMadminnput_area');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          position: myLatlng,
          map: map,
          draggable: true
        });
        autocomplete.addListener('place_changed', function () {
          var place = autocomplete.getPlace();
          var address = '';
          if (place.address_components) {
            address = [place.address_components[0] && place.address_components[0].short_name || '', place.address_components[1] && place.address_components[1].short_name || '', place.address_components[2] && place.address_components[2].short_name || ''].join(' ');
          }
          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
          var geocoder = new google.maps.Geocoder();
          var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
          geocoder.geocode({
            'latLng': latlng
          }, function (results, status) {
            if (results[0]) {
              var add = results[0].formatted_address;
              document.getElementById('selectedadd').value = results[0].formatted_address;
              document.getElementById('latitude_area').value = place.geometry.location.lat();
              document.getElementById('longitude_area').value = place.geometry.location.lng();
            } else {
              alert("address not found");
            }
          });
        });
        google.maps.event.addListener(marker, 'dragend', function (marker) {
          var latLng = marker.latLng;
          var currentLatitude = latLng.lat();
          var currentLongitude = latLng.lng();
          geocoder.geocode({
            'latLng': latLng
          }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                document.getElementById('searchMadminnput_area').value = results[0].formatted_address;
                document.getElementById('selectedadd').value = results[0].formatted_address;
                document.getElementById('latitude_area').value = currentLatitude;
                document.getElementById('longitude_area').value = currentLongitude;
                infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                infowindow.open(map, marker);
              }
            }
          });
        });
        clearInterval(timer);
      }, 1000);
    },
    open_countrymodal: function open_countrymodal() {
      this.$refs["city-modal"].hide();
      this.$refs["country-modal"].show();
    },
    open_statemodal: function open_statemodal() {
      this.$refs["city-modal"].hide();
      this.$refs["state-modal"].show();
    },
    popToast: function popToast(response, icon, variant) {
      this.$toast({
        component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
        position: 'bottom-right',
        props: {
          title: this.$t(response.data.message),
          icon: icon,
          variant: variant
        }
      });
    },
    onTabChange1: function onTabChange1(id) {
      if (id == 1) {
        this.isHidden1 = false;
      } else {
        this.isHidden1 = true;
      }
    },
    onTabChange_city: function onTabChange_city(id) {
      if (id == 1) {
        this.isHidden2 = false;
      } else {
        this.isHidden2 = true;
      }
    },
    onTabChange_state: function onTabChange_state(id) {
      if (id == 1) {
        this.isHidden3 = false;
      } else {
        this.isHidden3 = true;
      }
    },
    // onTabChange(id) {
    // if (id == 1) {
    //   document.getElementById("primary").style.display = 'block';
    //   document.getElementById("secondary").style.display = 'none';
    // }
    // else {
    //   document.getElementById("primary").style.display = 'none';
    //   document.getElementById("secondary").style.display = 'block';
    // }
    country_add: function country_add() {
      var _this2 = this;
      var citys = new FormData();
      citys.append('country', this.citys_country.country);
      citys.append('country_code', this.citys_country.country_code);
      citys.append('currency_symbol', this.citys_country.currency_symbol);
      citys.append('currency_code', this.citys_country.currency_code);
      this.$http.post("/admin/save_country", this.citys_country).then(function (response) {
        if (response.data.status == true) {
          _this2.$http.get('/admin/country_list').then(function (res) {
            _this2.country = res.data;
          });
          _this2.$refs["city-modal"].show();
          _this2.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
            position: 'bottom-right',
            props: {
              title: _this2.$t(response.data.message),
              icon: 'CheckIcon',
              variant: 'success'
            }
          });
        } else {
          _this2.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
            position: 'bottom-right',
            props: {
              title: _this2.$t(response.data.message),
              icon: 'CheckIcon',
              variant: 'warning'
            }
          });
        }
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this2.loading = false;
      });
    },
    state_add: function state_add() {
      var _this3 = this;
      var citys2 = new FormData();
      citys2.append('country_id', this.citys_state.country_id);
      citys2.append('state', this.citys_state.state);
      citys2.append('second_state', this.citys_state.second_state ? this.citys.second_state : '');
      this.$http.post("/admin/save_state", this.citys_state).then(function (response) {
        if (response.data.status == true) {
          _this3.$refs["city-modal"].show();
          _this3.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
            position: 'bottom-right',
            props: {
              title: _this3.$t(response.data.message),
              icon: 'CheckIcon',
              variant: 'success'
            }
          });
        } else {
          _this3.$toast({
            component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
            position: 'bottom-right',
            props: {
              title: _this3.$t(response.data.message),
              icon: 'CheckIcon',
              variant: 'warning'
            }
          });
        }
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this3.loading = false;
      });
    },
    onChange_city: function onChange_city(event) {
      var _this4 = this;
      this.$http.post('/admin/state_list_by_country', this.citys).then(function (res) {
        _this4.state = res.data;
      });
    },
    city_add: function city_add() {
      var _this5 = this;
      this.$http.post("/admin/city_add", this.citys).then(function (response) {
        if (response.data.status == true) {
          _this5.popToast(response, "CheckIcon", "success");
          _this5.$http.get('/admin/city_list').then(function (res) {
            _this5.cities = res.data;
          });
        } else {
          _this5.popToast(response, "AlertTriangleIcon", "danger");
        }
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this5.loading = false;
      });
    },
    area_add: function area_add() {
      var _this6 = this;
      var lati1 = document.getElementById('latitude_area').value;
      var long1 = document.getElementById('longitude_area').value;
      var area1 = document.getElementById('searchMadminnput_area').value;
      var data = new FormData();
      data.append('id', this.areas1.city);
      data.append('area', area1);
      data.append('status', this.areas1.status);
      data.append('latitude', lati1);
      data.append('longitude', long1);
      this.$http.post("/admin/area_add", data).then(function (response) {
        if (response.data.status == true) {
          _this6.popToast(response, "CheckIcon", "success");
          _this6.$http.get('/admin/area_list').then(function (res) {
            _this6.areas = res.data;
          });
        } else {
          _this6.popToast(response, "AlertTriangleIcon", "danger");
        }
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this6.loading = false;
      });
    },
    onChange: function onChange(event) {
      var _this7 = this;
      this.$http.post('/admin/area_list1', this.driver).then(function (res) {
        _this7.areas = res.data;
      });
    },
    formSubmitted: function formSubmitted() {
      this.$toast({
        component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
        props: {
          title: 'Form Submitted',
          icon: 'EditIcon',
          variant: 'success'
        }
      });
    },
    updatePhoneNumber: function updatePhoneNumber(data) {
      this.formattedNumber = data.nationalNumber;
      this.country_calling_code = data.countryCode;
      this.country_code = data.countryCallingCode;
    },
    open_image: function open_image(id) {
      // this.$refs['image-modal'].show()
      var youtubeimgsrc = document.getElementById("document_img" + id).src;
      // this.modal_image = youtubeimgsrc
      window.open(youtubeimgsrc);
    },
    onFileChange1: function onFileChange1(e) {
      var _this8 = this;
      var file = e.target.files[0];
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function () {
        _this8.url1 = reader.result;
      };
    },
    onFileChanges: function onFileChanges(e) {
      var file = e.target.files[0];
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function () {
        var img = document.querySelector('[id="' + 'document_img' + id + '"]');
        img.setAttribute("src", reader.result);
      };
    },
    validationForm: function validationForm() {
      var _this9 = this;
      var checkboxes1 = document.querySelectorAll('input[name="service_type"]:checked');
      var values1 = [];
      checkboxes1.forEach(function (checkbox) {
        values1.push(checkbox.id);
      });
      this.driver.services = values1;
      var data = new FormData();
      data.append('id', this.$route.params.id);
      data.append('email', this.driver.email);
      data.append('password', this.driver.password);
      data.append('profile_pic', this.driver.profile_pic);
      data.append('restaurant', this.driver.restaurant_id);
      data.append('vehicle_name', this.driver.vehicle_name);
      data.append('city', this.driver.city);
      data.append('driver_name', this.driver.name);
      data.append('phone_no', this.formattedNumber);
      data.append('area', this.driver.area);
      data.append('address_line_1', this.driver.address_line_1);
      data.append('address_line_2', this.driver.address_line_2);
      data.append('state_province', this.driver.state_province);
      data.append('address_city', this.driver.address_city);
      data.append('country', this.driver.country);
      data.append('about', this.driver.about);
      data.append('zip_code', this.driver.zip_code);
      data.append('status', this.driver.status);
      data.append('gender', this.driver.gender);
      data.append('services', this.driver.services);
      data.append('phone_country_calling_code', this.country_calling_code);
      data.append('phone_country_code', this.country_code);
      data.append('second_driver_name', this.driver.second_driver_name ? this.driver.second_driver_name : '');
      data.append('second_address_line_1', this.driver.second_address_line_1 ? this.driver.second_address_line_1 : '');
      data.append('second_address_line_2', this.driver.second_address_line_2 ? this.driver.second_address_line_2 : '');
      data.append('second_state_province', this.driver.second_state_province ? this.driver.second_state_province : '');
      data.append('second_address_city', this.driver.second_address_city ? this.driver.second_address_city : '');
      data.append('second_country', this.driver.second_country ? this.driver.second_country : '');
      this.$http.post("/admin/add_driver", data).then(function (response) {
        if (response.data.status == true) {
          _this9.popToast(response, "CheckIcon", "success");
          document.getElementById('driver_id').value = response.data.id;
        } else {
          _this9.popToast(response, "AlertTriangleIcon", "danger");
        }
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this9.loading = false;
      });
    }
  }, "popToast", function popToast(response, icon, variant) {
    this.$toast({
      component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_12__["default"],
      position: 'bottom-right',
      props: {
        title: this.$t(response.data.message),
        icon: icon,
        variant: variant
      }
    });
  }), "validationFormBank", function validationFormBank() {
    var _this10 = this;
    var data2 = new FormData();
    data2.append('id', this.$route.params.id);
    data2.append('routing_no', this.driver_tab.routing_no);
    data2.append('swift_code', this.driver_tab.swift_code);
    data2.append('branch_address', this.driver_tab.branch_address);
    data2.append('account_no', this.driver_tab.account_no);
    data2.append('bank_name', this.driver_tab.bank_name);
    data2.append('branch_name', this.driver_tab.branch_name);
    data2.append('account_name', this.driver_tab.account_name);
    data2.append('account_address', this.driver_tab.account_address);
    // driver_tab.append(driver_id,document.getElementById('driver_id').value);
    // alert(this.driver_tab.driver_id);
    this.$http.post("/admin/add_driver_tab", data2).then(function (response) {
      if (response.data.status == true) {
        _this10.popToast(response, "CheckIcon", "success");
        _this10.$router.push({
          name: "driver_list"
        });
      } else {
        _this10.popToast(response, "AlertTriangleIcon", "danger");
      }
    })["catch"](function (error) {
      return console.log(error);
    })["finally"](function () {
      return _this10.loading = false;
    });
  }), "validationFormDocument", function validationFormDocument() {
    var _this11 = this;
    var data = new FormData();
    data.append('id', this.$route.params.id);
    this.document_view.forEach(function (number) {
      if (number.document_for == 1) {
        var img = document.getElementById('image' + number.id).files[0];
        if (img !== undefined) {
          data.append('license[]', document.getElementById('image' + number.id).files[0]);
          data.append('doc_id[]', document.getElementById('doc_id' + number.id).value);
          if (number.expiry_date_needed == 1) {
            // Apply some style on paragraph
            data.append('license_expiry[]', document.getElementById('license_expiry' + number.id).value);
            data.append('exp_id[]', document.getElementById('exp_id' + number.id).value);
          }
        }
      }
    });
    this.$http.post("/admin/add_driver_tab1", data).then(function (response) {
      if (response.data.status == true) {
        _this11.popToast(response, "CheckIcon", "success");
        document.getElementById('driver_id').value = response.data.id;
      } else {
        _this11.popToast(response, "AlertTriangleIcon", "danger");
      }
    })["catch"](function (error) {
      return console.log(error);
    })["finally"](function () {
      return _this11.loading = false;
    });
  })
});

/***/ }),

/***/ "./node_modules/core-js/modules/es.array.join.js":
/*!*******************************************************!*\
  !*** ./node_modules/core-js/modules/es.array.join.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var uncurryThis = __webpack_require__(/*! ../internals/function-uncurry-this */ "./node_modules/core-js/internals/function-uncurry-this.js");
var IndexedObject = __webpack_require__(/*! ../internals/indexed-object */ "./node_modules/core-js/internals/indexed-object.js");
var toIndexedObject = __webpack_require__(/*! ../internals/to-indexed-object */ "./node_modules/core-js/internals/to-indexed-object.js");
var arrayMethodIsStrict = __webpack_require__(/*! ../internals/array-method-is-strict */ "./node_modules/core-js/internals/array-method-is-strict.js");

var nativeJoin = uncurryThis([].join);

var ES3_STRINGS = IndexedObject !== Object;
var FORCED = ES3_STRINGS || !arrayMethodIsStrict('join', ',');

// `Array.prototype.join` method
// https://tc39.es/ecma262/#sec-array.prototype.join
$({ target: 'Array', proto: true, forced: FORCED }, {
  join: function join(separator) {
    return nativeJoin(toIndexedObject(this), separator === undefined ? ',' : separator);
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

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "@charset \"UTF-8\";\n/**\n    Support for SASS is deprecated as of v3.18.\n\n    The files remain here if your build is dependent on them\n    but they will not receive updates in future releases. All\n    SASS variables have been translated into CSS variables, so\n    migration should be quite simple if you'd like to move over.\n\n    In v4, these files will be removed.\n */\n.v-select {\n  position: relative;\n  font-family: inherit;\n}\n.v-select,\n.v-select * {\n  box-sizing: border-box;\n}\n\n/* KeyFrames */\n@-webkit-keyframes vSelectSpinner-ltr {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@-webkit-keyframes vSelectSpinner-rtl {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(-360deg);\n}\n}\n@keyframes vSelectSpinner-ltr {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes vSelectSpinner-rtl {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(-360deg);\n}\n}\n/* Dropdown Default Transition */\n.vs__fade-enter-active,\n.vs__fade-leave-active {\n  pointer-events: none;\n  transition: opacity 0.15s cubic-bezier(1, 0.5, 0.8, 1);\n}\n.vs__fade-enter,\n.vs__fade-leave-to {\n  opacity: 0;\n}\n\n/** Component States */\n/*\n * Disabled\n *\n * When the component is disabled, all interaction\n * should be prevented. Here we modify the bg color,\n * and change the cursor displayed on the interactive\n * components.\n */\n[dir] .vs--disabled .vs__dropdown-toggle, [dir] .vs--disabled .vs__clear, [dir] .vs--disabled .vs__search, [dir] .vs--disabled .vs__selected, [dir] .vs--disabled .vs__open-indicator {\n  cursor: not-allowed;\n  background-color: #f8f8f8;\n}\n\n/*\n *  RTL - Right to Left Support\n *\n *  Because we're using a flexbox layout, the `dir=\"rtl\"`\n *  HTML attribute does most of the work for us by\n *  rearranging the child elements visually.\n */\n.v-select[dir=rtl] .vs__actions {\n  padding: 0 3px 0 6px;\n}\n.v-select[dir=rtl] .vs__clear {\n  margin-left: 6px;\n  margin-right: 0;\n}\n.v-select[dir=rtl] .vs__deselect {\n  margin-left: 0;\n  margin-right: 2px;\n}\n.v-select[dir=rtl] .vs__dropdown-menu {\n  text-align: right;\n}\n\n/**\n    Dropdown Toggle\n\n    The dropdown toggle is the primary wrapper of the component. It\n    has two direct descendants: .vs__selected-options, and .vs__actions.\n\n    .vs__selected-options holds the .vs__selected's as well as the\n    main search input.\n\n    .vs__actions holds the clear button and dropdown toggle.\n */\n.vs__dropdown-toggle {\n  appearance: none;\n  display: flex;\n  white-space: normal;\n}\n[dir] .vs__dropdown-toggle {\n  padding: 0 0 4px 0;\n  background: none;\n  border: 1px solid rgba(60, 60, 60, 0.26);\n  border-radius: 4px;\n}\n.vs__selected-options {\n  display: flex;\n  flex-basis: 100%;\n  flex-grow: 1;\n  flex-wrap: wrap;\n  position: relative;\n}\n[dir] .vs__selected-options {\n  padding: 0 2px;\n}\n.vs__actions {\n  display: flex;\n  align-items: center;\n}\n[dir=ltr] .vs__actions {\n  padding: 4px 6px 0 3px;\n}\n[dir=rtl] .vs__actions {\n  padding: 4px 3px 0 6px;\n}\n\n/* Dropdown Toggle States */\n[dir] .vs--searchable .vs__dropdown-toggle {\n  cursor: text;\n}\n[dir] .vs--unsearchable .vs__dropdown-toggle {\n  cursor: pointer;\n}\n[dir] .vs--open .vs__dropdown-toggle {\n  border-bottom-color: transparent;\n}\n[dir=ltr] .vs--open .vs__dropdown-toggle {\n  border-bottom-left-radius: 0;\n  border-bottom-right-radius: 0;\n}\n[dir=rtl] .vs--open .vs__dropdown-toggle {\n  border-bottom-right-radius: 0;\n  border-bottom-left-radius: 0;\n}\n.vs__open-indicator {\n  fill: rgba(60, 60, 60, 0.5);\n  transition: transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n}\n[dir] .vs__open-indicator {\n  transform: scale(1);\n  transition-timing-function: cubic-bezier(1, -0.115, 0.975, 0.855);\n}\n[dir=ltr] .vs--open .vs__open-indicator {\n  transform: rotate(180deg) scale(1);\n}\n[dir=rtl] .vs--open .vs__open-indicator {\n  transform: rotate(-180deg) scale(1);\n}\n.vs--loading .vs__open-indicator {\n  opacity: 0;\n}\n\n/* Clear Button */\n.vs__clear {\n  fill: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__clear {\n  padding: 0;\n  border: 0;\n  background-color: transparent;\n  cursor: pointer;\n}\n[dir=ltr] .vs__clear {\n  margin-right: 8px;\n}\n[dir=rtl] .vs__clear {\n  margin-left: 8px;\n}\n\n/* Dropdown Menu */\n.vs__dropdown-menu {\n  display: block;\n  box-sizing: border-box;\n  position: absolute;\n  top: calc(100% - 1px);\n  z-index: 1000;\n  width: 100%;\n  max-height: 350px;\n  min-width: 160px;\n  overflow-y: auto;\n  list-style: none;\n}\n[dir] .vs__dropdown-menu {\n  padding: 5px 0;\n  margin: 0;\n  box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.15);\n  border: 1px solid rgba(60, 60, 60, 0.26);\n  border-top-style: none;\n  border-radius: 0 0 4px 4px;\n  background: #fff;\n}\n[dir=ltr] .vs__dropdown-menu {\n  left: 0;\n  text-align: left;\n}\n[dir=rtl] .vs__dropdown-menu {\n  right: 0;\n  text-align: right;\n}\n[dir] .vs__no-options {\n  text-align: center;\n}\n\n/* List Items */\n.vs__dropdown-option {\n  line-height: 1.42857143;\n  /* Normalize line height */\n  display: block;\n  color: #333;\n  /* Overrides most CSS frameworks */\n  white-space: nowrap;\n}\n[dir] .vs__dropdown-option {\n  padding: 3px 20px;\n  clear: both;\n  cursor: pointer;\n}\n.vs__dropdown-option--highlight {\n  color: #fff;\n}\n[dir] .vs__dropdown-option--highlight {\n  background: #5897fb;\n}\n.vs__dropdown-option--deselect {\n  color: #fff;\n}\n[dir] .vs__dropdown-option--deselect {\n  background: #fb5858;\n}\n.vs__dropdown-option--disabled {\n  color: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__dropdown-option--disabled {\n  background: inherit;\n  cursor: inherit;\n}\n\n/* Selected Tags */\n.vs__selected {\n  display: flex;\n  align-items: center;\n  color: #333;\n  line-height: 1.4;\n  z-index: 0;\n}\n[dir] .vs__selected {\n  background-color: #f0f0f0;\n  border: 1px solid rgba(60, 60, 60, 0.26);\n  border-radius: 4px;\n  margin: 4px 2px 0px 2px;\n  padding: 0 0.25em;\n}\n.vs__deselect {\n  display: inline-flex;\n  appearance: none;\n  fill: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__deselect {\n  padding: 0;\n  border: 0;\n  cursor: pointer;\n  background: none;\n  text-shadow: 0 1px 0 #fff;\n}\n[dir=ltr] .vs__deselect {\n  margin-left: 4px;\n}\n[dir=rtl] .vs__deselect {\n  margin-right: 4px;\n}\n\n/* States */\n[dir] .vs--single .vs__selected {\n  background-color: transparent;\n  border-color: transparent;\n}\n.vs--single.vs--open .vs__selected, .vs--single.vs--loading .vs__selected {\n  position: absolute;\n  opacity: 0.4;\n}\n.vs--single.vs--searching .vs__selected {\n  display: none;\n}\n\n/* Search Input */\n/**\n * Super weird bug... If this declaration is grouped\n * below, the cancel button will still appear in chrome.\n * If it's up here on it's own, it'll hide it.\n */\n.vs__search::-webkit-search-cancel-button {\n  display: none;\n}\n.vs__search::-webkit-search-decoration,\n.vs__search::-webkit-search-results-button,\n.vs__search::-webkit-search-results-decoration,\n.vs__search::-ms-clear {\n  display: none;\n}\n.vs__search,\n.vs__search:focus {\n  appearance: none;\n  line-height: 1.4;\n  font-size: 1em;\n  outline: none;\n  width: 0;\n  max-width: 100%;\n  flex-grow: 1;\n  z-index: 1;\n}\n[dir] .vs__search, [dir] .vs__search:focus {\n  border: 1px solid transparent;\n  margin: 4px 0 0 0;\n  padding: 0 7px;\n  background: none;\n  box-shadow: none;\n}\n[dir=ltr] .vs__search, [dir=ltr] .vs__search:focus {\n  border-left: none;\n}\n[dir=rtl] .vs__search, [dir=rtl] .vs__search:focus {\n  border-right: none;\n}\n.vs__search::placeholder {\n  color: inherit;\n}\n\n/**\n    States\n */\n.vs--unsearchable .vs__search {\n  opacity: 1;\n}\n[dir] .vs--unsearchable:not(.vs--disabled) .vs__search {\n  cursor: pointer;\n}\n.vs--single.vs--searching:not(.vs--open):not(.vs--loading) .vs__search {\n  opacity: 0.2;\n}\n\n/* Loading Spinner */\n.vs__spinner {\n  align-self: center;\n  opacity: 0;\n  font-size: 5px;\n  text-indent: -9999em;\n  overflow: hidden;\n  transition: opacity 0.1s;\n}\n[dir] .vs__spinner {\n  border-top: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-bottom: 0.9em solid rgba(100, 100, 100, 0.1);\n  transform: translateZ(0);\n}\n[dir=ltr] .vs__spinner {\n  border-right: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-left: 0.9em solid rgba(60, 60, 60, 0.45);\n  animation:  vSelectSpinner-ltr 1.1s infinite linear;\n}\n[dir=rtl] .vs__spinner {\n  border-left: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-right: 0.9em solid rgba(60, 60, 60, 0.45);\n  animation:  vSelectSpinner-rtl 1.1s infinite linear;\n}\n.vs__spinner,\n.vs__spinner:after {\n  width: 5em;\n  height: 5em;\n}\n[dir] .vs__spinner, [dir] .vs__spinner:after {\n  border-radius: 50%;\n}\n\n/* Loading Spinner States */\n.vs--loading .vs__spinner {\n  opacity: 1;\n}\n[dir] .vue-form-wizard {\n  background-color: #fff;\n  box-shadow: 0px 4px 25px 0px rgba(34, 41, 47, 0.1);\n  border-radius: 0.5rem;\n  padding-bottom: 0;\n}\n[dir] .vue-form-wizard .wizard-header {\n  padding: 0;\n  margin: 0;\n}\n.vue-form-wizard .title {\n  color: #636363;\n}\n.vue-form-wizard .wizard-navigation .wizard-progress-with-circle {\n  display: none;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav {\n  padding: 1.42rem 1.42rem 2.14rem 1.42rem;\n  border-bottom: 1px solid rgba(34, 41, 47, 0.08);\n  padding-bottom: 0;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li {\n  flex-grow: 0;\n  flex: inherit;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav li {\n  padding-bottom: 2rem;\n}\n[dir=ltr] .vue-form-wizard .wizard-navigation .wizard-nav li {\n  margin-right: 3rem;\n}\n[dir=rtl] .vue-form-wizard .wizard-navigation .wizard-nav li {\n  margin-left: 3rem;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li.active a {\n  color: #E01764;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li.active a .wizard-icon-circle .wizard-icon-container .wizard-icon {\n  color: #fff;\n  font-size: 1rem;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav li.active a .checked {\n  box-shadow: 0 3px 6px 0 rgba(105, 108, 255, 0.4);\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle {\n  width: 2.71rem;\n  height: 2.71rem;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle {\n  border: none;\n  background-color: #ededed;\n  border-radius: 6px;\n}\n[dir=ltr] .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle {\n  margin-right: 1rem;\n}\n[dir=rtl] .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle {\n  margin-left: 1rem;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle .wizard-icon-container {\n  border-radius: 6px;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle .wizard-icon {\n  font-style: inherit;\n  font-size: 1rem;\n  color: #b8c2cc;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle .wizard-icon.feather {\n  font-size: 1.3rem;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle.checked {\n  background-color: rgba(224, 23, 100, 0.08);\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle.checked .wizard-icon {\n  color: #E01764;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li a {\n  flex-direction: row;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li a .stepTitle {\n  font-size: 1rem;\n  color: #b8c2cc;\n  font-weight: 600;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li a .wizard-icon {\n  transition: none !important;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav li:not(:first-child) a::before {\n  content: \"\\E844\";\n  font-family: feather !important;\n  speak: none;\n  font-style: normal;\n  font-weight: 400;\n  font-variant: normal;\n  text-transform: none;\n  line-height: 1;\n  font-size: 1.14rem;\n  position: absolute;\n}\n[dir=ltr] .vue-form-wizard .wizard-navigation .wizard-nav li:not(:first-child) a::before {\n  left: -30px;\n}\n[dir=rtl] .vue-form-wizard .wizard-navigation .wizard-nav li:not(:first-child) a::before {\n  right: -30px;\n}\n[dir] .vue-form-wizard .wizard-card-footer {\n  padding-bottom: 1rem;\n}\n.vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn,\n.vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn {\n  min-width: unset;\n}\n[dir] .vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn, [dir] .vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn {\n  padding: 0.786rem 1.5rem;\n  border-radius: 0.4285rem;\n}\n.vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn {\n  color: #FFCD04 !important;\n  font-weight: 400;\n}\n[dir] .vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn {\n  border: 1px solid #FFCD04 !important;\n  background-color: transparent !important;\n}\n[dir] .vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn:hover {\n  background-color: rgba(255, 205, 4, 0.04) !important;\n}\n.vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn::before {\n  content: \"\\E843\";\n  font-family: feather !important;\n  speak: none;\n  font-style: normal;\n  font-variant: normal;\n  text-transform: none;\n  line-height: 1;\n  font-size: 1rem;\n  position: relative;\n}\n[dir=ltr] .vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn::before {\n  left: -6px;\n}\n[dir=rtl] .vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn::before {\n  right: -6px;\n}\n.vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn {\n  font-weight: 400;\n}\n[dir] .vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn:hover {\n  box-shadow: 0 8px 25px -8px #E01764;\n}\n.vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn::after {\n  content: \"\\E844\";\n  font-family: feather !important;\n  speak: none;\n  font-style: normal;\n  font-weight: 400;\n  font-variant: normal;\n  text-transform: none;\n  line-height: 1;\n  font-size: 1rem;\n  position: relative;\n}\n[dir=ltr] .vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn::after {\n  right: -6px;\n}\n[dir=rtl] .vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn::after {\n  left: -6px;\n}\n[dir] .steps-transparent.vue-form-wizard {\n  background-color: transparent;\n  box-shadow: none;\n  padding-bottom: 0;\n}\n[dir] .steps-transparent.vue-form-wizard .wizard-header {\n  padding-top: 0;\n  padding-bottom: 0;\n}\n[dir] .steps-transparent.vue-form-wizard .wizard-navigation .wizard-nav {\n  border: none;\n}\n[dir] .steps-transparent .wizard-tab-content {\n  box-shadow: 0px 4px 25px 0px rgba(34, 41, 47, 0.1);\n  background-color: #fff;\n}\n[dir] .steps-transparent .wizard-card-footer {\n  background-color: #fff;\n  box-shadow: 0px 4px 25px 0px rgba(34, 41, 47, 0.1);\n}\n[dir=ltr] .steps-transparent .wizard-tab-content {\n  border-top-left-radius: 0.5rem;\n  border-top-right-radius: 0.5rem;\n}\n[dir=rtl] .steps-transparent .wizard-tab-content {\n  border-top-right-radius: 0.5rem;\n  border-top-left-radius: 0.5rem;\n}\n[dir=ltr] .steps-transparent .wizard-card-footer {\n  box-shadow: -1px 16px 25px 0px rgba(34, 41, 47, 0.1);\n  border-bottom-left-radius: 0.5rem;\n  border-bottom-right-radius: 0.5rem;\n}\n[dir=rtl] .steps-transparent .wizard-card-footer {\n  box-shadow: 1px 16px 25px 0px rgba(34, 41, 47, 0.1);\n  border-bottom-right-radius: 0.5rem;\n  border-bottom-left-radius: 0.5rem;\n}\n.vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav {\n  min-width: 230px;\n}\n[dir] .vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav {\n  border-bottom: none;\n}\n[dir=ltr] .vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav {\n  border-right: 1px solid rgba(34, 41, 47, 0.08);\n  padding-right: 2.5rem;\n}\n[dir=rtl] .vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav {\n  border-left: 1px solid rgba(34, 41, 47, 0.08);\n  padding-left: 2.5rem;\n}\n[dir=ltr] .vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav li {\n  margin-right: 0;\n}\n[dir=rtl] .vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav li {\n  margin-left: 0;\n}\n.vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav li a {\n  flex-wrap: nowrap;\n}\n.vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav li a::before {\n  content: none;\n}\n.vertical.wizard-vertical.vue-form-wizard .wizard-navigation .wizard-nav li .stepTitle {\n  max-width: 200px;\n  font-size: 1rem;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n  overflow: hidden;\n}\n.vertical.wizard-vertical.vue-form-wizard .wizard-tab-content {\n  width: 100%;\n}\n[dir] .vertical.wizard-vertical.vue-form-wizard .wizard-card-footer {\n  padding-top: 0;\n}\n[dir=ltr] .vertical.wizard-vertical.vue-form-wizard .wizard-card-footer {\n  margin-left: 229px;\n  border-left: 1px solid rgba(34, 41, 47, 0.08);\n}\n[dir=rtl] .vertical.wizard-vertical.vue-form-wizard .wizard-card-footer {\n  margin-right: 229px;\n  border-right: 1px solid rgba(34, 41, 47, 0.08);\n}\n.vertical-steps.vue-form-wizard .wizard-tab-content {\n  width: 100%;\n}\n.vertical-steps.vue-form-wizard .wizard-card-footer {\n  position: relative;\n  z-index: 9;\n}\n[dir] .vertical-steps.vue-form-wizard .wizard-card-footer {\n  padding-top: 0;\n}\n[dir=ltr] .vertical-steps.vue-form-wizard .wizard-card-footer {\n  margin-left: 223px;\n}\n[dir=rtl] .vertical-steps.vue-form-wizard .wizard-card-footer {\n  margin-right: 223px;\n}\n[dir] .vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav {\n  padding-top: 30px;\n}\n[dir=ltr] .vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav {\n  padding-right: 2.5rem;\n}\n[dir=rtl] .vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav {\n  padding-left: 2.5rem;\n}\n[dir=ltr] .vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav li {\n  margin-right: 0;\n}\n[dir=rtl] .vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav li {\n  margin-left: 0;\n}\n.vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav li a {\n  flex-wrap: nowrap;\n}\n.vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav li a::before {\n  content: none;\n}\n.vertical-steps.vue-form-wizard .wizard-navigation .wizard-nav li .stepTitle {\n  max-width: 200px;\n  font-size: 1rem;\n  white-space: nowrap;\n  text-overflow: ellipsis;\n  overflow: hidden;\n}\n@media (max-width: 767.98px) {\n.vue-form-wizard .wizard-navigation {\n    flex-direction: column !important;\n}\n.vue-form-wizard .wizard-navigation .wizard-nav.wizard-nav-pills li:not(:first-child) a::before {\n    content: none;\n}\n[dir] .vue-form-wizard .wizard-navigation .wizard-nav.wizard-nav-pills li:last-child {\n    margin-bottom: 0;\n}\n[dir=ltr] .vue-form-wizard .wizard-card-footer {\n    margin-left: 0 !important;\n}\n[dir=rtl] .vue-form-wizard .wizard-card-footer {\n    margin-right: 0 !important;\n}\n.vue-form-wizard.wizard-vertical.vertical .wizard-nav.wizard-nav-pills {\n    width: 100%;\n}\n[dir] .vue-form-wizard.wizard-vertical.vertical .wizard-nav.wizard-nav-pills {\n    border-bottom: 1px solid rgba(34, 41, 47, 0.08);\n    margin-bottom: 1.9rem;\n}\n[dir=ltr] .vue-form-wizard.wizard-vertical.vertical .wizard-nav.wizard-nav-pills {\n    border-right: none;\n}\n[dir=rtl] .vue-form-wizard.wizard-vertical.vertical .wizard-nav.wizard-nav-pills {\n    border-left: none;\n}\n[dir=ltr] .vue-form-wizard.wizard-vertical.vertical .wizard-tab-content {\n    margin-left: 0;\n}\n[dir=rtl] .vue-form-wizard.wizard-vertical.vertical .wizard-tab-content {\n    margin-right: 0;\n}\n}\n@media (max-width: 991.98px) {\n.vue-form-wizard .wizard-nav.wizard-nav-pills li:not(:first-child) a::before {\n    content: none;\n}\n}\n[dir] body.dark-layout .vue-form-wizard:not(.steps-transparent) {\n  background-color: #283046;\n  box-shadow: 0px 4px 25px 0px rgba(180, 183, 189, 0.1);\n}\n[dir] body.dark-layout .vue-form-wizard:not(.steps-transparent).vertical .wizard-card-footer {\n  border-color: #3b4253 !important;\n}\n[dir] body.dark-layout .vue-form-wizard.steps-transparent .wizard-tab-content, [dir] body.dark-layout .vue-form-wizard.steps-transparent .wizard-card-footer {\n  background-color: #283046;\n}\n[dir=ltr] body.dark-layout .vue-form-wizard.steps-transparent .wizard-tab-content, [dir=ltr] body.dark-layout .vue-form-wizard.steps-transparent .wizard-card-footer {\n  box-shadow: 1px 12px 25px 0px rgba(180, 183, 189, 0.1);\n}\n[dir=rtl] body.dark-layout .vue-form-wizard.steps-transparent .wizard-tab-content, [dir=rtl] body.dark-layout .vue-form-wizard.steps-transparent .wizard-card-footer {\n  box-shadow: -1px 12px 25px 0px rgba(180, 183, 189, 0.1);\n}\n[dir] body.dark-layout .vue-form-wizard .wizard-navigation .wizard-nav {\n  border-color: #3b4253 !important;\n}\n[dir] body.dark-layout .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle {\n  background-color: rgba(186, 191, 199, 0.12);\n}\n[dir] body.dark-layout .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle.checked {\n  background-color: rgba(224, 23, 100, 0.08);\n}\nbody.dark-layout .vue-form-wizard .wizard-navigation .wizard-nav li .wizard-icon-circle.checked .wizard-icon {\n  color: #E01764;\n}\nbody.dark-layout .vue-form-wizard .wizard-navigation .wizard-nav li.active a .wizard-icon-circle .wizard-icon {\n  color: #b4b7bd;\n}\nbody.dark-layout .vue-form-wizard .wizard-nav:not(:first-child) a::before {\n  color: #b4b7bd;\n}\nbody.dark-layout .vue-form-wizard .wizard-nav:not(:first-child) li.active a::before {\n  color: #E01764;\n}\nhtml[dir=rtl] .wizard-navigation .wizard-nav li a::before {\n  transform: rotate(180deg);\n}\nhtml[dir=rtl] .vue-form-wizard .wizard-card-footer .wizard-footer-left .wizard-btn::before {\n  right: 0;\n}\nhtml[dir=rtl] .vue-form-wizard .wizard-card-footer .wizard-footer-right .wizard-btn::after {\n  left: 0;\n}\n\n/**\n    Support for SASS is deprecated as of v3.18.\n\n    The files remain here if your build is dependent on them\n    but they will not receive updates in future releases. All\n    SASS variables have been translated into CSS variables, so\n    migration should be quite simple if you'd like to move over.\n\n    In v4, these files will be removed.\n */\n.v-select {\n  position: relative;\n  font-family: inherit;\n}\n.v-select,\n.v-select * {\n  box-sizing: border-box;\n}\n\n/* KeyFrames */\n@-webkit-keyframes vSelectSpinner-ltr {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@-webkit-keyframes vSelectSpinner-rtl {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(-360deg);\n}\n}\n@keyframes vSelectSpinner-ltr {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes vSelectSpinner-rtl {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(-360deg);\n}\n}\n/* Dropdown Default Transition */\n.vs__fade-enter-active,\n.vs__fade-leave-active {\n  pointer-events: none;\n  transition: opacity 0.15s cubic-bezier(1, 0.5, 0.8, 1);\n}\n.vs__fade-enter,\n.vs__fade-leave-to {\n  opacity: 0;\n}\n\n/** Component States */\n/*\n * Disabled\n *\n * When the component is disabled, all interaction\n * should be prevented. Here we modify the bg color,\n * and change the cursor displayed on the interactive\n * components.\n */\n[dir] .vs--disabled .vs__dropdown-toggle, [dir] .vs--disabled .vs__clear, [dir] .vs--disabled .vs__search, [dir] .vs--disabled .vs__selected, [dir] .vs--disabled .vs__open-indicator {\n  cursor: not-allowed;\n  background-color: #f8f8f8;\n}\n\n/*\n *  RTL - Right to Left Support\n *\n *  Because we're using a flexbox layout, the `dir=\"rtl\"`\n *  HTML attribute does most of the work for us by\n *  rearranging the child elements visually.\n */\n.v-select[dir=rtl] .vs__actions {\n  padding: 0 3px 0 6px;\n}\n.v-select[dir=rtl] .vs__clear {\n  margin-left: 6px;\n  margin-right: 0;\n}\n.v-select[dir=rtl] .vs__deselect {\n  margin-left: 0;\n  margin-right: 2px;\n}\n.v-select[dir=rtl] .vs__dropdown-menu {\n  text-align: right;\n}\n\n/**\n    Dropdown Toggle\n\n    The dropdown toggle is the primary wrapper of the component. It\n    has two direct descendants: .vs__selected-options, and .vs__actions.\n\n    .vs__selected-options holds the .vs__selected's as well as the\n    main search input.\n\n    .vs__actions holds the clear button and dropdown toggle.\n */\n.vs__dropdown-toggle {\n  appearance: none;\n  display: flex;\n  white-space: normal;\n}\n[dir] .vs__dropdown-toggle {\n  padding: 0 0 4px 0;\n  background: none;\n  border: 1px solid #d8d6de;\n  border-radius: 0.357rem;\n}\n.vs__selected-options {\n  display: flex;\n  flex-basis: 100%;\n  flex-grow: 1;\n  flex-wrap: wrap;\n  position: relative;\n}\n[dir] .vs__selected-options {\n  padding: 0 2px;\n}\n.vs__actions {\n  display: flex;\n  align-items: center;\n}\n[dir=ltr] .vs__actions {\n  padding: 4px 6px 0 3px;\n}\n[dir=rtl] .vs__actions {\n  padding: 4px 3px 0 6px;\n}\n\n/* Dropdown Toggle States */\n[dir] .vs--searchable .vs__dropdown-toggle {\n  cursor: text;\n}\n[dir] .vs--unsearchable .vs__dropdown-toggle {\n  cursor: pointer;\n}\n[dir] .vs--open .vs__dropdown-toggle {\n  border-bottom-color: transparent;\n}\n[dir=ltr] .vs--open .vs__dropdown-toggle {\n  border-bottom-left-radius: 0;\n  border-bottom-right-radius: 0;\n}\n[dir=rtl] .vs--open .vs__dropdown-toggle {\n  border-bottom-right-radius: 0;\n  border-bottom-left-radius: 0;\n}\n.vs__open-indicator {\n  fill: rgba(60, 60, 60, 0.5);\n  transition: transform 150ms cubic-bezier(1, -0.115, 0.975, 0.855);\n}\n[dir] .vs__open-indicator {\n  transform: scale(1);\n  transition-timing-function: cubic-bezier(1, -0.115, 0.975, 0.855);\n}\n[dir=ltr] .vs--open .vs__open-indicator {\n  transform: rotate(180deg) scale(1);\n}\n[dir=rtl] .vs--open .vs__open-indicator {\n  transform: rotate(-180deg) scale(1);\n}\n.vs--loading .vs__open-indicator {\n  opacity: 0;\n}\n\n/* Clear Button */\n.vs__clear {\n  fill: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__clear {\n  padding: 0;\n  border: 0;\n  background-color: transparent;\n  cursor: pointer;\n}\n[dir=ltr] .vs__clear {\n  margin-right: 8px;\n}\n[dir=rtl] .vs__clear {\n  margin-left: 8px;\n}\n\n/* Dropdown Menu */\n.vs__dropdown-menu {\n  display: block;\n  box-sizing: border-box;\n  position: absolute;\n  top: calc(100% - 1px);\n  z-index: 1000;\n  width: 100%;\n  max-height: 350px;\n  min-width: 160px;\n  overflow-y: auto;\n  list-style: none;\n}\n[dir] .vs__dropdown-menu {\n  padding: 5px 0;\n  margin: 0;\n  box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.1);\n  border: 1px solid #d8d6de;\n  border-top-style: none;\n  border-radius: 0 0 0.357rem 0.357rem;\n  background: #fff;\n}\n[dir=ltr] .vs__dropdown-menu {\n  left: 0;\n  text-align: left;\n}\n[dir=rtl] .vs__dropdown-menu {\n  right: 0;\n  text-align: right;\n}\n[dir] .vs__no-options {\n  text-align: center;\n}\n\n/* List Items */\n.vs__dropdown-option {\n  line-height: 1.42857143;\n  /* Normalize line height */\n  display: block;\n  color: #333;\n  /* Overrides most CSS frameworks */\n  white-space: nowrap;\n}\n[dir] .vs__dropdown-option {\n  padding: 3px 20px;\n  clear: both;\n  cursor: pointer;\n}\n.vs__dropdown-option--highlight {\n  color: #E01764 !important;\n}\n[dir] .vs__dropdown-option--highlight {\n  background: rgba(224, 23, 100, 0.12);\n}\n.vs__dropdown-option--deselect {\n  color: #fff;\n}\n[dir] .vs__dropdown-option--deselect {\n  background: #fb5858;\n}\n.vs__dropdown-option--disabled {\n  color: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__dropdown-option--disabled {\n  background: inherit;\n  cursor: inherit;\n}\n\n/* Selected Tags */\n.vs__selected {\n  display: flex;\n  align-items: center;\n  color: #333;\n  line-height: 1.8;\n  z-index: 0;\n}\n[dir] .vs__selected {\n  background-color: #E01764;\n  border: 0 solid rgba(60, 60, 60, 0.26);\n  border-radius: 0.357rem;\n  margin: 4px 2px 0px 2px;\n  padding: 0 0.25em;\n}\n.vs__deselect {\n  display: inline-flex;\n  appearance: none;\n  fill: rgba(60, 60, 60, 0.5);\n}\n[dir] .vs__deselect {\n  padding: 0;\n  border: 0;\n  cursor: pointer;\n  background: none;\n  text-shadow: 0 1px 0 #fff;\n}\n[dir=ltr] .vs__deselect {\n  margin-left: 4px;\n}\n[dir=rtl] .vs__deselect {\n  margin-right: 4px;\n}\n\n/* States */\n[dir] .vs--single .vs__selected {\n  background-color: transparent;\n  border-color: transparent;\n}\n.vs--single.vs--open .vs__selected, .vs--single.vs--loading .vs__selected {\n  position: absolute;\n  opacity: 0.4;\n}\n.vs--single.vs--searching .vs__selected {\n  display: none;\n}\n\n/* Search Input */\n/**\n * Super weird bug... If this declaration is grouped\n * below, the cancel button will still appear in chrome.\n * If it's up here on it's own, it'll hide it.\n */\n.vs__search::-webkit-search-cancel-button {\n  display: none;\n}\n.vs__search::-webkit-search-decoration,\n.vs__search::-webkit-search-results-button,\n.vs__search::-webkit-search-results-decoration,\n.vs__search::-ms-clear {\n  display: none;\n}\n.vs__search,\n.vs__search:focus {\n  appearance: none;\n  line-height: 1.8;\n  font-size: 1em;\n  outline: none;\n  width: 0;\n  max-width: 100%;\n  flex-grow: 1;\n  z-index: 1;\n}\n[dir] .vs__search, [dir] .vs__search:focus {\n  border: 1px solid transparent;\n  margin: 4px 0 0 0;\n  padding: 0 7px;\n  background: none;\n  box-shadow: none;\n}\n[dir=ltr] .vs__search, [dir=ltr] .vs__search:focus {\n  border-left: none;\n}\n[dir=rtl] .vs__search, [dir=rtl] .vs__search:focus {\n  border-right: none;\n}\n.vs__search::placeholder {\n  color: #6e6b7b;\n}\n\n/**\n    States\n */\n.vs--unsearchable .vs__search {\n  opacity: 1;\n}\n[dir] .vs--unsearchable:not(.vs--disabled) .vs__search {\n  cursor: pointer;\n}\n.vs--single.vs--searching:not(.vs--open):not(.vs--loading) .vs__search {\n  opacity: 0.2;\n}\n\n/* Loading Spinner */\n.vs__spinner {\n  align-self: center;\n  opacity: 0;\n  font-size: 5px;\n  text-indent: -9999em;\n  overflow: hidden;\n  transition: opacity 0.1s;\n}\n[dir] .vs__spinner {\n  border-top: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-bottom: 0.9em solid rgba(100, 100, 100, 0.1);\n  transform: translateZ(0);\n}\n[dir=ltr] .vs__spinner {\n  border-right: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-left: 0.9em solid rgba(60, 60, 60, 0.45);\n  animation:  vSelectSpinner-ltr 1.1s infinite linear;\n}\n[dir=rtl] .vs__spinner {\n  border-left: 0.9em solid rgba(100, 100, 100, 0.1);\n  border-right: 0.9em solid rgba(60, 60, 60, 0.45);\n  animation:  vSelectSpinner-rtl 1.1s infinite linear;\n}\n.vs__spinner,\n.vs__spinner:after {\n  width: 5em;\n  height: 5em;\n}\n[dir] .vs__spinner, [dir] .vs__spinner:after {\n  border-radius: 50%;\n}\n\n/* Loading Spinner States */\n.vs--loading .vs__spinner {\n  opacity: 1;\n}\n.vs__open-indicator {\n  fill: none;\n}\n[dir] .vs__open-indicator {\n  margin-top: 0.15rem;\n}\n.vs__dropdown-toggle {\n  transition: all 0.25s ease-in-out;\n}\n[dir] .vs__dropdown-toggle {\n  padding: 0.59px 0 4px 0;\n}\n[dir=ltr] .vs--single .vs__dropdown-toggle {\n  padding-left: 6px;\n}\n[dir=rtl] .vs--single .vs__dropdown-toggle {\n  padding-right: 6px;\n}\n.vs__dropdown-option--disabled {\n  opacity: 0.5;\n}\n[dir] .vs__dropdown-option--disabled.vs__dropdown-option--selected {\n  background: #E01764 !important;\n}\n.vs__dropdown-option {\n  color: #6e6b7b;\n}\n[dir] .vs__dropdown-option, [dir] .vs__no-options {\n  padding: 7px 20px;\n}\n.vs__dropdown-option--selected {\n  background-color: #E01764;\n  color: #fff;\n  position: relative;\n}\n.vs__dropdown-option--selected::after {\n  content: \"\";\n  height: 1.1rem;\n  width: 1.1rem;\n  display: inline-block;\n  position: absolute;\n  top: 50%;\n  transform: translateY(-50%);\n  right: 20px;\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-check'%3E%3Cpolyline points='20 6 9 17 4 12'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 1.1rem;\n}\n[dir=rtl] .vs__dropdown-option--selected::after {\n  left: 20px;\n  right: unset;\n}\n.vs__dropdown-option--selected.vs__dropdown-option--highlight {\n  color: #fff !important;\n  background-color: #E01764 !important;\n}\n.vs__clear svg {\n  color: #6e6b7b;\n}\n.vs__selected {\n  color: #fff;\n}\n.v-select.vs--single .vs__selected {\n  color: #6e6b7b;\n  transition: transform 0.2s ease;\n}\n[dir] .v-select.vs--single .vs__selected {\n  margin-top: 5px;\n}\n[dir=ltr] .v-select.vs--single .vs__selected input {\n  padding-left: 0;\n}\n[dir=rtl] .v-select.vs--single .vs__selected input {\n  padding-right: 0;\n}\n[dir=ltr] .vs--single.vs--open .vs__selected {\n  transform: translateX(5px);\n}\n[dir=rtl] .vs--single.vs--open .vs__selected {\n  transform: translateX(-5px);\n}\n.vs__selected .vs__deselect {\n  color: inherit;\n}\n.v-select:not(.vs--single) .vs__selected {\n  font-size: 0.9rem;\n}\n[dir] .v-select:not(.vs--single) .vs__selected {\n  border-radius: 3px;\n  padding: 0 0.6em;\n}\n[dir=ltr] .v-select:not(.vs--single) .vs__selected {\n  margin: 5px 2px 2px 5px;\n}\n[dir=rtl] .v-select:not(.vs--single) .vs__selected {\n  margin: 5px 5px 2px 2px;\n}\n.v-select:not(.vs--single) .vs__deselect svg {\n  vertical-align: text-top;\n}\n[dir] .v-select:not(.vs--single) .vs__deselect svg {\n  transform: scale(0.8);\n}\n.vs__dropdown-menu {\n  top: calc(100% + 1rem);\n}\n[dir] .vs__dropdown-menu {\n  border: none;\n  border-radius: 6px;\n  padding: 0;\n}\n[dir] .vs--open .vs__dropdown-toggle {\n  border-color: #E01764;\n  border-bottom-color: #E01764;\n  box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n}\n[dir=ltr] .vs--open .vs__dropdown-toggle {\n  border-bottom-left-radius: 0.357rem;\n  border-bottom-right-radius: 0.357rem;\n}\n[dir=rtl] .vs--open .vs__dropdown-toggle {\n  border-bottom-right-radius: 0.357rem;\n  border-bottom-left-radius: 0.357rem;\n}\n.select-size-lg .vs__selected {\n  font-size: 1rem !important;\n}\n[dir] .select-size-lg.vs--single.vs--open .vs__selected {\n  margin-top: 6px;\n}\n.select-size-lg .vs__dropdown-toggle,\n.select-size-lg .vs__selected {\n  font-size: 1.25rem;\n}\n[dir] .select-size-lg .vs__dropdown-toggle {\n  padding: 5px;\n}\n[dir] .select-size-lg .vs__dropdown-toggle input {\n  margin-top: 0;\n}\n.select-size-lg .vs__deselect svg {\n  vertical-align: middle !important;\n}\n[dir] .select-size-lg .vs__deselect svg {\n  transform: scale(1) !important;\n}\n[dir] .select-size-sm .vs__dropdown-toggle {\n  padding-bottom: 0;\n  padding: 1px;\n}\n[dir] .select-size-sm.vs--single .vs__dropdown-toggle {\n  padding: 2px;\n}\n.select-size-sm .vs__dropdown-toggle,\n.select-size-sm .vs__selected {\n  font-size: 0.9rem;\n}\n[dir] .select-size-sm .vs__actions {\n  padding-top: 2px;\n  padding-bottom: 2px;\n}\n.select-size-sm .vs__deselect svg {\n  vertical-align: middle !important;\n}\n[dir] .select-size-sm .vs__search {\n  margin-top: 0;\n}\n.select-size-sm.v-select .vs__selected {\n  font-size: 0.75rem;\n}\n[dir] .select-size-sm.v-select .vs__selected {\n  padding: 0 0.3rem;\n}\n[dir] .select-size-sm.v-select:not(.vs--single) .vs__selected {\n  margin: 4px 5px;\n}\n[dir] .select-size-sm.v-select.vs--single .vs__selected {\n  margin-top: 1px;\n}\n[dir] .select-size-sm.vs--single.vs--open .vs__selected {\n  margin-top: 4px;\n}\n.dark-layout .vs__dropdown-toggle {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .vs__dropdown-toggle {\n  background: #283046;\n  border-color: #404656;\n}\n.dark-layout .vs__selected-options input {\n  color: #b4b7bd;\n}\n.dark-layout .vs__selected-options input::placeholder {\n  color: #676d7d;\n}\n.dark-layout .vs__actions svg {\n  fill: #404656;\n}\n[dir] .dark-layout .vs__dropdown-menu {\n  background: #283046;\n}\n.dark-layout .vs__dropdown-menu li {\n  color: #b4b7bd;\n}\n.dark-layout .v-select:not(.vs--single) .vs__selected {\n  color: #E01764;\n}\n[dir] .dark-layout .v-select:not(.vs--single) .vs__selected {\n  background-color: rgba(224, 23, 100, 0.12);\n}\n.dark-layout .v-select.vs--single .vs__selected {\n  color: #b4b7bd !important;\n}\n.dark-layout .vs--disabled .vs__dropdown-toggle,\n.dark-layout .vs--disabled .vs__clear,\n.dark-layout .vs--disabled .vs__search,\n.dark-layout .vs--disabled .vs__selected,\n.dark-layout .vs--disabled .vs__open-indicator {\n  opacity: 0.5;\n}\n[dir] .dark-layout .vs--disabled .vs__dropdown-toggle, [dir] .dark-layout .vs--disabled .vs__clear, [dir] .dark-layout .vs--disabled .vs__search, [dir] .dark-layout .vs--disabled .vs__selected, [dir] .dark-layout .vs--disabled .vs__open-indicator {\n  background-color: #283046;\n}\n#user-profile {\n  /*-------------profile header section---------*/\n  /*-------- profile info section --------*/\n}\n#user-profile .profile-header {\n  overflow: hidden;\n}\n#user-profile .profile-header .profile-img-container {\n  position: absolute;\n  bottom: -2rem;\n  z-index: 2;\n}\n[dir=ltr] #user-profile .profile-header .profile-img-container {\n  left: 2.14rem;\n}\n[dir=rtl] #user-profile .profile-header .profile-img-container {\n  right: 2.14rem;\n}\n#user-profile .profile-header .profile-img-container .profile-img {\n  height: 8.92rem;\n  width: 8.92rem;\n}\n[dir] #user-profile .profile-header .profile-img-container .profile-img {\n  border: 0.357rem solid #fff;\n  background-color: #fff;\n  border-radius: 0.428rem;\n  box-shadow: 0px 4px 25px 0px rgba(0, 0, 0, 0.1);\n}\n[dir] #user-profile .profile-header .profile-header-nav .navbar {\n  padding: 0.8rem 1rem;\n}\n#user-profile .profile-header .profile-header-nav .navbar .navbar-toggler {\n  line-height: 0;\n}\n[dir=ltr] #user-profile .profile-header .profile-header-nav .navbar .profile-tabs .nav-item i, [dir=ltr] #user-profile .profile-header .profile-header-nav .navbar .profile-tabs .nav-item svg {\n  margin-right: 0;\n}\n[dir=rtl] #user-profile .profile-header .profile-header-nav .navbar .profile-tabs .nav-item i, [dir=rtl] #user-profile .profile-header .profile-header-nav .navbar .profile-tabs .nav-item svg {\n  margin-left: 0;\n}\n#user-profile #profile-info .profile-star {\n  color: #babfc7;\n}\n#user-profile #profile-info .profile-star i.profile-favorite,\n#user-profile #profile-info .profile-star svg.profile-favorite {\n  fill: #ff9f43;\n  stroke: #ff9f43;\n}\n#user-profile #profile-info .profile-likes {\n  fill: #ea5455;\n  stroke: #ea5455;\n}\n#user-profile #profile-info .profile-polls-info .progress {\n  height: 0.42rem;\n}\n#user-profile .profile-latest-img {\n  transition: all 0.2s ease-in-out;\n}\n#user-profile .profile-latest-img:hover {\n  z-index: 10;\n}\n[dir] #user-profile .profile-latest-img:hover {\n  transform: translateY(-4px) scale(1.2);\n}\n[dir] #user-profile .profile-latest-img img {\n  margin-top: 1.28rem;\n}\n[dir] #user-profile .block-element .spinner-border {\n  border-width: 0.14rem;\n}\n@media (max-width: 991.98px) {\n#user-profile .profile-latest-img img {\n    width: 100%;\n}\n}\n@media (min-width: 768px) {\n.profile-header-nav .profile-tabs {\n    width: 100%;\n}\n[dir=ltr] .profile-header-nav .profile-tabs {\n    margin-left: 13.2rem;\n}\n[dir=rtl] .profile-header-nav .profile-tabs {\n    margin-right: 13.2rem;\n}\n}\n@media (max-width: 575.98px) {\n#user-profile .profile-header .profile-img-container .profile-img {\n    height: 100px;\n    width: 100px;\n}\n#user-profile .profile-header .profile-img-container .profile-title h2 {\n    font-size: 1.5rem;\n}\n}\n[dir] #user-profile .profile-header .profile-header-nav .navbar .navbar-toggler {\n  border: none;\n}\n#avator-img {\n  width: 9.92rem;\n}\n.document-img {\n  width: 9.92rem;\n}\n[dir] .wizard-icon-container {\n  background-color: #117439 !important;\n}\n.stepTitle {\n  color: #117439 !important;\n}\n.wizard-btn {\n  color: white !important;\n}\n[dir] .wizard-btn {\n  background-color: #117439 !important;\n  border-color: #117439 !important;\n}\n.add-new-client-header {\n  color: #28c76f;\n}\n[dir] .add-new-client-header {\n  padding: 7px 20px;\n  cursor: pointer;\n}\n.pac-container {\n  z-index: 10000 !important;\n}", ""]);

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=template&id=3d222760":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=template&id=3d222760 ***!
  \*************************************************************************************************************************************************************************************************************************/
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
    "b-card",
    [
      _c(
        "b-tabs",
        { attrs: { pills: "" } },
        [
          _c(
            "b-tab",
            {
              attrs: { active: "" },
              scopedSlots: _vm._u([
                {
                  key: "title",
                  fn: function () {
                    return [
                      _c("feather-icon", {
                        staticClass: "mr-0 mr-sm-50",
                        attrs: { icon: "UserIcon", size: "16" },
                      }),
                      _vm._v(" "),
                      _c("span", { staticClass: "d-none d-sm-inline" }, [
                        _vm._v(
                          " " +
                            _vm._s(_vm.$t("driver")) +
                            " " +
                            _vm._s(_vm.$t("details"))
                        ),
                      ]),
                      _vm._v(" "),
                      _c("feather-icon", {
                        staticClass: "mr-0 mr-sm-50",
                        attrs: { icon: "ChevronRightIcon", size: "16" },
                      }),
                    ]
                  },
                  proxy: true,
                },
              ]),
            },
            [
              _vm._v(" "),
              _c(
                "div",
                [
                  _c("div", { staticClass: "d-flex" }, [
                    _c("h4", { staticClass: "mb-0 ml-50" }),
                  ]),
                  _vm._v(" "),
                  _c(
                    "b-form",
                    {
                      on: {
                        submit: function ($event) {
                          $event.preventDefault()
                          return _vm.validationForm($event)
                        },
                      },
                    },
                    [
                      _c("b-form-input", {
                        attrs: { id: "res_id", type: "text", hidden: "hidden" },
                        model: {
                          value: _vm.driver.id,
                          callback: function ($$v) {
                            _vm.$set(_vm.driver, "id", $$v)
                          },
                          expression: "driver.id",
                        },
                      }),
                      _vm._v(" "),
                      _c(
                        "b-row",
                        [
                          _vm.setting.lang.value == 1
                            ? _c(
                                "b-col",
                                { attrs: { md: "12" } },
                                [
                                  _c(
                                    "b-tabs",
                                    [
                                      _c("b-tab", {
                                        attrs: { active: "" },
                                        on: {
                                          click: function ($event) {
                                            return _vm.onTabChange(1)
                                          },
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "title",
                                              fn: function () {
                                                return [
                                                  _c("i", {
                                                    staticClass:
                                                      "fa fa-language",
                                                    attrs: {
                                                      "aria-hidden": "true",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "span",
                                                    {
                                                      staticClass:
                                                        "d-none d-sm-inline",
                                                    },
                                                    [
                                                      _vm._v(
                                                        _vm._s(
                                                          _vm.setting.primary
                                                            .value
                                                        )
                                                      ),
                                                    ]
                                                  ),
                                                ]
                                              },
                                              proxy: true,
                                            },
                                          ],
                                          null,
                                          false,
                                          1482655017
                                        ),
                                      }),
                                      _vm._v(" "),
                                      _c("b-tab", {
                                        on: {
                                          click: function ($event) {
                                            return _vm.onTabChange(2)
                                          },
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "title",
                                              fn: function () {
                                                return [
                                                  _c("i", {
                                                    staticClass:
                                                      "fa fa-language",
                                                    attrs: {
                                                      "aria-hidden": "true",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "span",
                                                    {
                                                      staticClass:
                                                        "d-none d-sm-inline",
                                                    },
                                                    [
                                                      _vm._v(
                                                        _vm._s(
                                                          _vm.setting.secondary
                                                            .value
                                                        )
                                                      ),
                                                    ]
                                                  ),
                                                ]
                                              },
                                              proxy: true,
                                            },
                                          ],
                                          null,
                                          false,
                                          4145262367
                                        ),
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "b-col",
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("Store") +
                                      " " +
                                      "(" +
                                      _vm.$t("optional") +
                                      ")",
                                  },
                                },
                                [
                                  _c("v-select", {
                                    attrs: {
                                      options: _vm.rows,
                                      label: "restaurant_name",
                                      reduce: function (sel) {
                                        return sel.id
                                      },
                                      placeholder: _vm.$t("Select Store"),
                                      dir: _vm.$store.state.appConfig.isRTL
                                        ? "rtl"
                                        : "ltr",
                                    },
                                    model: {
                                      value: _vm.driver.restaurant_id,
                                      callback: function ($$v) {
                                        _vm.$set(
                                          _vm.driver,
                                          "restaurant_id",
                                          $$v
                                        )
                                      },
                                      expression: "driver.restaurant_id",
                                    },
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("vehicle") +
                                      " " +
                                      _vm.$t("name") +
                                      "*",
                                  },
                                },
                                [
                                  _c("v-select", {
                                    attrs: {
                                      options: _vm.vehicles,
                                      label: "vehicle_name",
                                      reduce: function (sel) {
                                        return sel.id
                                      },
                                      placeholder:
                                        _vm.$t("select") +
                                        " " +
                                        _vm.$t("vehicle"),
                                      dir: _vm.$store.state.appConfig.isRTL
                                        ? "rtl"
                                        : "ltr",
                                    },
                                    model: {
                                      value: _vm.driver.vehicle_name,
                                      callback: function ($$v) {
                                        _vm.$set(
                                          _vm.driver,
                                          "vehicle_name",
                                          $$v
                                        )
                                      },
                                      expression: "driver.vehicle_name",
                                    },
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                { attrs: { label: _vm.$t("email") + "*" } },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "email",
                                      rules: "required|email",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                id: "email",
                                                type: "email",
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  "john.doe@email.com",
                                              },
                                              model: {
                                                value: _vm.driver.email,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver,
                                                    "email",
                                                    $$v
                                                  )
                                                },
                                                expression: "driver.email",
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("work") + " " + _vm.$t("city"),
                                  },
                                },
                                [
                                  _c("v-select", {
                                    attrs: {
                                      options: _vm.cities,
                                      label: "city",
                                      reduce: function (sel) {
                                        return sel.id
                                      },
                                      placeholder:
                                        _vm.$t("select") + " " + _vm.$t("city"),
                                      dir: _vm.$store.state.appConfig.isRTL
                                        ? "rtl"
                                        : "ltr",
                                    },
                                    on: {
                                      input: function ($event) {
                                        return _vm.onChange($event)
                                      },
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "list-header",
                                        fn: function () {
                                          return [
                                            _c(
                                              "li",
                                              {
                                                staticClass:
                                                  "add-new-client-header d-flex align-items-center my-50",
                                                on: {
                                                  click: _vm.open_citymodal,
                                                },
                                              },
                                              [
                                                _c("feather-icon", {
                                                  attrs: {
                                                    icon: "PlusIcon",
                                                    size: "16",
                                                  },
                                                }),
                                                _vm._v(" "),
                                                _c(
                                                  "span",
                                                  {
                                                    staticClass:
                                                      "align-middle ml-25",
                                                  },
                                                  [
                                                    _vm._v(
                                                      _vm._s(
                                                        _vm.$t("Add New City")
                                                      )
                                                    ),
                                                  ]
                                                ),
                                              ],
                                              1
                                            ),
                                          ]
                                        },
                                        proxy: true,
                                      },
                                    ]),
                                    model: {
                                      value: _vm.driver.city,
                                      callback: function ($$v) {
                                        _vm.$set(_vm.driver, "city", $$v)
                                      },
                                      expression: "driver.city",
                                    },
                                  }),
                                ],
                                1
                              ),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          !_vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label:
                                          _vm.$t("driver") +
                                          " " +
                                          _vm.$t("name") +
                                          "*",
                                      },
                                    },
                                    [
                                      _c("validation-provider", {
                                        attrs: {
                                          name: "drivername",
                                          "custom-messages": {
                                            required: _vm.$t(
                                              "The Drivername field is required"
                                            ),
                                          },
                                          rules: "required",
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "default",
                                              fn: function (ref) {
                                                var errors = ref.errors
                                                return [
                                                  _c("b-form-input", {
                                                    attrs: {
                                                      id: "name",
                                                      type: "text",
                                                      state:
                                                        errors.length > 0
                                                          ? false
                                                          : null,
                                                      placeholder:
                                                        _vm.$t("name"),
                                                    },
                                                    model: {
                                                      value: _vm.driver.name,
                                                      callback: function ($$v) {
                                                        _vm.$set(
                                                          _vm.driver,
                                                          "name",
                                                          $$v
                                                        )
                                                      },
                                                      expression: "driver.name",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "small",
                                                    {
                                                      staticClass:
                                                        "text-danger",
                                                    },
                                                    [_vm._v(_vm._s(errors[0]))]
                                                  ),
                                                ]
                                              },
                                            },
                                          ],
                                          null,
                                          false,
                                          3475961017
                                        ),
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label:
                                          _vm.$t("driver") +
                                          " " +
                                          _vm.$t("name") +
                                          "*",
                                      },
                                    },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          id: "name",
                                          type: "text",
                                          placeholder: _vm.$t("name"),
                                        },
                                        model: {
                                          value: _vm.driver.second_driver_name,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "second_driver_name",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "driver.second_driver_name",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "b-col",
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("phone") +
                                      " " +
                                      _vm.$t("no") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "number",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("VuePhoneNumberInput", {
                                              attrs: {
                                                id: "phone1",
                                                "no-example": "",
                                                state: errors[0]
                                                  ? false
                                                  : _vm.valid
                                                  ? true
                                                  : null,
                                                "default-country-code":
                                                  _vm.driver
                                                    .country_calling_code,
                                              },
                                              on: {
                                                update: _vm.updatePhoneNumber,
                                              },
                                              model: {
                                                value: _vm.driver.phone,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver,
                                                    "phone",
                                                    $$v
                                                  )
                                                },
                                                expression: "driver.phone",
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("work") +
                                      " " +
                                      _vm.$t("area") +
                                      "(" +
                                      _vm.$t("optional") +
                                      ")",
                                  },
                                },
                                [
                                  _c("v-select", {
                                    attrs: {
                                      options: _vm.areas,
                                      label: "area",
                                      reduce: function (sel) {
                                        return sel.id
                                      },
                                      placeholder:
                                        _vm.$t("select") + " " + _vm.$t("area"),
                                      dir: _vm.$store.state.appConfig.isRTL
                                        ? "rtl"
                                        : "ltr",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "list-header",
                                        fn: function () {
                                          return [
                                            _c(
                                              "li",
                                              {
                                                staticClass:
                                                  "add-new-client-header d-flex align-items-center my-50",
                                                on: {
                                                  click: _vm.open_areamodal,
                                                },
                                              },
                                              [
                                                _c("feather-icon", {
                                                  attrs: {
                                                    icon: "PlusIcon",
                                                    size: "16",
                                                  },
                                                }),
                                                _vm._v(" "),
                                                _c(
                                                  "span",
                                                  {
                                                    staticClass:
                                                      "align-middle ml-25",
                                                  },
                                                  [
                                                    _vm._v(
                                                      _vm._s(
                                                        _vm.$t("Add New Area")
                                                      )
                                                    ),
                                                  ]
                                                ),
                                              ],
                                              1
                                            ),
                                          ]
                                        },
                                        proxy: true,
                                      },
                                    ]),
                                    model: {
                                      value: _vm.driver.area,
                                      callback: function ($$v) {
                                        _vm.$set(_vm.driver, "area", $$v)
                                      },
                                      expression: "driver.area",
                                    },
                                  }),
                                ],
                                1
                              ),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          !_vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label:
                                          _vm.$t("address") +
                                          " " +
                                          _vm.$t("line") +
                                          "1 " +
                                          "*",
                                      },
                                    },
                                    [
                                      _c("validation-provider", {
                                        attrs: {
                                          name: "Address",
                                          "custom-messages": {
                                            required: _vm.$t(
                                              "The Address field is required"
                                            ),
                                          },
                                          rules: "required",
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "default",
                                              fn: function (ref) {
                                                var errors = ref.errors
                                                return [
                                                  _c("b-form-input", {
                                                    attrs: {
                                                      id: "name",
                                                      type: "text",
                                                      state:
                                                        errors.length > 0
                                                          ? false
                                                          : null,
                                                      placeholder:
                                                        _vm.$t("enter") +
                                                        " " +
                                                        _vm.$t("address"),
                                                    },
                                                    model: {
                                                      value:
                                                        _vm.driver
                                                          .address_line_1,
                                                      callback: function ($$v) {
                                                        _vm.$set(
                                                          _vm.driver,
                                                          "address_line_1",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "driver.address_line_1",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "small",
                                                    {
                                                      staticClass:
                                                        "text-danger",
                                                    },
                                                    [_vm._v(_vm._s(errors[0]))]
                                                  ),
                                                ]
                                              },
                                            },
                                          ],
                                          null,
                                          false,
                                          1191691807
                                        ),
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label:
                                          _vm.$t("address") +
                                          " " +
                                          _vm.$t("line") +
                                          "1 " +
                                          "*",
                                      },
                                    },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          id: "name",
                                          type: "text",
                                          placeholder:
                                            _vm.$t("enter") +
                                            " " +
                                            _vm.$t("address"),
                                        },
                                        model: {
                                          value:
                                            _vm.driver.second_address_line_1,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "second_address_line_1",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "driver.second_address_line_1",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          !_vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label:
                                          _vm.$t("address") +
                                          " " +
                                          _vm.$t("line") +
                                          "2",
                                      },
                                    },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          id: "name",
                                          type: "text",
                                          placeholder:
                                            _vm.$t("enter") +
                                            " " +
                                            _vm.$t("address"),
                                        },
                                        model: {
                                          value: _vm.driver.address_line_2,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "address_line_2",
                                              $$v
                                            )
                                          },
                                          expression: "driver.address_line_2",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label:
                                          _vm.$t("address") +
                                          " " +
                                          _vm.$t("line") +
                                          "2",
                                      },
                                    },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          id: "name",
                                          type: "text",
                                          placeholder:
                                            _vm.$t("enter") +
                                            " " +
                                            _vm.$t("address"),
                                        },
                                        model: {
                                          value:
                                            _vm.driver.second_address_line_2,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "second_address_line_2",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "driver.second_address_line_2",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          !_vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    { attrs: { label: _vm.$t("city") + "*" } },
                                    [
                                      _c("validation-provider", {
                                        attrs: {
                                          name: "City",
                                          "custom-messages": {
                                            required: _vm.$t(
                                              "The City field is required"
                                            ),
                                          },
                                          rules: "required",
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "default",
                                              fn: function (ref) {
                                                var errors = ref.errors
                                                return [
                                                  _c("b-form-input", {
                                                    attrs: {
                                                      type: "text",
                                                      state:
                                                        errors.length > 0
                                                          ? false
                                                          : null,
                                                      placeholder:
                                                        _vm.$t("enter") +
                                                        " " +
                                                        _vm.$t("city"),
                                                    },
                                                    model: {
                                                      value:
                                                        _vm.driver.address_city,
                                                      callback: function ($$v) {
                                                        _vm.$set(
                                                          _vm.driver,
                                                          "address_city",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "driver.address_city",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "small",
                                                    {
                                                      staticClass:
                                                        "text-danger",
                                                    },
                                                    [_vm._v(_vm._s(errors[0]))]
                                                  ),
                                                ]
                                              },
                                            },
                                          ],
                                          null,
                                          false,
                                          2478897845
                                        ),
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    { attrs: { label: _vm.$t("city") + "*" } },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          type: "text",
                                          placeholder:
                                            _vm.$t("enter") +
                                            " " +
                                            _vm.$t("city"),
                                        },
                                        model: {
                                          value: _vm.driver.second_address_city,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "second_address_city",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "driver.second_address_city",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          !_vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label: _vm.$t("state/province") + "*",
                                      },
                                    },
                                    [
                                      _c("validation-provider", {
                                        attrs: {
                                          name: "State",
                                          "custom-messages": {
                                            required: _vm.$t(
                                              "The State field is required"
                                            ),
                                          },
                                          rules: "required",
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "default",
                                              fn: function (ref) {
                                                var errors = ref.errors
                                                return [
                                                  _c("b-form-input", {
                                                    attrs: {
                                                      type: "text",
                                                      state:
                                                        errors.length > 0
                                                          ? false
                                                          : null,
                                                      placeholder:
                                                        _vm.$t("enter") +
                                                        " " +
                                                        _vm.$t(
                                                          "state/province"
                                                        ),
                                                    },
                                                    model: {
                                                      value:
                                                        _vm.driver
                                                          .state_province,
                                                      callback: function ($$v) {
                                                        _vm.$set(
                                                          _vm.driver,
                                                          "state_province",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "driver.state_province",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "small",
                                                    {
                                                      staticClass:
                                                        "text-danger",
                                                    },
                                                    [_vm._v(_vm._s(errors[0]))]
                                                  ),
                                                ]
                                              },
                                            },
                                          ],
                                          null,
                                          false,
                                          267881708
                                        ),
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: {
                                        label: _vm.$t("state/province") + "*",
                                      },
                                    },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          type: "text",
                                          placeholder:
                                            _vm.$t("enter") +
                                            " " +
                                            _vm.$t("state/province"),
                                        },
                                        model: {
                                          value:
                                            _vm.driver.second_state_province,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "second_state_province",
                                              $$v
                                            )
                                          },
                                          expression:
                                            "driver.second_state_province",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          !_vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: { label: _vm.$t("country") + "*" },
                                    },
                                    [
                                      _c("validation-provider", {
                                        attrs: {
                                          name: "Country",
                                          "custom-messages": {
                                            required: _vm.$t(
                                              "The Country field is required"
                                            ),
                                          },
                                          rules: "required",
                                        },
                                        scopedSlots: _vm._u(
                                          [
                                            {
                                              key: "default",
                                              fn: function (ref) {
                                                var errors = ref.errors
                                                return [
                                                  _c("b-form-input", {
                                                    attrs: {
                                                      type: "text",
                                                      state:
                                                        errors.length > 0
                                                          ? false
                                                          : null,
                                                      placeholder:
                                                        _vm.$t("enter") +
                                                        " " +
                                                        _vm.$t("country"),
                                                    },
                                                    model: {
                                                      value: _vm.driver.country,
                                                      callback: function ($$v) {
                                                        _vm.$set(
                                                          _vm.driver,
                                                          "country",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "driver.country",
                                                    },
                                                  }),
                                                  _vm._v(" "),
                                                  _c(
                                                    "small",
                                                    {
                                                      staticClass:
                                                        "text-danger",
                                                    },
                                                    [_vm._v(_vm._s(errors[0]))]
                                                  ),
                                                ]
                                              },
                                            },
                                          ],
                                          null,
                                          false,
                                          413431644
                                        ),
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _vm.isHidden
                            ? _c(
                                "b-col",
                                { attrs: { md: "4" } },
                                [
                                  _c(
                                    "b-form-group",
                                    {
                                      attrs: { label: _vm.$t("country") + "*" },
                                    },
                                    [
                                      _c("b-form-input", {
                                        attrs: {
                                          type: "text",
                                          placeholder:
                                            _vm.$t("enter") +
                                            " " +
                                            _vm.$t("country"),
                                        },
                                        model: {
                                          value: _vm.driver.second_country,
                                          callback: function ($$v) {
                                            _vm.$set(
                                              _vm.driver,
                                              "second_country",
                                              $$v
                                            )
                                          },
                                          expression: "driver.second_country",
                                        },
                                      }),
                                    ],
                                    1
                                  ),
                                ],
                                1
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "b-col",
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                { attrs: { label: _vm.$t("avatar") + "*" } },
                                [
                                  _c("b-form-file", {
                                    attrs: {
                                      "drop-placeholder": "Drag your image",
                                      accept: ".jpg, .png, .jpeg",
                                    },
                                    on: { change: _vm.onFileChange1 },
                                    model: {
                                      value: _vm.driver.profile_pic,
                                      callback: function ($$v) {
                                        _vm.$set(_vm.driver, "profile_pic", $$v)
                                      },
                                      expression: "driver.profile_pic",
                                    },
                                  }),
                                  _vm._v(" "),
                                  _c(
                                    "b-form-group",
                                    [
                                      _vm.driver.profile_pic
                                        ? _c("b-img", {
                                            attrs: {
                                              src: _vm.url1
                                                ? _vm.url1
                                                : _vm.driver.profile_pic,
                                              rounded: "",
                                              fluid: "",
                                              id: "avator-img",
                                              alt: "category photo",
                                            },
                                          })
                                        : _c("b-img", {
                                            attrs: {
                                              src: "/no_image.png",
                                              id: "avator-img",
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
                          ),
                          _vm._v(" "),
                          _c(
                            "b-col",
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("zip") + "" + _vm.$t("code") + "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Zip Code",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                type: "text",
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("zip") +
                                                  " " +
                                                  _vm.$t("code"),
                                              },
                                              model: {
                                                value: _vm.driver.zip_code,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver,
                                                    "zip_code",
                                                    $$v
                                                  )
                                                },
                                                expression: "driver.zip_code",
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                { attrs: { label: _vm.$t("Gender") } },
                                [
                                  _c("b-form-radio-group", {
                                    attrs: { options: _vm.online_radio },
                                    model: {
                                      value: _vm.driver.gender,
                                      callback: function ($$v) {
                                        _vm.$set(_vm.driver, "gender", $$v)
                                      },
                                      expression: "driver.gender",
                                    },
                                  }),
                                ],
                                1
                              ),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c("b-col", { attrs: { md: "12" } }, [
                            _c("h5", { staticClass: "mb-0" }, [
                              _vm._v(
                                "\n                  " +
                                  _vm._s(_vm.$t("security")) +
                                  " " +
                                  _vm._s(_vm.$t("settings")) +
                                  "\n                "
                              ),
                            ]),
                          ]),
                          _vm._v(" "),
                          _c(
                            "b-col",
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                { attrs: { label: _vm.$t("password") } },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Password",
                                      vid: "Password",
                                      rules: "required|password",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                id: "password",
                                                type: "text",
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("password"),
                                              },
                                              model: {
                                                value: _vm.driver.password,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver,
                                                    "password",
                                                    $$v
                                                  )
                                                },
                                                expression: "driver.password",
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("confirm") +
                                      " " +
                                      _vm.$t("password"),
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Password Confirm",
                                      rules: "required|confirmed:Password",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                id: "c-password",
                                                type: "text",
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  _vm.$t("re-type") +
                                                  " " +
                                                  _vm.$t("password"),
                                              },
                                              model: {
                                                value: _vm.driver.cpassword,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver,
                                                    "cpassword",
                                                    $$v
                                                  )
                                                },
                                                expression: "driver.cpassword",
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
                            { attrs: { md: "4" } },
                            [
                              _c(
                                "b-form-group",
                                { attrs: { label: _vm.$t("status") + "*" } },
                                [
                                  _c("v-select", {
                                    attrs: {
                                      options: _vm.options,
                                      label: "text",
                                      reduce: function (sel) {
                                        return sel.value
                                      },
                                      placeholder:
                                        _vm.$t("select") +
                                        " " +
                                        _vm.$t("status"),
                                      dir: _vm.$store.state.appConfig.isRTL
                                        ? "rtl"
                                        : "ltr",
                                    },
                                    model: {
                                      value: _vm.driver.status,
                                      callback: function ($$v) {
                                        _vm.$set(_vm.driver, "status", $$v)
                                      },
                                      expression: "driver.status",
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
                      ),
                      _vm._v(" "),
                      _c(
                        "b-row",
                        { staticClass: "mt-2" },
                        [
                          _c(
                            "b-col",
                            { staticStyle: { "text-align": "end" } },
                            [
                              _c(
                                "b-button",
                                {
                                  staticClass: "mb-1 mb-sm-0 mr-0 mr-sm-1",
                                  attrs: { variant: "primary", type: "submit" },
                                },
                                [
                                  _vm._v(
                                    "\n                  " +
                                      _vm._s(_vm.$t("save")) +
                                      "\n                "
                                  ),
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
            ]
          ),
          _vm._v(" "),
          _c(
            "b-tab",
            {
              scopedSlots: _vm._u([
                {
                  key: "title",
                  fn: function () {
                    return [
                      _c("feather-icon", {
                        staticClass: "mr-0 mr-sm-50",
                        attrs: { icon: "ClockIcon", size: "16" },
                      }),
                      _vm._v(" "),
                      _c("span", { staticClass: "d-none d-sm-inline" }, [
                        _vm._v(
                          _vm._s(_vm.$t("document")) +
                            " " +
                            _vm._s(_vm.$t("settings"))
                        ),
                      ]),
                      _vm._v(" "),
                      _c("feather-icon", {
                        staticClass: "mr-0 mr-sm-50",
                        attrs: { icon: "ChevronRightIcon", size: "16" },
                      }),
                    ]
                  },
                  proxy: true,
                },
              ]),
            },
            [
              _vm._v(" "),
              _c(
                "div",
                [
                  _c("div", { staticClass: "d-flex" }, [
                    _c("h4", { staticClass: "mb-0 ml-50" }),
                  ]),
                  _vm._v(" "),
                  _c(
                    "b-form",
                    {
                      on: {
                        submit: function ($event) {
                          $event.preventDefault()
                          return _vm.validationFormDocument($event)
                        },
                      },
                    },
                    [
                      _c("input", {
                        attrs: {
                          type: "hidden",
                          id: "driver_id",
                          name: "driver_id",
                        },
                      }),
                      _vm._v(" "),
                      _c(
                        "b-row",
                        [
                          _c(
                            "b-col",
                            { staticClass: "mb-2", attrs: { cols: "12" } },
                            [
                              _c("h5", { staticClass: "mb-0" }, [
                                _vm._v(
                                  "\n                  " +
                                    _vm._s(_vm.$t("document")) +
                                    " " +
                                    _vm._s(_vm.$t("settings")) +
                                    "\n                "
                                ),
                              ]),
                              _vm._v(" "),
                              _c("small", { staticClass: "text-muted" }, [
                                _vm._v(
                                  _vm._s(_vm.$t("add")) +
                                    " " +
                                    _vm._s(_vm.$t("document")) +
                                    " " +
                                    _vm._s(_vm.$t("details"))
                                ),
                              ]),
                            ]
                          ),
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm._l(_vm.document_view, function (item) {
                        return _c(
                          "div",
                          [
                            item.document_for == 1
                              ? _c(
                                  "b-row",
                                  [
                                    _c(
                                      "b-col",
                                      { attrs: { md: "6" } },
                                      [
                                        _c(
                                          "b-form-group",
                                          {
                                            attrs: {
                                              label: item.document_name,
                                            },
                                          },
                                          [
                                            _c("b-form-file", {
                                              attrs: {
                                                id: "image" + item.id,
                                                accept: ".jpg, .png, .jpeg",
                                              },
                                              on: {
                                                change: function ($event) {
                                                  return _vm.onFileChanges(
                                                    item.id
                                                  )
                                                },
                                              },
                                            }),
                                            _vm._v(" "),
                                            _c("b-form-input", {
                                              key: item.id,
                                              attrs: {
                                                id: "doc_id" + item.id,
                                                hidden: "hidden",
                                                value: item.id,
                                              },
                                            }),
                                          ],
                                          1
                                        ),
                                        _vm._v(" "),
                                        _c("b-form-group", [
                                          _c("img", {
                                            staticClass: "document-img",
                                            staticStyle: { cursor: "pointer" },
                                            attrs: {
                                              rounded: "",
                                              fluid: "",
                                              id: "document_img" + item.id,
                                              src: "/no_image.png",
                                              alt: "category photo",
                                            },
                                            on: {
                                              click: function ($event) {
                                                return _vm.open_image(item.id)
                                              },
                                            },
                                          }),
                                        ]),
                                      ],
                                      1
                                    ),
                                    _vm._v(" "),
                                    item.expiry_date_needed == 1
                                      ? _c(
                                          "b-col",
                                          { attrs: { md: "6" } },
                                          [
                                            _c(
                                              "b-form-group",
                                              {
                                                attrs: {
                                                  label: item.document_name,
                                                },
                                              },
                                              [
                                                _c("b-form-datepicker", {
                                                  attrs: {
                                                    id:
                                                      "license_expirys" +
                                                      item.id,
                                                    locale: "en",
                                                    min: new Date(),
                                                  },
                                                  model: {
                                                    value:
                                                      _vm.license_expiry[
                                                        item.id
                                                      ],
                                                    callback: function ($$v) {
                                                      _vm.$set(
                                                        _vm.license_expiry,
                                                        item.id,
                                                        $$v
                                                      )
                                                    },
                                                    expression:
                                                      "license_expiry[item.id]",
                                                  },
                                                }),
                                                _vm._v(" "),
                                                _c("b-form-input", {
                                                  key: item.id,
                                                  attrs: {
                                                    id:
                                                      "license_expiry" +
                                                      item.id,
                                                    hidden: "hidden",
                                                    value:
                                                      _vm.license_expiry[
                                                        item.id
                                                      ],
                                                  },
                                                }),
                                                _vm._v(" "),
                                                _c("b-form-input", {
                                                  key: item.id,
                                                  attrs: {
                                                    id: "exp_id" + item.id,
                                                    hidden: "hidden",
                                                    value: item.id,
                                                  },
                                                }),
                                              ],
                                              1
                                            ),
                                          ],
                                          1
                                        )
                                      : _vm._e(),
                                  ],
                                  1
                                )
                              : _vm._e(),
                          ],
                          1
                        )
                      }),
                      _vm._v(" "),
                      _c(
                        "b-row",
                        { staticClass: "mt-2" },
                        [
                          _c(
                            "b-col",
                            { staticStyle: { "text-align": "end" } },
                            [
                              _c(
                                "b-button",
                                {
                                  staticClass: "mb-1 mb-sm-0 mr-0 mr-sm-1",
                                  attrs: { variant: "primary", type: "submit" },
                                },
                                [
                                  _vm._v(
                                    "\n                  " +
                                      _vm._s(_vm.$t("save")) +
                                      "\n                "
                                  ),
                                ]
                              ),
                            ],
                            1
                          ),
                        ],
                        1
                      ),
                    ],
                    2
                  ),
                ],
                1
              ),
            ]
          ),
          _vm._v(" "),
          _c(
            "b-tab",
            {
              scopedSlots: _vm._u([
                {
                  key: "title",
                  fn: function () {
                    return [
                      _c("feather-icon", {
                        staticClass: "mr-0 mr-sm-50",
                        attrs: { icon: "FileTextIcon", size: "16" },
                      }),
                      _vm._v(" "),
                      _c("span", { staticClass: "d-none d-sm-inline" }, [
                        _vm._v(
                          " " +
                            _vm._s(_vm.$t("bank")) +
                            " " +
                            _vm._s(_vm.$t("details"))
                        ),
                      ]),
                      _vm._v(" "),
                      _c("feather-icon", {
                        staticClass: "mr-0 mr-sm-50",
                        attrs: { icon: "ChevronRightIcon", size: "16" },
                      }),
                    ]
                  },
                  proxy: true,
                },
              ]),
            },
            [
              _vm._v(" "),
              _c(
                "div",
                [
                  _c("div", { staticClass: "d-flex" }, [
                    _c("h4", { staticClass: "mb-0 ml-50" }),
                  ]),
                  _vm._v(" "),
                  _c(
                    "b-form",
                    {
                      on: {
                        submit: function ($event) {
                          $event.preventDefault()
                          return _vm.validationFormBank($event)
                        },
                      },
                    },
                    [
                      _c("b-form-input", {
                        attrs: { id: "res_id", type: "text", hidden: "hidden" },
                        model: {
                          value: _vm.driver_tab.id,
                          callback: function ($$v) {
                            _vm.$set(_vm.driver_tab, "id", $$v)
                          },
                          expression: "driver_tab.id",
                        },
                      }),
                      _vm._v(" "),
                      _c(
                        "b-row",
                        [
                          _c(
                            "b-col",
                            { staticClass: "mb-2", attrs: { cols: "12" } },
                            [
                              _c("h5", { staticClass: "mb-0" }, [
                                _vm._v(
                                  "\n                  " +
                                    _vm._s(_vm.$t("bank")) +
                                    " " +
                                    _vm._s(_vm.$t("details")) +
                                    "\n                "
                                ),
                              ]),
                              _vm._v(" "),
                              _c("small", { staticClass: "text-muted" }, [
                                _vm._v(
                                  _vm._s(_vm.$t("add")) +
                                    " " +
                                    _vm._s(_vm.$t("bank")) +
                                    " " +
                                    _vm._s(_vm.$t("details"))
                                ),
                              ]),
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "b-col",
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("account") +
                                      " " +
                                      _vm.$t("name") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Account Name",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("account") +
                                                  " " +
                                                  _vm.$t("name"),
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                              },
                                              model: {
                                                value:
                                                  _vm.driver_tab.account_name,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver_tab,
                                                    "account_name",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "driver_tab.account_name",
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("bank") +
                                      " " +
                                      _vm.$t("name") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Bank Name",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("bank") +
                                                  " " +
                                                  _vm.$t("name"),
                                              },
                                              model: {
                                                value: _vm.driver_tab.bank_name,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver_tab,
                                                    "bank_name",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "driver_tab.bank_name",
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("account") +
                                      " " +
                                      _vm.$t("address") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Account Address",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("account") +
                                                  " " +
                                                  _vm.$t("address"),
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                              },
                                              model: {
                                                value:
                                                  _vm.driver_tab
                                                    .account_address,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver_tab,
                                                    "account_address",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "driver_tab.account_address",
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("branch") +
                                      " " +
                                      _vm.$t("name") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Branch Name",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("branch") +
                                                  " " +
                                                  _vm.$t("name"),
                                              },
                                              model: {
                                                value:
                                                  _vm.driver_tab.branch_name,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver_tab,
                                                    "branch_name",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "driver_tab.branch_name",
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("account") +
                                      " " +
                                      _vm.$t("number") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Account Number",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("account") +
                                                  " " +
                                                  _vm.$t("number"),
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                              },
                                              model: {
                                                value:
                                                  _vm.driver_tab.account_no,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver_tab,
                                                    "account_no",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "driver_tab.account_no",
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("branch") +
                                      " " +
                                      _vm.$t("address") +
                                      "*",
                                  },
                                },
                                [
                                  _c("validation-provider", {
                                    attrs: {
                                      name: "Branch Address",
                                      rules: "required",
                                    },
                                    scopedSlots: _vm._u([
                                      {
                                        key: "default",
                                        fn: function (ref) {
                                          var errors = ref.errors
                                          return [
                                            _c("b-form-input", {
                                              attrs: {
                                                state:
                                                  errors.length > 0
                                                    ? false
                                                    : null,
                                                placeholder:
                                                  _vm.$t("enter") +
                                                  " " +
                                                  _vm.$t("branch") +
                                                  " " +
                                                  _vm.$t("address"),
                                              },
                                              model: {
                                                value:
                                                  _vm.driver_tab.branch_address,
                                                callback: function ($$v) {
                                                  _vm.$set(
                                                    _vm.driver_tab,
                                                    "branch_address",
                                                    $$v
                                                  )
                                                },
                                                expression:
                                                  "driver_tab.branch_address",
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("swift") + " " + _vm.$t("code"),
                                  },
                                },
                                [
                                  _c("b-form-input", {
                                    attrs: {
                                      placeholder:
                                        _vm.$t("enter") +
                                        " " +
                                        _vm.$t("swift") +
                                        " " +
                                        _vm.$t("code"),
                                    },
                                    model: {
                                      value: _vm.driver_tab.swift_code,
                                      callback: function ($$v) {
                                        _vm.$set(
                                          _vm.driver_tab,
                                          "swift_code",
                                          $$v
                                        )
                                      },
                                      expression: "driver_tab.swift_code",
                                    },
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
                            { attrs: { md: "6" } },
                            [
                              _c(
                                "b-form-group",
                                {
                                  attrs: {
                                    label:
                                      _vm.$t("routing") +
                                      " " +
                                      _vm.$t("number"),
                                  },
                                },
                                [
                                  _c("b-form-input", {
                                    attrs: {
                                      placeholder:
                                        _vm.$t("enter") +
                                        " " +
                                        _vm.$t("routing") +
                                        " " +
                                        _vm.$t("number"),
                                    },
                                    model: {
                                      value: _vm.driver_tab.routing_no,
                                      callback: function ($$v) {
                                        _vm.$set(
                                          _vm.driver_tab,
                                          "routing_no",
                                          $$v
                                        )
                                      },
                                      expression: "driver_tab.routing_no",
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
                      ),
                      _vm._v(" "),
                      _c(
                        "b-row",
                        { staticClass: "mt-2" },
                        [
                          _c(
                            "b-col",
                            { staticStyle: { "text-align": "end" } },
                            [
                              _c(
                                "b-button",
                                {
                                  staticClass: "mb-1 mb-sm-0 mr-0 mr-sm-1",
                                  attrs: { variant: "primary", type: "submit" },
                                },
                                [
                                  _vm._v(
                                    "\n                  " +
                                      _vm._s(_vm.$t("save")) +
                                      "\n                "
                                  ),
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
            ]
          ),
          _vm._v(" "),
          _c(
            "b-modal",
            {
              ref: "image-modal",
              attrs: {
                id: "modal-center",
                centered: "",
                title: _vm.modal_title,
                "hide-footer": "",
                "ok-only": "",
                "ok-title": "Accept",
              },
            },
            [
              _c("b-card-text", [
                _c("img", {
                  staticClass: "modal-content",
                  attrs: { src: _vm.modal_image, id: "img01" },
                }),
              ]),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-modal",
            {
              ref: "city-modal",
              attrs: {
                id: "modal-center",
                centered: "",
                title: _vm.$t("Add New City"),
                "ok-only": "",
                "ok-title": _vm.$t("Save"),
              },
              on: {
                hidden: _vm.resetsModal,
                show: _vm.resetsModal,
                ok: _vm.city_add,
              },
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("city") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "searchMadminnput",
                              placeholder: _vm.$t("city"),
                            },
                            model: {
                              value: _vm.citys.city,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys, "city", $$v)
                              },
                              expression: "citys.city",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("status") } },
                        [
                          _c("v-select", {
                            attrs: {
                              options: _vm.options_status,
                              label: "text",
                              reduce: function (sel) {
                                return sel.value
                              },
                              placeholder: _vm.$t("select status"),
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                            },
                            model: {
                              value: _vm.citys.status,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys, "status", $$v)
                              },
                              expression: "citys.status",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("country") } },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "country",
                              options: _vm.country,
                              label: "country",
                              reduce: function (sel) {
                                return sel.id
                              },
                              placeholder: _vm.$t("select country"),
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                            },
                            on: {
                              input: function ($event) {
                                return _vm.onChange_city($event)
                              },
                            },
                            scopedSlots: _vm._u([
                              {
                                key: "list-header",
                                fn: function () {
                                  return [
                                    _c(
                                      "li",
                                      {
                                        staticClass:
                                          "add-new-client-header d-flex align-items-center my-50",
                                        on: { click: _vm.open_countrymodal },
                                      },
                                      [
                                        _c("feather-icon", {
                                          attrs: {
                                            icon: "PlusIcon",
                                            size: "16",
                                          },
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "span",
                                          { staticClass: "align-middle ml-25" },
                                          [
                                            _vm._v(
                                              _vm._s(_vm.$t("Add New Country"))
                                            ),
                                          ]
                                        ),
                                      ],
                                      1
                                    ),
                                  ]
                                },
                                proxy: true,
                              },
                            ]),
                            model: {
                              value: _vm.citys.country,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys, "country", $$v)
                              },
                              expression: "citys.country",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("state") } },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "country",
                              options: this.state,
                              label: "state",
                              reduce: function (sel) {
                                return sel.id
                              },
                              placeholder: _vm.$t("select state"),
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                            },
                            scopedSlots: _vm._u([
                              {
                                key: "list-header",
                                fn: function () {
                                  return [
                                    _c(
                                      "li",
                                      {
                                        staticClass:
                                          "add-new-client-header d-flex align-items-center my-50",
                                        on: { click: _vm.open_statemodal },
                                      },
                                      [
                                        _c("feather-icon", {
                                          attrs: {
                                            icon: "PlusIcon",
                                            size: "16",
                                          },
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "span",
                                          { staticClass: "align-middle ml-25" },
                                          [
                                            _vm._v(
                                              _vm._s(_vm.$t("Add New State"))
                                            ),
                                          ]
                                        ),
                                      ],
                                      1
                                    ),
                                  ]
                                },
                                proxy: true,
                              },
                            ]),
                            model: {
                              value: _vm.citys.state,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys, "state", $$v)
                              },
                              expression: "citys.state",
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
              ),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-modal",
            {
              ref: "area-modal",
              attrs: {
                id: "modal-center",
                centered: "",
                title: _vm.$t("Add New Area"),
                "ok-only": "",
                "ok-title": _vm.$t("Save"),
              },
              on: {
                hidden: _vm.resetsModal,
                show: _vm.resetsModal,
                ok: _vm.area_add,
              },
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("city") } },
                        [
                          _c("v-select", {
                            attrs: {
                              options: _vm.cities,
                              label: "city",
                              reduce: function (sel) {
                                return sel.id
                              },
                              placeholder:
                                _vm.$t("select") + " " + _vm.$t("city"),
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                            },
                            scopedSlots: _vm._u([
                              {
                                key: "list-header",
                                fn: function () {
                                  return [
                                    _c(
                                      "li",
                                      {
                                        staticClass:
                                          "add-new-client-header d-flex align-items-center my-50",
                                        on: { click: _vm.open_citymodal },
                                      },
                                      [
                                        _c("feather-icon", {
                                          attrs: {
                                            icon: "PlusIcon",
                                            size: "16",
                                          },
                                        }),
                                        _vm._v(" "),
                                        _c(
                                          "span",
                                          { staticClass: "align-middle ml-25" },
                                          [
                                            _vm._v(
                                              _vm._s(_vm.$t("Add New City"))
                                            ),
                                          ]
                                        ),
                                      ],
                                      1
                                    ),
                                  ]
                                },
                                proxy: true,
                              },
                            ]),
                            model: {
                              value: _vm.areas1.city,
                              callback: function ($$v) {
                                _vm.$set(_vm.areas1, "city", $$v)
                              },
                              expression: "areas1.city",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("status") } },
                        [
                          _c("v-select", {
                            attrs: {
                              options: _vm.options_status1,
                              label: "text",
                              reduce: function (sel) {
                                return sel.value
                              },
                              placeholder: _vm.$t("select status"),
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                            },
                            model: {
                              value: _vm.areas1.status,
                              callback: function ($$v) {
                                _vm.$set(_vm.areas1, "status", $$v)
                              },
                              expression: "areas1.status",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("area") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "searchMadminnput_area",
                              placeholder: _vm.$t("area"),
                            },
                            model: {
                              value: _vm.areas1.area,
                              callback: function ($$v) {
                                _vm.$set(_vm.areas1, "area", $$v)
                              },
                              expression: "areas1.area",
                            },
                          }),
                          _vm._v(" "),
                          _c("br"),
                          _vm._v(" "),
                          _c("input", {
                            attrs: { type: "hidden", id: "selectedadd" },
                          }),
                          _vm._v(" "),
                          _c("input", {
                            attrs: {
                              type: "hidden",
                              id: "latitude_area",
                              name: "latitude",
                              value: "12.9813658",
                            },
                          }),
                          _vm._v(" "),
                          _c("input", {
                            attrs: {
                              type: "hidden",
                              id: "longitude_area",
                              name: "longitude",
                              value: "80.2199712",
                            },
                          }),
                          _vm._v(" "),
                          _c("div", { attrs: { id: "gmap" } }),
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
          _vm._v(" "),
          _c(
            "b-modal",
            {
              ref: "country-modal",
              attrs: {
                id: "modal-center",
                centered: "",
                title: _vm.$t("Add New Country"),
                "ok-only": "",
                "ok-title": _vm.$t("Save"),
              },
              on: {
                hidden: _vm.resetsModal,
                show: _vm.resetsModal,
                ok: _vm.country_add,
              },
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("country") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "mc-first-name",
                              placeholder: _vm.$t("country"),
                            },
                            model: {
                              value: _vm.citys_country.country,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys_country, "country", $$v)
                              },
                              expression: "citys_country.country",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("country code") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "mc-first-name",
                              placeholder: _vm.$t("country code"),
                            },
                            model: {
                              value: _vm.citys_country.country_code,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys_country, "country_code", $$v)
                              },
                              expression: "citys_country.country_code",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("currency code") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "mc-first-name",
                              placeholder: _vm.$t("currency code"),
                            },
                            model: {
                              value: _vm.citys_country.currency_code,
                              callback: function ($$v) {
                                _vm.$set(
                                  _vm.citys_country,
                                  "currency_code",
                                  $$v
                                )
                              },
                              expression: "citys_country.currency_code",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("currency symbol") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "mc-first-name",
                              placeholder: _vm.$t("currency symbol"),
                            },
                            model: {
                              value: _vm.citys_country.currency_symbol,
                              callback: function ($$v) {
                                _vm.$set(
                                  _vm.citys_country,
                                  "currency_symbol",
                                  $$v
                                )
                              },
                              expression: "citys_country.currency_symbol",
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
              ),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "b-modal",
            {
              ref: "state-modal",
              attrs: {
                id: "modal-center",
                centered: "",
                title: _vm.$t("Add New State"),
                "ok-only": "",
                "ok-title": _vm.$t("Save"),
              },
              on: {
                hidden: _vm.resetsModal,
                show: _vm.resetsModal,
                ok: _vm.state_add,
              },
            },
            [
              _c(
                "b-row",
                [
                  _c(
                    "b-col",
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("state") } },
                        [
                          _c("b-form-input", {
                            attrs: {
                              id: "mc-first-name",
                              placeholder: _vm.$t("state"),
                            },
                            model: {
                              value: _vm.citys_state.state,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys_state, "state", $$v)
                              },
                              expression: "citys_state.state",
                            },
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
                    { attrs: { md: "6" } },
                    [
                      _c(
                        "b-form-group",
                        { attrs: { label: _vm.$t("country") } },
                        [
                          _c("v-select", {
                            attrs: {
                              id: "country",
                              options: _vm.country,
                              label: "country",
                              reduce: function (sel) {
                                return sel.id
                              },
                              placeholder: _vm.$t("select country"),
                              dir: _vm.$store.state.appConfig.isRTL
                                ? "rtl"
                                : "ltr",
                            },
                            model: {
                              value: _vm.citys_state.country_id,
                              callback: function ($$v) {
                                _vm.$set(_vm.citys_state, "country_id", $$v)
                              },
                              expression: "citys_state.country_id",
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

/***/ "./node_modules/vue-prism-component/dist/vue-prism-component.common.js":
/*!*****************************************************************************!*\
  !*** ./node_modules/vue-prism-component/dist/vue-prism-component.common.js ***!
  \*****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function _interopDefault (ex) { return (ex && (typeof ex === 'object') && 'default' in ex) ? ex['default'] : ex; }

var Prism = _interopDefault(__webpack_require__(/*! prismjs */ "./node_modules/prismjs/prism.js"));

function assign(obj) {
  for (var i = 1; i < arguments.length; i++) {
    // eslint-disable-next-line guard-for-in, prefer-rest-params
    for (var p in arguments[i]) {
      obj[p] = arguments[i][p];
    }
  }

  return obj;
}

var index = {
  functional: true,
  props: {
    code: {
      type: String
    },
    inline: {
      type: Boolean,
      "default": false
    },
    language: {
      type: String,
      "default": 'markup'
    }
  },
  render: function render(h, ctx) {
    var code = ctx.props.code || (ctx.children && ctx.children.length > 0 ? ctx.children[0].text : '');
    var inline = ctx.props.inline;
    var language = ctx.props.language;
    var prismLanguage = Prism.languages[language];
    var className = "language-".concat(language);

    if ( true && !prismLanguage) {
      throw new Error("Prism component for language \"".concat(language, "\" was not found, did you forget to register it? See all available ones: https://cdn.jsdelivr.net/npm/prismjs/components/"));
    }

    if (inline) {
      return h('code', assign({}, ctx.data, {
        "class": [ctx.data["class"], className],
        domProps: assign({}, ctx.data.domProps, {
          innerHTML: Prism.highlight(code, prismLanguage)
        })
      }));
    }

    return h('pre', assign({}, ctx.data, {
      "class": [ctx.data["class"], className]
    }), [h('code', {
      "class": className,
      domProps: {
        innerHTML: Prism.highlight(code, prismLanguage)
      }
    })]);
  }
};

module.exports = index;


/***/ }),

/***/ "./resources/js/src/@core/components/b-card-code/index.js":
/*!****************************************************************!*\
  !*** ./resources/js/src/@core/components/b-card-code/index.js ***!
  \****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _BCardCode_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BCardCode.vue */ "./resources/js/src/@core/components/b-card-code/BCardCode.vue");

/* harmony default export */ __webpack_exports__["default"] = (_BCardCode_vue__WEBPACK_IMPORTED_MODULE_0__["default"]);

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

/***/ "./resources/js/src/views/admin/driver/edit_driver.vue":
/*!*************************************************************!*\
  !*** ./resources/js/src/views/admin/driver/edit_driver.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _edit_driver_vue_vue_type_template_id_3d222760__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./edit_driver.vue?vue&type=template&id=3d222760 */ "./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=template&id=3d222760");
/* harmony import */ var _edit_driver_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit_driver.vue?vue&type=script&lang=js */ "./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _edit_driver_vue_vue_type_style_index_0_id_3d222760_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss */ "./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _edit_driver_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _edit_driver_vue_vue_type_template_id_3d222760__WEBPACK_IMPORTED_MODULE_0__["render"],
  _edit_driver_vue_vue_type_template_id_3d222760__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/admin/driver/edit_driver.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=script&lang=js":
/*!*************************************************************************************!*\
  !*** ./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=script&lang=js ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./edit_driver.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss ***!
  \**********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_style_index_0_id_3d222760_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=style&index=0&id=3d222760&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_style_index_0_id_3d222760_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_style_index_0_id_3d222760_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_style_index_0_id_3d222760_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_style_index_0_id_3d222760_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=template&id=3d222760":
/*!*******************************************************************************************!*\
  !*** ./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=template&id=3d222760 ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_template_id_3d222760__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./edit_driver.vue?vue&type=template&id=3d222760 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/driver/edit_driver.vue?vue&type=template&id=3d222760");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_template_id_3d222760__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_edit_driver_vue_vue_type_template_id_3d222760__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);