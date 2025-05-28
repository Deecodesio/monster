(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[211],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.match.js */ "./node_modules/core-js/modules/es.string.match.js");
/* harmony import */ var core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_string_search_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.string.search.js */ "./node_modules/core-js/modules/es.string.search.js");
/* harmony import */ var core_js_modules_es_string_search_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_search_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _vue_composition_api__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @vue/composition-api */ "./node_modules/@vue/composition-api/dist/vue-composition-api.mjs");
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @/store */ "./resources/js/src/store/index.js");
/* harmony import */ var _router__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @/router */ "./resources/js/src/router/index.js");
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _core_layouts_components_Logo_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @core/layouts/components/Logo.vue */ "./resources/js/src/@core/layouts/components/Logo.vue");
/* harmony import */ var vue_ripple_directive__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! vue-ripple-directive */ "./node_modules/vue-ripple-directive/src/ripple.js");



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  directives: {
    Ripple: vue_ripple_directive__WEBPACK_IMPORTED_MODULE_8__["default"],
    'b-toggle': bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["VBToggle"]
  },
  components: {
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BCol"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BCard"],
    BCardBody: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BCardBody"],
    BTableLite: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BTableLite"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BCardText"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BButton"],
    BAlert: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BAlert"],
    BLink: bootstrap_vue__WEBPACK_IMPORTED_MODULE_6__["BLink"],
    Logo: _core_layouts_components_Logo_vue__WEBPACK_IMPORTED_MODULE_7__["default"]
  },
  data: function data() {
    return {
      req_details: [],
      food_details: [],
      order_status: [],
      setting: {},
      rows: {},
      url1: null
    };
  },
  created: function created() {
    var _this = this;
    this.$http.get('/admin/print_order_bill/' + this.$route.params.id).then(function (res) {
      _this.req_details = res.data.message;
      _this.food_details = res.data.food;
      _this.order_status = res.data.order_status;
    });
    this.$http.get('/admin/get_currency').then(function (res) {
      _this.setting = res.data;
    });
    this.$http.get('/admin/get_logo').then(function (res) {
      _this.rows = res.data;
    });
  },
  mounted: function mounted() {
    this.call();
    document.onreadystatechange = function () {
      if (document.readyState == "complete") {
        console.log('Page completed with image and files!');

        // fetch to next page or some code
      }
    };
  },
  methods: {
    call: function call() {
      window.print();
    }
  }
});
window.console = window.console || function (t) {};
if (document.location.search.match(/type=embed/gi)) {
  window.parent.postMessage("resize", "*");
}

/***/ }),

/***/ "./node_modules/core-js/internals/same-value.js":
/*!******************************************************!*\
  !*** ./node_modules/core-js/internals/same-value.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// `SameValue` abstract operation
// https://tc39.es/ecma262/#sec-samevalue
// eslint-disable-next-line es/no-object-is -- safe
module.exports = Object.is || function is(x, y) {
  // eslint-disable-next-line no-self-compare -- NaN check
  return x === y ? x !== 0 || 1 / x === 1 / y : x !== x && y !== y;
};


/***/ }),

/***/ "./node_modules/core-js/modules/es.string.search.js":
/*!**********************************************************!*\
  !*** ./node_modules/core-js/modules/es.string.search.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var call = __webpack_require__(/*! ../internals/function-call */ "./node_modules/core-js/internals/function-call.js");
var fixRegExpWellKnownSymbolLogic = __webpack_require__(/*! ../internals/fix-regexp-well-known-symbol-logic */ "./node_modules/core-js/internals/fix-regexp-well-known-symbol-logic.js");
var anObject = __webpack_require__(/*! ../internals/an-object */ "./node_modules/core-js/internals/an-object.js");
var isNullOrUndefined = __webpack_require__(/*! ../internals/is-null-or-undefined */ "./node_modules/core-js/internals/is-null-or-undefined.js");
var requireObjectCoercible = __webpack_require__(/*! ../internals/require-object-coercible */ "./node_modules/core-js/internals/require-object-coercible.js");
var sameValue = __webpack_require__(/*! ../internals/same-value */ "./node_modules/core-js/internals/same-value.js");
var toString = __webpack_require__(/*! ../internals/to-string */ "./node_modules/core-js/internals/to-string.js");
var getMethod = __webpack_require__(/*! ../internals/get-method */ "./node_modules/core-js/internals/get-method.js");
var regExpExec = __webpack_require__(/*! ../internals/regexp-exec-abstract */ "./node_modules/core-js/internals/regexp-exec-abstract.js");

// @@search logic
fixRegExpWellKnownSymbolLogic('search', function (SEARCH, nativeSearch, maybeCallNative) {
  return [
    // `String.prototype.search` method
    // https://tc39.es/ecma262/#sec-string.prototype.search
    function search(regexp) {
      var O = requireObjectCoercible(this);
      var searcher = isNullOrUndefined(regexp) ? undefined : getMethod(regexp, SEARCH);
      return searcher ? call(searcher, regexp, O) : new RegExp(regexp)[SEARCH](toString(O));
    },
    // `RegExp.prototype[@@search]` method
    // https://tc39.es/ecma262/#sec-regexp.prototype-@@search
    function (string) {
      var rx = anObject(this);
      var S = toString(string);
      var res = maybeCallNative(nativeSearch, rx, S);

      if (res.done) return res.value;

      var previousLastIndex = rx.lastIndex;
      if (!sameValue(previousLastIndex, 0)) rx.lastIndex = 0;
      var result = regExpExec(rx, S);
      if (!sameValue(rx.lastIndex, previousLastIndex)) rx.lastIndex = previousLastIndex;
      return result === null ? -1 : result.index;
    }
  ];
});


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "[dir=ltr] .invoice-preview .invoice-padding[data-v-9b491638], [dir=ltr] .invoice-edit .invoice-padding[data-v-9b491638], [dir=ltr] .invoice-add .invoice-padding[data-v-9b491638] {\n  padding-left: 2.5rem;\n  padding-right: 2.5rem;\n}[dir=rtl] .invoice-preview .invoice-padding[data-v-9b491638], [dir=rtl] .invoice-edit .invoice-padding[data-v-9b491638], [dir=rtl] .invoice-add .invoice-padding[data-v-9b491638] {\n  padding-right: 2.5rem;\n  padding-left: 2.5rem;\n}\n[dir=ltr] .invoice-preview .table th[data-v-9b491638]:first-child, [dir=ltr] .invoice-preview .table td[data-v-9b491638]:first-child, [dir=ltr] .invoice-edit .table th[data-v-9b491638]:first-child, [dir=ltr] .invoice-edit .table td[data-v-9b491638]:first-child, [dir=ltr] .invoice-add .table th[data-v-9b491638]:first-child, [dir=ltr] .invoice-add .table td[data-v-9b491638]:first-child {\n  padding-left: 2.5rem;\n}\n[dir=rtl] .invoice-preview .table th[data-v-9b491638]:first-child, [dir=rtl] .invoice-preview .table td[data-v-9b491638]:first-child, [dir=rtl] .invoice-edit .table th[data-v-9b491638]:first-child, [dir=rtl] .invoice-edit .table td[data-v-9b491638]:first-child, [dir=rtl] .invoice-add .table th[data-v-9b491638]:first-child, [dir=rtl] .invoice-add .table td[data-v-9b491638]:first-child {\n  padding-right: 2.5rem;\n}\n.invoice-preview .logo-wrapper[data-v-9b491638],\n.invoice-edit .logo-wrapper[data-v-9b491638],\n.invoice-add .logo-wrapper[data-v-9b491638] {\n  display: flex;\n  align-items: center;\n}\n[dir] .invoice-preview .logo-wrapper[data-v-9b491638], [dir] .invoice-edit .logo-wrapper[data-v-9b491638], [dir] .invoice-add .logo-wrapper[data-v-9b491638] {\n  margin-bottom: 1.9rem;\n}\n.invoice-preview .logo-wrapper .invoice-logo[data-v-9b491638],\n.invoice-edit .logo-wrapper .invoice-logo[data-v-9b491638],\n.invoice-add .logo-wrapper .invoice-logo[data-v-9b491638] {\n  font-size: 2.142rem;\n  font-weight: bold;\n  letter-spacing: -0.54px;\n}\n[dir] .invoice-preview .logo-wrapper .invoice-logo[data-v-9b491638], [dir] .invoice-edit .logo-wrapper .invoice-logo[data-v-9b491638], [dir] .invoice-add .logo-wrapper .invoice-logo[data-v-9b491638] {\n  margin-bottom: 0;\n}\n[dir=ltr] .invoice-preview .logo-wrapper .invoice-logo[data-v-9b491638], [dir=ltr] .invoice-edit .logo-wrapper .invoice-logo[data-v-9b491638], [dir=ltr] .invoice-add .logo-wrapper .invoice-logo[data-v-9b491638] {\n  margin-left: 1rem;\n}\n[dir=rtl] .invoice-preview .logo-wrapper .invoice-logo[data-v-9b491638], [dir=rtl] .invoice-edit .logo-wrapper .invoice-logo[data-v-9b491638], [dir=rtl] .invoice-add .logo-wrapper .invoice-logo[data-v-9b491638] {\n  margin-right: 1rem;\n}\n.invoice-preview .invoice-title[data-v-9b491638],\n.invoice-edit .invoice-title[data-v-9b491638],\n.invoice-add .invoice-title[data-v-9b491638] {\n  font-size: 1.285rem;\n}\n[dir] .invoice-preview .invoice-title[data-v-9b491638], [dir] .invoice-edit .invoice-title[data-v-9b491638], [dir] .invoice-add .invoice-title[data-v-9b491638] {\n  margin-bottom: 1rem;\n}\n.invoice-preview .invoice-title .invoice-number[data-v-9b491638],\n.invoice-edit .invoice-title .invoice-number[data-v-9b491638],\n.invoice-add .invoice-title .invoice-number[data-v-9b491638] {\n  font-weight: 600;\n}\n.invoice-preview .invoice-date-wrapper[data-v-9b491638],\n.invoice-edit .invoice-date-wrapper[data-v-9b491638],\n.invoice-add .invoice-date-wrapper[data-v-9b491638] {\n  display: flex;\n  align-items: center;\n}\n[dir] .invoice-preview .invoice-date-wrapper[data-v-9b491638]:not(:last-of-type), [dir] .invoice-edit .invoice-date-wrapper[data-v-9b491638]:not(:last-of-type), [dir] .invoice-add .invoice-date-wrapper[data-v-9b491638]:not(:last-of-type) {\n  margin-bottom: 0.5rem;\n}\n.invoice-preview .invoice-date-wrapper .invoice-date-title[data-v-9b491638],\n.invoice-edit .invoice-date-wrapper .invoice-date-title[data-v-9b491638],\n.invoice-add .invoice-date-wrapper .invoice-date-title[data-v-9b491638] {\n  width: 7rem;\n}\n[dir] .invoice-preview .invoice-date-wrapper .invoice-date-title[data-v-9b491638], [dir] .invoice-edit .invoice-date-wrapper .invoice-date-title[data-v-9b491638], [dir] .invoice-add .invoice-date-wrapper .invoice-date-title[data-v-9b491638] {\n  margin-bottom: 0;\n}\n.invoice-preview .invoice-date-wrapper .invoice-date[data-v-9b491638],\n.invoice-edit .invoice-date-wrapper .invoice-date[data-v-9b491638],\n.invoice-add .invoice-date-wrapper .invoice-date[data-v-9b491638] {\n  font-weight: 600;\n}\n[dir] .invoice-preview .invoice-date-wrapper .invoice-date[data-v-9b491638], [dir] .invoice-edit .invoice-date-wrapper .invoice-date[data-v-9b491638], [dir] .invoice-add .invoice-date-wrapper .invoice-date[data-v-9b491638] {\n  margin-bottom: 0;\n}\n[dir=ltr] .invoice-preview .invoice-date-wrapper .invoice-date[data-v-9b491638], [dir=ltr] .invoice-edit .invoice-date-wrapper .invoice-date[data-v-9b491638], [dir=ltr] .invoice-add .invoice-date-wrapper .invoice-date[data-v-9b491638] {\n  margin-left: 0.5rem;\n}\n[dir=rtl] .invoice-preview .invoice-date-wrapper .invoice-date[data-v-9b491638], [dir=rtl] .invoice-edit .invoice-date-wrapper .invoice-date[data-v-9b491638], [dir=rtl] .invoice-add .invoice-date-wrapper .invoice-date[data-v-9b491638] {\n  margin-right: 0.5rem;\n}\n[dir] .invoice-preview .invoice-spacing[data-v-9b491638], [dir] .invoice-edit .invoice-spacing[data-v-9b491638], [dir] .invoice-add .invoice-spacing[data-v-9b491638] {\n  margin: 1.45rem 0;\n}\n.invoice-preview .invoice-number-date .title[data-v-9b491638],\n.invoice-edit .invoice-number-date .title[data-v-9b491638],\n.invoice-add .invoice-number-date .title[data-v-9b491638] {\n  width: 115px;\n}\n.invoice-preview .invoice-total-wrapper[data-v-9b491638],\n.invoice-edit .invoice-total-wrapper[data-v-9b491638],\n.invoice-add .invoice-total-wrapper[data-v-9b491638] {\n  width: 100%;\n  max-width: 12rem;\n}\n.invoice-preview .invoice-total-wrapper .invoice-total-item[data-v-9b491638],\n.invoice-edit .invoice-total-wrapper .invoice-total-item[data-v-9b491638],\n.invoice-add .invoice-total-wrapper .invoice-total-item[data-v-9b491638] {\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n}\n[dir] .invoice-preview .invoice-total-wrapper .invoice-total-item .invoice-total-title[data-v-9b491638], [dir] .invoice-edit .invoice-total-wrapper .invoice-total-item .invoice-total-title[data-v-9b491638], [dir] .invoice-add .invoice-total-wrapper .invoice-total-item .invoice-total-title[data-v-9b491638] {\n  margin-bottom: 0.35rem;\n}\n.invoice-preview .invoice-total-wrapper .invoice-total-item .invoice-total-amount[data-v-9b491638],\n.invoice-edit .invoice-total-wrapper .invoice-total-item .invoice-total-amount[data-v-9b491638],\n.invoice-add .invoice-total-wrapper .invoice-total-item .invoice-total-amount[data-v-9b491638] {\n  font-weight: 600;\n}\n[dir] .invoice-preview .invoice-total-wrapper .invoice-total-item .invoice-total-amount[data-v-9b491638], [dir] .invoice-edit .invoice-total-wrapper .invoice-total-item .invoice-total-amount[data-v-9b491638], [dir] .invoice-add .invoice-total-wrapper .invoice-total-item .invoice-total-amount[data-v-9b491638] {\n  margin-bottom: 0.35rem;\n}\n@media (min-width: 768px) {\n[dir] .invoice-preview .invoice-title[data-v-9b491638], [dir] .invoice-edit .invoice-title[data-v-9b491638], [dir] .invoice-add .invoice-title[data-v-9b491638] {\n    margin-bottom: 3rem;\n}\n[dir=ltr] .invoice-preview .invoice-title[data-v-9b491638], [dir=ltr] .invoice-edit .invoice-title[data-v-9b491638], [dir=ltr] .invoice-add .invoice-title[data-v-9b491638] {\n    text-align: right;\n}\n[dir=rtl] .invoice-preview .invoice-title[data-v-9b491638], [dir=rtl] .invoice-edit .invoice-title[data-v-9b491638], [dir=rtl] .invoice-add .invoice-title[data-v-9b491638] {\n    text-align: left;\n}\n}\n[dir] .invoice-edit .invoice-preview-card .invoice-title[data-v-9b491638], [dir] .invoice-add .invoice-preview-card .invoice-title[data-v-9b491638] {\n  margin-bottom: 0;\n}\n[dir=ltr] .invoice-edit .invoice-preview-card .invoice-title[data-v-9b491638], [dir=ltr] .invoice-add .invoice-preview-card .invoice-title[data-v-9b491638] {\n  text-align: left;\n  margin-right: 3.5rem;\n}\n[dir=rtl] .invoice-edit .invoice-preview-card .invoice-title[data-v-9b491638], [dir=rtl] .invoice-add .invoice-preview-card .invoice-title[data-v-9b491638] {\n  text-align: right;\n  margin-left: 3.5rem;\n}\n.invoice-edit .invoice-preview-card .invoice-edit-input[data-v-9b491638],\n.invoice-edit .invoice-preview-card .invoice-edit-input-group[data-v-9b491638],\n.invoice-add .invoice-preview-card .invoice-edit-input[data-v-9b491638],\n.invoice-add .invoice-preview-card .invoice-edit-input-group[data-v-9b491638] {\n  max-width: 11.21rem;\n}\n[dir] .invoice-edit .invoice-preview-card .invoice-product-details[data-v-9b491638], [dir] .invoice-add .invoice-preview-card .invoice-product-details[data-v-9b491638] {\n  background-color: #fcfcfc;\n  padding: 3.75rem 3.45rem 2.3rem 3.45rem;\n}\n[dir] .invoice-edit .invoice-preview-card .invoice-product-details .product-details-border[data-v-9b491638], [dir] .invoice-add .invoice-preview-card .invoice-product-details .product-details-border[data-v-9b491638] {\n  border: 1px solid #ebe9f1;\n  border-radius: 0.357rem;\n}\n[dir] .invoice-edit .invoice-preview-card .invoice-to-title[data-v-9b491638], [dir] .invoice-add .invoice-preview-card .invoice-to-title[data-v-9b491638] {\n  margin-bottom: 1.9rem;\n}\n.invoice-edit .invoice-preview-card .col-title[data-v-9b491638],\n.invoice-add .invoice-preview-card .col-title[data-v-9b491638] {\n  position: absolute;\n  top: -3.2rem;\n}\n.invoice-edit .invoice-preview-card .item-options-menu[data-v-9b491638],\n.invoice-add .invoice-preview-card .item-options-menu[data-v-9b491638] {\n  min-width: 20rem;\n}\n[dir] .invoice-edit .invoice-preview-card .repeater-wrapper[data-v-9b491638]:not(:last-child), [dir] .invoice-add .invoice-preview-card .repeater-wrapper[data-v-9b491638]:not(:last-child) {\n  margin-bottom: 3rem;\n}\n.invoice-edit .invoice-preview-card .invoice-calculations .total-amt-title[data-v-9b491638],\n.invoice-add .invoice-preview-card .invoice-calculations .total-amt-title[data-v-9b491638] {\n  width: 100px;\n}\n@media (max-width: 769px) {\n.invoice-edit .invoice-preview-card .invoice-title[data-v-9b491638],\n.invoice-add .invoice-preview-card .invoice-title[data-v-9b491638] {\n    width: 115px;\n}\n[dir=ltr] .invoice-edit .invoice-preview-card .invoice-title[data-v-9b491638], [dir=ltr] .invoice-add .invoice-preview-card .invoice-title[data-v-9b491638] {\n    margin-right: 0;\n}\n[dir=rtl] .invoice-edit .invoice-preview-card .invoice-title[data-v-9b491638], [dir=rtl] .invoice-add .invoice-preview-card .invoice-title[data-v-9b491638] {\n    margin-left: 0;\n}\n.invoice-edit .invoice-preview-card .invoice-edit-input[data-v-9b491638],\n.invoice-add .invoice-preview-card .invoice-edit-input[data-v-9b491638] {\n    max-width: 100%;\n}\n}\n@media (max-width: 992px) {\n.invoice-edit .col-title[data-v-9b491638],\n.invoice-add .col-title[data-v-9b491638] {\n    top: -1.5rem !important;\n}\n}\n@media print {\n[dir] .invoice-edit hr[data-v-9b491638], [dir] .invoice-add hr[data-v-9b491638] {\n    margin-top: 1rem !important;\n    margin-bottom: 1rem !important;\n}\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "@media print {\n[dir] body {\n    background-color: transparent !important;\n}\nnav.header-navbar {\n    display: none;\n}\n.main-menu {\n    display: none;\n}\n.header-navbar-shadow {\n    display: none !important;\n}\n[dir] .content.app-content {\n    padding-top: 2rem !important;\n}\n[dir=ltr] .content.app-content {\n    margin-left: 0;\n}\n[dir=rtl] .content.app-content {\n    margin-right: 0;\n}\nfooter.footer {\n    display: none;\n}\n[dir] .card {\n    background-color: transparent;\n    box-shadow: none;\n}\n.customizer-toggle {\n    display: none !important;\n}\n.invoice-preview-wrapper .row.invoice-preview .col-md-8 {\n    max-width: 100%;\n    flex-grow: 1;\n}\n.invoice-preview-wrapper .row.invoice-preview .invoice-preview-card .card-body:nth-of-type(2) .row > .col-12 {\n    max-width: 50% !important;\n}\n.invoice-preview-wrapper .row.invoice-preview .invoice-preview-card .card-body:nth-of-type(2) .row .col-12:nth-child(2) {\n    display: flex;\n    align-items: flex-start;\n    justify-content: flex-end;\n}\n[dir] .invoice-preview-wrapper .row.invoice-preview .invoice-preview-card .card-body:nth-of-type(2) .row .col-12:nth-child(2) {\n    margin-top: 0 !important;\n}\n.invoice-preview-wrapper .invoice-actions {\n    display: none;\n}\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n@media print {\n.page-break { display: block; page-break-before: always;\n}\n}\n#invoice-POS {\n  /* box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); */\n  width: 44mm;\n}\n[dir] #invoice-POS {\n  padding: 2mm;\n  margin: 0 0;\n  background: #FFF;\n}\n#invoice-POS ::selection {\n  color: #FFF;\n}\n[dir] #invoice-POS ::selection {\n  background: #f31544;\n}\n#invoice-POS ::moz-selection {\n  color: #FFF;\n}\n[dir] #invoice-POS ::moz-selection {\n  background: #f31544;\n}\n#invoice-POS h1 {\n  font-size: 1.5em;\n  color: #222;\n}\n#invoice-POS h2 {\n  font-size: .9em;\n}\n#invoice-POS h3 {\n  font-size: 1.2em;\n  font-weight: 300;\n  line-height: 2em;\n}\n#invoice-POS p {\n  font-size: .7em;\n  color: #666;\n  line-height: 1.2em;\n}\n#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {\n  /* Targets all id with 'col-' */\n}\n[dir] #invoice-POS #top, [dir] #invoice-POS #mid, [dir] #invoice-POS #bot {\n  border-bottom: 1px solid #EEE;\n}\n#invoice-POS #bot {\n  min-height: 50px;\n}\n#invoice-POS #top .logo {\n  height: 60px;\n  width: 60px;\n  /*background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;*/\n}\n[dir] #invoice-POS #top .logo {\n  background-size: 60px 60px;\n}\n#invoice-POS .clientlogo {\n  height: 60px;\n  width: 60px;\n  /*background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;*/\n}\n[dir] #invoice-POS .clientlogo {\n  background-size: 60px 60px;\n  border-radius: 50px;\n}\n[dir=ltr] #invoice-POS .clientlogo { float: left;\n}\n[dir=rtl] #invoice-POS .clientlogo {\n  float: right;\n}\n#invoice-POS .info {\n  display: block;\n}\n[dir=ltr] #invoice-POS .info { margin-left: 0;\n}\n[dir=rtl] #invoice-POS .info {\n  margin-right: 0;\n}\n[dir=ltr] #invoice-POS .title { float: right;\n}\n[dir=rtl] #invoice-POS .title {\n  float: left;\n}\n[dir=ltr] #invoice-POS .title p { text-align: right;\n}\n[dir=rtl] #invoice-POS .title p {\n  text-align: left;\n}\n#invoice-POS table {\n  width: 100%;\n  border-collapse: collapse;\n}\n#invoice-POS .tabletitle {\n  font-size: .5em;\n}\n[dir] #invoice-POS .tabletitle {\n  background: #EEE;\n}\n[dir] #invoice-POS .service {\n  border-bottom: 1px solid #EEE;\n}\n#invoice-POS .item {\n  width: 24mm;\n}\n#invoice-POS .itemtext {\n  font-size: .6em;\n}\n[dir] #invoice-POS #legalcopy {\n  margin-top: 5mm;\n}\n\n    ", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss");

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

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=style&index=1&id=9b491638&lang=css */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/layouts/components/Logo.vue?vue&type=template&id=46f77075":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/@core/layouts/components/Logo.vue?vue&type=template&id=46f77075 ***!
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
    "svg",
    {
      attrs: {
        viewBox: "0 0 139 95",
        version: "1.1",
        xmlns: "http://www.w3.org/2000/svg",
        "xmlns:xlink": "http://www.w3.org/1999/xlink",
        height: "24",
      },
    },
    [
      _c(
        "defs",
        [
          _c(
            "linearGradient",
            {
              attrs: {
                id: "linearGradient-1",
                x1: "100%",
                y1: "10.5120544%",
                x2: "50%",
                y2: "89.4879456%",
              },
            },
            [
              _c("stop", { attrs: { "stop-color": "#000000", offset: "0%" } }),
              _vm._v(" "),
              _c("stop", {
                attrs: { "stop-color": "#FFFFFF", offset: "100%" },
              }),
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "linearGradient",
            {
              attrs: {
                id: "linearGradient-2",
                x1: "64.0437835%",
                y1: "46.3276743%",
                x2: "37.373316%",
                y2: "100%",
              },
            },
            [
              _c("stop", {
                attrs: {
                  "stop-color": "#EEEEEE",
                  "stop-opacity": "0",
                  offset: "0%",
                },
              }),
              _vm._v(" "),
              _c("stop", {
                attrs: { "stop-color": "#FFFFFF", offset: "100%" },
              }),
            ],
            1
          ),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "g",
        {
          attrs: {
            id: "Page-1",
            stroke: "none",
            "stroke-width": "1",
            fill: "none",
            "fill-rule": "evenodd",
          },
        },
        [
          _c(
            "g",
            {
              attrs: {
                id: "Artboard",
                transform: "translate(-400.000000, -178.000000)",
              },
            },
            [
              _c(
                "g",
                {
                  attrs: {
                    id: "Group",
                    transform: "translate(400.000000, 178.000000)",
                  },
                },
                [
                  _c("path", {
                    staticClass: "text-primary",
                    staticStyle: { fill: "currentColor" },
                    attrs: {
                      id: "Path",
                      d: "M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z",
                    },
                  }),
                  _vm._v(" "),
                  _c("path", {
                    attrs: {
                      id: "Path1",
                      d: "M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z",
                      fill: "url(#linearGradient-1)",
                      opacity: "0.2",
                    },
                  }),
                  _vm._v(" "),
                  _c("polygon", {
                    attrs: {
                      id: "Path-2",
                      fill: "#000000",
                      opacity: "0.049999997",
                      points:
                        "69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325",
                    },
                  }),
                  _vm._v(" "),
                  _c("polygon", {
                    attrs: {
                      id: "Path-21",
                      fill: "#000000",
                      opacity: "0.099999994",
                      points:
                        "69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338",
                    },
                  }),
                  _vm._v(" "),
                  _c("polygon", {
                    attrs: {
                      id: "Path-3",
                      fill: "url(#linearGradient-2)",
                      opacity: "0.099999994",
                      points:
                        "101.428699 0 83.0667527 94.1480575 130.378721 47.0740288",
                    },
                  }),
                ]
              ),
            ]
          ),
        ]
      ),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=template&id=9b491638&scoped=true":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=template&id=9b491638&scoped=true ***!
  \**********************************************************************************************************************************************************************************************************************************************/
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
  return _c("section", { staticClass: "invoice-preview-wrapper" }, [
    _c(
      "div",
      { attrs: { id: "invoice-POS" } },
      [
        _c("center", { attrs: { id: "top" } }, [
          _c("h2", [_vm._v("  " + _vm._s(_vm.req_details.restaurant_name))]),
        ]),
        _vm._v(" "),
        _c("div", { attrs: { id: "mid" } }, [
          _c("div", { staticClass: "info" }, [
            _c("p", { staticStyle: { "text-align": "center" } }, [
              _c("span", { staticStyle: { color: "#000" } }, [
                _vm._v("Address :"),
              ]),
              _vm._v(" "),
              _c("span", { staticStyle: { "font-size": ".8em" } }, [
                _vm._v(_vm._s(_vm.req_details.address)),
              ]),
              _c("br"),
              _vm._v(" "),
              _c("span", { staticStyle: { color: "#000" } }, [
                _vm._v("Email   :"),
              ]),
              _vm._v(" "),
              _c("span", { staticStyle: { "font-size": ".8em" } }, [
                _vm._v(" " + _vm._s(_vm.req_details.restaurants_email)),
              ]),
              _c("br"),
              _vm._v(" "),
              _c("span", { staticStyle: { color: "#000" } }, [
                _vm._v("Phone   :"),
              ]),
              _vm._v(" "),
              _c("span", { staticStyle: { "font-size": ".8em" } }, [
                _vm._v("  " + _vm._s(_vm.req_details.contact)),
              ]),
              _c("br"),
              _vm._v(" "),
              _c("span", { staticStyle: { color: "#000" } }, [
                _vm._v("  " + _vm._s(_vm.rows[0].name) + " Order Number   :"),
              ]),
              _vm._v(" "),
              _c("span", { staticStyle: { "font-size": ".8em" } }, [
                _vm._v(_vm._s(_vm.req_details.order_id)),
              ]),
              _c("br"),
            ]),
          ]),
        ]),
        _vm._v(" "),
        _c("div", { attrs: { id: "bot" } }, [
          _c("div", { attrs: { id: "table" } }, [
            _c(
              "table",
              [
                _vm._m(0),
                _vm._v(" "),
                _vm._l(_vm.food_details, function (option) {
                  return _c("tr", { staticClass: "service" }, [
                    _c("td", { staticClass: "tableitem" }, [
                      _c("p", { staticClass: "itemtext" }, [
                        _c("span", { staticStyle: { color: "#000" } }, [
                          _vm._v(_vm._s(option.Name)),
                        ]),
                        _vm._v(" "),
                        _c("br"),
                      ]),
                      option.sizeName
                        ? _c("div", [
                            _vm._v(
                              "\n                                " +
                                _vm._s(option.sizeName) +
                                "\n                            "
                            ),
                          ])
                        : _vm._e(),
                      _vm._v(
                        "\n                          \n                                " +
                          _vm._s(option.addonNames) +
                          "\n                           \n                        "
                      ),
                      _c("p"),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "tableitem" }, [
                      _c("p", { staticClass: "itemtext" }, [
                        _vm._v(_vm._s(option.quantity)),
                      ]),
                    ]),
                    _vm._v(" "),
                    _c("td", { staticClass: "tableitem" }, [
                      _c("p", { staticClass: "itemtext" }, [
                        _vm._v(
                          _vm._s(_vm.setting.value) + " " + _vm._s(option.price)
                        ),
                      ]),
                    ]),
                  ])
                }),
                _vm._v(" "),
                _c("tr", { staticClass: "tabletitle" }, [
                  _c("td"),
                  _vm._v(" "),
                  _vm._m(1),
                  _vm._v(" "),
                  _c("td", { staticClass: "payment" }, [
                    _c("h2", [
                      _vm._v(
                        _vm._s(_vm.setting.value) +
                          " " +
                          _vm._s(_vm.req_details.bill_amount)
                      ),
                    ]),
                  ]),
                ]),
              ],
              2
            ),
          ]),
          _vm._v(" "),
          _vm._m(2),
        ]),
      ],
      1
    ),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tr", { staticClass: "tabletitle" }, [
      _c("td", { staticClass: "item" }, [_c("h2", [_vm._v("Item")])]),
      _vm._v(" "),
      _c("td", { staticClass: "Hours" }, [_c("h2", [_vm._v("Qty")])]),
      _vm._v(" "),
      _c("td", { staticClass: "Rate" }, [_c("h2", [_vm._v("Sub Total")])]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("td", { staticClass: "Rate" }, [
      _c("h2", [_vm._v("Bill Amount")]),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { attrs: { id: "legalcopy" } }, [
      _c(
        "p",
        { staticClass: "legal", staticStyle: { "text-align": "center" } },
        [_c("strong", [_vm._v("Thank you for your business!")])]
      ),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/@core/layouts/components/Logo.vue":
/*!************************************************************!*\
  !*** ./resources/js/src/@core/layouts/components/Logo.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Logo_vue_vue_type_template_id_46f77075__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Logo.vue?vue&type=template&id=46f77075 */ "./resources/js/src/@core/layouts/components/Logo.vue?vue&type=template&id=46f77075");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}


/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  script,
  _Logo_vue_vue_type_template_id_46f77075__WEBPACK_IMPORTED_MODULE_0__["render"],
  _Logo_vue_vue_type_template_id_46f77075__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/@core/layouts/components/Logo.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/@core/layouts/components/Logo.vue?vue&type=template&id=46f77075":
/*!******************************************************************************************!*\
  !*** ./resources/js/src/@core/layouts/components/Logo.vue?vue&type=template&id=46f77075 ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Logo_vue_vue_type_template_id_46f77075__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Logo.vue?vue&type=template&id=46f77075 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/@core/layouts/components/Logo.vue?vue&type=template&id=46f77075");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Logo_vue_vue_type_template_id_46f77075__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Logo_vue_vue_type_template_id_46f77075__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/store/app-config/index.js":
/*!****************************************************!*\
  !*** ./resources/js/src/store/app-config/index.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
/* harmony import */ var core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_exec_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.match.js */ "./node_modules/core-js/modules/es.string.match.js");
/* harmony import */ var core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_match_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _themeConfig__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @themeConfig */ "./resources/js/themeConfig.js");



/* harmony default export */ __webpack_exports__["default"] = ({
  namespaced: true,
  state: {
    layout: {
      isRTL: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.isRTL,
      skin: localStorage.getItem('vuexy-skin') || _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.skin,
      routerTransition: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.routerTransition,
      type: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.type,
      contentWidth: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.contentWidth,
      menu: {
        hidden: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.menu.hidden
      },
      navbar: {
        type: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.navbar.type,
        backgroundColor: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.navbar.backgroundColor
      },
      footer: {
        type: _themeConfig__WEBPACK_IMPORTED_MODULE_2__["$themeConfig"].layout.footer.type
      }
    }
  },
  getters: {},
  mutations: {
    TOGGLE_RTL: function TOGGLE_RTL(state) {
      state.layout.isRTL = !state.layout.isRTL;
      document.documentElement.setAttribute('dir', state.layout.isRTL ? 'rtl' : 'ltr');
    },
    UPDATE_SKIN: function UPDATE_SKIN(state, skin) {
      state.layout.skin = skin;

      // Update value in localStorage
      localStorage.setItem('vuexy-skin', skin);

      // Update DOM for dark-layout
      if (skin === 'dark') document.body.classList.add('dark-layout');else if (document.body.className.match('dark-layout')) document.body.classList.remove('dark-layout');
    },
    UPDATE_ROUTER_TRANSITION: function UPDATE_ROUTER_TRANSITION(state, val) {
      state.layout.routerTransition = val;
    },
    UPDATE_LAYOUT_TYPE: function UPDATE_LAYOUT_TYPE(state, val) {
      state.layout.type = val;
    },
    UPDATE_CONTENT_WIDTH: function UPDATE_CONTENT_WIDTH(state, val) {
      state.layout.contentWidth = val;
    },
    UPDATE_NAV_MENU_HIDDEN: function UPDATE_NAV_MENU_HIDDEN(state, val) {
      state.layout.menu.hidden = val;
    },
    UPDATE_NAVBAR_CONFIG: function UPDATE_NAVBAR_CONFIG(state, obj) {
      Object.assign(state.layout.navbar, obj);
    },
    UPDATE_FOOTER_CONFIG: function UPDATE_FOOTER_CONFIG(state, obj) {
      Object.assign(state.layout.footer, obj);
    }
  },
  actions: {}
});

/***/ }),

/***/ "./resources/js/src/store/app/index.js":
/*!*********************************************!*\
  !*** ./resources/js/src/store/app/index.js ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _themeConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @themeConfig */ "./resources/js/themeConfig.js");

/* harmony default export */ __webpack_exports__["default"] = ({
  namespaced: true,
  state: {
    windowWidth: 0,
    shallShowOverlay: false
  },
  getters: {
    currentBreakPoint: function currentBreakPoint(state) {
      var windowWidth = state.windowWidth;
      if (windowWidth >= _themeConfig__WEBPACK_IMPORTED_MODULE_0__["$themeBreakpoints"].xl) return 'xl';
      if (windowWidth >= _themeConfig__WEBPACK_IMPORTED_MODULE_0__["$themeBreakpoints"].lg) return 'lg';
      if (windowWidth >= _themeConfig__WEBPACK_IMPORTED_MODULE_0__["$themeBreakpoints"].md) return 'md';
      if (windowWidth >= _themeConfig__WEBPACK_IMPORTED_MODULE_0__["$themeBreakpoints"].sm) return 'sm';
      return 'xs';
    }
  },
  mutations: {
    UPDATE_WINDOW_WIDTH: function UPDATE_WINDOW_WIDTH(state, val) {
      state.windowWidth = val;
    },
    TOGGLE_OVERLAY: function TOGGLE_OVERLAY(state, val) {
      state.shallShowOverlay = val !== undefined ? val : !state.shallShowOverlay;
    }
  },
  actions: {}
});

/***/ }),

/***/ "./resources/js/src/store/index.js":
/*!*****************************************!*\
  !*** ./resources/js/src/store/index.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(process) {/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var _views_apps_e_commerce_eCommerceStoreModule__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/views/apps/e-commerce/eCommerceStoreModule */ "./resources/js/src/views/apps/e-commerce/eCommerceStoreModule.js");
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./app */ "./resources/js/src/store/app/index.js");
/* harmony import */ var _app_config__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./app-config */ "./resources/js/src/store/app-config/index.js");
/* harmony import */ var _vertical_menu__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./vertical-menu */ "./resources/js/src/store/vertical-menu/index.js");



// Modules




vue__WEBPACK_IMPORTED_MODULE_0___default.a.use(vuex__WEBPACK_IMPORTED_MODULE_1__["default"]);
/* harmony default export */ __webpack_exports__["default"] = (new vuex__WEBPACK_IMPORTED_MODULE_1__["default"].Store({
  modules: {
    app: _app__WEBPACK_IMPORTED_MODULE_3__["default"],
    appConfig: _app_config__WEBPACK_IMPORTED_MODULE_4__["default"],
    verticalMenu: _vertical_menu__WEBPACK_IMPORTED_MODULE_5__["default"],
    'app-ecommerce': _views_apps_e_commerce_eCommerceStoreModule__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  strict: process.env.DEV
}));
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../node_modules/process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./resources/js/src/store/vertical-menu/index.js":
/*!*******************************************************!*\
  !*** ./resources/js/src/store/vertical-menu/index.js ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _themeConfig__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @themeConfig */ "./resources/js/themeConfig.js");

/* harmony default export */ __webpack_exports__["default"] = ({
  namespaced: true,
  state: {
    isVerticalMenuCollapsed: _themeConfig__WEBPACK_IMPORTED_MODULE_0__["$themeConfig"].layout.menu.isCollapsed
  },
  getters: {},
  mutations: {
    UPDATE_VERTICAL_MENU_COLLAPSED: function UPDATE_VERTICAL_MENU_COLLAPSED(state, val) {
      state.isVerticalMenuCollapsed = val;
    }
  },
  actions: {}
});

/***/ }),

/***/ "./resources/js/src/views/admin/ordermanagement/print_order.vue":
/*!**********************************************************************!*\
  !*** ./resources/js/src/views/admin/ordermanagement/print_order.vue ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _print_order_vue_vue_type_template_id_9b491638_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./print_order.vue?vue&type=template&id=9b491638&scoped=true */ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=template&id=9b491638&scoped=true");
/* harmony import */ var _print_order_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./print_order.vue?vue&type=script&lang=js */ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _print_order_vue_vue_type_style_index_0_id_9b491638_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true */ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true");
/* harmony import */ var _print_order_vue_vue_type_style_index_1_id_9b491638_lang_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./print_order.vue?vue&type=style&index=1&id=9b491638&lang=css */ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css");
/* harmony import */ var _print_order_vue_vue_type_style_index_2_id_9b491638_lang_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss */ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");








/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_5__["default"])(
  _print_order_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _print_order_vue_vue_type_template_id_9b491638_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"],
  _print_order_vue_vue_type_template_id_9b491638_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "9b491638",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/admin/ordermanagement/print_order.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=script&lang=js":
/*!**********************************************************************************************!*\
  !*** ./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true":
/*!*******************************************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true ***!
  \*******************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_0_id_9b491638_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=0&id=9b491638&lang=scss&scoped=true");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_0_id_9b491638_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_0_id_9b491638_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_0_id_9b491638_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_0_id_9b491638_lang_scss_scoped_true__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css ***!
  \******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_1_id_9b491638_lang_css__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader??ref--6-1!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=style&index=1&id=9b491638&lang=css */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=1&id=9b491638&lang=css");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_1_id_9b491638_lang_css__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_1_id_9b491638_lang_css__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_1_id_9b491638_lang_css__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_1_id_9b491638_lang_css__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss ***!
  \*******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_2_id_9b491638_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=style&index=2&id=9b491638&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_2_id_9b491638_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_2_id_9b491638_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_2_id_9b491638_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_style_index_2_id_9b491638_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=template&id=9b491638&scoped=true":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=template&id=9b491638&scoped=true ***!
  \****************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_template_id_9b491638_scoped_true__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./print_order.vue?vue&type=template&id=9b491638&scoped=true */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/ordermanagement/print_order.vue?vue&type=template&id=9b491638&scoped=true");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_template_id_9b491638_scoped_true__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_print_order_vue_vue_type_template_id_9b491638_scoped_true__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/src/views/apps/e-commerce/eCommerceStoreModule.js":
/*!************************************************************************!*\
  !*** ./resources/js/src/views/apps/e-commerce/eCommerceStoreModule.js ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.keys.js */ "./node_modules/core-js/modules/es.object.keys.js");
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @axios */ "./resources/js/src/libs/axios.js");



/* harmony default export */ __webpack_exports__["default"] = ({
  namespaced: true,
  state: {
    cartItemsCount: function () {
      var userData = JSON.parse(localStorage.getItem('userData'));
      return userData && userData.extras ? userData.extras.eCommerceCartItemsCount : 0;
    }()
  },
  getters: {},
  mutations: {
    UPDATE_CART_ITEMS_COUNT: function UPDATE_CART_ITEMS_COUNT(state, count) {
      state.cartItemsCount = count;
    }
  },
  actions: {
    fetchProducts: function fetchProducts(ctx, payload) {
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"].get('/apps/ecommerce/products', {
          params: payload
        }).then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    fetchProduct: function fetchProduct(ctx, _ref) {
      var productId = _ref.productId;
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"].get("/apps/ecommerce/products/".concat(productId)).then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    fetchWishlistProducts: function fetchWishlistProducts() {
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"].get('/apps/ecommerce/wishlist').then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    fetchCartProducts: function fetchCartProducts() {
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"].get('/apps/ecommerce/cart').then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    // ------------------------------------------------
    // Product Actions
    // ------------------------------------------------
    addProductInWishlist: function addProductInWishlist(ctx, _ref2) {
      var productId = _ref2.productId;
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"].post('/apps/ecommerce/wishlist', {
          productId: productId
        }).then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    removeProductFromWishlist: function removeProductFromWishlist(ctx, _ref3) {
      var productId = _ref3.productId;
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"]["delete"]("/apps/ecommerce/wishlist/".concat(productId)).then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    addProductInCart: function addProductInCart(ctx, _ref4) {
      var productId = _ref4.productId;
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"].post('/apps/ecommerce/cart', {
          productId: productId
        }).then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    },
    removeProductFromCart: function removeProductFromCart(ctx, _ref5) {
      var productId = _ref5.productId;
      return new Promise(function (resolve, reject) {
        _axios__WEBPACK_IMPORTED_MODULE_2__["default"]["delete"]("/apps/ecommerce/cart/".concat(productId)).then(function (response) {
          return resolve(response);
        })["catch"](function (error) {
          return reject(error);
        });
      });
    }
  }
});

/***/ })

}]);