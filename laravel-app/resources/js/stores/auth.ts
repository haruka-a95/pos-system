import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as any | null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAdmin: (state) => state.user?.role === 'admin',
    isLoggedIn: (state) => !!state.user,
  },
  actions: {
    // ログイン
    async login(email: string, password: string) {
      const res = await axios.post('/api/login', { email, password });
      this.token = res.data.token;
      localStorage.setItem('token', this.token);
      this.user = res.data.user;
    },
    // ログアウト
    async logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
    },
    // ログイン状態
    async checkLogin() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.user = null;
        return;
      }

      try {
        const res = await axios.get('/api/login-status', {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.user = res.data.user;
      } catch {
        this.user = null;
        localStorage.removeItem('token');
      }
    },
  },
});
