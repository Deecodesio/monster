(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[352],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=script&lang=js":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=script&lang=js ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_array_splice_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.splice.js */ "./node_modules/core-js/modules/es.array.splice.js");
/* harmony import */ var core_js_modules_es_array_splice_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_splice_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_3__["BImg"]
  },
  data: function data() {
    return {
      isopen: 0,
      title1: "Featured",
      title2: "Recent Products",
      page_content: "",
      image: "",
      layout_type: 1,
      page: {
        name: '',
        category: '',
        sub_category: '',
        segments: [{
          id: 0,
          type: 1,
          image: '',
          content: '',
          existing: ''
        }]
      }
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
    this.$http.get('/admin/fetch_header_page/' + this.$route.params.id).then(function (res) {
      console.log(res);
      _this.page.name = res.data.header_pages.page_name;
      _this.page.category = res.data.header_pages.header_category;
      _this.page.sub_category = res.data.header_pages.header_sub_category;
      if (res.data.page_contents && res.data.page_contents.length) {
        _this.page.segments.splice(0, 1);
        for (var i = 0; i < res.data.page_contents.length; i++) {
          var temp = {};
          temp.type = res.data.page_contents[i].type;
          temp.content = res.data.page_contents[i].content[i].data;
          temp.image = res.data.page_contents[i].image;
          temp.existing = 1;
          _this.page.segments.push(temp);
        }
      }
    });
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "/*=========================================================================================\n    File Name: ext-component-swiper.scss\n    Description: swiper plugin scss.\n    ----------------------------------------------------------------------------------------\n    Item Name: Vuexy - Vuejs, React, Angular, HTML & Laravel Admin Dashboard Template\n    Author: PIXINVENT\n    Author URL: http://www.themeforest.net/user/pixinvent\n==========================================================================================*/\n/* Swiper css */\n/* ---------- */\n/* swiper slide shadow */\n[dir=ltr] .swiper-container .swiper-shadow {\n  box-shadow: 2px 8px 10px 0 rgba(25, 42, 70, 0.13) !important;\n}\n[dir=rtl] .swiper-container .swiper-shadow {\n  box-shadow: -2px 8px 10px 0 rgba(25, 42, 70, 0.13) !important;\n}\n.swiper-pagination .swiper-pagination-bullet:focus {\n  outline: none;\n}\n[dir] .swiper-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active {\n  background-color: #E01764;\n}\n[dir] .swiper-pagination.swiper-pagination-progressbar .swiper-pagination-progressbar-fill {\n  background-color: #E01764;\n}\n.swiper-centered-slides.swiper-container .swiper-slide {\n  font-weight: 500;\n  height: auto;\n  width: auto !important;\n}\n[dir] .swiper-centered-slides.swiper-container .swiper-slide {\n  text-align: center;\n  background-color: #fff;\n  padding: 2rem 5.5rem;\n  cursor: pointer;\n}\n[dir] .swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active {\n  border: 2px solid #E01764;\n}\n.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active i,\n.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active svg {\n  color: #E01764;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n[dir] .swiper-centered-slides .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n.swiper-centered-slides .swiper-button-next,\n.swiper-centered-slides .swiper-button-prev {\n  height: 40px !important;\n  width: 40px !important;\n}\n.swiper-centered-slides .swiper-button-next:after,\n.swiper-centered-slides .swiper-button-prev:after {\n  height: 40px !important;\n  width: 40px !important;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after, [dir] .swiper-centered-slides .swiper-button-prev:after {\n  border-radius: 50%;\n  background-color: #E01764;\n  box-shadow: 0 2px 4px 0 rgba(34, 41, 47, 0.5) !important;\n  background-size: 24px !important;\n}\n[dir] .swiper-centered-slides.swiper-container-rtl .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n[dir] .swiper-centered-slides.swiper-container-rtl .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n}\n.swiper-centered-slides-2.swiper-container .swiper-slide {\n  font-weight: 500;\n  height: auto;\n  width: auto !important;\n}\n[dir] .swiper-centered-slides-2.swiper-container .swiper-slide {\n  background-color: #f2f4f4;\n  cursor: pointer;\n}\n.swiper-centered-slides-2.swiper-container .swiper-slide.swiper-slide-active {\n  color: #fff;\n}\n[dir] .swiper-centered-slides-2.swiper-container .swiper-slide.swiper-slide-active {\n  background-color: #E01764 !important;\n  box-shadow: 0 3px 6px 0 rgba(224, 23, 100, 0.5) !important;\n}\n\n/* cube effect */\n.swiper-cube-effect.swiper-container {\n  width: 300px;\n}\n[dir] .swiper-cube-effect.swiper-container {\n  margin-top: -12px;\n}\n[dir=ltr] .swiper-cube-effect.swiper-container {\n  left: 50%;\n  margin-left: -150px;\n}\n[dir=rtl] .swiper-cube-effect.swiper-container {\n  right: 50%;\n  margin-right: -150px;\n}\n\n/* swiper coverflow slide width */\n.swiper-coverflow.swiper-container .swiper-slide {\n  width: 300px;\n}\n[dir] .gallery-thumbs {\n  padding: 10px 0;\n  background: #22292f;\n}\n.gallery-thumbs .swiper-slide {\n  opacity: 0.4;\n}\n.gallery-thumbs .swiper-slide-thumb-active {\n  opacity: 1;\n}\n[dir] .swiper-parallax .swiper-slide {\n  padding: 2.67rem 4rem;\n}\n.swiper-parallax .swiper-slide .title {\n  font-size: 1.07rem;\n}\n[dir] .swiper-parallax .swiper-slide .title {\n  padding: 0.5rem 0;\n}\n.swiper-parallax .swiper-slide .text {\n  font-size: 1rem;\n}\n.swiper-parallax .parallax-bg {\n  position: absolute;\n  width: 130%;\n}\n.swiper-virtual.swiper-container {\n  height: 300px;\n}\n.swiper-virtual.swiper-container .swiper-slide {\n  /* virtual slides  */\n  font-size: 1.5rem;\n  display: flex;\n  justify-content: center;\n  align-items: center;\n}\n[dir] .swiper-virtual.swiper-container .swiper-slide {\n  background-color: #eee;\n}\n.swiper-button-prev,\n.swiper-button-next,\n.swiper-container-rtl .swiper-button-prev,\n.swiper-container-rtl .swiper-button-next {\n  color: #fff;\n  width: 38px;\n  font-size: 2rem;\n}\n[dir] .swiper-button-prev, [dir] .swiper-button-next, [dir] .swiper-container-rtl .swiper-button-prev, [dir] .swiper-container-rtl .swiper-button-next {\n  background-image: none;\n}\n.swiper-button-prev:focus,\n.swiper-button-next:focus,\n.swiper-container-rtl .swiper-button-prev:focus,\n.swiper-container-rtl .swiper-button-next:focus {\n  outline: none;\n}\n.swiper-button-prev:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n[dir=ltr] .swiper-button-prev:after {\n  padding-right: 1px;\n}\n[dir=rtl] .swiper-button-prev:after {\n  padding-left: 1px;\n}\n.swiper-button-next:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n[dir=ltr] .swiper-button-next:after {\n  padding-right: 2px;\n}\n[dir=rtl] .swiper-button-next:after {\n  padding-left: 2px;\n}\n.swiper-container-rtl .swiper-button-prev:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-container-rtl .swiper-button-prev:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-right'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n.swiper-container-rtl .swiper-button-next:after {\n  color: #6e6b7b;\n  width: 44px;\n  height: 44px;\n  content: \"\";\n}\n[dir] .swiper-container-rtl .swiper-button-next:after {\n  background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23E01764' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-left'%3E%3Cpolyline points='15 18 9 12 15 6'%3E%3C/polyline%3E%3C/svg%3E\");\n  background-repeat: no-repeat;\n  background-position: center;\n  background-size: 44px;\n}\n@media only screen and (max-width: 768px) {\n.swiper-button-prev {\n    font-size: 1.286rem;\n    top: 55%;\n}\n.swiper-button-prev:after {\n    height: 28px;\n    width: 28px;\n}\n[dir] .swiper-button-prev:after {\n    background-size: 24px;\n}\n.swiper-button-next {\n    font-size: 1.286rem;\n    top: 55%;\n}\n[dir] .swiper-button-next:after {\n    background-size: 24px;\n}\n.swiper-centered-slides .swiper-button-next:after,\n.swiper-centered-slides .swiper-button-prev:after {\n    height: 28px;\n    width: 28px;\n}\n[dir] .swiper-centered-slides .swiper-button-next:after, [dir] .swiper-centered-slides .swiper-button-prev:after {\n    background-size: 18px;\n}\n[dir] .swiper-parallax .swiper-slide {\n    padding: 1rem 1.2rem;\n}\n.swiper-parallax img {\n    height: 100% !important;\n}\n}\n@media only screen and (max-width: 576px) {\n[dir] .swiper-centered-slides.swiper-container .swiper-slide {\n    padding: 1.6rem 2.5rem;\n}\n.swiper-centered-slides.swiper-container .swiper-slide i,\n.swiper-centered-slides.swiper-container .swiper-slide svg {\n    height: 1.07rem !important;\n    width: 1.07rem !important;\n    font-size: 1.07rem !important;\n}\n.swiper-cube-effect.swiper-container {\n    width: 150px;\n}\n[dir=ltr] .swiper-cube-effect.swiper-container {\n    left: 70%;\n}\n[dir=rtl] .swiper-cube-effect.swiper-container {\n    right: 70%;\n}\n[dir] .swiper-parallax .swiper-slide {\n    padding: 1rem 1.3rem;\n}\n.swiper-virtual.swiper-container .swiper-slide {\n    font-size: 1rem;\n}\n}\n[dir] .dark-layout .swiper-container:not(.swiper-parallax) .swiper-slide {\n  background-color: #161d31;\n}\n[dir] .dark-layout .swiper-container.swiper-centered-slides .swiper-slide {\n  background-color: #283046;\n}\n.dark-layout .swiper-container.swiper-parallax .swiper-slide * {\n  color: #6e6b7b;\n}\n.swiper-slide.swiper-slide-active {\n  opacity: 1;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=template&id=362f99e2":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=template&id=362f99e2 ***!
  \********************************************************************************************************************************************************************************************************************************/
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
          _vm._l(_vm.page.segments, function (item, index) {
            return _c(
              "div",
              { key: item.id, attrs: { id: item.id } },
              [
                item.type == 2
                  ? _c(
                      "b-row",
                      [
                        _c(
                          "b-col",
                          { attrs: { lg: "6", md: "6", sm: "12" } },
                          [
                            _c("b-img", {
                              attrs: {
                                src: item.image,
                                fluid: "",
                                alt: "Scan",
                              },
                            }),
                          ],
                          1
                        ),
                        _vm._v(" "),
                        _c("b-col", { attrs: { lg: "6", md: "6", sm: "12" } }, [
                          _c("div", {
                            domProps: { innerHTML: _vm._s(item.content) },
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
                            domProps: { innerHTML: _vm._s(item.content) },
                          }),
                        ]),
                        _vm._v(" "),
                        _c(
                          "b-col",
                          { attrs: { lg: "6", md: "6", sm: "12" } },
                          [
                            _c("b-img", {
                              attrs: {
                                src: item.image,
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
          }),
          0
        ),
      ]),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/src/views/admin/contents/view_header_page.vue":
/*!********************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/view_header_page.vue ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _view_header_page_vue_vue_type_template_id_362f99e2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./view_header_page.vue?vue&type=template&id=362f99e2 */ "./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=template&id=362f99e2");
/* harmony import */ var _view_header_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./view_header_page.vue?vue&type=script&lang=js */ "./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _view_header_page_vue_vue_type_style_index_0_id_362f99e2_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss */ "./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _view_header_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _view_header_page_vue_vue_type_template_id_362f99e2__WEBPACK_IMPORTED_MODULE_0__["render"],
  _view_header_page_vue_vue_type_template_id_362f99e2__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/admin/contents/view_header_page.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=script&lang=js":
/*!********************************************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=script&lang=js ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./view_header_page.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss ***!
  \*****************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_style_index_0_id_362f99e2_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=style&index=0&id=362f99e2&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_style_index_0_id_362f99e2_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_style_index_0_id_362f99e2_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_style_index_0_id_362f99e2_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_style_index_0_id_362f99e2_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=template&id=362f99e2":
/*!**************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=template&id=362f99e2 ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_template_id_362f99e2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./view_header_page.vue?vue&type=template&id=362f99e2 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/view_header_page.vue?vue&type=template&id=362f99e2");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_template_id_362f99e2__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_view_header_page_vue_vue_type_template_id_362f99e2__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);