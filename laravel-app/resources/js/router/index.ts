import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import About from '../views/About.vue';
import Todo from '../views/Todo.vue';

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/about', name: 'About', component: About },
  { path: '/todo', name: 'Todo', component: Todo },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
