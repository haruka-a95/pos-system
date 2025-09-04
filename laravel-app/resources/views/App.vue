<template>
    <!-- ナビゲーション -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <router-link class="nav-link" to="/">Home</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/about">About</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" to="/todo">Todo</router-link>
            </li>
        </ul>
    </nav>

  <!-- ルートコンポーネント -->
    <router-view></router-view>
</template>

<script lang="ts">
export default {
  name: 'App',
};

</script>
