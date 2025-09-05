# API 基本設計書

## 認証関連

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| POST | /login | ログイン | なし |
| POST | /logout | ログアウト | 必須 |
| GET | /login-status | ログイン状態確認 | 必須 |

---

## 商品管理 (Products)

### 公開API

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| GET | /api/products | 商品一覧取得 | なし |
| GET | /api/products/search | 商品検索 (名前/カテゴリ/価格範囲) | なし |

### 管理者用API

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| POST | /api/products | 商品登録 | 必須 |
| PUT | /api/products/{product} | 商品更新 | 必須 |
| DELETE | /api/products/{product} | 商品削除 | 必須 |

---

## カテゴリ管理 (Categories)

### 公開API

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| GET | /api/categories | カテゴリ一覧取得 | なし |

### 管理者用API

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| POST | /api/categories | カテゴリ登録 | 必須 |
| PUT | /api/categories/{category} | カテゴリ更新 | 必須 |
| DELETE | /api/categories/{category} | カテゴリ削除 | 必須 |

---

## テーブル管理 (Desks)

### 公開API

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| GET | /api/desks | テーブル一覧取得 | なし |

### 管理者用API

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| POST | /api/desks | テーブル登録 | 必須 |
| PUT | /api/desks/{desk} | テーブル更新 | 必須 |
| DELETE | /api/desks/{desk} | テーブル削除 | 必須 |

---

## 注文管理 (Orders)

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| GET | /api/orders | 注文一覧取得 | なし |
| POST | /api/orders | 注文登録 | なし |
| GET | /api/orders/{deskId}/items | 指定テーブルの注文商品一覧取得 | なし |
| PUT | /api/orders/{order} | 注文更新 | なし |
| DELETE | /api/orders/{order} | 注文削除 | なし |

---

## 注文明細管理 (Order Items)

| メソッド | URL | 説明 | 認証 |
|----------|-----|------|------|
| GET | /api/order-items | 注文明細一覧取得 | なし |
| POST | /api/order-items | 注文明細登録 | なし |
| PUT | /api/order-items/{orderItem} | 注文明細更新 | なし |
| DELETE | /api/order-items/{orderItem} | 注文明細削除 | なし |

---

## 共通事項

- 認証必須APIは **Sanctum トークン認証** を利用
- ページネーション対応は `page` と `per_page` パラメータで制御
- 商品検索は `/api/products/search` にて `name`, `category_id`, `min_price`, `max_price` でフィルタ可能
