import './bootstrap';                       // Laravel Breeze などの初期設定
import '../css/app.css';                    // カスタム CSS
import 'bootstrap';                         // Bootstrap JS（モーダルやドロップダウン用）
import 'bootstrap/dist/css/bootstrap.min.css'; // Bootstrap CSS
import { createApp } from 'vue';
import App from '../views/App.vue';         // メイン Vue コンポーネント
import router from './router';              // ナビゲーション
import { createPinia } from 'pinia';        // 状態管理
import { useAuthStore } from './stores/auth';
import axios from 'axios';

// Appにrouterを使ってマウント
const app = createApp(App);
const pinia = createPinia();
app.use(router);
app.use(pinia);

const auth = useAuthStore();
if (auth.token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`;
}
auth.checkLogin().then(() => {
    app.mount('#app');
});
