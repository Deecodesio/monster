import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import { canNavigate } from '@@@/libs/acl/routeProtection'
import { isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser } from '@@@/auth/utils'

import user from './routes/user'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    { path: '/', redirect: { name: 'home' } },
    ...user,

    // Add this route definition
    {
      path: '/error-404',
      name: 'error-404',
      component: () => import('@@@/views/error/Error404.vue'),
      meta: {
        layout: 'full',
        resource: 'Auth',
        action: 'read',
      },
    },

    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

// router.beforeEach((to, _, next) => {
//   // First, check if the route exists in our defined routes
//   const routeExists = router.options.routes.some(route => {
//     // Check exact path match
//     if (route.path === to.path) return true

//     // Check if it's one of our dynamic routes
//     if (route.path && (
//       route.path.includes('/blogs') ||
//       route.path.includes('/careers') ||
//       route.path.includes('/franchise')
//     )) {
//       // Check if the current path starts with one of these base paths
//       const pathStart = to.path.split('/')[1]
//       const routeStart = route.path.split('/')[1]
//       return pathStart === routeStart
//     }

//     return false
//   })

//   // Check if the route is a public route (like blogs, careers, franchise)
//   if (to.meta && to.meta.publicRoute) {
//     console.log('Public route detected:', to.path)
//     return next() // Allow navigation to public routes without restrictions
//   }

  // const isLoggedIn = isUserLoggedIn()

  // if (!canNavigate(to)) {
  //   // Redirect to login if not logged in
  //   if (!isLoggedIn) return next({ name: 'login' })

  //   // If logged in => not authorized
  //   return next({ name: 'error-404' })
  // }

  // // Redirect if logged in
  // if (to.meta.redirectIfLoggedIn && isLoggedIn) {
  //   const userData = getUserData()
  //   next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
  // }

  // return next()
// })

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router