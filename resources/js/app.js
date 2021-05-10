require('./bootstrap');
window.Vue = require('vue').default;
Vue.component('purchase-order', require('./components/PurchaseOrder.vue').default);
Vue.component('sales-order', require('./components/SalesOrder.vue').default);
