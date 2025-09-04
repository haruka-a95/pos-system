# Vue.jsでのルーティングについて
(実施済： ルーターのインストール `npm install vue-router@4`)

1. ルーター設定ファイル作成 `resources/js/router/index.ts`
詳細は実際のファイルでご確認ください。
```ts
// 例
import { createRouter, createWebHistory } from 'vue-router';
// 必要なvueコンポーネントをimport
import Home from '../views/Home.vue';
// ルートを設定
const routes = [
  { path: '/', name: 'Home', component: Home },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});
export default router;
```

2. App.vue にルーターを登録 `resources/js/App.vue`
詳細は実際のファイルでご確認ください。
```ts
<template>
  <div>
    <!-- ナビゲーション -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <router-link class="nav-link" to="/">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/about">About</router-link>
        </li>
      </ul>
    </nav>

    <!-- ルートコンポーネントの表示 -->
    <router-view></router-view>
  </div>
</template>

<script lang="ts">
export default {
  name: 'App',
};
</script>
```

3. Vueアプリにルーターを登録
```ts
import { createApp } from 'vue';
import App from '../views/App.vue';
import router from './router';

const app = createApp(App);
app.use(router);
app.mount('#app');
```

4. コンポーネント作成 `resources/js/views/Home.vue`
```ts
<template>
  <div>
    <h1>HOMEページ</h1>
    <p>Welcome to the Home page!</p>
  </div>
</template>

<script lang="ts">
export default { name: 'Home' };
</script>
```

## ポイント
- `<router-link>` で SPA 内リンクを作成
- `<router-view>` が現在のルートに対応するコンポーネントを表示
- Laravel 側は API サーバとして動作するので、Vue が画面表示を担う