import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import About from '../views/About.vue';
import Todo from '../views/Todo.vue';
import Product from '../views/Product.vue';
import Category from '../views/Category.vue';
import Desk from '../views/Desk.vue';
import DeskList from '../views/DeskList.vue';
import DeskCart from '../views/DeskCart.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/about', name: 'About', component: About },
  { path: '/todo', name: 'Todo', component: Todo },
  { path: '/product', name: 'Product', component: Product},
  { path: '/category', name: 'Category', component: Category},
  { path: '/desk', name: 'Desk', component: Desk},
  { path: '/desk-list', name: 'DeskList', component: DeskList},
  { path: '/desk-cart/:id', component: DeskCart, props: route => ({ deskId: Number(route.params.id )})},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
