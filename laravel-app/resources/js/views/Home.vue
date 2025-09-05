<template>
  <div class="px-2">
    <h1>商品一覧</h1>

    <!-- 検索フォーム -->
    <div class="mb-3 d-flex gap-2">
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

    <Table :columns="columns" :data="displayItems">
      <!-- カテゴリ -->
      <template #category_id="{ row }">
        <span
          :class="[
            'px-2 py-1 rounded',
            categories.find(c => c.id === row.category_id) ? 'bg-secondary text-white' : ''
          ]"
        >
          {{ categories.find(c => c.id === row.category_id)?.name || '' }}
        </span>
      </template>

      <!-- 商品名・画像 -->
      <template #name="{ row }">
        <div class="d-flex flex-column align-items-center justify-content-center">
          <img v-if="row.image_path" :src="`/storage/${row.image_path}`" class="img-fluid w-25" :alt="row.name"/>
          <span>{{ row.name }}</span>
        </div>
      </template>
    </Table>

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
import { ref, onMounted, computed, watch } from "vue";
import Table from "../components/Table.vue";
import axios from "axios";

export default {
  name: "Home",
  components: { Table },
  setup() {
    const items = ref<any[]>([]);           // 表示データ（ページごと）
    const categories = ref<any[]>([]);
    const allItems = ref<any[]>([]);        // 初回ロードの全件（フロント検索用）

    // 検索条件
    const searchName = ref("");
    const searchCategory = ref("");
    const minPrice = ref<number | null>(null);
    const maxPrice = ref<number | null>(null);

    // ページネーション
    const page = ref(1);
    const perPage = 10;
    const totalPages = ref(1);

    const columns = [
      { key: "category_name", label: "商品カテゴリ", width: "15%" },
      { key: "name", label: "商品名", width: "25%" },
      { key: "price", label: "金額", width: "10%" },
      { key: "description", label: "詳細", width: "30%" },
    ];

    const fetchInitialData = async () => {
      const [resCategories, resItems] = await Promise.all([
        axios.get("/api/categories"),
        axios.get("/api/products") // 初回ロードで全件取得
      ]);
      categories.value = resCategories.data;
      allItems.value = resItems.data.map((p: any) => ({
        ...p,
        category_name: categories.value.find(c => c.id === p.category_id)?.name || "",
      }));

      totalPages.value = Math.ceil(allItems.value.length / perPage);
      updateDisplayItems();
    };

    const updateDisplayItems = () => {
      const start = (page.value - 1) * perPage;
      const end = start + perPage;
      items.value = allItems.value.slice(start, end);
    };

    const goPage = (p: number) => {
      page.value = p;
      updateDisplayItems();
    };

    // サーバー検索（ページネーション対応）
    const searchServer = async () => {
      const params: any = { page: page.value, per_page: perPage };
      if (searchName.value) params.name = searchName.value;
      if (searchCategory.value) params.category_id = searchCategory.value;
      if (minPrice.value !== null) params.min_price = minPrice.value;
      if (maxPrice.value !== null) params.max_price = maxPrice.value;

      const res = await axios.get("/api/products/search", { params });
      items.value = res.data.data.map((p: any) => ({
        ...p,
        category_name: categories.value.find(c => c.id === p.category_id)?.name || "",
      }));
      totalPages.value = res.data.last_page || 1;
    };

    // ページ番号が変わったら再取得
    watch(page, () => {
      searchServer();
    });

    // クリアボタン
    const clearSearch = async () => {
      searchName.value = '';
      searchCategory.value = '';
      minPrice.value = null;
      maxPrice.value = null;
      await searchServer();
    };

    onMounted(fetchInitialData);

    const displayItems = computed(() => items.value);

    return {
      items, categories, columns,
      searchName, searchCategory, minPrice, maxPrice,
      searchServer, page, totalPages, goPage, displayItems, clearSearch,
    };
  },
};
</script>
