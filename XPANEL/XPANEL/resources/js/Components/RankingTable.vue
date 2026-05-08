<template>
    <div class="overflow-x-auto overflow-y-wrap">
      <table class="w-full border-collapse">
        <thead class=" text-sm text-white bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-100% shadow-md">
          <tr>
            <th class="  py-3 text-lg">Position</th>
            <th v-for="(rank_attribute, index) in rank_attributes" :key="index" class="px-5 py-3 w-auto text-lg font-bold">
              {{ rank_attribute }}
            </th>
          </tr>
        </thead>
        <tbody class=" text-center text-sm text-black dark:text-white">
          <slot name="row"></slot>
        </tbody>
      </table>
    </div>
  </template>
<script>
import { defineComponent, ref } from "vue";
import Pagination from "@/Components/Pagination.vue";
export default defineComponent({
    components: {
        Pagination,
    },
    props: {
        ranking: Object,
        rank_attributes: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const rank_values = ref([]);

        function getOverallIndex(index) {
            return (
                props.ranking.current_page * props.ranking.per_page -
                props.ranking.per_page +
                index +
                1
            );
        }

        return {
            getOverallIndex,
        };
    },
});
</script>

<style scoped>
table {
    border-spacing: 0px 0.8rem;
}
</style>
