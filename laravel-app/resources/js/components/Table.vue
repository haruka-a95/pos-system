<template>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th v-for="col in columns" :key="col.key">
            {{ col.label }}
          </th>
          <th v-if="hasActions">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in data" :key="row.id">
          <td v-for="col in columns" :key="col.key">
            {{ row[col.key] }}
          </td>
          <td v-if="hasActions">
            <slot name="actions" :row="row"></slot>
          </td>
        </tr>
        <tr v-if="!data.length">
          <td :colspan="columns.length + (hasActions ? 1 : 0)" class="text-center">
            データがありません
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from "vue";

export default defineComponent({
  name: "Table",
  props: {
    columns: {
      type: Array as PropType<{ key: string; label: string }[]>,
      required: true,
      default: () => [],
    },
    data: {
      type: Array as PropType<Record<string, any>[]>,
      required: true,
      default: () => [],
    },
    hasActions: {
      type: Boolean,
      default: false,
    },
  },
});
</script>
