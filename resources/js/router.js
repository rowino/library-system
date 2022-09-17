import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from './views/auth/login.vue'
import Libraries from './views/libraries/index.vue';
import LibraryDetails from './views/libraries/details.vue';
import BookDetails from './views/books/details.vue';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/libraries/:id',
      component: LibraryDetails,
    },
    {
      path: '/book/:id',
      component: BookDetails,
    },
    {
      path: '*',
      component: Libraries,
    }
  ],
});

router.beforeEach((to, from, next) => {
  if (!localStorage.getItem('token') && to.name !== 'login') {
    return next('/login');
  }
  next();
})

export default router;
