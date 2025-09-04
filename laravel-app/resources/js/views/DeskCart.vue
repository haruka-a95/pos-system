<template>
  <div class="p-2 border rounded">
    <h5>テーブル: {{ desk?.name }}</h5>

    <!-- 商品選択 -->
    <div class="d-flex gap-2 mb-2">
      <select v-model="selectedProductId" class="form-select">
        <option value="">商品を選択</option>
        <option v-for="product in products" :key="product.id" :value="product.id">
          {{ product.name }} ({{ product.price }}円)
        </option>
      </select>
      <input type="number" v-model.number="quantity" min="1" class="form-control" style="width: 80px;" />
      <button class="btn btn-primary" @click="addToCart">追加</button>
    </div>

    <!-- カート内容 -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>商品名</th>
          <th>数量</th>
          <th>小計</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in cartItems" :key="item.id">
          <td>{{ item.product.name }}</td>
          <td>
            <input type="number" v-model.number="item.quantity" @change="updateItem(item)" min="1" class="form-control" />
          </td>
          <td>{{ item.product.price * item.quantity }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="removeItem(item)">削除</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="text-end fw-bold">
      合計: {{ total }} 円
    </div>
  </div>
</template>

<script lang="ts">
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'DeskCart',
  props: {
    deskId: { type: Number, required: true },
  },
  setup(props) {
    const desk = ref<any>(null);
    const cartItems = ref<any[]>([]);
    const selectedProductId = ref('');
    const quantity = ref(1);
    const products = ref<any[]>([]);

    // テーブル情報取得
    const fetchDesk = async () => {
        if (!props.deskId) return;
      const res = await axios.get(`/api/desks/${props.deskId}`);
      desk.value = res.data;
    };

    // 商品情報
    const fetchProducts = async () => {
        const res = await axios.get('/api/products');
        products.value = res.data;
    }

    // カート情報取得
    const fetchCart = async () => {
      const res = await axios.get(`/api/orders/${props.deskId}/items`);
      cartItems.value = res.data;
    };

    const addToCart = async () => {
      if (!selectedProductId.value || quantity.value < 1) return;

      const res = await axios.post('/api/order-items', {
        order_id: props.deskId,
        product_id: selectedProductId.value,
        quantity: quantity.value,
      });

      cartItems.value.push(res.data);
      selectedProductId.value = '';
      quantity.value = 1;
    };

    const updateItem = async (item: any) => {
      if (item.quantity < 1) item.quantity = 1;
      await axios.put(`/api/order-items/${item.id}`, { quantity: item.quantity });
    };

    const removeItem = async (item: any) => {
      await axios.delete(`/api/order-items/${item.id}`);
      cartItems.value = cartItems.value.filter(i => i.id !== item.id);
    };

    const total = computed(() => {
      return cartItems.value.reduce((sum, item) => sum + item.product.price * item.quantity, 0);
    });

    onMounted(() => {
      fetchDesk();
      fetchProducts();
      fetchCart();
    });

    return { desk, products, cartItems, selectedProductId, quantity, total, addToCart, updateItem, removeItem, };
  }
};
</script>
