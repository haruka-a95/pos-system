<template>
  <div>
    <h1>アクセスOK! HOMEへようこそ</h1>
        <p>ボタンをクリックした回数：{{ counter }}</p>
        <button class="btn btn-primary" @click="counter += 1">
            click!
        </button>
  </div>
</template>

<script lang="ts">
export default {
  name: 'Home',
};
</script>
