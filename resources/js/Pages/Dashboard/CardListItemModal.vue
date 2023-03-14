<script setup>
import { computed, defineProps, watch } from "vue";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import {
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { TrashIcon } from "@heroicons/vue/20/solid";
import { Link, useForm, router } from "@inertiajs/vue3";

const props = defineProps({
  card: Object,
});

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

const isOpen = computed(() => !!props.card);

const form = useForm({
  title: props.card?.title,
  description: props.card?.description,
  color: props.card?.color,
  redirectUrl: route("dashboard"),
});

watch(
  () => props.card,
  (card) => {
    if (card) {
      form.title = card.title;
      form.description = card.description;
      form.redirectUrl = `/dashboard`;
    }
  }
);

function closeModal() {
  router.get(
    route("dashboard"),
    {},
    {
      preserveState: true,
    }
  );
}

function onSubmit() {
  form.put(route("cards.update", { card: props.card.id }));
}
</script>

<template>
  <TransitionRoot :show="isOpen" appear as="template">
    <Dialog as="div" class="relative z-10" @close="closeModal">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-40" />
      </TransitionChild>

      <div class="overflow-y-auto fixed inset-0">
        <div
          class="flex justify-center items-start px-4 py-12 min-h-full text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="overflow-hidden w-full max-w-3xl text-left align-middle bg-white rounded-md shadow-xl transition-all transform"
            >
              <div class="flex flex-col sm:flex-row">
                <form class="flex-1 p-5" @submit.prevent="onSubmit">
                  <div>
                    <label class="sr-only" for="title">Title</label>
                    <textarea
                      id="title"
                      v-model="form.title"
                      class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-400 focus:ring-indigo-400"
                      name="title"
                      rows="1"
                    ></textarea>
                  </div>
                  <div class="mt-4">
                    <label
                      class="inline-block mb-1 text-sm font-semibold text-gray-700"
                      for="description"
                      >Description</label
                    >
                    <textarea
                      id="description"
                      v-model="form.description"
                      class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-400 focus:ring-indigo-400"
                      name="description"
                      rows="4"
                    ></textarea>
                  </div>

                  <div class="mt-3 px-1">
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
                      class="px-4 py-2 text-sm font-medium text-white bg-rose-600 rounded-md shadow-sm hover:bg-rose-500 focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
                      type="submit"
                    >
                      Save card
                    </button>
                    <button
                      class="px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:text-black focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
                      type="button"
                      @click="closeModal()"
                    >
                      Cancel
                    </button>
                  </div>
                </form>
                <div class="p-5 bg-gray-100 sm:w-48">
                  <h3
                    class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase"
                  >
                    Actions
                  </h3>
                  <Link
                    :href="`/cards/${card?.id}`"
                    method="delete"
                    as="button"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 focus:outline-none"
                  >
                    <TrashIcon class="mr-1 -ml-1 w-4 h-4 shrink-0" />
                    <span>Delete card</span>
                  </Link>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
