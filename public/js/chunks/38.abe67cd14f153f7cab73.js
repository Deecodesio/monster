(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[38],{

/***/ "./resources/js/src/@core/utils/utils.js":
/*!***********************************************!*\
  !*** ./resources/js/src/@core/utils/utils.js ***!
  \***********************************************/
/*! exports provided: isObject, isToday, getRandomBsVariant, isDynamicRouteActive, useRouter */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isObject", function() { return isObject; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isToday", function() { return isToday; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getRandomBsVariant", function() { return getRandomBsVariant; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isDynamicRouteActive", function() { return isDynamicRouteActive; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useRouter", function() { return useRouter; });
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/objectSpread2.js */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_typeof_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/router */ "./resources/js/src/router/index.js");
/* harmony import */ var _vue_composition_api__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @vue/composition-api */ "./node_modules/@vue/composition-api/dist/vue-composition-api.mjs");



// eslint-disable-next-line object-curly-newline

var isObject = function isObject(obj) {
  return Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_typeof_js__WEBPACK_IMPORTED_MODULE_1__["default"])(obj) === 'object' && obj !== null;
};
var isToday = function isToday(date) {
  var today = new Date();
  return /* eslint-disable operator-linebreak */(
    date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear()
    /* eslint-enable */
  );
};
var getRandomFromArray = function getRandomFromArray(array) {
  return array[Math.floor(Math.random() * array.length)];
};

// ? Light and Dark variant is not included
// prettier-ignore
var getRandomBsVariant = function getRandomBsVariant() {
  return getRandomFromArray(['primary', 'secondary', 'success', 'warning', 'danger', 'info']);
};
var isDynamicRouteActive = function isDynamicRouteActive(route) {
  var _router$resolve = _router__WEBPACK_IMPORTED_MODULE_2__["default"].resolve(route),
    resolvedRoute = _router$resolve.route;
  return resolvedRoute.path === _router__WEBPACK_IMPORTED_MODULE_2__["default"].currentRoute.path;
};

// Thanks: https://medium.com/better-programming/reactive-vue-routes-with-the-composition-api-18c1abd878d1
var useRouter = function useRouter() {
  var vm = Object(_vue_composition_api__WEBPACK_IMPORTED_MODULE_3__["getCurrentInstance"])().proxy;
  var state = Object(_vue_composition_api__WEBPACK_IMPORTED_MODULE_3__["reactive"])({
    route: vm.$route
  });
  Object(_vue_composition_api__WEBPACK_IMPORTED_MODULE_3__["watch"])(function () {
    return vm.$route;
  }, function (r) {
    state.route = r;
  });
  return Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__["default"])(Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__["default"])({}, Object(_vue_composition_api__WEBPACK_IMPORTED_MODULE_3__["toRefs"])(state)), {}, {
    router: vm.$router
  });
};

/**
 * This is just enhancement over Object.extend [Gives deep extend]
 * @param {target} a Object which contains values to be overridden
 * @param {source} b Object which contains values to override
 */
// export const objectExtend = (a, b) => {
//   // Don't touch 'null' or 'undefined' objects.
//   if (a == null || b == null) {
//     return a
//   }

//   Object.keys(b).forEach(key => {
//     if (Object.prototype.toString.call(b[key]) === '[object Object]') {
//       if (Object.prototype.toString.call(a[key]) !== '[object Object]') {
//         // eslint-disable-next-line no-param-reassign
//         a[key] = b[key]
//       } else {
//         // eslint-disable-next-line no-param-reassign
//         a[key] = objectExtend(a[key], b[key])
//       }
//     } else {
//       // eslint-disable-next-line no-param-reassign
//       a[key] = b[key]
//     }
//   })

//   return a
// }

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

/***/ }),

/***/ "./resources/js/src/views/apps/e-commerce/useEcommerce.js":
/*!****************************************************************!*\
  !*** ./resources/js/src/views/apps/e-commerce/useEcommerce.js ***!
  \****************************************************************/
/*! exports provided: useEcommerce, useEcommerceUi */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useEcommerce", function() { return useEcommerce; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "useEcommerceUi", function() { return useEcommerceUi; });
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _store__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/store */ "./resources/js/src/store/index.js");
/* harmony import */ var _core_utils_utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @core/utils/utils */ "./resources/js/src/@core/utils/utils.js");



var useEcommerce = function useEcommerce() {
  // eslint-disable-next-line arrow-body-style
  var addProductInWishlist = function addProductInWishlist(productId) {
    return _store__WEBPACK_IMPORTED_MODULE_1__["default"].dispatch('app-ecommerce/addProductInWishlist', {
      productId: productId
    });
  };

  // eslint-disable-next-line arrow-body-style
  var removeProductFromWishlist = function removeProductFromWishlist(productId) {
    return _store__WEBPACK_IMPORTED_MODULE_1__["default"].dispatch('app-ecommerce/removeProductFromWishlist', {
      productId: productId
    });
  };

  // eslint-disable-next-line arrow-body-style
  var addProductInCart = function addProductInCart(productId) {
    return _store__WEBPACK_IMPORTED_MODULE_1__["default"].dispatch('app-ecommerce/addProductInCart', {
      productId: productId
    });
  };

  // eslint-disable-next-line arrow-body-style
  var removeProductFromCart = function removeProductFromCart(productId) {
    return _store__WEBPACK_IMPORTED_MODULE_1__["default"].dispatch('app-ecommerce/removeProductFromCart', {
      productId: productId
    });
  };
  return {
    addProductInWishlist: addProductInWishlist,
    removeProductFromWishlist: removeProductFromWishlist,
    addProductInCart: addProductInCart,
    removeProductFromCart: removeProductFromCart
  };
};
var useEcommerceUi = function useEcommerceUi() {
  var _useRouter = Object(_core_utils_utils__WEBPACK_IMPORTED_MODULE_2__["useRouter"])(),
    router = _useRouter.router;
  var toggleProductInWishlist = function toggleProductInWishlist(product) {
    var _useEcommerce = useEcommerce(),
      addProductInWishlist = _useEcommerce.addProductInWishlist,
      removeProductFromWishlist = _useEcommerce.removeProductFromWishlist;
    if (product.isInWishlist) {
      removeProductFromWishlist(product.id).then(function () {
        // eslint-disable-next-line no-param-reassign
        product.isInWishlist = false;
      });
    } else {
      addProductInWishlist(product.id).then(function () {
        // eslint-disable-next-line no-param-reassign
        product.isInWishlist = true;
      });
    }
  };
  var handleCartActionClick = function handleCartActionClick(product) {
    var _useEcommerce2 = useEcommerce(),
      addProductInCart = _useEcommerce2.addProductInCart;
    if (product.isInCart) {
      router.push({
        name: 'apps-e-commerce-checkout'
      });
    } else {
      addProductInCart(product.id).then(function () {
        // eslint-disable-next-line no-param-reassign
        product.isInCart = true;

        // Update cart items count
        _store__WEBPACK_IMPORTED_MODULE_1__["default"].commit('app-ecommerce/UPDATE_CART_ITEMS_COUNT', _store__WEBPACK_IMPORTED_MODULE_1__["default"].state['app-ecommerce'].cartItemsCount + 1);
      });
    }
  };
  var handleWishlistCartActionClick = function handleWishlistCartActionClick(product, removeProductFromWishlistUi) {
    var _useEcommerce3 = useEcommerce(),
      addProductInCart = _useEcommerce3.addProductInCart,
      removeProductFromWishlist = _useEcommerce3.removeProductFromWishlist;
    if (product.isInCart) {
      router.push({
        name: 'apps-e-commerce-checkout'
      });
    } else {
      addProductInCart(product.id).then(function () {
        // eslint-disable-next-line no-param-reassign
        product.isInCart = true;
        removeProductFromWishlist(product.id);

        // Update cart items count
        _store__WEBPACK_IMPORTED_MODULE_1__["default"].commit('app-ecommerce/UPDATE_CART_ITEMS_COUNT', _store__WEBPACK_IMPORTED_MODULE_1__["default"].state['app-ecommerce'].cartItemsCount + 1);
      }).then(function () {
        // eslint-disable-next-line no-param-reassign
        product.isInWishlist = false;
        removeProductFromWishlistUi(product);
      });
    }
  };
  return {
    toggleProductInWishlist: toggleProductInWishlist,
    handleCartActionClick: handleCartActionClick,
    handleWishlistCartActionClick: handleWishlistCartActionClick
  };
};

/***/ })

}]);