(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[23],{

/***/ "./resources/js/src/@core/auth/jwt/jwtDefaultConfig.js":
/*!*************************************************************!*\
  !*** ./resources/js/src/@core/auth/jwt/jwtDefaultConfig.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  // Endpoints
  loginEndpoint: '/jwt/login',
  registerEndpoint: '/jwt/register',
  refreshEndpoint: '/jwt/refresh-token',
  logoutEndpoint: '/jwt/logout',
  // This will be prefixed in authorization header with token
  // e.g. Authorization: Bearer <token>
  tokenType: 'Bearer',
  // Value of this property will be used as key to store JWT token in storage
  storageTokenKeyName: 'accessToken',
  storageRefreshTokenKeyName: 'refreshToken'
});

/***/ }),

/***/ "./resources/js/src/@core/auth/jwt/jwtService.js":
/*!*******************************************************!*\
  !*** ./resources/js/src/@core/auth/jwt/jwtService.js ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return JwtService; });
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/objectSpread2.js */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_classCallCheck_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_createClass_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/createClass.js */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/defineProperty.js */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! core-js/modules/es.array.filter.js */ "./node_modules/core-js/modules/es.array.filter.js");
/* harmony import */ var core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_filter_js__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
/* harmony import */ var core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_push_js__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _jwtDefaultConfig__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./jwtDefaultConfig */ "./resources/js/src/@core/auth/jwt/jwtDefaultConfig.js");









var JwtService = /*#__PURE__*/function () {
  function JwtService(axiosIns, jwtOverrideConfig) {
    var _this = this;
    Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_classCallCheck_js__WEBPACK_IMPORTED_MODULE_1__["default"])(this, JwtService);
    // Will be used by this service for making API calls
    Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_3__["default"])(this, "axiosIns", null);
    // jwtConfig <= Will be used by this service
    Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_3__["default"])(this, "jwtConfig", Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__["default"])({}, _jwtDefaultConfig__WEBPACK_IMPORTED_MODULE_8__["default"]));
    // For Refreshing Token
    Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_3__["default"])(this, "isAlreadyFetchingAccessToken", false);
    // For Refreshing Token
    Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_defineProperty_js__WEBPACK_IMPORTED_MODULE_3__["default"])(this, "subscribers", []);
    this.axiosIns = axiosIns;
    this.jwtConfig = Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__["default"])(Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_objectSpread2_js__WEBPACK_IMPORTED_MODULE_0__["default"])({}, this.jwtConfig), jwtOverrideConfig);

    // Request Interceptor
    this.axiosIns.interceptors.request.use(function (config) {
      // Get token from localStorage
      var accessToken = _this.getToken();

      // If token is present add it to request's Authorization Header
      if (accessToken) {
        // eslint-disable-next-line no-param-reassign
        config.headers.Authorization = "".concat(_this.jwtConfig.tokenType, " ").concat(accessToken);
      }
      return config;
    }, function (error) {
      return Promise.reject(error);
    });

    // Add request/response interceptor
    this.axiosIns.interceptors.response.use(function (response) {
      return response;
    }, function (error) {
      // const { config, response: { status } } = error
      var config = error.config,
        response = error.response;
      var originalRequest = config;

      // if (status === 401) {
      if (response && response.status === 401) {
        if (!_this.isAlreadyFetchingAccessToken) {
          _this.isAlreadyFetchingAccessToken = true;
          _this.refreshToken().then(function (r) {
            _this.isAlreadyFetchingAccessToken = false;

            // Update accessToken in localStorage
            _this.setToken(r.data.accessToken);
            _this.setRefreshToken(r.data.refreshToken);
            _this.onAccessTokenFetched(r.data.accessToken);
          });
        }
        var retryOriginalRequest = new Promise(function (resolve) {
          _this.addSubscriber(function (accessToken) {
            // Make sure to assign accessToken according to your response.
            // Check: https://pixinvent.ticksy.com/ticket/2413870
            // Change Authorization header
            originalRequest.headers.Authorization = "".concat(_this.jwtConfig.tokenType, " ").concat(accessToken);
            resolve(_this.axiosIns(originalRequest));
          });
        });
        return retryOriginalRequest;
      }
      return Promise.reject(error);
    });
  }
  Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_createClass_js__WEBPACK_IMPORTED_MODULE_2__["default"])(JwtService, [{
    key: "onAccessTokenFetched",
    value: function onAccessTokenFetched(accessToken) {
      this.subscribers = this.subscribers.filter(function (callback) {
        return callback(accessToken);
      });
    }
  }, {
    key: "addSubscriber",
    value: function addSubscriber(callback) {
      this.subscribers.push(callback);
    }
  }, {
    key: "getToken",
    value: function getToken() {
      return localStorage.getItem(this.jwtConfig.storageTokenKeyName);
    }
  }, {
    key: "getRefreshToken",
    value: function getRefreshToken() {
      return localStorage.getItem(this.jwtConfig.storageRefreshTokenKeyName);
    }
  }, {
    key: "setToken",
    value: function setToken(value) {
      localStorage.setItem(this.jwtConfig.storageTokenKeyName, value);
    }
  }, {
    key: "setRefreshToken",
    value: function setRefreshToken(value) {
      localStorage.setItem(this.jwtConfig.storageRefreshTokenKeyName, value);
    }
  }, {
    key: "login",
    value: function login() {
      var _this$axiosIns;
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      return (_this$axiosIns = this.axiosIns).post.apply(_this$axiosIns, [this.jwtConfig.loginEndpoint].concat(args));
    }
  }, {
    key: "register",
    value: function register() {
      var _this$axiosIns2;
      for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
        args[_key2] = arguments[_key2];
      }
      return (_this$axiosIns2 = this.axiosIns).post.apply(_this$axiosIns2, [this.jwtConfig.registerEndpoint].concat(args));
    }
  }, {
    key: "refreshToken",
    value: function refreshToken() {
      return this.axiosIns.post(this.jwtConfig.refreshEndpoint, {
        refreshToken: this.getRefreshToken()
      });
    }
  }]);
  return JwtService;
}();


/***/ }),

/***/ "./resources/js/src/@core/auth/jwt/useJwt.js":
/*!***************************************************!*\
  !*** ./resources/js/src/@core/auth/jwt/useJwt.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return useJwt; });
/* harmony import */ var _jwtService__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./jwtService */ "./resources/js/src/@core/auth/jwt/jwtService.js");

function useJwt(axiosIns, jwtOverrideConfig) {
  var jwt = new _jwtService__WEBPACK_IMPORTED_MODULE_0__["default"](axiosIns, jwtOverrideConfig);
  return {
    jwt: jwt
  };
}

/***/ }),

/***/ "./resources/js/src/auth/jwt/useJwt.js":
/*!*********************************************!*\
  !*** ./resources/js/src/auth/jwt/useJwt.js ***!
  \*********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _core_auth_jwt_useJwt__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @core/auth/jwt/useJwt */ "./resources/js/src/@core/auth/jwt/useJwt.js");
/* harmony import */ var _axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @axios */ "./resources/js/src/libs/axios.js");


var _useJwt = Object(_core_auth_jwt_useJwt__WEBPACK_IMPORTED_MODULE_0__["default"])(_axios__WEBPACK_IMPORTED_MODULE_1__["default"], {}),
  jwt = _useJwt.jwt;
/* harmony default export */ __webpack_exports__["default"] = (jwt);

/***/ }),

/***/ "./resources/js/src/auth/utils.js":
/*!****************************************!*\
  !*** ./resources/js/src/auth/utils.js ***!
  \****************************************/
/*! exports provided: isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "isUserLoggedIn", function() { return isUserLoggedIn; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getUserData", function() { return getUserData; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getHomeRouteForLoggedInUser", function() { return getHomeRouteForLoggedInUser; });
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.keys.js */ "./node_modules/core-js/modules/es.object.keys.js");
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _auth_jwt_useJwt__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/auth/jwt/useJwt */ "./resources/js/src/auth/jwt/useJwt.js");



/**
 * Return if user is logged in
 * This is completely up to you and how you want to store the token in your frontend application
 * e.g. If you are using cookies to store the application please update this function
 */
// eslint-disable-next-line arrow-body-style
var isUserLoggedIn = function isUserLoggedIn() {
  return localStorage.getItem('userData') && localStorage.getItem(_auth_jwt_useJwt__WEBPACK_IMPORTED_MODULE_1__["default"].jwtConfig.storageTokenKeyName);
};
var getUserData = function getUserData() {
  return JSON.parse(localStorage.getItem('userData'));
};

/**
 * This function is used for demo purpose route navigation
 * In real app you won't need this function because your app will navigate to same route for each users regardless of ability
 * Please note role field is just for showing purpose it's not used by anything in frontend
 * We are checking role just for ease
 * NOTE: If you have different pages to navigate based on user ability then this function can be useful. However, you need to update it.
 * @param {String} userRole Role of user
 */
var getHomeRouteForLoggedInUser = function getHomeRouteForLoggedInUser(userRole) {
  if (userRole === 'admin') return '/';
  if (userRole === 'client') return {
    name: 'access-control'
  };
  return {
    name: 'auth-login'
  };
};

/***/ }),

/***/ "./resources/js/src/libs/acl/ability.js":
/*!**********************************************!*\
  !*** ./resources/js/src/libs/acl/ability.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.keys.js */ "./node_modules/core-js/modules/es.object.keys.js");
/* harmony import */ var core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_keys_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _casl_ability__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @casl/ability */ "./node_modules/@casl/ability/dist/es5m/index.js");
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./config */ "./resources/js/src/libs/acl/config.js");




//  Read ability from localStorage
// * Handles auto fetching previous abilities if already logged in user
// ? You can update this if you store user abilities to more secure place
// ! Anyone can update localStorage so be careful and please update this
var userData = JSON.parse(localStorage.getItem('userData'));
var existingAbility = userData ? userData.ability : null;
/* harmony default export */ __webpack_exports__["default"] = (new _casl_ability__WEBPACK_IMPORTED_MODULE_1__["Ability"](existingAbility || _config__WEBPACK_IMPORTED_MODULE_2__["initialAbility"]));

/***/ }),

/***/ "./resources/js/src/libs/acl/config.js":
/*!*********************************************!*\
  !*** ./resources/js/src/libs/acl/config.js ***!
  \*********************************************/
/*! exports provided: initialAbility, _ */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "initialAbility", function() { return initialAbility; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "_", function() { return _; });
var initialAbility = [{
  action: 'read',
  subject: 'Auth'
}];
var _ = undefined;

/***/ }),

/***/ "./resources/js/src/libs/acl/routeProtection.js":
/*!******************************************************!*\
  !*** ./resources/js/src/libs/acl/routeProtection.js ***!
  \******************************************************/
/*! exports provided: canNavigate, _ */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "canNavigate", function() { return canNavigate; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "_", function() { return _; });
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _ability__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ability */ "./resources/js/src/libs/acl/ability.js");


var canNavigate = function canNavigate(to) {
  return to.matched.some(function (route) {
    return _ability__WEBPACK_IMPORTED_MODULE_1__["default"].can(route.meta.action || 'read', route.meta.resource);
  });
};
var _ = undefined;

/***/ }),

/***/ "./resources/js/src/libs/axios.js":
/*!****************************************!*\
  !*** ./resources/js/src/libs/axios.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_1__);


// axios

var axiosIns = axios__WEBPACK_IMPORTED_MODULE_1___default.a.create({
  // You can add your headers here
  // ================================
  // baseURL: 'https://some-domain.com/api/',
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
});
vue__WEBPACK_IMPORTED_MODULE_0___default.a.prototype.$http = axiosIns;
/* harmony default export */ __webpack_exports__["default"] = (axiosIns);

/***/ }),

/***/ "./resources/js/src/router/index.js":
/*!******************************************!*\
  !*** ./resources/js/src/router/index.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* WEBPACK VAR INJECTION */(function(process) {/* harmony import */ var _var_www_html_monster_node_modules_babel_runtime_helpers_esm_toConsumableArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");
/* harmony import */ var core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_array_concat_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.common.js");
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(vue__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var vue_router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-router */ "./node_modules/vue-router/dist/vue-router.esm.js");
/* harmony import */ var _libs_acl_routeProtection__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @/libs/acl/routeProtection */ "./resources/js/src/libs/acl/routeProtection.js");
/* harmony import */ var _auth_utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @/auth/utils */ "./resources/js/src/auth/utils.js");
/* harmony import */ var _routes_apps__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./routes/apps */ "./resources/js/src/router/routes/apps.js");
/* harmony import */ var _routes_pages__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./routes/pages */ "./resources/js/src/router/routes/pages.js");
/* harmony import */ var _routes_others__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./routes/others */ "./resources/js/src/router/routes/others.js");
/* harmony import */ var _routes_admin__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./routes/admin */ "./resources/js/src/router/routes/admin.js");





// Routes






vue__WEBPACK_IMPORTED_MODULE_2___default.a.use(vue_router__WEBPACK_IMPORTED_MODULE_3__["default"]);
var router = new vue_router__WEBPACK_IMPORTED_MODULE_3__["default"]({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior: function scrollBehavior() {
    return {
      x: 0,
      y: 0
    };
  },
  routes: [{
    path: '/admin',
    redirect: {
      name: 'dashboard-ecommerce'
    }
  }].concat(Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_toConsumableArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(_routes_apps__WEBPACK_IMPORTED_MODULE_6__["default"]), Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_toConsumableArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(_routes_pages__WEBPACK_IMPORTED_MODULE_7__["default"]), Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_toConsumableArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(_routes_others__WEBPACK_IMPORTED_MODULE_8__["default"]), Object(_var_www_html_monster_node_modules_babel_runtime_helpers_esm_toConsumableArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(_routes_admin__WEBPACK_IMPORTED_MODULE_9__["default"]), [{
    path: '*',
    redirect: 'error-404'
  }])
});
router.beforeEach(function (to, _, next) {
  var isLoggedIn = Object(_auth_utils__WEBPACK_IMPORTED_MODULE_5__["isUserLoggedIn"])();
  if (!Object(_libs_acl_routeProtection__WEBPACK_IMPORTED_MODULE_4__["canNavigate"])(to)) {
    // Redirect to login if not logged in
    if (!isLoggedIn) return next({
      name: 'auth-login'
    });

    // If logged in => not authorized
    return next({
      name: 'misc-not-authorized'
    });
  }

  // Redirect if logged in
  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    var userData = Object(_auth_utils__WEBPACK_IMPORTED_MODULE_5__["getUserData"])();
    next(Object(_auth_utils__WEBPACK_IMPORTED_MODULE_5__["getHomeRouteForLoggedInUser"])(userData ? userData.role : null));
  }
  return next();
});

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(function () {
  // Remove initial loading
  var appLoading = document.getElementById('loading-bg');
  if (appLoading) {
    appLoading.style.display = 'none';
  }
});
/* harmony default export */ __webpack_exports__["default"] = (router);
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../node_modules/process/browser.js */ "./node_modules/process/browser.js")))

/***/ }),

/***/ "./resources/js/src/router/routes/admin.js":
/*!*************************************************!*\
  !*** ./resources/js/src/router/routes/admin.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__);



/* harmony default export */ __webpack_exports__["default"] = ([{
  path: '/admin/analytics',
  name: 'dashboard-analytics',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(4), __webpack_require__.e(17), __webpack_require__.e(55), __webpack_require__.e(78), __webpack_require__.e(133)]).then(__webpack_require__.bind(null, /*! @/views/admin/dashboard/analytics/Analytics.vue */ "./resources/js/src/views/admin/dashboard/analytics/Analytics.vue"));
  },
  meta: {
    resource: 'dashboard-view',
    action: 'read'
  }
}, {
  path: '/admin/dashboard',
  name: 'dashboard-ecommerce',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(4), __webpack_require__.e(8), __webpack_require__.e(17), __webpack_require__.e(99)]).then(__webpack_require__.bind(null, /*! @/views/admin/dashboard/ecommerce/Ecommerce.vue */ "./resources/js/src/views/admin/dashboard/ecommerce/Ecommerce.vue"));
  },
  meta: {
    resource: 'dashboard-view',
    action: 'read'
  }
}, {
  path: '/admin/enquirys',
  name: 'enquirys',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(357)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/enquirys.vue */ "./resources/js/src/views/admin/ordermanagement/enquirys.vue"));
  },
  resource: 'order-view',
  action: 'read',
  meta: {
    pageTitle: 'Enquirys',
    breadcrumb: [{
      text: 'Order Management'
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/new',
  name: 'new',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(359)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/new_orders.vue */ "./resources/js/src/views/admin/ordermanagement/new_orders.vue"));
  },
  resource: 'order-view',
  action: 'read',
  meta: {
    pageTitle: 'New Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'New Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/processing',
  name: 'processing',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(361)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/processing.vue */ "./resources/js/src/views/admin/ordermanagement/processing.vue"));
  },
  meta: {
    pageTitle: 'Processing Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Processing Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/pickup',
  name: 'pickup',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(360)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/pickup.vue */ "./resources/js/src/views/admin/ordermanagement/pickup.vue"));
  },
  meta: {
    pageTitle: 'Pickup Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Pickup Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/delivered',
  name: 'delivered',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(356)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/delivered.vue */ "./resources/js/src/views/admin/ordermanagement/delivered.vue"));
  },
  meta: {
    pageTitle: 'Delivered Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Delivered Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/cancelled',
  name: 'cancelled',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(355)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/cancelled.vue */ "./resources/js/src/views/admin/ordermanagement/cancelled.vue"));
  },
  meta: {
    pageTitle: 'Cancelled Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Cancelled Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/failed',
  name: 'failed',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(358)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/failed.vue */ "./resources/js/src/views/admin/ordermanagement/failed.vue"));
  },
  meta: {
    pageTitle: 'Failed Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Failed Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/abandon',
  name: 'abandon',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(354)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/abandon.vue */ "./resources/js/src/views/admin/ordermanagement/abandon.vue"));
  },
  meta: {
    pageTitle: 'Abandon Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Abandon Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/refund',
  name: 'refund',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(19), __webpack_require__.e(362)]).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/refund.vue */ "./resources/js/src/views/admin/ordermanagement/refund.vue"));
  },
  meta: {
    pageTitle: 'Refund Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'Refund Orders',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/users',
  name: 'user_management',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(223)]).then(__webpack_require__.bind(null, /*! @/views/admin/users/Usermanagement.vue */ "./resources/js/src/views/admin/users/Usermanagement.vue"));
  },
  meta: {
    pageTitle: 'User Management',
    breadcrumb: [{
      text: 'User Management'
    }, {
      text: 'users',
      active: true
    }],
    resource: 'user-view',
    action: 'read'
  }
}, {
  path: '/admin/city_lists',
  name: 'city_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(35), __webpack_require__.e(348)]).then(__webpack_require__.bind(null, /*! @/views/admin/city/city_list.vue */ "./resources/js/src/views/admin/city/city_list.vue"));
  },
  meta: {
    pageTitle: 'City List',
    breadcrumb: [{
      text: 'City List'
    }, {
      text: 'City',
      active: true
    }],
    resource: 'city-view',
    action: 'read'
  }
}, {
  path: '/admin/country_lists',
  name: 'country_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(35), __webpack_require__.e(349)]).then(__webpack_require__.bind(null, /*! @/views/admin/city/country_list.vue */ "./resources/js/src/views/admin/city/country_list.vue"));
  },
  meta: {
    pageTitle: 'Country List',
    breadcrumb: [{
      text: 'Country List'
    }, {
      text: 'Country',
      active: true
    }],
    resource: 'city-view',
    action: 'read'
  }
}, {
  path: '/admin/add_country',
  name: 'add_country',
  component: function component() {
    return __webpack_require__.e(/*! import() */ 305).then(__webpack_require__.bind(null, /*! @/views/admin/city/add_country.vue */ "./resources/js/src/views/admin/city/add_country.vue"));
  },
  meta: {
    pageTitle: 'Add Country',
    breadcrumb: [{
      text: 'Country'
    }, {
      text: 'Add Country',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: '/admin/add_state',
  name: 'add_state',
  component: function component() {
    return __webpack_require__.e(/*! import() */ 306).then(__webpack_require__.bind(null, /*! @/views/admin/city/add_state.vue */ "./resources/js/src/views/admin/city/add_state.vue"));
  },
  meta: {
    pageTitle: 'Add State',
    breadcrumb: [{
      text: 'State'
    }, {
      text: 'Add State',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: '/admin/states_list',
  name: 'state_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(35), __webpack_require__.e(350)]).then(__webpack_require__.bind(null, /*! @/views/admin/city/state_list.vue */ "./resources/js/src/views/admin/city/state_list.vue"));
  },
  meta: {
    pageTitle: 'State List',
    breadcrumb: [{
      text: 'State List'
    }, {
      text: 'State',
      active: true
    }],
    resource: 'city-view',
    action: 'read'
  }
}, {
  path: '/admin/restaurants',
  name: 'restaurant_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(222)]).then(__webpack_require__.bind(null, /*! @/views/admin/restaurants/restaurants.vue */ "./resources/js/src/views/admin/restaurants/restaurants.vue"));
  },
  meta: {
    pageTitle: 'Store List',
    breadcrumb: [{
      text: 'Store List'
    }, {
      text: 'Store',
      active: true
    }],
    resource: 'restaurant-view',
    action: 'read'
  }
}, {
  path: '/admin/add_restaurants',
  name: 'add_restaurant',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(4), __webpack_require__.e(7), __webpack_require__.e(8), __webpack_require__.e(12), __webpack_require__.e(20), __webpack_require__.e(22), __webpack_require__.e(24), __webpack_require__.e(29), __webpack_require__.e(1), __webpack_require__.e(105)]).then(__webpack_require__.bind(null, /*! @/views/admin/restaurants/add_restaurant.vue */ "./resources/js/src/views/admin/restaurants/add_restaurant.vue"));
  },
  meta: {
    pageTitle: 'Store',
    breadcrumb: [{
      text: 'Store List'
    }, {
      text: 'Add Store',
      active: true
    }],
    resource: 'restaurant-edit',
    action: 'read'
  }
}, {
  path: '/admin/eagle_view',
  name: 'eagle_view',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(31), __webpack_require__.e(135)]).then(__webpack_require__.bind(null, /*! @/views/admin/restaurants/eagle.vue */ "./resources/js/src/views/admin/restaurants/eagle.vue"));
  },
  meta: {
    pageTitle: 'Store',
    breadcrumb: [{
      text: 'Eagle View'
    }],
    resource: 'restaurant-edit',
    action: 'read'
  }
}, {
  path: '/admin/edit_restaurants/:id',
  name: 'edit_restaurant',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(4), __webpack_require__.e(7), __webpack_require__.e(8), __webpack_require__.e(12), __webpack_require__.e(20), __webpack_require__.e(22), __webpack_require__.e(29), __webpack_require__.e(1), __webpack_require__.e(106)]).then(__webpack_require__.bind(null, /*! @/views/admin/restaurants/edit_restaturant.vue */ "./resources/js/src/views/admin/restaurants/edit_restaturant.vue"));
  },
  meta: {
    pageTitle: 'Store',
    breadcrumb: [{
      text: 'Edit Store '
    }, {
      text: 'Edit Store',
      active: true
    }],
    resource: 'restaurant-edit',
    action: 'read'
  }
}, {
  path: '/admin/view_restaurants/:id',
  name: 'restaurant_view',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(4), __webpack_require__.e(334)]).then(__webpack_require__.bind(null, /*! @/views/admin/restaurants/restaurant_view.vue */ "./resources/js/src/views/admin/restaurants/restaurant_view.vue"));
  },
  meta: {
    pageTitle: 'Store',
    breadcrumb: [{
      text: 'Store View'
    }, {
      text: 'View Store',
      active: true
    }],
    resource: 'restaurant-edit',
    action: 'read'
  }
}, {
  path: '/admin/vehicles',
  name: 'vehicles_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(224)]).then(__webpack_require__.bind(null, /*! @/views/admin/vehicles/vehicles_list.vue */ "./resources/js/src/views/admin/vehicles/vehicles_list.vue"));
  },
  meta: {
    pageTitle: 'Vehicles List',
    breadcrumb: [{
      text: 'Vehicles List'
    }, {
      text: 'Vehicles',
      active: true
    }],
    resource: 'vehicle-view',
    action: 'read'
  }
}, {
  path: '/admin/brands',
  name: 'brands_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(216)]).then(__webpack_require__.bind(null, /*! @/views/admin/brands/brands_list.vue */ "./resources/js/src/views/admin/brands/brands_list.vue"));
  },
  meta: {
    pageTitle: 'Brands List',
    breadcrumb: [{
      text: 'Brands List'
    }, {
      text: 'Brands',
      active: true
    }],
    resource: 'brands',
    action: 'read'
  }
}, {
  path: '/admin/drivers',
  name: 'driver_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(232)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/driver_list.vue */ "./resources/js/src/views/admin/driver/driver_list.vue"));
  },
  meta: {
    pageTitle: 'Driver List',
    breadcrumb: [{
      text: 'Driver List'
    }, {
      text: 'Driver',
      active: true
    }],
    resource: 'driver-view',
    action: 'read'
  }
}, {
  path: '/admin/view_drivers/:id',
  name: 'view_driver',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(4), __webpack_require__.e(333)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/view_driver.vue */ "./resources/js/src/views/admin/driver/view_driver.vue"));
  },
  meta: {
    pageTitle: 'View Driver',
    breadcrumb: [{
      text: 'View Driver'
    }, {
      text: 'Driver',
      active: true
    }],
    resource: 'driver-view',
    action: 'read'
  }
}, {
  path: '/admin/new_drivers',
  name: 'new_driver_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(233)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/new_driver_list.vue */ "./resources/js/src/views/admin/driver/new_driver_list.vue"));
  },
  meta: {
    pageTitle: 'New Driver List',
    breadcrumb: [{
      text: 'Driver List'
    }, {
      text: 'New Driver',
      active: true
    }],
    resource: 'driver-view',
    action: 'read'
  }
}, {
  path: '/admin/rider_in_map',
  name: 'rider_in_map',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(31), __webpack_require__.e(134)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/rider_in_map.vue */ "./resources/js/src/views/admin/driver/rider_in_map.vue"));
  },
  meta: {
    pageTitle: 'Drivers in Map',
    breadcrumb: [{
      text: 'Drivers '
    }, {
      text: 'Drivers in Map',
      active: true
    }],
    resource: 'driver-view',
    action: 'read'
  }
}, {
  path: '/admin/documents',
  name: 'document_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(231)]).then(__webpack_require__.bind(null, /*! @/views/admin/documents/document_list.vue */ "./resources/js/src/views/admin/documents/document_list.vue"));
  },
  meta: {
    pageTitle: 'Document List',
    breadcrumb: [{
      text: 'Document List'
    }, {
      text: 'Document',
      active: true
    }],
    resource: 'document-view',
    action: 'read'
  }
}, {
  path: '/admin/add_document',
  name: 'add_document',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(80)]).then(__webpack_require__.bind(null, /*! @/views/admin/documents/add_document.vue */ "./resources/js/src/views/admin/documents/add_document.vue"));
  },
  meta: {
    pageTitle: 'Add Document',
    breadcrumb: [{
      text: 'Document'
    }, {
      text: 'Add Document',
      active: true
    }],
    resource: 'document-edit',
    action: 'read'
  }
}, {
  path: '/admin/edit_document/:id',
  name: 'edit_document',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(80)]).then(__webpack_require__.bind(null, /*! @/views/admin/documents/add_document.vue */ "./resources/js/src/views/admin/documents/add_document.vue"));
  },
  meta: {
    pageTitle: 'Edit Document',
    breadcrumb: [{
      text: 'Document'
    }, {
      text: 'Edit Document',
      active: true
    }],
    resource: 'document-edit',
    action: 'read'
  }
}, {
  path: '/admin/reason_lists',
  name: 'reason_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(221)]).then(__webpack_require__.bind(null, /*! @/views/admin/reason_list/reason_list.vue */ "./resources/js/src/views/admin/reason_list/reason_list.vue"));
  },
  meta: {
    pageTitle: 'Cancellation Reason List',
    breadcrumb: [{
      text: 'Cancellation Reason List'
    }, {
      text: 'Cancellation Reasons',
      active: true
    }],
    resource: 'reason',
    action: 'read'
  }
}, {
  path: '/admin/add_reason',
  name: 'add_reason',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(83)]).then(__webpack_require__.bind(null, /*! @/views/admin/reason_list/add_reason.vue */ "./resources/js/src/views/admin/reason_list/add_reason.vue"));
  },
  meta: {
    pageTitle: 'Add Cancellation Reason',
    breadcrumb: [{
      text: 'Add Cancellation Reason '
    }, {
      text: 'Add Cancellation Reasons',
      active: true
    }],
    resource: 'reason',
    action: 'read'
  }
}, {
  path: '/admin/edit_reason/:id',
  name: 'edit_reason',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(83)]).then(__webpack_require__.bind(null, /*! @/views/admin/reason_list/add_reason.vue */ "./resources/js/src/views/admin/reason_list/add_reason.vue"));
  },
  meta: {
    pageTitle: 'Edit Cancellation Reason',
    breadcrumb: [{
      text: 'Cancellation Reason '
    }, {
      text: 'Edit Cancellation Reasons',
      active: true
    }],
    resource: 'reason',
    action: 'read'
  }
}, {
  path: '/admin/promocode_lists',
  name: 'promocode_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(220)]).then(__webpack_require__.bind(null, /*! @/views/admin/promocodes/promocode_list.vue */ "./resources/js/src/views/admin/promocodes/promocode_list.vue"));
  },
  meta: {
    pageTitle: 'Promocode List',
    breadcrumb: [{
      text: 'Promocodes'
    }, {
      text: 'Promocode List',
      active: true
    }],
    resource: 'promocodes-view',
    action: 'read'
  }
}, {
  path: '/admin/add_promocode',
  name: 'add_promocode',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(76)]).then(__webpack_require__.bind(null, /*! @/views/admin/promocodes/add_promocode.vue */ "./resources/js/src/views/admin/promocodes/add_promocode.vue"));
  },
  meta: {
    pageTitle: 'Add Promocode',
    breadcrumb: [{
      text: 'Promocode'
    }, {
      text: 'Add Promocode',
      active: true
    }],
    resource: 'promocodes-edit',
    action: 'read'
  }
}, {
  path: '/admin/edit_promocodes/:id',
  name: 'edit_promocode',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(76)]).then(__webpack_require__.bind(null, /*! @/views/admin/promocodes/add_promocode.vue */ "./resources/js/src/views/admin/promocodes/add_promocode.vue"));
  },
  meta: {
    pageTitle: 'Edit Promocode',
    breadcrumb: [{
      text: 'Promocode'
    }, {
      text: 'Edit Promocode',
      active: true
    }],
    resource: 'promocodes-edit',
    action: 'read'
  }
}, {
  path: '/admin/custom_push',
  name: 'custom_push',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(1), __webpack_require__.e(269)]).then(__webpack_require__.bind(null, /*! @/views/admin/promocodes/custompush.vue */ "./resources/js/src/views/admin/promocodes/custompush.vue"));
  },
  meta: {
    pageTitle: 'Custom Push',
    breadcrumb: [{
      text: 'Promocode'
    }, {
      text: 'Push Notification',
      active: true
    }],
    resource: 'promocodes',
    action: 'read'
  }
}, {
  path: '/admin/admin_custom_push',
  name: 'admin_custom_push',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(1), __webpack_require__.e(268)]).then(__webpack_require__.bind(null, /*! @/views/admin/promocodes/admin_custompush.vue */ "./resources/js/src/views/admin/promocodes/admin_custompush.vue"));
  },
  meta: {
    pageTitle: 'Admin Custompush',
    breadcrumb: [{
      text: 'Promocode'
    }, {
      text: 'Admin Custompush',
      active: true
    }],
    resource: 'promocodes',
    action: 'read'
  }
}, {
  path: '/admin/banner_lists',
  name: 'banner_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(215)]).then(__webpack_require__.bind(null, /*! @/views/admin/banner/banner_list.vue */ "./resources/js/src/views/admin/banner/banner_list.vue"));
  },
  meta: {
    pageTitle: 'Banner List',
    breadcrumb: [{
      text: 'Banners'
    }, {
      text: 'Banner List',
      active: true
    }],
    resource: 'banners',
    action: 'read'
  }
}, {
  path: '/admin/add_banner',
  name: 'add_banner',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(57)]).then(__webpack_require__.bind(null, /*! @/views/admin/banner/add_banner.vue */ "./resources/js/src/views/admin/banner/add_banner.vue"));
  },
  meta: {
    pageTitle: 'Add Banner',
    breadcrumb: [{
      text: 'Banners'
    }, {
      text: 'Add Banner',
      active: true
    }],
    resource: 'banners',
    action: 'read'
  }
}, {
  path: '/admin/edit_banner/:id',
  name: 'edit_banner',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(57)]).then(__webpack_require__.bind(null, /*! @/views/admin/banner/add_banner.vue */ "./resources/js/src/views/admin/banner/add_banner.vue"));
  },
  meta: {
    pageTitle: 'Edit Banner',
    breadcrumb: [{
      text: 'Banners'
    }, {
      text: 'Edit Banner',
      active: true
    }],
    resource: 'banners',
    action: 'read'
  }
}, {
  path: '/admin/cuisines',
  name: 'cuisines_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(89), __webpack_require__.e(285)]).then(__webpack_require__.bind(null, /*! @/views/admin/itemmaster/cuisines.vue */ "./resources/js/src/views/admin/itemmaster/cuisines.vue"));
  },
  meta: {
    pageTitle: 'Cuisines List',
    breadcrumb: [{
      text: 'Cuisines'
    }, {
      text: 'Cuisines List',
      active: true
    }],
    resource: 'cuisines-view',
    action: 'read'
  }
}, {
  path: '/admin/edit_cuisine/:id',
  name: 'edit_cuisine',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(71)]).then(__webpack_require__.bind(null, /*! @/views/admin/itemmaster/add_cuisine.vue */ "./resources/js/src/views/admin/itemmaster/add_cuisine.vue"));
  },
  meta: {
    pageTitle: 'Edit Cuisines',
    breadcrumb: [{
      text: 'Cuisines'
    }, {
      text: 'Edit Cuisines',
      active: true
    }],
    resource: 'cuisines-edit',
    action: 'read'
  }
}, {
  path: '/admin/add_cuisine',
  name: 'add_cuisine',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(71)]).then(__webpack_require__.bind(null, /*! @/views/admin/itemmaster/add_cuisine.vue */ "./resources/js/src/views/admin/itemmaster/add_cuisine.vue"));
  },
  meta: {
    pageTitle: 'Add Cuisines',
    breadcrumb: [{
      text: 'Cuisines'
    }, {
      text: 'Add Cuisines',
      active: true
    }],
    resource: 'cuisines-edit',
    action: 'read'
  }
}, {
  path: '/admin/edit_addon/:id',
  name: 'edit_addon',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(82)]).then(__webpack_require__.bind(null, /*! @/views/admin/itemmaster/add_addon.vue */ "./resources/js/src/views/admin/itemmaster/add_addon.vue"));
  },
  meta: {
    pageTitle: 'Edit Addon',
    breadcrumb: [{
      text: 'Cuisines'
    }, {
      text: 'Edit Addon',
      active: true
    }],
    resource: 'addon',
    action: 'read'
  }
}, {
  path: '/admin/add_addon',
  name: 'add_addon',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(82)]).then(__webpack_require__.bind(null, /*! @/views/admin/itemmaster/add_addon.vue */ "./resources/js/src/views/admin/itemmaster/add_addon.vue"));
  },
  meta: {
    pageTitle: 'Add Addon',
    breadcrumb: [{
      text: 'Addon'
    }, {
      text: 'Add Addon',
      active: true
    }],
    resource: 'addon',
    action: 'read'
  }
}, {
  path: '/admin/addons_lists',
  name: 'addons_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(89), __webpack_require__.e(353)]).then(__webpack_require__.bind(null, /*! @/views/admin/itemmaster/addons_list.vue */ "./resources/js/src/views/admin/itemmaster/addons_list.vue"));
  },
  meta: {
    pageTitle: 'Addons List',
    breadcrumb: [{
      text: 'Addons'
    }, {
      text: 'Addons List',
      active: true
    }],
    resource: 'addon',
    action: 'read'
  }
}, {
  path: '/admin/restaurant_payout_list',
  name: 'restaurant_payout_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(39), __webpack_require__.e(287)]).then(__webpack_require__.bind(null, /*! @/views/admin/payouts/restaurant_payout_list.vue */ "./resources/js/src/views/admin/payouts/restaurant_payout_list.vue"));
  },
  meta: {
    pageTitle: 'Store Payout List',
    breadcrumb: [{
      text: 'Store Payout List'
    }, {
      text: 'Store Payout',
      active: true
    }],
    resource: 'payout',
    action: 'read'
  }
}, {
  path: '/admin/driver_payout_list',
  name: 'driver_payout_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(39), __webpack_require__.e(286)]).then(__webpack_require__.bind(null, /*! @/views/admin/payouts/driver_payout_list.vue */ "./resources/js/src/views/admin/payouts/driver_payout_list.vue"));
  },
  meta: {
    pageTitle: 'Driver Payout List',
    breadcrumb: [{
      text: 'Driver Payout List'
    }, {
      text: 'Driver Payout',
      active: true
    }],
    resource: 'payout',
    action: 'read'
  }
}, {
  path: '/admin/restaurant_transaction_history',
  name: 'restaurant_transaction_history',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(39), __webpack_require__.e(364)]).then(__webpack_require__.bind(null, /*! @/views/admin/payouts/restaurant_history.vue */ "./resources/js/src/views/admin/payouts/restaurant_history.vue"));
  },
  meta: {
    pageTitle: 'Store Transaction History',
    breadcrumb: [{
      text: 'Transaction History'
    }, {
      text: 'History',
      active: true
    }],
    resource: 'payout',
    action: 'read'
  }
}, {
  path: '/admin/driver_transaction_history',
  name: 'driver_transaction_history',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(39), __webpack_require__.e(363)]).then(__webpack_require__.bind(null, /*! @/views/admin/payouts/driver_history.vue */ "./resources/js/src/views/admin/payouts/driver_history.vue"));
  },
  meta: {
    pageTitle: 'Driver Transaction History',
    breadcrumb: [{
      text: 'Driver Transaction History'
    }, {
      text: 'Driver Transaction History',
      active: true
    }],
    resource: 'payout',
    action: 'read'
  }
}, {
  path: '/admin/food_quantity_lists',
  name: 'food_quantity_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(303)]).then(__webpack_require__.bind(null, /*! @/views/admin/food_quantity/food_quantity_list.vue */ "./resources/js/src/views/admin/food_quantity/food_quantity_list.vue"));
  },
  meta: {
    pageTitle: 'Sizes List',
    breadcrumb: [{
      text: 'Sizes List'
    }, {
      text: 'Sizes List',
      active: true
    }],
    resource: 'food',
    action: 'read'
  }
}, {
  path: '/admin/add_food_quantity',
  name: 'add_food_quantity',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(81)]).then(__webpack_require__.bind(null, /*! @/views/admin/food_quantity/add_food_quantity.vue */ "./resources/js/src/views/admin/food_quantity/add_food_quantity.vue"));
  },
  meta: {
    pageTitle: 'Add New Size',
    breadcrumb: [{
      text: 'Size'
    }, {
      text: 'Add New Size',
      active: true
    }],
    resource: 'food',
    action: 'read'
  }
}, {
  path: '/admin/edit_food_quantity/:id',
  name: 'edit_food_quantity',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(81)]).then(__webpack_require__.bind(null, /*! @/views/admin/food_quantity/add_food_quantity.vue */ "./resources/js/src/views/admin/food_quantity/add_food_quantity.vue"));
  },
  meta: {
    pageTitle: 'Edit Size',
    breadcrumb: [{
      text: 'Size'
    }, {
      text: 'Edit Size',
      active: true
    }],
    resource: 'food',
    action: 'read'
  }
}, {
  path: '/admin/category_lists',
  name: 'category_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(217)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/category_list.vue */ "./resources/js/src/views/admin/category/category_list.vue"));
  },
  meta: {
    pageTitle: 'Category List',
    breadcrumb: [{
      text: 'Category'
    }, {
      text: 'Category List',
      active: true
    }],
    resource: 'category-view',
    action: 'read'
  }
}, {
  path: '/admin/add_category',
  name: 'add_category',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(61)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/add_category.vue */ "./resources/js/src/views/admin/category/add_category.vue"));
  },
  meta: {
    pageTitle: 'Add Category',
    breadcrumb: [{
      text: 'Category'
    }, {
      text: 'Add Category',
      active: true
    }],
    resource: 'category-edit',
    action: 'read'
  }
}, {
  path: '/admin/edit_categorys/:id',
  name: 'edit_category',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(61)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/add_category.vue */ "./resources/js/src/views/admin/category/add_category.vue"));
  },
  meta: {
    pageTitle: 'Edit Category',
    breadcrumb: [{
      text: 'Category'
    }, {
      text: 'Edit Category',
      active: true
    }],
    resource: 'category-edit',
    action: 'read'
  }
}, {
  path: '/admin/category_sort',
  name: 'category_sort',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(11), __webpack_require__.e(200)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/category_sort.vue */ "./resources/js/src/views/admin/category/category_sort.vue"));
  },
  meta: {
    pageTitle: 'Category Sort',
    breadcrumb: [{
      text: 'Category'
    }, {
      text: 'Category Sort',
      active: true
    }],
    resource: 'category-edit',
    action: 'read'
  }
}, {
  path: '/admin/roles_list',
  name: 'roles_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(90), __webpack_require__.e(288)]).then(__webpack_require__.bind(null, /*! @/views/admin/roles/roles_list.vue */ "./resources/js/src/views/admin/roles/roles_list.vue"));
  },
  meta: {
    pageTitle: 'Roles List',
    breadcrumb: [{
      text: 'Roles'
    }, {
      text: 'Roles List',
      active: true
    }],
    resource: 'roles',
    action: 'read'
  }
}, {
  path: '/admin/edit_privilege',
  name: 'edit_privilege',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(298)]).then(__webpack_require__.bind(null, /*! @/views/admin/roles/add_privilege.vue */ "./resources/js/src/views/admin/roles/add_privilege.vue"));
  },
  meta: {
    pageTitle: 'Edit Privilege',
    breadcrumb: [{
      text: 'Privilege'
    }, {
      text: 'Edit Privilege',
      active: true
    }],
    resource: 'roles',
    action: 'read'
  }
}, {
  path: '/admin/show_admin',
  name: 'show_admins',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(90), __webpack_require__.e(365)]).then(__webpack_require__.bind(null, /*! @/views/admin/roles/show_admins.vue */ "./resources/js/src/views/admin/roles/show_admins.vue"));
  },
  meta: {
    pageTitle: 'Admins List',
    breadcrumb: [{
      text: 'Admins'
    }, {
      text: 'Admins List',
      active: true
    }],
    resource: 'roles',
    action: 'read'
  }
}, {
  path: '/admin/restaurant_reports',
  name: 'restaurant_report',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(27), __webpack_require__.e(321)]).then(__webpack_require__.bind(null, /*! @/views/admin/reports/restaurant_report.vue */ "./resources/js/src/views/admin/reports/restaurant_report.vue"));
  },
  meta: {
    pageTitle: 'Store Report',
    breadcrumb: [{
      text: 'Store Report'
    }, {
      text: 'Store Report',
      active: true
    }],
    resource: 'report-view',
    action: 'read'
  }
}, {
  path: '/admin/delivery_people_reports',
  name: 'delivery_people_report',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(27), __webpack_require__.e(319)]).then(__webpack_require__.bind(null, /*! @/views/admin/reports/delivery_people_report.vue */ "./resources/js/src/views/admin/reports/delivery_people_report.vue"));
  },
  meta: {
    pageTitle: 'Delivery People Report',
    breadcrumb: [{
      text: 'Delivery People Report'
    }, {
      text: 'Delivery People Report',
      active: true
    }],
    resource: 'report-view',
    action: 'read'
  }
}, {
  path: '/admin/order_reports',
  name: 'order_report',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(27), __webpack_require__.e(320)]).then(__webpack_require__.bind(null, /*! @/views/admin/reports/order_report.vue */ "./resources/js/src/views/admin/reports/order_report.vue"));
  },
  meta: {
    pageTitle: 'Order Report',
    breadcrumb: [{
      text: 'Order Report'
    }, {
      text: 'Order Report',
      active: true
    }],
    resource: 'report-view',
    action: 'read'
  }
}, {
  path: '/admin/top_user',
  name: 'top_users',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(27), __webpack_require__.e(322)]).then(__webpack_require__.bind(null, /*! @/views/admin/reports/top_users_report.vue */ "./resources/js/src/views/admin/reports/top_users_report.vue"));
  },
  meta: {
    pageTitle: 'Top Users Report',
    breadcrumb: [{
      text: 'Top Users Report'
    }, {
      text: 'Top Users Report',
      active: true
    }],
    resource: 'report-view',
    action: 'read'
  }
}, {
  path: '/admin/wallet_reports',
  name: 'wallet_report',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(27), __webpack_require__.e(323)]).then(__webpack_require__.bind(null, /*! @/views/admin/reports/wallet_report.vue */ "./resources/js/src/views/admin/reports/wallet_report.vue"));
  },
  meta: {
    pageTitle: 'Wallet Report',
    breadcrumb: [{
      text: 'Wallet Report'
    }, {
      text: 'Wallet Report',
      active: true
    }],
    resource: 'report-view',
    action: 'read'
  }
}, {
  path: "/admin/add_driver",
  name: "add-driver",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(20), __webpack_require__.e(22), __webpack_require__.e(29), __webpack_require__.e(1), __webpack_require__.e(239)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/add_driver.vue */ "./resources/js/src/views/admin/driver/add_driver.vue"));
  },
  meta: {
    pageTitle: 'Add Driver',
    breadcrumb: [{
      text: 'Driver'
    }, {
      text: 'Add Driver',
      active: true
    }],
    resource: 'driver-edit',
    action: 'read'
  }
}, {
  path: "/admin/edit_driver/:id",
  name: "edit-driver",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(20), __webpack_require__.e(22), __webpack_require__.e(29), __webpack_require__.e(1), __webpack_require__.e(240)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/edit_driver.vue */ "./resources/js/src/views/admin/driver/edit_driver.vue"));
  },
  meta: {
    pageTitle: 'Edit Driver',
    breadcrumb: [{
      text: 'Driver'
    }, {
      text: 'Edit Driver',
      active: true
    }],
    resource: 'driver-edit',
    action: 'read'
  }
}, {
  path: "/admin/delivery_boy_list/:id",
  name: "deliveryboy_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(219)]).then(__webpack_require__.bind(null, /*! @/views/admin/driver/deliver_people_list.vue */ "./resources/js/src/views/admin/driver/deliver_people_list.vue"));
  },
  meta: {
    pageTitle: 'Deliveryboy List',
    breadcrumb: [{
      text: 'List'
    }, {
      text: 'Deliveryboy_List',
      active: true
    }],
    resource: 'driver-view',
    action: 'read'
  }
}, {
  path: "/admin/add_city",
  name: "add_city",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 54).then(__webpack_require__.bind(null, /*! @/views/admin/city/add_city.vue */ "./resources/js/src/views/admin/city/add_city.vue"));
  },
  meta: {
    pageTitle: 'Add City',
    breadcrumb: [{
      text: 'City'
    }, {
      text: 'Add City',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: "/admin/edit_cities/:id",
  name: "edit_city",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 54).then(__webpack_require__.bind(null, /*! @/views/admin/city/add_city.vue */ "./resources/js/src/views/admin/city/add_city.vue"));
  },
  meta: {
    pageTitle: 'Edit City',
    breadcrumb: [{
      text: 'City'
    }, {
      text: 'Edit City',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: "/admin/add_vehicle",
  name: "add_vehicle",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(73)]).then(__webpack_require__.bind(null, /*! @/views/admin/vehicles/add_vehicle.vue */ "./resources/js/src/views/admin/vehicles/add_vehicle.vue"));
  },
  meta: {
    pageTitle: 'Add Vehicle',
    breadcrumb: [{
      text: 'Vehicle'
    }, {
      text: 'Add Vehicle',
      active: true
    }],
    resource: 'vehicle-edit',
    action: 'read'
  }
}, {
  path: "/admin/edit_vehicle/:id",
  name: "edit_vehicle",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(73)]).then(__webpack_require__.bind(null, /*! @/views/admin/vehicles/add_vehicle.vue */ "./resources/js/src/views/admin/vehicles/add_vehicle.vue"));
  },
  meta: {
    pageTitle: 'Edit Vehicle',
    breadcrumb: [{
      text: 'Vehicle'
    }, {
      text: 'Edit Vehicle',
      active: true
    }],
    resource: 'vehicle-edit',
    action: 'read'
  }
}, {
  path: "/admin/add_Brand",
  name: "add_Brand",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(79)]).then(__webpack_require__.bind(null, /*! @/views/admin/brands/add_Brand.vue */ "./resources/js/src/views/admin/brands/add_Brand.vue"));
  },
  meta: {
    pageTitle: 'Add Brands',
    breadcrumb: [{
      text: 'Brands'
    }, {
      text: 'Add Brands',
      active: true
    }],
    resource: 'brands',
    action: 'read'
  }
}, {
  path: "/admin/edit_Brand/:id",
  name: "edit_Brand",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(79)]).then(__webpack_require__.bind(null, /*! @/views/admin/brands/add_Brand.vue */ "./resources/js/src/views/admin/brands/add_Brand.vue"));
  },
  meta: {
    pageTitle: 'Edit Brand',
    breadcrumb: [{
      text: 'Brand'
    }, {
      text: 'Edit Brand',
      active: true
    }],
    resource: 'brands',
    action: 'read'
  }
}, {
  path: "/admin/brand_sort",
  name: "brand_sort",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(11), __webpack_require__.e(199)]).then(__webpack_require__.bind(null, /*! @/views/admin/brands/brands_sort.vue */ "./resources/js/src/views/admin/brands/brands_sort.vue"));
  },
  meta: {
    pageTitle: 'Brand Sort',
    breadcrumb: [{
      text: 'Brand'
    }, {
      text: 'Brand Sort',
      active: true
    }],
    resource: 'brands',
    action: 'read'
  }
}, {
  path: "/admin/create_role",
  name: "create_role",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(299)]).then(__webpack_require__.bind(null, /*! @/views/admin/roles/create_role.vue */ "./resources/js/src/views/admin/roles/create_role.vue"));
  },
  meta: {
    pageTitle: 'Create Role',
    breadcrumb: [{
      text: 'Roles'
    }, {
      text: 'Create Role',
      active: true
    }],
    resource: 'roles',
    action: 'read'
  }
}, {
  path: "/admin/create_staff",
  name: "create_staff",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(84)]).then(__webpack_require__.bind(null, /*! @/views/admin/roles/create_staff.vue */ "./resources/js/src/views/admin/roles/create_staff.vue"));
  },
  meta: {
    pageTitle: 'Create Staff',
    breadcrumb: [{
      text: 'Roles'
    }, {
      text: 'Create Staff',
      active: true
    }],
    resource: 'roles',
    action: 'read'
  }
}, {
  path: "/admin/edit_staff/:id",
  name: "edit_staff",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(84)]).then(__webpack_require__.bind(null, /*! @/views/admin/roles/create_staff.vue */ "./resources/js/src/views/admin/roles/create_staff.vue"));
  },
  meta: {
    pageTitle: 'Edit Staff',
    breadcrumb: [{
      text: 'Roles'
    }, {
      text: 'Edit Staff',
      active: true
    }],
    resource: 'roles',
    action: 'read'
  }
}, {
  path: "/admin/privacy_policys",
  name: "privacy_policy",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(9), __webpack_require__.e(13), __webpack_require__.e(282)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/privacy_policy.vue */ "./resources/js/src/views/admin/contents/privacy_policy.vue"));
  },
  meta: {
    pageTitle: 'Privacy Policy',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Privacy Policy',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/cancellation",
  name: "cancellation",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(9), __webpack_require__.e(13), __webpack_require__.e(280)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/cancellation.vue */ "./resources/js/src/views/admin/contents/cancellation.vue"));
  },
  meta: {
    pageTitle: 'Cancellation Policy',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Cancellation Policy',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/shipping",
  name: "shipping",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(9), __webpack_require__.e(13), __webpack_require__.e(283)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/shipping.vue */ "./resources/js/src/views/admin/contents/shipping.vue"));
  },
  meta: {
    pageTitle: 'Shipping and Delivery Policy',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Shipping and Delivery Policy',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/terms_condition",
  name: "terms_conditions",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(9), __webpack_require__.e(13), __webpack_require__.e(284)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/terms_conditions.vue */ "./resources/js/src/views/admin/contents/terms_conditions.vue"));
  },
  meta: {
    pageTitle: 'Terms and Conditions',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Terms and Conditions',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/faqs/:id",
  name: "faq",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 267).then(__webpack_require__.bind(null, /*! @/views/admin/contents/faq.vue */ "./resources/js/src/views/admin/contents/faq.vue"));
  },
  meta: {
    pageTitle: 'FAQ',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'FAQ',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/faq_list",
  name: "faq_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(218)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/faq_list.vue */ "./resources/js/src/views/admin/contents/faq_list.vue"));
  },
  meta: {
    pageTitle: 'FAQ',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'FAQ List',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/add_faq",
  name: "add_faq",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(70)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_faq.vue */ "./resources/js/src/views/admin/contents/add_faq.vue"));
  },
  meta: {
    pageTitle: 'FAQ',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Add FAQ',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/home_settings",
  name: "home_settings",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(11), __webpack_require__.e(207)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/home.vue */ "./resources/js/src/views/admin/contents/home.vue"));
  },
  meta: {
    pageTitle: 'Home',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Home',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/add_section",
  name: "add_section",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(9), __webpack_require__.e(40), __webpack_require__.e(37), __webpack_require__.e(87)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_page.vue */ "./resources/js/src/views/admin/contents/add_page.vue"));
  },
  meta: {
    pageTitle: 'Home',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Add Section',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/edit_page/:id",
  name: "edit_page",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(9), __webpack_require__.e(40), __webpack_require__.e(37), __webpack_require__.e(87)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_page.vue */ "./resources/js/src/views/admin/contents/add_page.vue"));
  },
  meta: {
    pageTitle: 'Home',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Edit Page',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/header",
  name: "header",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(2), __webpack_require__.e(11), __webpack_require__.e(206)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/header.vue */ "./resources/js/src/views/admin/contents/header.vue"));
  },
  meta: {
    pageTitle: 'Header',
    breadcrumb: [{
      text: 'Pages'
    }, {
      text: 'Pages List'
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/add_header_page",
  name: "add_header_page",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(9), __webpack_require__.e(12), __webpack_require__.e(40), __webpack_require__.e(37), __webpack_require__.e(85)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_header_page.vue */ "./resources/js/src/views/admin/contents/add_header_page.vue"));
  },
  meta: {
    pageTitle: 'Home',
    breadcrumb: [{
      text: 'Pages'
    }, {
      text: 'Add Page',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/edit_header_page/:id",
  name: "edit_header_page",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(9), __webpack_require__.e(12), __webpack_require__.e(40), __webpack_require__.e(37), __webpack_require__.e(85)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_header_page.vue */ "./resources/js/src/views/admin/contents/add_header_page.vue"));
  },
  meta: {
    pageTitle: 'Home',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Edit Page',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/view_header_page/:id",
  name: "view_header_page",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 352).then(__webpack_require__.bind(null, /*! @/views/admin/contents/view_header_page.vue */ "./resources/js/src/views/admin/contents/view_header_page.vue"));
  },
  meta: {
    pageTitle: 'Home',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'View Page',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/header_menu",
  name: "header_menu",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(2), __webpack_require__.e(3), __webpack_require__.e(11), __webpack_require__.e(173)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/header_cat_list.vue */ "./resources/js/src/views/admin/contents/header_cat_list.vue"));
  },
  meta: {
    pageTitle: 'Menu List',
    breadcrumb: [{
      text: 'Pages'
    }, {
      text: 'Header Menu List'
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/add_page_to_category/:id",
  name: "add_page_to_category",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(11), __webpack_require__.e(261)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_page_to_category.vue */ "./resources/js/src/views/admin/contents/add_page_to_category.vue"));
  },
  meta: {
    pageTitle: 'Header',
    breadcrumb: [{
      text: 'Header category List'
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/header_subcat_list",
  name: "header_subcat_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(2), __webpack_require__.e(3), __webpack_require__.e(11), __webpack_require__.e(194)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/header_subcat_list.vue */ "./resources/js/src/views/admin/contents/header_subcat_list.vue"));
  },
  meta: {
    pageTitle: 'Header',
    breadcrumb: [{
      text: 'Header Sub category List'
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/view_cat_sub_page/:id",
  name: "view_cat_sub_page",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(11), __webpack_require__.e(208)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/view_cat_sub_page.vue */ "./resources/js/src/views/admin/contents/view_cat_sub_page.vue"));
  },
  meta: {
    pageTitle: 'Header',
    breadcrumb: [{
      text: 'Category'
    }, {
      text: 'View Category',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/edit_faq/:id",
  name: "edit_faq",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(70)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/add_faq.vue */ "./resources/js/src/views/admin/contents/add_faq.vue"));
  },
  meta: {
    pageTitle: 'FAQ',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Add FAQ',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/about_us",
  name: "about-us",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(9), __webpack_require__.e(13), __webpack_require__.e(279)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/about-us.vue */ "./resources/js/src/views/admin/contents/about-us.vue"));
  },
  meta: {
    pageTitle: 'About Us',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'About Us',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/contacts",
  name: "contact",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(9), __webpack_require__.e(13), __webpack_require__.e(281)]).then(__webpack_require__.bind(null, /*! @/views/admin/contents/contact.vue */ "./resources/js/src/views/admin/contents/contact.vue"));
  },
  meta: {
    pageTitle: 'Contact',
    breadcrumb: [{
      text: 'Contents'
    }, {
      text: 'Contact',
      active: true
    }],
    resource: 'contents',
    action: 'read'
  }
}, {
  path: "/admin/site_settings",
  name: "site_settings",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(262)]).then(__webpack_require__.bind(null, /*! @/views/admin/settings/site_settings.vue */ "./resources/js/src/views/admin/settings/site_settings.vue"));
  },
  meta: {
    pageTitle: 'Site Settings',
    breadcrumb: [{
      text: 'Settings'
    }, {
      text: 'Site Settings',
      active: true
    }],
    resource: 'settings',
    action: 'read'
  }
}, {
  path: "/admin/google_settings",
  name: "google_settings",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(300)]).then(__webpack_require__.bind(null, /*! @/views/admin/settings/google_settings.vue */ "./resources/js/src/views/admin/settings/google_settings.vue"));
  },
  meta: {
    pageTitle: 'Google Settings',
    breadcrumb: [{
      text: 'Settings'
    }, {
      text: 'Google Settings',
      active: true
    }],
    resource: 'settings',
    action: 'read'
  }
}, {
  path: "/admin/email_settings",
  name: "email_settings",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(95)]).then(__webpack_require__.bind(null, /*! @/views/admin/settings/email_settings.vue */ "./resources/js/src/views/admin/settings/email_settings.vue"));
  },
  meta: {
    pageTitle: 'Email Settings',
    breadcrumb: [{
      text: 'Settings'
    }, {
      text: 'Email Settings',
      active: true
    }],
    resource: 'settings',
    action: 'read'
  }
}, {
  path: "/admin/sms_settings",
  name: "sms_settings",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 308).then(__webpack_require__.bind(null, /*! @/views/admin/settings/sms_Settings.vue */ "./resources/js/src/views/admin/settings/sms_Settings.vue"));
  },
  meta: {
    pageTitle: 'SMS Settings',
    breadcrumb: [{
      text: 'Settings'
    }, {
      text: 'SMS Settings',
      active: true
    }],
    resource: 'settings',
    action: 'read'
  }
}, {
  path: "/admin/payment_settings",
  name: "payment_settings",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 307).then(__webpack_require__.bind(null, /*! @/views/admin/settings/payment_settings.vue */ "./resources/js/src/views/admin/settings/payment_settings.vue"));
  },
  meta: {
    pageTitle: 'Payment Settings',
    breadcrumb: [{
      text: 'Settings'
    }, {
      text: 'Payment Settings',
      active: true
    }],
    resource: 'settings',
    action: 'read'
  }
},
// {
//   path: "/admin/deliverypartner_settings",
//   name: "deliverypartner_settings",
//   component: () => import("@/views/admin/settings/deliverypartner_settings.vue"),
//   meta: {
//       pageTitle: 'Delivery Partner Settings',
//       breadcrumb: [
//         {
//           text: 'Settings',
//         },
//         {
//           text: 'Delivery Partner Settings',
//           active: true,
//         },
//       ],
//       resource: 'settings',
//       action: 'read',
//     },

// },
{
  path: '/admin/view_orders/:id',
  name: 'view_order',
  // component: () => import('@/views/ordermanagement/view_order.vue'),
  component: function component() {
    return __webpack_require__.e(/*! import() */ 252).then(__webpack_require__.bind(null, /*! @/views/apps/invoice/invoice-preview/view_order.vue */ "./resources/js/src/views/apps/invoice/invoice-preview/view_order.vue"));
  },
  meta: {
    pageTitle: 'Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'View Order',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: '/admin/view_enquiry/:id',
  name: 'view_enquiry',
  // component: () => import('@/views/ordermanagement/view_order.vue'),
  component: function component() {
    return __webpack_require__.e(/*! import() */ 253).then(__webpack_require__.bind(null, /*! @/views/apps/invoice/invoice-preview/view_order_enquiry.vue */ "./resources/js/src/views/apps/invoice/invoice-preview/view_order_enquiry.vue"));
  },
  meta: {
    pageTitle: 'Orders',
    breadcrumb: [{
      text: 'Order Management'
    }, {
      text: 'View Order',
      active: true
    }],
    resource: 'order-view',
    action: 'read'
  }
}, {
  path: "/admin/add_areas",
  name: "add_area",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 265).then(__webpack_require__.bind(null, /*! @/views/admin/city/add_area.vue */ "./resources/js/src/views/admin/city/add_area.vue"));
  },
  meta: {
    pageTitle: 'Add Area',
    breadcrumb: [{
      text: 'Area'
    }, {
      text: 'Add Area',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: "/admin/edit_areas/:id",
  name: "edit_area",
  component: function component() {
    return __webpack_require__.e(/*! import() */ 266).then(__webpack_require__.bind(null, /*! @/views/admin/city/edit_area.vue */ "./resources/js/src/views/admin/city/edit_area.vue"));
  },
  meta: {
    pageTitle: 'Edit Area',
    breadcrumb: [{
      text: 'Area'
    }, {
      text: 'Edit Area',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: '/admin/view_city_areas/:id',
  name: "view_area",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(35), __webpack_require__.e(351)]).then(__webpack_require__.bind(null, /*! @/views/admin/city/view_area.vue */ "./resources/js/src/views/admin/city/view_area.vue"));
  },
  meta: {
    pageTitle: 'View Area',
    breadcrumb: [{
      text: 'View'
    }, {
      text: 'View Area',
      active: true
    }],
    resource: 'city-edit',
    action: 'read'
  }
}, {
  path: '/admin/account-setting',
  name: 'account_setting',
  component: function component() {
    return __webpack_require__.e(/*! import() */ 244).then(__webpack_require__.bind(null, /*! @/views/admin/account/AccountSetting.vue */ "./resources/js/src/views/admin/account/AccountSetting.vue"));
  },
  meta: {
    pageTitle: 'Account Settings',
    breadcrumb: [{
      text: 'Pages'
    }, {
      text: 'Account Settings',
      active: true
    }]
  }
}, {
  path: '/admin/print_order/:id',
  name: 'print_order',
  component: function component() {
    return __webpack_require__.e(/*! import() */ 211).then(__webpack_require__.bind(null, /*! @/views/admin/ordermanagement/print_order.vue */ "./resources/js/src/views/admin/ordermanagement/print_order.vue"));
  },
  meta: {
    pageTitle: 'Print Order',
    breadcrumb: [{
      text: 'Print'
    }, {
      text: 'Print Order',
      active: true
    }]
  }
}, {
  path: "/admin/business_list",
  name: "business_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(228)]).then(__webpack_require__.bind(null, /*! @/views/admin/Business/business_list.vue */ "./resources/js/src/views/admin/Business/business_list.vue"));
  },
  meta: {
    pageTitle: 'Business List',
    breadcrumb: [{
      text: 'Business'
    }, {
      text: 'Business List',
      active: true
    }],
    resource: 'business',
    action: 'read'
  }
}, {
  path: "/admin/add_business",
  name: "add_business",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(58)]).then(__webpack_require__.bind(null, /*! @/views/admin/Business/add_business.vue */ "./resources/js/src/views/admin/Business/add_business.vue"));
  },
  meta: {
    pageTitle: 'Add Business',
    breadcrumb: [{
      text: 'Business'
    }, {
      text: 'Add Business',
      active: true
    }],
    resource: 'business',
    action: 'read'
  }
}, {
  path: "/admin/edit_business/:id",
  name: "edit_business",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(58)]).then(__webpack_require__.bind(null, /*! @/views/admin/Business/add_business.vue */ "./resources/js/src/views/admin/Business/add_business.vue"));
  },
  meta: {
    pageTitle: 'Edit Business',
    breadcrumb: [{
      text: 'Business'
    }, {
      text: 'Edit Business',
      active: true
    }],
    resource: 'business',
    action: 'read'
  }
}, {
  path: "/admin/business_category_list",
  name: "business_category_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(230)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/business_category_list.vue */ "./resources/js/src/views/admin/category/business_category_list.vue"));
  },
  meta: {
    pageTitle: ' Business Category List',
    breadcrumb: [{
      text: 'Business Category'
    }, {
      text: 'Business Category List',
      active: true
    }],
    resource: 'category-view',
    action: 'read'
  }
}, {
  path: "/admin/business_category_edit/:id",
  name: "business_category_edit",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(62)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/business_category_add.vue */ "./resources/js/src/views/admin/category/business_category_add.vue"));
  },
  meta: {
    pageTitle: 'Edit Business Category',
    breadcrumb: [{
      text: 'Business'
    }, {
      text: 'Edit Business Category',
      active: true
    }],
    resource: 'category-edit',
    action: 'read'
  }
}, {
  path: "/admin/business_category_add",
  name: "business_category_add",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(12), __webpack_require__.e(1), __webpack_require__.e(62)]).then(__webpack_require__.bind(null, /*! @/views/admin/category/business_category_add.vue */ "./resources/js/src/views/admin/category/business_category_add.vue"));
  },
  meta: {
    pageTitle: 'Add Business Category',
    breadcrumb: [{
      text: 'Business'
    }, {
      text: 'Add Business Category',
      active: true
    }],
    resource: 'category-edit',
    action: 'read'
  }
}, {
  path: "/admin/add_package",
  name: "add_package",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(72)]).then(__webpack_require__.bind(null, /*! @/views/admin/package/add_package.vue */ "./resources/js/src/views/admin/package/add_package.vue"));
  },
  meta: {
    pageTitle: 'Add Package Type',
    breadcrumb: [{
      text: 'Package'
    }, {
      text: 'Add Package',
      active: true
    }],
    resource: 'package-edit',
    action: 'read'
  }
}, {
  path: "/admin/package",
  name: "package_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(234)]).then(__webpack_require__.bind(null, /*! @/views/admin/package/package_list.vue */ "./resources/js/src/views/admin/package/package_list.vue"));
  },
  meta: {
    pageTitle: 'Package Type List',
    breadcrumb: [{
      text: 'Package'
    }, {
      text: 'Package List',
      active: true
    }],
    resource: 'package-view',
    action: 'read'
  }
}, {
  path: "/admin/edit_package/:id",
  name: "edit_package",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(1), __webpack_require__.e(72)]).then(__webpack_require__.bind(null, /*! @/views/admin/package/add_package.vue */ "./resources/js/src/views/admin/package/add_package.vue"));
  },
  meta: {
    pageTitle: 'Edit Package Type',
    breadcrumb: [{
      text: 'Package'
    }, {
      text: 'Edit Package',
      active: true
    }],
    resource: 'package-edit',
    action: 'read'
  }
}, {
  path: "/admin/carrier",
  name: "carrier_list",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(229)]).then(__webpack_require__.bind(null, /*! @/views/admin/carrier/carrier_list.vue */ "./resources/js/src/views/admin/carrier/carrier_list.vue"));
  },
  meta: {
    pageTitle: 'Carrier Type List',
    breadcrumb: [{
      text: 'Carrier'
    }, {
      text: 'Carrier List',
      active: true
    }],
    resource: 'carrier-view',
    action: 'read'
  }
}, {
  path: "/admin/add_carrier",
  name: "add_carrier",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(69)]).then(__webpack_require__.bind(null, /*! @/views/admin/carrier/add_carrier.vue */ "./resources/js/src/views/admin/carrier/add_carrier.vue"));
  },
  meta: {
    pageTitle: 'Add Carrier Type',
    breadcrumb: [{
      text: 'Carrier'
    }, {
      text: 'Add Carrier',
      active: true
    }],
    resource: 'carrier-edit',
    action: 'read'
  }
}, {
  path: "/admin/edit_carrier",
  name: "edit_carrier",
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(5), __webpack_require__.e(1), __webpack_require__.e(69)]).then(__webpack_require__.bind(null, /*! @/views/admin/carrier/add_carrier.vue */ "./resources/js/src/views/admin/carrier/add_carrier.vue"));
  },
  meta: {
    pageTitle: 'Edit Carrier Type',
    breadcrumb: [{
      text: 'Carrier'
    }, {
      text: 'Edit Carrier',
      active: true
    }],
    resource: 'carrier-edit',
    action: 'read'
  }
},
// {
//   path: "/admin/edit_areas",
//   name: "edit_area",
//   component: () => import("@/views/admin/city/edit_area.vue"),
//   meta: {
//       pageTitle: 'edit Area',
//       breadcrumb: [
//         {
//           text: 'Area',
//         },
//         {
//           text: 'edit Area',
//           active: true,
//         },
//       ],
//     resource: 'city-edit',
//     action: 'read',
//     },

// },

{
  path: '/admin/user_detail/:id/',
  name: 'user_detail',
  component: function component() {
    return __webpack_require__.e(/*! import() */ 335).then(__webpack_require__.bind(null, /*! @/views/admin/users/user_detail.vue */ "./resources/js/src/views/admin/users/user_detail.vue"));
  },
  meta: {
    pageTitle: 'User Detail',
    breadcrumb: [{
      text: 'User Management'
    }, {
      text: 'users',
      active: true
    }],
    resource: 'user-view',
    action: 'read'
  }
}, {
  path: '/admin/plans_list',
  name: 'plans_list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(2), __webpack_require__.e(1), __webpack_require__.e(235)]).then(__webpack_require__.bind(null, /*! @/views/admin/plans/plans_list.vue */ "./resources/js/src/views/admin/plans/plans_list.vue"));
  },
  meta: {
    pageTitle: 'Plans',
    breadcrumb: [{
      text: 'Plans'
    }, {
      text: 'Plans List',
      active: true
    }],
    resource: 'cuisines-edit',
    action: 'read'
  }
}, {
  path: '/admin/add_plans',
  name: 'add_plans',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(1), __webpack_require__.e(64)]).then(__webpack_require__.bind(null, /*! @/views/admin/plans/add_plans.vue */ "./resources/js/src/views/admin/plans/add_plans.vue"));
  },
  meta: {
    pageTitle: 'Add Plans',
    breadcrumb: [{
      text: 'Plans'
    }, {
      text: 'Add Plans',
      active: true
    }],
    resource: 'cuisines-edit',
    action: 'read'
  }
}, {
  path: '/admin/edit_plans/:id',
  name: 'edit_plans',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(0), __webpack_require__.e(3), __webpack_require__.e(1), __webpack_require__.e(64)]).then(__webpack_require__.bind(null, /*! @/views/admin/plans/add_plans.vue */ "./resources/js/src/views/admin/plans/add_plans.vue"));
  },
  meta: {
    pageTitle: 'Add Plans',
    breadcrumb: [{
      text: 'Plans'
    }, {
      text: 'Edit Plan',
      active: true
    }],
    resource: 'cuisines-edit',
    action: 'read'
  }
}]);

/***/ }),

/***/ "./resources/js/src/router/routes/apps.js":
/*!************************************************!*\
  !*** ./resources/js/src/router/routes/apps.js ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__);



/* harmony default export */ __webpack_exports__["default"] = ([
// *===============================================---*
// *--------- ECOMMERCE  ---------------------------------------*
// *===============================================---*
{
  path: '/apps/e-commerce/shop',
  name: 'apps-e-commerce-shop',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(25), __webpack_require__.e(38), __webpack_require__.e(212)]).then(__webpack_require__.bind(null, /*! @/views/apps/e-commerce/e-commerce-shop/ECommerceShop.vue */ "./resources/js/src/views/apps/e-commerce/e-commerce-shop/ECommerceShop.vue"));
  },
  meta: {
    contentRenderer: 'sidebar-left-detached',
    contentClass: 'ecommerce-application',
    pageTitle: 'Shop',
    breadcrumb: [{
      text: 'ECommerce'
    }, {
      text: 'Shop',
      active: true
    }]
  }
}, {
  path: '/apps/e-commerce/wishlist',
  name: 'apps-e-commerce-wishlist',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(38), __webpack_require__.e(366)]).then(__webpack_require__.bind(null, /*! @/views/apps/e-commerce/e-commerce-wishlist/ECommerceWishlist.vue */ "./resources/js/src/views/apps/e-commerce/e-commerce-wishlist/ECommerceWishlist.vue"));
  },
  meta: {
    pageTitle: 'Wishlist',
    contentClass: 'ecommerce-application',
    breadcrumb: [{
      text: 'ECommerce'
    }, {
      text: 'Wishlist',
      active: true
    }]
  }
}, {
  path: '/apps/e-commerce/checkout',
  name: 'apps-e-commerce-checkout',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(4), __webpack_require__.e(20), __webpack_require__.e(38), __webpack_require__.e(161)]).then(__webpack_require__.bind(null, /*! @/views/apps/e-commerce/e-commerce-checkout/ECommerceCheckout.vue */ "./resources/js/src/views/apps/e-commerce/e-commerce-checkout/ECommerceCheckout.vue"));
  },
  meta: {
    pageTitle: 'Checkout',
    contentClass: 'ecommerce-application',
    breadcrumb: [{
      text: 'ECommerce'
    }, {
      text: 'Checkout',
      active: true
    }]
  }
}, {
  path: '/apps/e-commerce/:slug',
  name: 'apps-e-commerce-product-details',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(10), __webpack_require__.e(92), __webpack_require__.e(38), __webpack_require__.e(195)]).then(__webpack_require__.bind(null, /*! @/views/apps/e-commerce/e-commerce-product-details/ECommerceProductDetails.vue */ "./resources/js/src/views/apps/e-commerce/e-commerce-product-details/ECommerceProductDetails.vue"));
  },
  meta: {
    pageTitle: 'Product Details',
    contentClass: 'ecommerce-application',
    breadcrumb: [{
      text: 'ECommerce'
    }, {
      text: 'Shop',
      active: true
    }, {
      text: 'Product Details',
      active: true
    }]
  }
},
// Invoice
{
  path: '/apps/invoice/list',
  name: 'apps-invoice-list',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(4), __webpack_require__.e(55)]).then(__webpack_require__.bind(null, /*! @/views/apps/invoice/invoice-list/InvoiceList.vue */ "./resources/js/src/views/apps/invoice/invoice-list/InvoiceList.vue"));
  }
}, {
  path: '/apps/invoice/preview/:id',
  name: 'apps-invoice-preview',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(7), __webpack_require__.e(8), __webpack_require__.e(60), __webpack_require__.e(324)]).then(__webpack_require__.bind(null, /*! @/views/apps/invoice/invoice-preview/InvoicePreview.vue */ "./resources/js/src/views/apps/invoice/invoice-preview/InvoicePreview.vue"));
  }
}, {
  path: '/apps/invoice/add/',
  name: 'apps-invoice-add',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(7), __webpack_require__.e(8), __webpack_require__.e(5), __webpack_require__.e(182)]).then(__webpack_require__.bind(null, /*! @/views/apps/invoice/invoice-add/InvoiceAdd.vue */ "./resources/js/src/views/apps/invoice/invoice-add/InvoiceAdd.vue"));
  }
}, {
  path: '/apps/invoice/edit/:id',
  name: 'apps-invoice-edit',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(7), __webpack_require__.e(8), __webpack_require__.e(5), __webpack_require__.e(60), __webpack_require__.e(317)]).then(__webpack_require__.bind(null, /*! @/views/apps/invoice/invoice-edit/InvoiceEdit.vue */ "./resources/js/src/views/apps/invoice/invoice-edit/InvoiceEdit.vue"));
  }
}]);

/***/ }),

/***/ "./resources/js/src/router/routes/others.js":
/*!**************************************************!*\
  !*** ./resources/js/src/router/routes/others.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__);



/* harmony default export */ __webpack_exports__["default"] = ([{
  path: '/access-control',
  name: 'access-control',
  component: function component() {
    return __webpack_require__.e(/*! import() */ 395).then(__webpack_require__.bind(null, /*! @/views/extensions/acl/AccessControl.vue */ "./resources/js/src/views/extensions/acl/AccessControl.vue"));
  },
  meta: {
    resource: 'ACL',
    action: 'read'
  }
}]);

/***/ }),

/***/ "./resources/js/src/router/routes/pages.js":
/*!*************************************************!*\
  !*** ./resources/js/src/router/routes/pages.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
/* harmony import */ var core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_string_iterator_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
/* harmony import */ var core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_web_dom_collections_iterator_js__WEBPACK_IMPORTED_MODULE_2__);



/* harmony default export */ __webpack_exports__["default"] = ([{
  path: '/error-404',
  name: 'error-404',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(49), __webpack_require__.e(367)]).then(__webpack_require__.bind(null, /*! @/views/error/Error404.vue */ "./resources/js/src/views/error/Error404.vue"));
  },
  meta: {
    layout: 'full',
    resource: 'Auth',
    action: 'read'
  }
}, {
  path: '/admin/login',
  name: 'auth-login',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(209)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/Login.vue */ "./resources/js/src/views/pages/authentication/Login.vue"));
  },
  meta: {
    layout: 'full',
    resource: 'Auth',
    redirectIfLoggedIn: true
  }
}, {
  path: '/pages/authentication/login-v1',
  name: 'auth-login-v1',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(63), __webpack_require__.e(370)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/Login-v1.vue */ "./resources/js/src/views/pages/authentication/Login-v1.vue"));
  },
  meta: {
    layout: 'full'
  }
}, {
  path: '/pages/authentication/login-v2',
  name: 'auth-login-v2',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(66), __webpack_require__.e(371)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/Login-v2.vue */ "./resources/js/src/views/pages/authentication/Login-v2.vue"));
  },
  meta: {
    layout: 'full'
  }
}, {
  path: '/register',
  name: 'auth-register',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(36), __webpack_require__.e(372)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/Register.vue */ "./resources/js/src/views/pages/authentication/Register.vue"));
  },
  meta: {
    layout: 'full',
    resource: 'Auth',
    redirectIfLoggedIn: true
  }
}, {
  path: '/pages/authentication/register-v1',
  name: 'auth-register-v1',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(236)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/Register-v1.vue */ "./resources/js/src/views/pages/authentication/Register-v1.vue"));
  },
  meta: {
    layout: 'full'
  }
}, {
  path: '/pages/authentication/register-v2',
  name: 'auth-register-v2',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(36), __webpack_require__.e(289)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/Register-v2.vue */ "./resources/js/src/views/pages/authentication/Register-v2.vue"));
  },
  meta: {
    layout: 'full'
  }
}, {
  path: '/forgot-password',
  name: 'auth-forgot-password',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(33), __webpack_require__.e(369)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/ForgotPassword.vue */ "./resources/js/src/views/pages/authentication/ForgotPassword.vue"));
  },
  meta: {
    layout: 'full',
    resource: 'Auth',
    redirectIfLoggedIn: true
  }
}, {
  path: '/pages/authentication/forgot-password-v1',
  name: 'auth-forgot-password-v1',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(309)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/ForgotPassword-v1.vue */ "./resources/js/src/views/pages/authentication/ForgotPassword-v1.vue"));
  },
  meta: {
    layout: 'full'
  }
}, {
  path: '/pages/authentication/forgot-password-v2',
  name: 'auth-forgot-password-v2',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(33), __webpack_require__.e(368)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/ForgotPassword-v2.vue */ "./resources/js/src/views/pages/authentication/ForgotPassword-v2.vue"));
  },
  meta: {
    layout: 'full',
    resource: 'Auth'
  }
}, {
  path: '/pages/authentication/reset-password-v1',
  name: 'auth-reset-password-v1',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(241)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/ResetPassword-v1.vue */ "./resources/js/src/views/pages/authentication/ResetPassword-v1.vue"));
  },
  meta: {
    layout: 'full'
  }
}, {
  path: '/pages/authentication/reset-password-v2',
  name: 'auth-reset-password-v2',
  component: function component() {
    return Promise.all(/*! import() */[__webpack_require__.e(3), __webpack_require__.e(67), __webpack_require__.e(373)]).then(__webpack_require__.bind(null, /*! @/views/pages/authentication/ResetPassword-v2.vue */ "./resources/js/src/views/pages/authentication/ResetPassword-v2.vue"));
  },
  meta: {
    layout: 'full'
  }
}]);

/***/ })

}]);