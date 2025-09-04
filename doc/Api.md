# API 基本設計（POSシステム）
## 1. 概要
管理者用の管理画面と一般ユーザー用のフロント画面をSPA（Vue.js）で提供し、バックエンドはLaravel APIで処理を行います。
認証は管理者ページのみ必須で、一般ユーザーの注文・閲覧は認証不要です。

## 2. フロントエンド（Vur Router）
| パス               | コンポーネント         | メタ情報                           | 説明             |                   |
| ---------------- | --------------- | ------------------------------ | -------------- | ----------------- |
| `/`              | `Home.vue`      | title: "ホーム"                   | 一般ユーザー用トップページ  |                   |
| `/login`         | `Login.vue`     | title: "ログイン"                  | 管理者ログインページ     |                   |
| `/admin/home`    | `AdminHome.vue` | requiresAuth: true, title: "管理 | ホーム"           | 管理者トップページ（ログイン必須） |
| `/about`         | `About.vue`     | title: "アバウト"                  | 一般ユーザー向け紹介ページ  |                   |
| `/product`       | `Product.vue`   | title: "商品"                    | 管理者の商品管理ページ    |                   |
| `/category`      | `Category.vue`  | title: "カテゴリ"                  | 管理者のカテゴリ管理ページ  |                   |
| `/desk`          | `Desk.vue`      | title: "座席"                    | 管理者のテーブル管理ページ  |                   |
| `/desk-list`     | `DeskList.vue`  | title: "座席一覧"                  | 一般ユーザー向けテーブル一覧 |                   |
| `/desk-cart/:id` | `DeskCart.vue`  | title: "カート"                   | 特定テーブルのカート表示   |                   |

- `router.beforeEach` による管理者ページの認証チェック
- `router.afterEach` でページタイトルを動的更新

## 3. APIエンドポイント（Laravel）
### 3.1 認証関連（管理者）
| メソッド | URL                 | 説明          | 認証        |
| ---- | ------------------- | ----------- | --------- |
| POST | `/api/login`        | ログイン        | なし        |
| POST | `/api/logout`       | ログアウト       | Sanctum認証 |
| GET  | `/api/login-status` | 現在のログイン状態確認 | Sanctum認証 |

### 3.2 管理者用リソース（認証必須）
| メソッド   | URL                          | コントローラー                     | 説明     |
| ------ | ---------------------------- | --------------------------- | ------ |
| POST   | `/api/products`              | ProductController\@store    | 商品登録   |
| PUT    | `/api/products/{product}`    | ProductController\@update   | 商品更新   |
| DELETE | `/api/products/{product}`    | ProductController\@destroy  | 商品削除   |
| POST   | `/api/categories`            | CategoryController\@store   | カテゴリ登録 |
| PUT    | `/api/categories/{category}` | CategoryController\@update  | カテゴリ更新 |
| DELETE | `/api/categories/{category}` | CategoryController\@destroy | カテゴリ削除 |
| POST   | `/api/desks`                 | DeskController\@store       | テーブル登録 |
| PUT    | `/api/desks/{desk}`          | DeskController\@update      | テーブル更新 |
| DELETE | `/api/desks/{desk}`          | DeskController\@destroy     | テーブル削除 |

※ `except('index')` のため一覧取得は一般ユーザーもアクセス可能

### 3.3 一般ユーザー（認証不要）
| メソッド                | URL                          | コントローラー                   | 説明              |
| ------------------- | ---------------------------- | ------------------------- | --------------- |
| GET                 | `/api/products`              | ProductController\@index  | 商品一覧取得          |
| GET                 | `/api/categories`            | CategoryController\@index | カテゴリ一覧取得        |
| GET                 | `/api/desks`                 | DeskController\@index     | テーブル一覧取得        |
| GET                 | `/api/orders/{deskId}/items` | OrderController\@items    | 特定テーブルの注文アイテム取得 |
| GET/POST/PUT/DELETE | `/api/orders`                | OrderController           | 注文CRUD          |
| GET/POST/PUT/DELETE | `/api/order-items`           | OrderItemController       | 注文アイテムCRUD      |

### 3.4 API認証方式
- 管理者ページは Laravel Sanctum を利用
- 一般ユーザー向けページ・注文は認証不要

## 4. ポイント
- 管理者ページは SPA 内でログイン状態をチェックし、未ログイン時は /login にリダイレクト
- `<router-link>` と `<router-view>` でナビゲーション制御
- APIは `/api/` プレフィックスで統一