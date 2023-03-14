<script setup>
import { PencilIcon } from "@heroicons/vue/20/solid";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { computed, nextTick, ref } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
import { store } from "@/store";

const props = defineProps({
  card: Object,
});

const emit = defineEmits(["created"]);

const colors = [
  { name: "White", bgColor: "bg-white", selectedColor: "ring-indigo-500" },
  { name: "Pink", bgColor: "bg-pink-500", selectedColor: "ring-pink-500" },
  {
    name: "Purple",
    bgColor: "bg-purple-500",
    selectedColor: "ring-purple-500",
  },
  { name: "Blue", bgColor: "bg-blue-500", selectedColor: "ring-blue-500" },
  { name: "Green", bgColor: "bg-green-500", selectedColor: "ring-green-500" },
  {
    name: "Yellow",
    bgColor: "bg-yellow-500",
    selectedColor: "ring-yellow-500",
  },
];

const titleNameRef = ref();
const isShowingForm = computed(
  () => props.card.id === store.value.editingCardId
);
const form = useForm({
  title: props.card.title,
  color: props.card.color,
  date: props.card.date,
});

async function showForm() {
  store.value.editingCardId = props.card.id;
  store.value.listCreatingCardId = null;
  await nextTick();
  var element = document.getElementById("listRef-" + props.date);
  titleNameRef.value.focus();
}

function onSubmit() {
  form.put(route("cards.update", props.card.id), {
    onSuccess: () => {
      form.reset();
      store.value.editingCardId = null;
    },
  });
}
</script>
<template>
  <li>
    <div
      :class="!isShowingForm ? 'hover:bg-gray-50 dark:hover:bg-gray-700' : ''"
      class="group relative z-10 bg-white dark:bg-gray-800 p-3 dark:shadow-none rounded-md border-b border-gray-300 dark:border-none"
    >
      <form
        @keydown.esc="store.editingCardId = null"
        v-if="isShowingForm"
        @submit.prevent="onSubmit()"
      >
        <textarea
          ref="titleNameRef"
          v-model="form.title"
          rows="3"
          @keydown.enter.prevent="onSubmit()"
          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
          placeholder="Enter todo..."
        ></textarea>

        <div class="mt-2 px-1">
          <RadioGroup v-model="form.color">
            <div class="mt-4 flex items-center space-x-3">
              <RadioGroupOption
                as="template"
                v-for="color in colors"
                :key="color.name"
                :value="color.bgColor"
                v-slot="{ active, checked }"
              >
                <div
                  :class="[
                    color.selectedColor,
                    active && checked ? 'ring ring-offset-1' : '',
                    !active && checked ? 'ring-2' : '',
                    'relative -m-0.5 flex cursor-pointer items-center justify-center rounded-full p-0.5 focus:outline-none',
                  ]"
                >
                  <RadioGroupLabel as="span" class="sr-only">{{
                    color.name
                  }}</RadioGroupLabel>
                  <span
                    aria-hidden="true"
                    :class="[
                      color.bgColor,
                      'h-5 w-5 rounded-full border border-black border-opacity-10',
                    ]"
                  />
                </div>
              </RadioGroupOption>
            </div>
          </RadioGroup>
        </div>

        <div class="mt-4 space-x-2">
          <button
            class="px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-500 rounded-md shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
            type="submit"
          >
            Save todo
          </button>
          <button
            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:text-black rounded-md focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
            type="button"
            @click="store.editingCardId = null"
          >
            Cancel
          </button>
        </div>
      </form>
      <template v-if="!isShowingForm">
        <Link
          :href="route('dashboard', { board: card.board_id, card: card.id })"
          preserve-stateclass="text-sm dark:text-gray-400"
          ><span
            v-if="card.color"
            class="h-3 w-3 inline-block rounded-full mr-1 align-middle"
            :class="card.color"
          ></span
          >{{ card.title }}</Link
        >
        <button
          @click="showForm()"
          class="hidden absolute top-1 right-1 w-8 h-8 bg-gray-50 dark:border-gray-700 dark:bg-transparent group-hover:grid place-content-center rounded-md text-gray-600 dark:hover:text-gray-300 dark:text-gray-400 hover:text-black dark:hover:bg-gray-800 hover:bg-gray-200"
        >
          <PencilIcon class="w-5 h-5" />
        </button>
      </template>
    </div>
  </li>
</template>
<style scoped>
.drag > div {
  transform: rotate(5deg);
}

.ghost {
  background: #e5e7eb;
  border-radius: 6px;
}
.ghost > div {
  visibility: hidden;
}
</style>
