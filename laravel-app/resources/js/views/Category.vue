<template>
    <div class="p-2">
        <h1 class="text-center">カテゴリ管理</h1>
        <!-- 登録・編集フォーム -->
        <div class="border border-info p-3 rounded mb-4">
            <form @submit.prevent="submitCategory" class="d-flex align-items-center gap-2">
                <input v-model="form.name" class="form-control" placeholder="カテゴリ名" required />
                <button type="submit" class="btn btn-primary text-nowrap">登録</button>
            </form>
        </div>

        <!-- 一覧 -->
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <td scope="col" style="width:5%;">#</td>
                    <td scope="col" style="width:20%;">カテゴリ名</td>
                    <td scope="col" style="width:15%;">操作</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories" :key="category.id">
                    <td>{{ category.id }}</td>
                    <td>{{ category.name }}</td>
                    <td>
                        <button class="btn btn-warning me-2" @click="editCategory(category)">編集</button>
                        <button class="btn btn-danger me-2" @click="openModal(category)">削除</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- モーダル -->
        <ConfirmModal
            :visible="modalVisible"
            title="確認"
            message="本当に削除しますか?"
            @confirm="deleteCategory"
            @cancel="modalVisible = false"
                             />
    </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue';
import ConfirmModal from '../components/ConfirmModal.vue';
import axios from 'axios';

export default {
    name: 'Category',
    components: { ConfirmModal },
    setup() {
        const categories = ref<any[]>([])
        const form = ref({ id: null, name: '' });

        const modalVisible = ref(false);
        const selectedCategory = ref<any>(null);

        // 全件取得
        const fetchCategories = async () => {
            const res = await axios.get('/api/categories');
            categories.value = res.data;
        };

        // カテゴリ登録・編集
        const submitCategory = async () => {
            if (!form.value.name) return;

            if (form.value.id) { // 編集
                await axios.put(`/api/categories/${form.value.id}`, { name: form.value.name,});
            } else { // 新規
                await axios.post('api/categories', { name: form.value.name,});
            }
            form.value = { id: null, name: ''};
            await fetchCategories();
        }

        // カテゴリ編集時、コピーして表示
        const editCategory = (category: any) => {
            form.value = { id: category.id, name: category.name};
        }

        const openModal = (editCategory: any) => {
            selectedCategory.value = editCategory
            modalVisible.value = true
        }

        // 削除
        const deleteCategory = async () => {
            if (!selectedCategory.value) return
            await axios.delete(`api/categories/${selectedCategory.value.id}`);
            categories.value = categories.value.filter(c => c.id !== selectedCategory.value.id);
            modalVisible.value = false
            selectedCategory.value = null
        };

        onMounted(() => {
            fetchCategories();
        });

        return { categories, form, modalVisible, selectedCategory, submitCategory, editCategory, openModal, deleteCategory}
    },
};
</script>