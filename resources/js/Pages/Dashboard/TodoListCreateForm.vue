<script setup>
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { PlusIcon } from "@heroicons/vue/20/solid";
import { nextTick, ref, computed } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { store } from "@/store";

const props = defineProps({
  date: String,
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

const inputNameRef = ref();
const isShowingForm = computed(
  () => props.date === store.value.listCreatingCardId
);
const form = useForm({
  title: "",
  color: colors[0].bgColor,
  date: props.date,
});

async function showForm() {
  store.value.listCreatingCardId = props.date;
  store.value.editingCardId = null;
  await nextTick();
  var element = document.getElementById("listRef-" + props.date);
  element.scrollIntoView({ block: "end" });
  inputNameRef.value.focus();
}

function onSubmit() {
  form.post(route("cards.store"), {
    only: ["cards"],
    onSuccess: () => {
      form.reset();
      inputNameRef.value.focus();
      emit("created", form.date);
    },
  });
}
</script>
<template>
  <form
    class="bg-white dark:bg-gray-800 dark:shadow-none p-2 rounded-md shadow rounded-md border-b border-gray-300 dark:border-none"
    @keydown.esc="store.listCreatingCardId = null"
    v-if="isShowingForm"
    @submit.prevent="onSubmit()"
  >
    <textarea
      ref="inputNameRef"
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
        Add todo
      </button>
      <button
        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:text-black rounded-md focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
        type="button"
        @click="store.listCreatingCardId = null"
      >
        Cancel
      </button>
    </div>
  </form>

  <button
    @click="showForm()"
    v-if="!isShowingForm"
    class="flex items-center p-2 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-300 w-full rounded-md"
  >
    <PlusIcon class="h-5 w-5"></PlusIcon>
    <span class="ml-1">Add todo</span>
  </button>
</template>
