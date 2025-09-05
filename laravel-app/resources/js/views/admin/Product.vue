<template>
  <div class="p-2">
    <h1 class="text-center">商品管理</h1>

    <!-- 商品登録 -->
     <div class="border border-info p-3 rounded mb-4">
        <h2 class="text-center">{{ form.id ? '編集' : '新規登録' }}</h2>
        <form @submit.prevent="submitProduct">
            <div class="mb-2">
                <input v-model="form.name" class="form-control" placeholder="商品名" required />
            </div>
            <div class="mb-2">
                <input v-model.number="form.price" class="form-control" placeholder="価格" type="number" required />
            </div>
            <div class="mb-2">
                <textarea v-model="form.description" class="form-control" placeholder="説明"></textarea>
            </div>
            <div class="mb-2">
                <select v-model="form.category_id" class="form-select">
                    <option value="">カテゴリを選択</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
            <div class="mb-2">
                <input type="file" @change="onFileChange" class="form-control" />
                <small class="text-danger" v-if="fileErrMsg">{{ fileErrMsg }}</small>
            </div>
            <button type="submit" class="btn btn-primary">登録</button>
        </form>
     </div>

      <!-- 検索フォーム -->
    <div class="mb-3 d-flex gap-2 border p-2 rounded border-dark-subtle">
      <input v-model="searchName" type="text" class="form-control" placeholder="商品名" />
      <select v-model="searchCategory" class="form-select">
        <option value="">カテゴリ選択</option>
        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
      </select>
      <input v-model.number="minPrice" type="number" class="form-control" placeholder="最低金額" />
      <input v-model.number="maxPrice" type="number" class="form-control" placeholder="最高金額" />
      <button class="btn btn-primary" @click="searchServer">検索</button>
      <button class="btn btn-secondary" @click="clearSearch">クリア</button>
    </div>

    <!-- 商品一覧 -->
    <div>
      <Table :columns="columns" :data="products" has-actions>
        <!-- カテゴリ表示カスタム -->
        <template #category_id="{ row }">
          <span
            :class="[
              'px-2 py-1 rounded',
              categories.find(c => c.id === row.category_id) ? 'bg-secondary text-white' : ''
            ]"
          >
            {{ categories.find(c => c.id === row.category_id)?.name || '未登録' }}
          </span>
        </template>

        <!-- 商品名（画像付き） -->
        <template #name="{ row }">
          <div class="d-flex flex-column align-items-center justify-content-center">
            <img
              v-if="row.image_path"
              :src="`/storage/${row.image_path}`"
              class="img-fluid w-25"
              :alt="row.name"
            />
            <span>{{ row.name }}</span>
          </div>
        </template>
        <!-- 操作 -->
        <template #actions="{ row }">
          <button class="btn btn-sm btn-warning me-2" @click="editProduct(row)">
            編集
          </button>
          <button class="btn btn-sm btn-danger" @click="openModal(row)">
            削除
          </button>
        </template>
      </Table>
    </div>

     <!-- 削除確認モーダル -->
      <ConfirmModal
        :visible="modalVisible"
        title="確認"
        :message="`本当に「${selectedProduct?.name || ''}」を削除しますか?`"
        @confirm="deleteProduct"
        @cancel="modalVisible = false"
        />

        <!-- ページネーション -->
    <nav v-if="totalPages > 1" class="mt-3">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: page === 1 }">
          <button class="page-link" @click="page--" :disabled="page === 1">前へ</button>
        </li>
        <li
          class="page-item"
          v-for="p in totalPages"
          :key="p"
          :class="{ active: page === p }"
        >
          <button class="page-link" @click="goPage(p)">{{ p }}</button>
        </li>
        <li class="page-item" :class="{ disabled: page === totalPages }">
          <button class="page-link" @click="page++" :disabled="page === totalPages">次へ</button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import ConfirmModal from '@/components/ConfirmModal.vue';
import Table from '../../components/Table.vue';

export default {
  name: 'Product',
  components: { ConfirmModal, Table },
  setup() {
    const fileErrMsg = ref('');
    const modalVisible = ref(false);
    const selectedProduct = ref<any>(null);

    const allItems = ref<any[]>([]); // 初期ロード全件
    const products = ref<any[]>([]); // Table に表示するデータ
    const categories = ref<any[]>([]);

    // 検索条件
    const searchName = ref('');
    const searchCategory = ref('');
    const minPrice = ref<number | null>(null);
    const maxPrice = ref<number | null>(null);

    // ページネーション
    const page = ref(1);
    const perPage = 10;
    const totalPages = ref(1);

    const columns = [
      { key: 'category_name', label: '商品カテゴリ', width: '15%' },
      { key: 'name', label: '商品名', width: '25%' },
      { key: 'price', label: '金額', width: '10%' },
      { key: 'description', label: '詳細', width: '30%' },
    ];

    const form = ref({
      id: null,
      name: '',
      price: 0,
      category_id: '',
      image: null,
    });

    const token = localStorage.getItem('token');
    const api = axios.create({ headers: { Authorization: `Bearer ${token}` } });

    // 画像選択
    const onFileChange = (event: Event) => {
      const target = event.target as HTMLInputElement;
      fileErrMsg.value = '';
      if (!target.files?.length) return;
      const file = target.files[0];

      // サイズ制限 2MB
      if (file.size / 1024 / 1024 > 2) {
        fileErrMsg.value = 'ファイルサイズは2MB以下にしてください';
        target.value = '';
        return;
      }

      // タイプ制限
      if (!['image/webp','image/jpeg','image/png'].includes(file.type)) {
        fileErrMsg.value = 'webp, jpeg, png のみアップロード可能';
        target.value = '';
        return;
      }

      form.value.image = file;
    };

    // 初期データ取得
    const fetchInitialData = async () => {
      const [resCategories, resProducts] = await Promise.all([
        axios.get('/api/categories'),
        axios.get('/api/products'),
      ]);
      categories.value = resCategories.data;
      allItems.value = resProducts.data.map(p => ({
        ...p,
        category_name: categories.value.find(c => c.id === p.category_id)?.name || '',
      }));

      updateDisplayItems();
    };

    // 表示データを更新（フロント検索 + ページ）
    const updateDisplayItems = () => {
      let filtered = allItems.value;

      // フロント検索
      if (searchName.value) {
        filtered = filtered.filter(p =>
          p.name.includes(searchName.value)
        );
      }
      if (searchCategory.value) {
        filtered = filtered.filter(p => p.category_id == searchCategory.value);
      }
      if (minPrice.value !== null) filtered = filtered.filter(p => p.price >= minPrice.value);
      if (maxPrice.value !== null) filtered = filtered.filter(p => p.price <= maxPrice.value);

      totalPages.value = Math.ceil(filtered.length / perPage);
      const start = (page.value - 1) * perPage;
      products.value = filtered.slice(start, start + perPage);
    };

    // サーバー検索（ページネーション用）
    const searchServer = async () => {
      const params: any = { page: page.value, per_page: perPage };
      if (searchName.value) params.name = searchName.value;
      if (searchCategory.value) params.category_id = searchCategory.value;
      if (minPrice.value !== null) params.min_price = minPrice.value;
      if (maxPrice.value !== null) params.max_price = maxPrice.value;

      const res = await axios.get('/api/products/search', { params });
      products.value = res.data.data.map(p => ({
        ...p,
        category_name: categories.value.find(c => c.id === p.category_id)?.name || '',
      }));
      totalPages.value = res.data.last_page || 1;
    };

    // 検索ボタン
    const search = () => {
      page.value = 1; // ページリセット
      updateDisplayItems();
    };

    const clearSearch = () => {
      searchName.value = '';
      searchCategory.value = '';
      minPrice.value = null;
      maxPrice.value = null;
      page.value = 1;
      updateDisplayItems();
    };

     // 商品登録/更新
    const submitProduct = async () => {
      const data = new FormData();
      data.append('name', form.value.name);
      data.append('price', form.value.price.toString());
      data.append('description', form.value.description || '');
      data.append('category_id', form.value.category_id || '');
      if (form.value.image instanceof File) data.append('image', form.value.image);

      try {
        if (form.value.id) {
          data.append('_method', 'PUT');
          await api.post(`/api/products/${form.value.id}`, data);
        } else {
          await api.post('/api/products', data);
        }
        form.value = { id: null, name: '', price: 0, category_id: '', image: null };
        searchServer();
      } catch (err) {
        console.error(err);
      }
    };

    // 編集
    const editProduct = (product: any) => {
      form.value = {
        id: product.id,
        name: product.name,
        price: product.price,
        description: product.description,
        category_id: product.category_id || '',
        image: null
      };
    };

    // 削除
    const openModal = (product: any) => {
      selectedProduct.value = product;
      modalVisible.value = true;
    };

    const deleteProduct = async () => {
      if (!selectedProduct.value) return;
      await api.delete(`/api/products/${selectedProduct.value.id}`);
      products.value = products.value.filter(p => p.id !== selectedProduct.value.id);
      modalVisible.value = false;
      selectedProduct.value = null;
    };

        // ページ変更
    const goPage = (p: number) => {
      page.value = p;
    };
    watch(page, () => searchServer());

    onMounted(fetchInitialData);

    return {
      fileErrMsg,
      products,
      categories,
      modalVisible,
      form,
      selectedProduct,
      searchName,
      searchCategory,
      minPrice,
      maxPrice,
      page,
      totalPages,
      columns,
      search,
      clearSearch,
      updateDisplayItems,
      searchServer,
      openModal,
      deleteProduct,
      onFileChange,
      editProduct,
      submitProduct,
      goPage,
    };
}

};
</script>