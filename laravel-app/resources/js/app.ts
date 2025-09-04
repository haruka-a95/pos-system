import './bootstrap';                       // Laravel Breeze などの初期設定
import '../css/app.css';                    // カスタム CSS
import 'bootstrap';                         // Bootstrap JS（モーダルやドロップダウン用）
import 'bootstrap/dist/css/bootstrap.min.css'; // Bootstrap CSS
import { createApp } from 'vue';
import App from '../views/App.vue';         // メイン Vue コンポーネント
import router from './router';              // ナビゲーション

// Appにrouterを使ってマウント
const app = createApp(App);
app.use(router);
app.mount('#app');
