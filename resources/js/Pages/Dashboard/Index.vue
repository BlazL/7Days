<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TodoListCreateForm from "@/Pages/Dashboard/TodoListCreateForm.vue";
import CardListItem from "@/Pages/Dashboard/CardListItem.vue";
import CardListItemModal from "@/Pages/Dashboard/CardListItemModal.vue";
import { Head, router } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import Draggable from "vuedraggable";

import {
  DotsHorizontalIcon,
  PencilIcon,
  PlusIcon,
} from "@heroicons/vue/20/solid";

const props = defineProps({
  week: Object,
  currentDay: String,
  title: String,
  cards: Object,
  card: Object,
});

const cards = ref(props.cards);

onMounted(() => {
  document.getElementById(props.currentDay).scrollIntoView({
    behavior: "auto",
    block: "center",
    inline: "center",
  });
});

watch(
  () => props.cards,
  (newCards) => (cards.value = newCards)
);

const onCardCreated = (elementId) => {
  var element = document.getElementById("listRef-" + elementId);
  element.scrollIntoView({ block: "end" });
};

function onChange(day, e) {
  let item = e.added || e.moved;

  if (!item) return;
  let index = item.newIndex;
  let prevCard = cards.value[day][index - 1];
  let nextCard = cards.value[day][index + 1];
  let card = cards.value[day][index];

  let position = card.position;

  if (prevCard && nextCard) {
    position = (prevCard.position + nextCard.position) / 2;
  } else if (prevCard) {
    position = prevCard.position + prevCard.position / 2;
  } else if (nextCard) {
    position = nextCard.position / 2;
  }

  router.put(route("cards.move", { card: card.id }), {
    position: position,
    day: day,
  });
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="flex-col flex h-screen">
      <div class="flex items-center justify-between px-3 py-2 mt-4">
        <h1 class="text-2xl text-gray-800 font-bold">{{ title }}</h1>
      </div>
      <div id="board" class="flex-1 overflow-y-hidden mt-4">
        <div class="inline-flex h-full items-start px-4 space-x-4">
          <div
            v-for="(day, index) in week"
            :key="index"
            :id="day.unFormattedDate"
            :class="
              day.current
                ? 'border-indigo-500'
                : 'border-gray-300 dark:border-gray-700'
            "
            class="w-72 border border-dashed border-4 flex flex-col rounded-md h-[calc((100%-4rem))]"
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
              <div
                class="w-full border-t border-gray-300 dark:border-gray-700"
              ></div>
            </div>
            <div class="overflow-auto">
              <div
                :id="'listRef-' + day.unFormattedDate"
                class="px-3 flex-1 overflow-y-auto"
              >
                <Draggable
                  class="space-y-3"
                  :list="cards[day.unFormattedDate]"
                  group="cards"
                  item-key="id"
                  drag-class="drag"
                  ghost-class="ghost"
                  tag="ul"
                  @change="(e) => onChange(day.unFormattedDate, e)"
                >
                  <template #item="{ element }">
                    <CardListItem :key="index" :card="element" />
                  </template>
                </Draggable>
                <ul></ul>
              </div>
            </div>
            <div class="flex pt-3 py-2 pb-3">
              <div class="px-3 w-72">
                <TodoListCreateForm
                  :date="day.unFormattedDate"
                  @created="onCardCreated"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <CardListItemModal :card="props.card" />
  </AuthenticatedLayout>
</template>
