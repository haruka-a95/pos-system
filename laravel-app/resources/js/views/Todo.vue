<template>
  <div class="container mt-4">
    <h1>Todo リスト</h1>

    <!-- 新規追加 -->
    <form @submit.prevent="addTodo">
      <input v-model="newTitle" class="form-control mb-2" placeholder="新しいタスク名を入力" />
      <input v-model="newDescription" class="form-control mb-2" placeholder="詳細を入力" />
      <button class="btn btn-primary mb-3">追加</button>
    </form>

    <!-- Todo 一覧 -->
     <p>完了したらチェックしてください。</p>
    <ul class="list-group">
      <li v-for="todo in todos" :key="todo.id" class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <input type="checkbox" v-model="todo.completed" @change="updateTodo(todo)" />
          <span :class="{ 'text-decoration-line-through': todo.completed }">
            {{ todo.title }} - {{ todo.description }}
          </span>
        </div>
        <button class="btn btn-sm btn-danger" @click="deleteTodo(todo.id)">削除</button>
      </li>
    </ul>
  </div>
</template>

<script lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'Todo',
  setup() {
    const todos = ref<any[]>([])
    const newTitle = ref('')
    const newDescription = ref('')

    // 全部取得
    const fetchTodos = async () => {
      const res = await axios.get('/api/todos')
      todos.value = res.data
    }

    // 追加
    const addTodo = async () => {
      if (!newTitle.value) return
      const res = await axios.post('/api/todos', {
        title: newTitle.value,
        description: newDescription.value,
        completed: false,
      })
      todos.value.push(res.data)
      newTitle.value = ''
      newDescription.value = ''
    }

    // 更新
    const updateTodo = async (todo: any) => {
      await axios.put(`/api/todos/${todo.id}`, todo)
    }

    // 削除
    const deleteTodo = async (id: number) => {
      await axios.delete(`/api/todos/${id}`)
      todos.value = todos.value.filter(t => t.id !== id)
    }

    onMounted(fetchTodos)

    return { todos, newTitle, newDescription, addTodo, updateTodo, deleteTodo }
  },
}
</script>
