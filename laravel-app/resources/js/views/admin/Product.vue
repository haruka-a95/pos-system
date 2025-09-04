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

    <!-- 商品一覧 -->
     <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col" style="width:5%;">#</th>
                <th scope="col" style="width:15%;">商品カテゴリ</th>
                <th scope="col" style="width:25%;">商品名</th>
                <th scope="col" style="width:10%;">金額</th>
                <th scope="col" style="width:30%;">商品説明</th>
                <th scope="col" style="width:15%;">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>{{ product.id }}</td>
                <td>
                    <span :class="[
                        'px-2 py-1 rounded',
                        categories?.find?.(c => c.id === product.category_id) ? 'bg-secondary text-white' : ''
                        ]"
                    >
                    {{ categories?.find?.(c => c.id === product.category_id)?.name || '未登録' }}
                    </span>
                </td>
                <td class="d-flex flex-column align-items-center justify-content-center">
                    <img v-if="product.image_path" :src="`/storage/${product.image_path}`" class="img-fluid w-25" alt="`${product.name}`" />
                    <span>{{ product.name }}</span>
                </td>
                <td>{{ product.price }}</td>
                <!-- <td>{{ product.status }}</td> -->
                <td>{{ product.description }}</td>
                <td>
                    <button class="btn btn-sm btn-warning me-2" @click="editProduct(product)">編集</button>
                    <button class="btn btn-sm btn-danger me-2" @click="openModal(product)">削除</button>
                </td>
            </tr>
        </tbody>
     </table>
     <!-- 削除確認モーダル -->
      <ConfirmModal
        :visible="modalVisible"
        title="確認"
        :message="`本当に「${selectedProduct?.name || ''}」を削除しますか?`"
        @confirm="deleteProduct"
        @cancel="modalVisible = false"
        />
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ConfirmModal from '@/components/ConfirmModal.vue';

export default {
  name: 'Product',
  components: { ConfirmModal },
  setup() {
    const fileErrMsg = ref('');
    const modalVisible = ref(false);
    const selectedProduct = ref<any>(null);
    const products = ref<any[]>([]);
    const categories = ref<any[]>([]);
    const form = ref({
      id: null,
      name: '',
      price: 0,
      category_id: '',
      image: null,
    });

    // Authorization ヘッダ用トークン
    const token = localStorage.getItem('token');

    // axios インスタンス作成（認証ヘッダ付き）
    const api = axios.create({
      headers: {
        Authorization: `Bearer ${token}`
      }
    });

    // 商品一覧取得
    const fetchProducts = async () => {
      const res = await axios.get('/api/products');
      products.value = res.data;
    };

    // カテゴリ一覧取得
    const fetchCategories = async () => {
      const res = await axios.get('/api/categories');
      categories.value = res.data;
    };

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
        await fetchProducts();
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

    onMounted(() => {
      fetchProducts();
      fetchCategories();
    });

    return {
      fileErrMsg,
      products,
      categories,
      modalVisible,
      form,
      selectedProduct,
      onFileChange,
      submitProduct,
      editProduct,
      openModal,
      deleteProduct
    };
  }
};
</script>