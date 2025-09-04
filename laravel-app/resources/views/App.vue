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
<<<<<<< HEAD
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
                <router-link class="nav-link" to="/desk-list">テーブル選択</router-link>
            </li>
=======
>>>>>>> 984c5cb55f60197108e7baf85c60c4001f8da349
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
