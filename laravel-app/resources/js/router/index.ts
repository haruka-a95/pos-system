import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import About from '../views/About.vue';
import Product from '../views/admin/Product.vue';
import Category from '../views/admin/Category.vue';
import Desk from '../views/admin/Desk.vue';
import DeskList from '../views/DeskList.vue';
import DeskCart from '../views/DeskCart.vue';
import Login from '../views/Login.vue';
import AdminHome from '../views/admin/AdminHome.vue';
import axios from 'axios';


const routes = [
  { path: '/', name: 'Home', component: Home, meta: { title: 'ホーム'}},
  { path: '/login', name: 'Login', component: Login, meta: { title: 'ログイン'}},
  { path: '/admin/home', component: AdminHome, meta: { requiresAuth: true, title: '管理 | ホーム'},},
  { path: '/about', name: 'About', component: About, meta: { title: 'アバウト'}},
  { path: '/product', name: 'Product', component: Product, meta: { title: '商品'}},
  { path: '/category', name: 'Category', component: Category, meta: { title: 'カテゴリ'}},
  { path: '/desk', name: 'Desk', component: Desk, meta: { title: '座席'}},
  { path: '/desk-list', name: 'DeskList', component: DeskList, meta: { title: '座席一覧'}},
  { path: '/desk-cart/:id', component: DeskCart, props: route => ({ deskId: Number(route.params.id )}), meta: { title: 'カート'}},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// ログインチェック
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      // Laravelでセッションが有効か確認
      await axios.get('/login-status', { withCredentials: true });
      next();
    } catch {
      next('/login');
    }
  } else {
    next();
  }
});

// ナビゲーションごとにタイトル更新
router.afterEach((to) => {
  document.title = to.meta.title || 'POS システム';
});

export default router;
