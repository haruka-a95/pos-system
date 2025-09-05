<template>
    <div class="p-2">
        <h1 class="text-center">テーブル管理</h1>
        <!-- 登録・編集フォーム -->
        <div class="border border-info p-3 rounded mb-4">
            <form @submit.prevent="submitDesk" class="d-flex align-items-center gap-2">
                <input v-model="form.name" class="form-control" placeholder="テーブル名" required />
                <input v-model="form.max_num" class="form-control" placeholder="最大人数" />
                <button type="submit" class="btn btn-primary text-nowrap">登録</button>
            </form>
        </div>

        <!-- 検索フォーム -->
         <div class="mb-4 border p-2 rounded border-dark-subtle">
            <div class="d-flex space-2 justify-content-center">
                <input type="text" v-model="keyword" class="form-control" placeholder="テーブル名で検索"/>
                <button class="btn btn-secondary ms-2" @click="keyword=''">クリア</button>
            </div>
         </div>
        <!-- 一覧 -->
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <td scope="col" style="width:5%;">#</td>
                    <td scope="col" style="width:20%;">テーブル名</td>
                    <td scope="col" style="width:15%;">最大人数</td>
                    <td scope="col" style="width:15%;">操作</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="desk in filteredDesks" :key="desk.id">
                    <td>{{ desk.id }}</td>
                    <td>{{ desk.name }}</td>
                    <td>{{ desk.max_num }}</td>
                    <td>
                        <button class="btn btn-warning me-2" @click="editDesk(desk)">編集</button>
                        <button class="btn btn-danger me-2" @click="openModal(desk)">削除</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- モーダル -->
        <ConfirmModal
            :visible="modalVisible"
            title="確認"
            message="本当に削除しますか?"
            @confirm="deleteDesk"
            @cancel="modalVisible = false"
                             />
    </div>
</template>

<script lang="ts">
import { ref, onMounted, computed } from 'vue';
import ConfirmModal from '../../components/ConfirmModal.vue';
import axios from 'axios';

export default {
    name: 'Desk',
    components: { ConfirmModal },
    setup() {
        const desks = ref<any[]>([])
        const form = ref({ id: null, name: '', max_num:'' });

        const modalVisible = ref(false);
        const selectedDesk = ref<any>(null);

        const keyword = ref('');

        // 全件取得
        const fetchDesks = async () => {
            const res = await axios.get('/api/desks');
            desks.value = res.data;
        };

        // 登録・編集
        const submitDesk = async () => {
            if (!form.value.name) return;

            if (form.value.id) { // 編集
                await axios.put(`/api/desks/${form.value.id}`, { name: form.value.name, max_num: form.value.max_num});
            } else { // 新規
                await axios.post('api/desks', { name: form.value.name, max_num: form.value.max_num});
            }
            form.value = { id: null, name: '', max_num: ''};
            await fetchDesks();
        }

        // 編集時、コピーして表示
        const editDesk = (desk: any) => {
            form.value = { id: desk.id, name: desk.name, max_num: desk.max_num};
        }

        const openModal = (editDesk: any) => {
            selectedDesk.value = editDesk
            modalVisible.value = true
        }

        // 削除
        const deleteDesk = async () => {
            if (!selectedDesk.value) return
            await axios.delete(`api/desks/${selectedDesk.value.id}`);
            desks.value = desks.value.filter(c => c.id !== selectedDesk.value.id);
            modalVisible.value = false
            selectedDesk.value = null
        };

        // 検索
        const filteredDesks = computed(() => {
            if (!keyword.value) return desks.value;
            return desks.value.filter(desk =>
                desk.name.includes(keyword.value)
            );
        })

        onMounted(() => {
            fetchDesks();
        });

        return { desks, form, modalVisible, selectedDesk, submitDesk, editDesk, openModal, deleteDesk, keyword, filteredDesks}
    },
};
</script>