(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[34],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=script&lang=js":
/*!***************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=script&lang=js ***!
  \***************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var core_js_modules_es_string_replace_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! core-js/modules/es.string.replace.js */ "./node_modules/core-js/modules/es.string.replace.js");
/* harmony import */ var core_js_modules_es_string_replace_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_replace_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-awesome-swiper */ "./node_modules/vue-awesome-swiper/dist/vue-awesome-swiper.js");
/* harmony import */ var vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var swiper_css_swiper_css__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! swiper/css/swiper.css */ "./node_modules/swiper/css/swiper.css");
/* harmony import */ var swiper_css_swiper_css__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(swiper_css_swiper_css__WEBPACK_IMPORTED_MODULE_6__);




//
//
//
//
//
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
    Swiper: vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_4__["Swiper"],
    SwiperSlide: vue_awesome_swiper__WEBPACK_IMPORTED_MODULE_4__["SwiperSlide"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_5__["BImg"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_5__["BCard"]
  },
  data: function data() {
    return {
      rows: {},
      isempty: false,
      swiperOptions: {
        breakpoints: {
          320: {
            slidesPerView: 1,
            spaceBetween: 10
          },
          760: {
            slidesPerView: 1,
            spaceBetween: 30
          },
          1200: {
            slidesPerView: 1,
            spaceBetween: 30
          }
        },
        autoplay: {
          delay: 2500,
          disableOnInteraction: true
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true
        }
      }
    };
  },
  created: function created() {
    var _this = this;
    var lat = localStorage.getItem('latitude');
    var lng = localStorage.getItem('longitude');
    this.isempty = true;
    this.$http.get('/banner_list/' + lat + '/' + lng).then(function (res) {
      _this.rows = res.data.data;
      for (var index = 0; index < _this.rows.length; index++) {
        var str = _this.rows[index].name;
        str.replace(/\-/g, "");
        _this.rows[index].str = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
      }
      if (_this.rows.length > 0) {
        _this.isempty = false;
      }
    });
  },
  methods: {
    "goto": function goto(data) {
      console.log(data);
      if (data.redirect_type != 0) {
        if (data.redirect_type == 1) {
          var str = data.redirect_name;
          str.replace(/\-/g, "");
          var str2 = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
          this.$router.push({
            name: 'cat_list',
            params: {
              slug: data.redirect_to
            }
          });
        }
        if (data.redirect_type == 2) {
          var str = data.redirect_name;
          str.replace(/\-/g, "");
          var str2 = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
          this.$router.push({
            name: 'product',
            params: {
              id: data.redirect_to,
              name: str2
            }
          });
        }
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/download.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
      faqData: {},
      rows: '',
      appname: '',
      is_loading: true
    };
  },
  created: function created() {
    var _this = this;
    this.$http.get('/get_qrcode').then(function (res) {
      _this.rows = res.data.qrcode;
      _this.appname = res.data.app_name;
      _this.is_loading = false;
    });
  }
});

/***/ }),

/***/ "./node_modules/core-js/modules/es.object.values.js":
/*!**********************************************************!*\
  !*** ./node_modules/core-js/modules/es.object.values.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $ = __webpack_require__(/*! ../internals/export */ "./node_modules/core-js/internals/export.js");
var $values = __webpack_require__(/*! ../internals/object-to-array */ "./node_modules/core-js/internals/object-to-array.js").values;

// `Object.values` method
// https://tc39.es/ecma262/#sec-object.values
$({ target: 'Object', stat: true }, {
  values: function values(O) {
    return $values(O);
  }
});


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "[dir] .faq-search {\n  background-size: cover;\n  background-color: rgba(224, 23, 100, 0.12) !important;\n}\n[dir] .faq-search .faq-search-input .input-group:focus-within {\n  box-shadow: none;\n}\n[dir] .faq-contact .faq-contact-card {\n  background-color: rgba(186, 191, 199, 0.12);\n}\n@media (min-width: 992px) {\n[dir] .faq-search .card-body {\n    padding: 8rem !important;\n}\n}\n@media (min-width: 768px) and (max-width: 991.98px) {\n[dir] .faq-search .card-body {\n    padding: 6rem !important;\n}\n}\n@media (min-width: 768px) {\n.faq-search .faq-search-input .input-group {\n    width: 576px;\n}\n[dir] .faq-search .faq-search-input .input-group {\n    margin: 0 auto;\n}\n.faq-navigation {\n    height: 550px;\n}\n}\n.faq-navigation .nav.nav-pills {\n  height: 100%;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n[dir] .rounded[data-v-4f691850] {\n    border-radius: 2px !important;\n}\n[dir] .rounded3[data-v-4f691850] {\n    cursor: pointer;\n}\n[dir] .pt-6[data-v-4f691850] {\n    padding-top: 6rem !important;\n}\n.top_ban_img[data-v-4f691850] {\n    width: 100vw;\n    height: 250px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.nav-link[data-v-c617aa34] {\n  color: black;\n}\n.ht[data-v-c617aa34] {\n  min-height: 600px;\n}\n[dir] .cat-card[data-v-c617aa34] {\n  background: rgba(234, 93, 39, 0.18);\n  border-color: #80BE3F;\n  border-style: solid;\n}\n[dir] .cat-card2[data-v-c617aa34] {\n  background: rgba(234, 93, 39, 0.30);\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./download.vue?vue&type=style&index=0&id=c617aa34&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=template&id=4f691850&scoped=true":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=template&id=4f691850&scoped=true ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
  return !_vm.isempty
    ? _c(
        "b",
        [
          _c(
            "swiper",
            {
              staticClass: "swiper-multiple",
              attrs: {
                options: _vm.swiperOptions,
                dir: _vm.$store.state.appConfig.isRTL ? "rtl" : "ltr",
              },
            },
            [
              _vm._l(_vm.rows, function (data, index) {
                return _c(
                  "swiper-slide",
                  { key: index },
                  [
                    _c("b-img", {
                      staticClass: "rounded3 top_ban_img",
                      attrs: { src: data.img, fluid: "" },
                      on: {
                        click: function ($event) {
                          return _vm.goto(data)
                        },
                      },
                    }),
                  ],
                  1
                )
              }),
              _vm._v(" "),
              _c("div", {
                staticClass: "swiper-pagination",
                attrs: { slot: "pagination" },
                slot: "pagination",
              }),
            ],
            2
          ),
        ],
        1
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=template&id=c617aa34&scoped=true":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/user_src/views/user/home/download.vue?vue&type=template&id=c617aa34&scoped=true ***!
  \************************************************************************************************************************************************************************************************************************************/
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
      { attrs: { id: "faq-search-filter" } },
      [
        _c(
          "b-card",
          { staticClass: "cat-card rounded3 " },
          [
            _c(
              "b-row",
              [
                _c("b-col", {
                  staticClass: "text-center",
                  attrs: { lg: "1", md: "1", sm: "1" },
                }),
                _vm._v(" "),
                _c(
                  "b-col",
                  { attrs: { lg: "4", md: "4", sm: "12" } },
                  [
                    _c("h2", { staticClass: "text-primary pt-6 f-size-5" }, [
                      _vm._v(
                        "\n            " +
                          _vm._s(_vm.$t("Get")) +
                          " " +
                          _vm._s(_vm.appname) +
                          "\n          "
                      ),
                    ]),
                    _vm._v(" "),
                    _c(
                      "b-card-text",
                      { staticClass: "mb-2 pt-2" },
                      [
                        _c(
                          "b-nav",
                          { attrs: { vertical: "" } },
                          [
                            _c(
                              "b-nav-item",
                              [
                                _c("b-img", {
                                  attrs: {
                                    src: "/tick.svg",
                                    fluid: "",
                                    alt: "Scan",
                                    width: "35",
                                    height: "37",
                                  },
                                }),
                                _vm._v(" "),
                                _c("b", { staticClass: "pl-1 f-size-1" }, [
                                  _vm._v(
                                    _vm._s(_vm.$t("Get")) +
                                      "\n                  " +
                                      _vm._s(_vm.$t("Live Order Tracking"))
                                  ),
                                ]),
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "b-nav-item",
                              { staticClass: "mb-4" },
                              [
                                _c("b-img", {
                                  attrs: {
                                    src: "/tick.svg",
                                    fluid: "",
                                    alt: "Scan",
                                    width: "35",
                                    height: "37",
                                  },
                                }),
                                _vm._v(" "),
                                _c("b", { staticClass: "pl-1 f-size-1" }, [
                                  _vm._v(
                                    _vm._s(_vm.$t("Get")) +
                                      " " +
                                      _vm._s(_vm.$t("Latest Feature Updates"))
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
                          "b-card",
                          { staticClass: "cat-card2" },
                          [
                            _c(
                              "b-row",
                              [
                                _c(
                                  "b-col",
                                  { attrs: { lg: "4", md: "6", sm: "12" } },
                                  [
                                    !_vm.is_loading
                                      ? _c("b-img", {
                                          attrs: {
                                            src: _vm.rows,
                                            fluid: "",
                                            alt: "Scan",
                                            width: "100",
                                            height: "100",
                                          },
                                        })
                                      : _vm._e(),
                                  ],
                                  1
                                ),
                                _vm._v(" "),
                                _c(
                                  "b-col",
                                  { attrs: { lg: "8", md: "6", sm: "12" } },
                                  [
                                    _c("h5", { staticClass: "f-size-1 pt-2" }, [
                                      _c("b", [
                                        _vm._v(
                                          _vm._s(_vm.$t("Simple way")) +
                                            " " +
                                            _vm._s(_vm.$t("to")) +
                                            " " +
                                            _vm._s(_vm.$t("download")) +
                                            " the " +
                                            _vm._s(_vm.appname)
                                        ),
                                      ]),
                                    ]),
                                    _vm._v(" "),
                                    _c("h6", { staticClass: "f-size-0" }, [
                                      _vm._v(
                                        _vm._s(
                                          _vm.$t(
                                            "Scan QR Code and download now"
                                          )
                                        )
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
                _vm._v(" "),
                _c("b-col", {
                  staticClass: "text-center",
                  attrs: { lg: "1", md: "6", sm: "12" },
                }),
                _vm._v(" "),
                _c(
                  "b-col",
                  {
                    staticClass: "text-center",
                    attrs: { lg: "4", md: "5", sm: "12" },
                  },
                  [
                    _c("b-img", {
                      attrs: { src: "/Group.png", fluid: "", alt: "Scan" },
                    }),
                  ],
                  1
                ),
                _vm._v(" "),
                _c("b-col", {
                  staticClass: "text-center",
                  attrs: { lg: "1", md: "6", sm: "12" },
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
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/user_src/views/user/business/top_banners.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/top_banners.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _top_banners_vue_vue_type_template_id_4f691850_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./top_banners.vue?vue&type=template&id=4f691850&scoped=true */ "./resources/js/user_src/views/user/business/top_banners.vue?vue&type=template&id=4f691850&scoped=true");
/* harmony import */ var _top_banners_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./top_banners.vue?vue&type=script&lang=js */ "./resources/js/user_src/views/user/business/top_banners.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _top_banners_vue_vue_type_style_index_0_id_4f691850_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css */ "./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _top_banners_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _top_banners_vue_vue_type_template_id_4f691850_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"],
  _top_banners_vue_vue_type_template_id_4f691850_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "4f691850",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/user_src/views/user/business/top_banners.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/user_src/views/user/business/top_banners.vue?vue&type=script&lang=js":
/*!*******************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/top_banners.vue?vue&type=script&lang=js ***!
  \*******************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./top_banners.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css ***!
  \***************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_style_index_0_id_4f691850_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=style&index=0&id=4f691850&scoped=true&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_style_index_0_id_4f691850_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_style_index_0_id_4f691850_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_style_index_0_id_4f691850_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_style_index_0_id_4f691850_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/business/top_banners.vue?vue&type=template&id=4f691850&scoped=true":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/business/top_banners.vue?vue&type=template&id=4f691850&scoped=true ***!
  \*************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_template_id_4f691850_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./top_banners.vue?vue&type=template&id=4f691850&scoped=true */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/business/top_banners.vue?vue&type=template&id=4f691850&scoped=true");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_template_id_4f691850_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_top_banners_vue_vue_type_template_id_4f691850_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/user_src/views/user/home/download.vue":
/*!************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/download.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _download_vue_vue_type_template_id_c617aa34_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./download.vue?vue&type=template&id=c617aa34&scoped=true */ "./resources/js/user_src/views/user/home/download.vue?vue&type=template&id=c617aa34&scoped=true");
/* harmony import */ var _download_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./download.vue?vue&type=script&lang=js */ "./resources/js/user_src/views/user/home/download.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _download_vue_vue_type_style_index_0_id_c617aa34_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./download.vue?vue&type=style&index=0&id=c617aa34&lang=scss */ "./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss");
/* harmony import */ var _download_vue_vue_type_style_index_1_id_c617aa34_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css */ "./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");







/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_4__["default"])(
  _download_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _download_vue_vue_type_template_id_c617aa34_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"],
  _download_vue_vue_type_template_id_c617aa34_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "c617aa34",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/user_src/views/user/home/download.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/user_src/views/user/home/download.vue?vue&type=script&lang=js":
/*!************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/download.vue?vue&type=script&lang=js ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./download.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss ***!
  \*********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_0_id_c617aa34_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./download.vue?vue&type=style&index=0&id=c617aa34&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=0&id=c617aa34&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_0_id_c617aa34_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_0_id_c617aa34_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_0_id_c617aa34_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_0_id_c617aa34_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css":
/*!********************************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css ***!
  \********************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_1_id_c617aa34_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=style&index=1&id=c617aa34&scoped=true&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_1_id_c617aa34_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_1_id_c617aa34_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_1_id_c617aa34_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_style_index_1_id_c617aa34_scoped_true_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/user_src/views/user/home/download.vue?vue&type=template&id=c617aa34&scoped=true":
/*!******************************************************************************************************!*\
  !*** ./resources/js/user_src/views/user/home/download.vue?vue&type=template&id=c617aa34&scoped=true ***!
  \******************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_template_id_c617aa34_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./download.vue?vue&type=template&id=c617aa34&scoped=true */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/user_src/views/user/home/download.vue?vue&type=template&id=c617aa34&scoped=true");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_template_id_c617aa34_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_download_vue_vue_type_template_id_c617aa34_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);