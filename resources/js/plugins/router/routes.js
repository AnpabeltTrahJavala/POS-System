export const routes = [
  { path: '/', redirect: '/dashboard' },
  {
    path: '/',
    component: () => import('@/layouts/default.vue'),
    children: [
      {
        path: 'dashboard',
        component: () => import('@/pages/dashboard.vue'),
      },
      {
        path: 'adminlist',
        component: () => import('@/pages/adminlist.vue'),
      },
      {
        path: 'editadmin/:id',
        component: () => import('@/pages/editadmin.vue'),
        props: true,
      },
      {
        path: 'categories',
        component: () => import('@/pages/categories.vue'),
      },
      {
        path: 'editCategories/:id',
        component: () => import('@/pages/editCategories.vue'),
        props: true,
      },
      {
        path: 'customer',
        component: () => import('@/pages/customer.vue'),
      },
      {
        path: 'editCustomer/:id',
        component: () => import('@/pages/editCustomer.vue'),
        props: true,
      },
      {
        path: 'products',
        component: () => import('@/pages/products.vue'),
      },
      {
        path: 'editProducts/:id',
        component: () => import('@/pages/editProducts.vue'),
        props: true,
      },
      {
        path: 'orders',
        component: () => import('@/pages/orders.vue'),
      },
      {
        path: 'createOrder/:id',
        component: () => import('@/pages/createOrder.vue'),
        props: true,
      },
      {
        path: 'editOrder/:id',
        component: () => import('@/pages/editOrder.vue'),
        props: true,
      },
      {
        path: 'detailadmin/:id',
        component: () => import('@/pages/detailadmin.vue'),
        props: true,
      },
    ],
  },
  {
    path: '/',
    component: () => import('@/layouts/blank.vue'),
    children: [
      {
        path: 'login',
        component: () => import('@/pages/login.vue'),
      },
      {
        path: 'register',
        component: () => import('@/pages/register.vue'),
      },
      {
        path: '/:pathMatch(.*)*',
        component: () => import('@/pages/[...error].vue'),
      },
    ],
  },
]
