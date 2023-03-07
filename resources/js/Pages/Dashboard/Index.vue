<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TodoListCreateForm from "@/Pages/Dashboard/TodoListCreateForm.vue";

import {
  DotsHorizontalIcon,
  PencilIcon,
  PlusIcon,
} from "@heroicons/vue/20/solid";

import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const props = defineProps({
  cards: Array,
  daysOfWeek: Object,
  currentDay: String,
  title: String,
});

const listRef = ref();

onMounted(() => {
  document.getElementById(props.currentDay).scrollIntoView({
    behavior: "auto",
    block: "center",
    inline: "center",
  });
});

function onCardCreated() {
  listRef.value.scrollTop = listRef.value.scrollBottom;

  console.log(listRef.value);
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="flex flex-col h-screen">
      <div class="shrink-0 flex justify-between items-center p-4">
        <h1 class="text-2xl text-gray-800 font-bold">{{ title }}</h1>
      </div>
      <div id="board" class="flex-1 overflow-x-auto">
        <div class="inline-flex h-full items-start px-4 pb-4 space-x-4">
          <div
            v-for="(day, index) in daysOfWeek"
            :key="index"
            :id="day.unFormattedDate"
            :class="day.current ? 'border-indigo-500' : 'border-gray-300'"
            class="w-72 h-full border border-dashed border-4 flex flex-col rounded-md"
          >
            <div class="mt-3 flex items-center justify-between px-3 py-2">
              <h3
                :class="day.current ? 'text-indigo-500' : 'text-gray-700'"
                class="text-xl"
              >
                {{ day.weekDay }}
              </h3>
              <h3
                :class="day.current ? 'text-indigo-500' : 'text-gray-700'"
                class="text-xl font-semibold"
              >
                {{ day.day }}
              </h3>
            </div>
            <div class="px-3 py-2">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="pb-3 flex flex-col overflow-hidden">
              <div ref="listRef" class="px-3 flex-1 overflow-y-auto">
                <ul class="space-y-3">
                  <li
                    v-for="(item, index) in cards[day.unFormattedDate]"
                    :key="index"
                    class="group relative bg-white p-3 shadow rounded-md border-b border-gray-300 hover:bg-gray-50"
                  >
                    <a href="#" class="text-sm"
                      ><span
                        class="h-3 w-3 inline-block rounded-full mr-1 align-middle"
                        :class="item.color"
                      ></span
                      >{{ item.title }}</a
                    >
                    <button
                      class="hidden absolute top-1 right-1 w-8 h-8 bg-gray-50 group-hover:grid place-content-center rounded-md text-gray-600 hover:text-black hover:bg-gray-200"
                    >
                      <PencilIcon class="w-5 h-5" />
                    </button>
                  </li>
                </ul>
              </div>

              <div class="px-3 mt-3">
                <TodoListCreateForm
                  :date="day.unFormattedDate"
                  :list="list"
                  @created="onCardCreated()"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
