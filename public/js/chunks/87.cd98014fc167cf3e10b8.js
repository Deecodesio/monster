(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[87],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=script&lang=js":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/add_page.vue?vue&type=script&lang=js ***!
  \********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
/* harmony import */ var core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_function_name_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var quill_dist_quill_core_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! quill/dist/quill.core.css */ "./node_modules/quill/dist/quill.core.css");
/* harmony import */ var quill_dist_quill_core_css__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(quill_dist_quill_core_css__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var quill_dist_quill_snow_css__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! quill/dist/quill.snow.css */ "./node_modules/quill/dist/quill.snow.css");
/* harmony import */ var quill_dist_quill_snow_css__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(quill_dist_quill_snow_css__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var quill_dist_quill_bubble_css__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! quill/dist/quill.bubble.css */ "./node_modules/quill/dist/quill.bubble.css");
/* harmony import */ var quill_dist_quill_bubble_css__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(quill_dist_quill_bubble_css__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var vee_validate__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! vee-validate */ "./node_modules/vee-validate/dist/vee-validate.esm.js");
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _validations__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @validations */ "./resources/js/src/@core/utils/validations/validations.js");
/* harmony import */ var vue_quill_editor__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! vue-quill-editor */ "./node_modules/vue-quill-editor/dist/vue-quill-editor.js");
/* harmony import */ var vue_quill_editor__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(vue_quill_editor__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @core/components/toastification/ToastificationContent.vue */ "./resources/js/src/@core/components/toastification/ToastificationContent.vue");



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    quillEditor: vue_quill_editor__WEBPACK_IMPORTED_MODULE_9__["quillEditor"],
    ValidationProvider: vee_validate__WEBPACK_IMPORTED_MODULE_6__["ValidationProvider"],
    ValidationObserver: vee_validate__WEBPACK_IMPORTED_MODULE_6__["ValidationObserver"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BCardText"],
    BFormInput: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BFormInput"],
    BFormGroup: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BFormGroup"],
    BForm: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BForm"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BCol"],
    BButton: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BButton"],
    BImg: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BImg"],
    BCard: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BCard"],
    BFormFile: bootstrap_vue__WEBPACK_IMPORTED_MODULE_7__["BFormFile"]
  },
  data: function data() {
    return {
      content: "",
      name: "",
      layout_type: 1,
      index: 1,
      image: ''
    };
  },
  methods: {
    feature_image: function feature_image(image_id) {
      this.layout_type = image_id;
    },
    remove_image: function remove_image(image_id) {
      console.log(image_id);
      if (this.layout_type == image_id) {
        this.layout_type = 0;
      }
    },
    validationForm: function validationForm() {
      var _this = this;
      this.$refs.simpleRules.validate().then(function (success) {
        if (success) {
          var data = new FormData();
          if (_this.$route.params.id) {
            data.append('id', _this.$route.params.id);
          }
          data.append('content_value', _this.content);
          data.append('content_name', _this.name);
          data.append('layout_type', _this.layout_type);
          data.append('image', _this.image);
          _this.$http.post('/admin/add_new_page', data).then(function (response) {
            _this.$toast({
              component: _core_components_toastification_ToastificationContent_vue__WEBPACK_IMPORTED_MODULE_10__["default"],
              position: 'bottom-right',
              props: {
                title: _this.$t(response.data.message),
                icon: "CheckIcon",
                variant: "success"
              }
            });
            if (response.data.status == true) {
              _this.$router.push({
                name: "home_settings"
              });
            }
          })["catch"](function (error) {
            return console.log(error);
          })["finally"](function () {
            return _this.loading = false;
          });
        }
      });
    }
  },
  created: function created() {
    var _this2 = this;
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_pages/' + this.$route.params.id).then(function (res) {
        _this2.content = res.data.data[0].data;
        _this2.name = res.data.page_details.name;
        _this2.layout_type = res.data.page_details.layout_type;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".imgoverlay {\n  position: absolute;\n  top: 0;\n  width: 100%;\n  height: 100%;\n  transition: background 0.5s ease;\n  display: flex !important;\n}[dir] .imgoverlay {\n  background: rgba(0, 0, 0, 0);\n}[dir=ltr] .imgoverlay {\n  left: 0;\n}[dir=rtl] .imgoverlay {\n  right: 0;\n}\n.imgcontainer:hover .imgoverlay {\n  display: block;\n}\n[dir] .imgcontainer:hover .imgoverlay {\n  background: rgba(0, 0, 0, 0.3);\n}\n.imgbutton {\n  width: 500px;\n  height: 50px;\n  justify-content: center;\n  align-self: center;\n  opacity: 0;\n  transition: opacity 0.35s ease;\n  display: flex;\n  align-items: center;\n  justify-content: center;\n}\n.imgbutton a {\n  width: 200px;\n  color: white;\n  z-index: 1;\n}\n[dir] .imgbutton a {\n  padding: 12px 48px;\n  text-align: center;\n  border: solid 2px white;\n}\n.imgcontainer:hover .imgbutton {\n  opacity: 1;\n}\n[dir] .featured {\n  border-style: solid !important;\n  border-color: #E01764 !important;\n  border-width: thick !important;\n}", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/sass-loader/dist/cjs.js??ref--7-3!./node_modules/sass-loader/dist/cjs.js??ref--11-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss */ "./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss");

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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=template&id=fa17f230":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/src/views/admin/contents/add_page.vue?vue&type=template&id=fa17f230 ***!
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
    "div",
    [
      _c(
        "b-card",
        [
          _c(
            "validation-observer",
            { ref: "simpleRules" },
            [
              _c(
                "b-form",
                [
                  _c(
                    "b-form-group",
                    { attrs: { label: _vm.$t("Section Name") } },
                    [
                      _c("validation-provider", {
                        attrs: { name: "Section Name", rules: "required" },
                        scopedSlots: _vm._u([
                          {
                            key: "default",
                            fn: function (ref) {
                              var errors = ref.errors
                              return [
                                _c("b-form-input", {
                                  attrs: {
                                    state: errors.length > 0 ? false : null,
                                    placeholder: "Section Name",
                                  },
                                  model: {
                                    value: _vm.name,
                                    callback: function ($$v) {
                                      _vm.name = $$v
                                    },
                                    expression: "name",
                                  },
                                }),
                                _vm._v(" "),
                                _c("small", { staticClass: "text-danger" }, [
                                  _vm._v(_vm._s(errors[0])),
                                ]),
                              ]
                            },
                          },
                        ]),
                      }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "b-form-group",
                    { attrs: { label: _vm.$t("Layout Type") } },
                    [
                      _c(
                        "b-row",
                        [
                          _c("b-col", { attrs: { cols: "6" } }, [
                            _c(
                              "div",
                              { staticClass: "imgcontainer" },
                              [
                                _c("b-img", {
                                  staticClass: "mt-1",
                                  class: {
                                    "mt-1 featured": _vm.layout_type == 1,
                                    "mt-1": _vm.layout_type != 1,
                                  },
                                  staticStyle: { width: "125%", padding: "1%" },
                                  attrs: {
                                    thumbnail: "",
                                    src: "/rightimg.png",
                                    rounded: "",
                                    fluid: "",
                                    alt: "banner photo",
                                    id: "lazy",
                                    id: "imgs" + _vm.index + "r",
                                  },
                                }),
                                _vm._v(" "),
                                _c("div", { staticClass: "imgoverlay" }, [
                                  _c(
                                    "div",
                                    { staticClass: "imgbutton" },
                                    [
                                      _vm.layout_type != 1
                                        ? _c(
                                            "b-button",
                                            {
                                              directives: [
                                                {
                                                  name: "ripple",
                                                  rawName: "v-ripple.400",
                                                  value:
                                                    "rgba(255, 255, 255, 0.15)",
                                                  expression:
                                                    "'rgba(255, 255, 255, 0.15)'",
                                                  modifiers: { 400: true },
                                                },
                                              ],
                                              staticClass: "mr-1",
                                              attrs: { variant: "primary" },
                                              on: {
                                                click: function ($event) {
                                                  return _vm.feature_image(1)
                                                },
                                              },
                                            },
                                            [
                                              _c("feather-icon", {
                                                attrs: {
                                                  icon: "CheckCircleIcon",
                                                  size: "16",
                                                },
                                              }),
                                            ],
                                            1
                                          )
                                        : _vm._e(),
                                    ],
                                    1
                                  ),
                                ]),
                              ],
                              1
                            ),
                          ]),
                          _vm._v(" "),
                          _c("b-col", { attrs: { cols: "6" } }, [
                            _c(
                              "div",
                              { staticClass: "imgcontainer" },
                              [
                                _c("b-img", {
                                  staticClass: "mt-1",
                                  class: {
                                    "mt-1 featured": _vm.layout_type == 2,
                                    "mt-1": _vm.layout_type != 2,
                                  },
                                  staticStyle: { width: "125%", padding: "1%" },
                                  attrs: {
                                    thumbnail: "",
                                    src: "/leftimg.png",
                                    rounded: "",
                                    fluid: "",
                                    alt: "banner photo",
                                    id: "lazy",
                                    id: "imgs" + _vm.index + "r",
                                  },
                                }),
                                _vm._v(" "),
                                _c("div", { staticClass: "imgoverlay" }, [
                                  _c(
                                    "div",
                                    { staticClass: "imgbutton" },
                                    [
                                      _vm.layout_type != 2
                                        ? _c(
                                            "b-button",
                                            {
                                              directives: [
                                                {
                                                  name: "ripple",
                                                  rawName: "v-ripple.400",
                                                  value:
                                                    "rgba(255, 255, 255, 0.15)",
                                                  expression:
                                                    "'rgba(255, 255, 255, 0.15)'",
                                                  modifiers: { 400: true },
                                                },
                                              ],
                                              staticClass: "mr-1",
                                              attrs: { variant: "primary" },
                                              on: {
                                                click: function ($event) {
                                                  return _vm.feature_image(2)
                                                },
                                              },
                                            },
                                            [
                                              _c("feather-icon", {
                                                attrs: {
                                                  icon: "CheckCircleIcon",
                                                  size: "16",
                                                },
                                              }),
                                            ],
                                            1
                                          )
                                        : _vm._e(),
                                    ],
                                    1
                                  ),
                                ]),
                              ],
                              1
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
                    "b-row",
                    [
                      _c(
                        "b-col",
                        { attrs: { md: "12" } },
                        [
                          _c(
                            "b-form-group",
                            { attrs: { label: _vm.$t("Image") } },
                            [
                              _c("validation-provider", {
                                attrs: { name: "Image", rules: "required" },
                                scopedSlots: _vm._u([
                                  {
                                    key: "default",
                                    fn: function (ref) {
                                      var errors = ref.errors
                                      return [
                                        _c("b-form-file", {
                                          attrs: {
                                            id: "wildcard",
                                            accept: "image/*",
                                            required: "",
                                          },
                                          model: {
                                            value: _vm.image,
                                            callback: function ($$v) {
                                              _vm.image = $$v
                                            },
                                            expression: "image",
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
                            { attrs: { label: _vm.$t("Page Content") } },
                            [
                              _c("validation-provider", {
                                attrs: { name: "Content", rules: "required" },
                                scopedSlots: _vm._u([
                                  {
                                    key: "default",
                                    fn: function (ref) {
                                      var errors = ref.errors
                                      return [
                                        _c("quill-editor", {
                                          model: {
                                            value: _vm.content,
                                            callback: function ($$v) {
                                              _vm.content = $$v
                                            },
                                            expression: "content",
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
                                  return _vm.validationForm($event)
                                },
                              },
                            },
                            [
                              _vm._v(
                                "\n                            " +
                                  _vm._s(_vm.$t("Submit")) +
                                  "\n                        "
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

/***/ "./resources/js/src/views/admin/contents/add_page.vue":
/*!************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/add_page.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _add_page_vue_vue_type_template_id_fa17f230__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./add_page.vue?vue&type=template&id=fa17f230 */ "./resources/js/src/views/admin/contents/add_page.vue?vue&type=template&id=fa17f230");
/* harmony import */ var _add_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./add_page.vue?vue&type=script&lang=js */ "./resources/js/src/views/admin/contents/add_page.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _add_page_vue_vue_type_style_index_0_id_fa17f230_lang_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss */ "./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _add_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _add_page_vue_vue_type_template_id_fa17f230__WEBPACK_IMPORTED_MODULE_0__["render"],
  _add_page_vue_vue_type_template_id_fa17f230__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/src/views/admin/contents/add_page.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/src/views/admin/contents/add_page.vue?vue&type=script&lang=js":
/*!************************************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/add_page.vue?vue&type=script&lang=js ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./add_page.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss ***!
  \*********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_style_index_0_id_fa17f230_lang_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader!../../../../../../node_modules/css-loader!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/src??ref--7-2!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--7-3!../../../../../../node_modules/sass-loader/dist/cjs.js??ref--11-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/sass-loader/dist/cjs.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=style&index=0&id=fa17f230&lang=scss");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_style_index_0_id_fa17f230_lang_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_style_index_0_id_fa17f230_lang_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_style_index_0_id_fa17f230_lang_scss__WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_sass_loader_dist_cjs_js_ref_7_3_node_modules_sass_loader_dist_cjs_js_ref_11_0_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_style_index_0_id_fa17f230_lang_scss__WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/src/views/admin/contents/add_page.vue?vue&type=template&id=fa17f230":
/*!******************************************************************************************!*\
  !*** ./resources/js/src/views/admin/contents/add_page.vue?vue&type=template&id=fa17f230 ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_template_id_fa17f230__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./add_page.vue?vue&type=template&id=fa17f230 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/src/views/admin/contents/add_page.vue?vue&type=template&id=fa17f230");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_template_id_fa17f230__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_add_page_vue_vue_type_template_id_fa17f230__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);