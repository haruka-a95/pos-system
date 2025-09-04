<template>
  <nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
    <ul class="navbar-nav">
      <!-- 管理者用ページ -->
      <template v-if="isAdmin">
        <li class="nav-item">
          <router-link class="nav-link" to="/admin/home">ホーム（管理者）</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/product">商品管理</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/category">カテゴリ管理</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/desk">テーブル管理</router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" @click.prevent="logout">ログアウト</a>
        </li>
      </template>

      <!-- 一般ユーザー用ページ -->
      <template v-else>
        <li class="nav-item">
          <router-link class="nav-link" to="/">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/about">About</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/desk-list">テーブル選択</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/login">管理者用ログイン</router-link>
        </li>
      </template>
    </ul>
  </nav>

  <router-view></router-view>
</template>

<script lang="ts">
import { onMounted, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const auth = useAuthStore();
    const router = useRouter();

    // computed でリアクティブに監視
    const isAdmin = computed(() => auth.isAdmin);

    const logout = async () => {
      await auth.logout();
      router.push('/');
    };

    onMounted(async () => {
      await auth.checkLogin(); // 非同期でログイン状態を更新
    });

    return { isAdmin, logout };
  }
};
</script>
