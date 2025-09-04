<template>
  <div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-3">管理者ログイン</h3>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <input
          v-model="email"
          type="email"
          placeholder="メールアドレス"
          class="form-control"
          required
        />
      </div>
      <div class="mb-3">
        <input
          v-model="password"
          type="password"
          placeholder="パスワード"
          class="form-control"
          required
        />
      </div>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      <button class="btn btn-primary w-100" type="submit">ログイン</button>
    </form>
  </div>
</template>

<script lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

export default {
  setup() {
    const router = useRouter();
    const auth = useAuthStore();

    const email = ref('');
    const password = ref('');
    const error = ref('');

    const submit = async () => {
      error.value = '';

      try {
        // auth.ts の loginを呼ぶ
        await auth.login(email.value, password.value);
        // 管理者ホームへ
        router.push('/admin/home');
      } catch (err: any) {
        error.value = err.response?.data?.message || err.message || 'ログインに失敗しました';
      }
    };

    return { email, password, error, submit };
  }
};
</script>