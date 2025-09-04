<template>
  <div class="p-2">
    <h1>テーブルを選択</h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>テーブル名</th>
          <th>最大人数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="desk in desks" :key="desk.id">
          <td>{{ desk.id }}</td>
          <td>{{ desk.name }}</td>
          <td>{{ desk.max_num }}</td>
          <td>
            <router-link :to="`/desk-cart/${desk.id}`" class="btn btn-primary">
              注文する
            </router-link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'DeskList',
  setup() {
    const desks = ref<any[]>([]);

    const fetchDesks = async () => {
      const res = await axios.get('/api/desks');
      desks.value = res.data;
    };

    onMounted(fetchDesks);

    return { desks };
  }
};
</script>
