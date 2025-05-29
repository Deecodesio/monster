(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[310],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/Embed.vue?vue&type=script&lang=js":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/components/embed/Embed.vue?vue&type=script&lang=js ***!
  \*************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _EmbedTypes_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EmbedTypes.vue */ "./resources/js/store_src/views/components/embed/EmbedTypes.vue");
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
    EmbedTypes: _EmbedTypes_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    BRow: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BRow"],
    BCol: bootstrap_vue__WEBPACK_IMPORTED_MODULE_0__["BCol"]
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=script&lang=js":
/*!******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=script&lang=js ***!
  \******************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _core_components_b_card_code__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @core/components/b-card-code */ "./resources/js/src/@core/components/b-card-code/index.js");
/* harmony import */ var bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! bootstrap-vue */ "./node_modules/bootstrap-vue/esm/index.js");
/* harmony import */ var _code__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./code */ "./resources/js/store_src/views/components/embed/code.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    BCardCode: _core_components_b_card_code__WEBPACK_IMPORTED_MODULE_0__["default"],
    BEmbed: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BEmbed"],
    BCardText: bootstrap_vue__WEBPACK_IMPORTED_MODULE_1__["BCardText"]
  },
  data: function data() {
    return {
      codeTypes: _code__WEBPACK_IMPORTED_MODULE_2__["codeTypes"]
    };
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/Embed.vue?vue&type=template&id=590a4d32":
/*!*****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/components/embed/Embed.vue?vue&type=template&id=590a4d32 ***!
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
    [_c("b-col", { attrs: { cols: "12" } }, [_c("embed-types")], 1)],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=template&id=a12f87d2":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=template&id=a12f87d2 ***!
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
  return _c(
    "b-card-code",
    {
      attrs: { title: "Embed types" },
      scopedSlots: _vm._u([
        {
          key: "code",
          fn: function () {
            return [_vm._v("\n    " + _vm._s(_vm.codeTypes) + "\n  ")]
          },
          proxy: true,
        },
      ]),
    },
    [
      _c("b-card-text", [
        _c("span", [
          _vm._v(
            "Supported embed types are iframe (default), video, embed and object, which translate to the standard HTML "
          ),
        ]),
        _vm._v(" "),
        _c("code", [_vm._v("<iframe>")]),
        _vm._v(" "),
        _c("span", [_vm._v(" , ")]),
        _vm._v(" "),
        _c("code", [_vm._v("<video>")]),
        _vm._v(" "),
        _c("span", [_vm._v(" , ")]),
        _vm._v(" "),
        _c("code", [_vm._v("<embed>")]),
        _vm._v(" "),
        _c("span", [_vm._v(" and ")]),
        _vm._v(" "),
        _c("code", [_vm._v("<object>")]),
        _vm._v(" "),
        _c("span", [_vm._v(" elements.")]),
      ]),
      _vm._v(" "),
      _c(
        "div",
        [
          _c("b-embed", {
            attrs: {
              type: "iframe",
              aspect: "16by9",
              src: "https://www.youtube.com/embed/zpOULjyy-n8?rel=0",
              allowfullscreen: "",
            },
          }),
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

/***/ "./resources/js/store_src/views/components/embed/Embed.vue":
/*!*****************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/Embed.vue ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Embed_vue_vue_type_template_id_590a4d32__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Embed.vue?vue&type=template&id=590a4d32 */ "./resources/js/store_src/views/components/embed/Embed.vue?vue&type=template&id=590a4d32");
/* harmony import */ var _Embed_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Embed.vue?vue&type=script&lang=js */ "./resources/js/store_src/views/components/embed/Embed.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Embed_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _Embed_vue_vue_type_template_id_590a4d32__WEBPACK_IMPORTED_MODULE_0__["render"],
  _Embed_vue_vue_type_template_id_590a4d32__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/store_src/views/components/embed/Embed.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/store_src/views/components/embed/Embed.vue?vue&type=script&lang=js":
/*!*****************************************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/Embed.vue?vue&type=script&lang=js ***!
  \*****************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Embed_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Embed.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/Embed.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Embed_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/store_src/views/components/embed/Embed.vue?vue&type=template&id=590a4d32":
/*!***********************************************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/Embed.vue?vue&type=template&id=590a4d32 ***!
  \***********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Embed_vue_vue_type_template_id_590a4d32__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Embed.vue?vue&type=template&id=590a4d32 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/Embed.vue?vue&type=template&id=590a4d32");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Embed_vue_vue_type_template_id_590a4d32__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Embed_vue_vue_type_template_id_590a4d32__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/store_src/views/components/embed/EmbedTypes.vue":
/*!**********************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/EmbedTypes.vue ***!
  \**********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _EmbedTypes_vue_vue_type_template_id_a12f87d2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EmbedTypes.vue?vue&type=template&id=a12f87d2 */ "./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=template&id=a12f87d2");
/* harmony import */ var _EmbedTypes_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EmbedTypes.vue?vue&type=script&lang=js */ "./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EmbedTypes_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  _EmbedTypes_vue_vue_type_template_id_a12f87d2__WEBPACK_IMPORTED_MODULE_0__["render"],
  _EmbedTypes_vue_vue_type_template_id_a12f87d2__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/store_src/views/components/embed/EmbedTypes.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=script&lang=js":
/*!**********************************************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=script&lang=js ***!
  \**********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EmbedTypes_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EmbedTypes.vue?vue&type=script&lang=js */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=script&lang=js");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EmbedTypes_vue_vue_type_script_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=template&id=a12f87d2":
/*!****************************************************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=template&id=a12f87d2 ***!
  \****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EmbedTypes_vue_vue_type_template_id_a12f87d2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EmbedTypes.vue?vue&type=template&id=a12f87d2 */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/store_src/views/components/embed/EmbedTypes.vue?vue&type=template&id=a12f87d2");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EmbedTypes_vue_vue_type_template_id_a12f87d2__WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EmbedTypes_vue_vue_type_template_id_a12f87d2__WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/store_src/views/components/embed/code.js":
/*!***************************************************************!*\
  !*** ./resources/js/store_src/views/components/embed/code.js ***!
  \***************************************************************/
/*! exports provided: codeTypes, codeDefault */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeTypes", function() { return codeTypes; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "codeDefault", function() { return codeDefault; });
var codeTypes = "\n<template>\n  <div>\n    <b-embed type=\"iframe\" aspect=\"16by9\" src=\"https://www.youtube.com/embed/zpOULjyy-n8?rel=0\" allowfullscreen/>\n  </div>\n</template>\n\n<script>\nimport { BEmbed } from 'bootstrap-vue'\n\nexport default {\n  components: {\n    BEmbed,\n  },\n}\n</script>\n";
var codeDefault = '';

/***/ })

}]);