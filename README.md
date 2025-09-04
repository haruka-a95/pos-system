# Vue.js と Laravel での開発環境構築
Laravel 12 + Vue 3 + Bootstrap + Vite を Docker Compose + Nginx で動かす開発環境のセットアップ手順です。


フロントエンドは Vue SPA、バックエンドは Laravel API で構成されています。


Dockerを立ち上げておいてください。

## 1. リポジトリをクローン & 移動
```bash
git clone https://github.com/haruka-a95/Laravel-Vue.git
cd Laravel-Vue/laravel-app
```

## 2. `.env` のコピー
```bash
cp .env.example .env
```

## 3. Docker コンテナ構築 & 起動
```bash
docker-compose up -d --build
```

## 4. コンテナ内でLaravelの初期設定
```bash
docker compose exec -it app bash

# Composer 依存関係インストール
composer install

# アプリキー生成
php artisan key:generate

# マイグレーション作成
php artisan migrate --seed

# storage 権限設定
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Node.js 依存関係インストール
npm install
## Bootstrap 5 を使用
## TypeScript 用の型定義もインストール済み

# Laravel 開発サーバーの起動 （開発中常に起動させておく）
php artisan serve --host=0.0.0.0 --port=8000

# VScodeターミナル右側の+ボタンからもう一つターミナルを開き、Vite dev server 起動
cd Laravel-Vue/laravel-app/
docker-compose exec app npm run dev
# 画面を更新する度、ctrl + c で停止、再度npm run devする
```

## 5. アクセス確認
- Laravel アプリ: http://localhost:8000
- phpMyAdmin: http://localhost:8081

## 6. ディレクトリ構成
```css
├ Dockerfile            Docker 設定
├ docker-compose.yml    Docker Compose 設定
├ laravel-app/
├─ app/                 Laravel コアコード（モデル・コントローラー）
├─ database/            マイグレーション・Seeder・Factory
├─ public/              公開ディレクトリ（index.php, 静的ファイル）
├─ resources/           
│   ├─ js/              Vue コンポーネント
│   └─ css/             Tailwind CSS / カスタム CSS
├─ routes/              
│   └─ api.php          API ルート
├─ storage/             ログ・キャッシュ・セッション
├─ package.json         npm 依存・スクリプト
├─ vite.config.js       Vite 設定
├─ tsconfig.json        TypeScript 設定
├─ .env                 環境設定
└── nginx.conf         Nginx 設定
```

## 7. Vue.js でのナビゲーション
以下ドキュメントを参照してください。
[aboutRouter.md](https://github.com/haruka-a95/Laravel-Vue/blob/main/doc/aboutRouter.md)

## 8. APIについて
- `TodoController.php` および `api.php` を参照してください。
- Vueコンポーネントから`axios`を使ってデータを取得

```javascript
import axios from 'axios';
// データ取得
axios.get('/api/products')
  .then(res => this.products = res.data)
  .catch(err => console.error(err));
```
- APIルートは`routes/api.php`にまとめる
```php
Route::get('/products', [ProductController::class, 'index']);
```
- コントローラーに必要な処理を実装する

## 9. ポイント
- Vue が画面表示を担うため、routes/web.php へのルートは記載しない
- Laravel は API サーバとして動作

## 10. その他
### VScodeで追加しておく拡張機能
- Vue - Official (Vue 3 と TypeScript の公式拡張機能)

### 現在のHOME画面
Vue.jsでの状態管理
![screen](https://github.com/haruka-a95/Laravel-Vue/blob/main/doc/image.png)


ボタンをクリックするとカウンターが増える仕組み

- Vue インスタンスの data に counter を定義（初期値 0）
- `<p>` で {{ counter }} として表示
- `<button>` のクリックで counter += 1 が実行される
- Vue が counter の変化を検知
- `<p>` の内容が自動で更新される
```ts
<template>
  <div>
    <h1>アクセスOK! HOMEへようこそ</h1>
        <p>ボタンをクリックした回数：{{ counter }}</p>
        <button class="btn btn-primary" @click="counter += 1">
            click!
        </button>
  </div>
</template>

<script lang="ts">
export default {
  name: 'Home',
};
</script>
```
