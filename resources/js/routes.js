export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/products', component: require('./components/product/Products.vue').default },
    { path: '/product/tag', component: require('./components/product/Tag.vue').default },
    { path: '/product/category', component: require('./components/product/Category.vue').default },
    { path: '/store', component: require('./components/Public/Products.vue').default },
    { path: '/store/product/:id', component: require('./components/Public/Product.vue').default },
    { path: '/orders', component: require('./components/Order/Orders.vue').default },
    { path: '/order/:id', component: require('./components/Order/Orders.vue').default },
    { path: '/invoices', component: require('./components/Order/Invoices.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
];
