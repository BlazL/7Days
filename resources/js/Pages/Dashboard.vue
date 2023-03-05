<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  DotsHorizontalIcon,
  PencilIcon,
  PlusIcon,
} from "@heroicons/vue/20/solid";

import { Head } from "@inertiajs/vue3";
import { nextTick, onMounted } from "vue";

const props = defineProps({
  cards: Array,
  daysOfWeek: Object,
  currentDay: String,
  title: String,
});

onMounted(() => {
  document.getElementById(props.currentDay).scrollIntoView({
    behavior: "auto",
    block: "center",
    inline: "center",
  });
});
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
            class="w-72 border border-dashed border-4 h-[35rem] flex flex-col rounded-md"
          >
            <div class="flex items-center justify-between px-3 py-2">
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
            <div class="pb-3 flex flex-col overflow-hidden">
              <div class="px-3 flex-1">
                <ul class="space-y-3">
                  <li
                    v-for="(item, index) in cards[day.unFormattedDate]"
                    :key="index"
                    class="group relative bg-white p-3 shadow rounded-md border-b border-gray-300 hover:bg-gray-50"
                  >
                    <a href="#" class="text-sm">{{ item.title }}</a>
                    <button
                      class="hidden absolute top-1 right-1 w-8 h-8 bg-gray-50 group-hover:grid place-content-center rounded-md text-gray-600 hover:text-black hover:bg-gray-200"
                    >
                      <PencilIcon class="w-5 h-5" />
                    </button>
                  </li>
                </ul>
              </div>

              <div class="px-3 mt-3">
                <button
                  class="flex items-center p-2 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-300 w-full rounded-md"
                >
                  <PlusIcon class="h-5 w-5"></PlusIcon>
                  <span class="ml-1">Add new note</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
