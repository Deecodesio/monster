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
    {
      path: '/blogs',
      name: 'blogs',
      component: () => import('@@@/views/blog/BlogList.vue'),
      meta: {
        publicRoute: true,
        contentRenderer: 'sidebar-left',
        permission: 'public'
      }
    },
    {
      path: '/blogs/:slug',
      name: 'blog-detail',
      component: () => import('@@@/views/blog/BlogDetail.vue'),
      props: true,
    },
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

// router.beforeEach((to, _, next) => {
//   const isLoggedIn = isUserLoggedIn()

//   if (!canNavigate(to)) {
//     // Redirect to login if not logged in
//     if (!isLoggedIn) return next({ name: 'auth-login' })

//     // If logged in => not authorized
//     return next({ name: 'misc-not-authorized' })
//   }

//   // Redirect if logged in
//   if (to.meta.redirectIfLoggedIn && isLoggedIn) {
//     const userData = getUserData()
//     next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
//   }

//   return next()
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
