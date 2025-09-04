<template>
    <div v-if="visible" class="modal-backdrop">
        <div class="modal-content">
             <h5 class="mb-3">{{ title }}</h5>
             <p>{{ message }}</p>
             <div class="d-flex justify-content-end gap-2 mt-3">
                <button class="btn btn-secondary" @click="$emit('cancel')">キャンセル</button>
                <button class="btn btn-danger" @click="$emit('confirm')">OK</button>
             </div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    visible: boolean
    title?: string
    message?: string
}>()

defineEmits<{
    (e: 'confirm'): void
    (e: 'cancel'): void
}>()
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: #fff;
  padding: 1.5rem;
  border-radius: .5rem;
  max-width: 400px;
  width: 100%;
}
</style>