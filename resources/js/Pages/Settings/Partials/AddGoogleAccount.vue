<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import _ from "lodash";

const props = defineProps({
  accounts: Object,
});

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  selectedCalendars: props.accounts.selectedCalendars,
});

const updateCalendars = () => {
  form.patch(route("settings.update"), {
    preserveScroll: true,
    onSuccess: () => {},
    onError: () => {},
  });
};

function isChecked(calendar) {
  console.log(calendar);
  return calendar === true;
}
</script>

<template>
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <section class="space-y-3 max-w-xl">
      <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Google calendar sync
        </h2>
      </header>
      <div class="space-y-3" v-if="props.accounts.name === null">
        <div class="text-sm text-gray-600 dark:text-gray-400">
          Week planner supports synchronization with your personal Google
          Calendar. If you want to display events from your calendar, click on
          the button "Add Google account".
        </div>
        <div>
          <a
            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            :href="route('settings.store')"
            >Add Google account</a
          >
        </div>
      </div>
      <div class="space-y-3" v-else>
        <div class="flex justify-between">
          <div>
            <h2 class="font-medium leading-6">
              Connected with Google Account:
            </h2>
            <p class="underline">{{ props.accounts?.name }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div
    v-if="props.accounts.name !== null"
    class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
  >
    <section class="space-y-3">
      <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Google Calendars
        </h2>
      </header>
      <form @submit.prevent="updateCalendars" class="mt-6 space-y-6">
        <div class="space-y-3">
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Select below which calendars do you want import into app.
          </div>
          <fieldset class="max-w-sm">
            <div
              class="mt-4 divide-y divide-gray-200 border-t border-b border-gray-200"
            >
              <div
                v-for="(calendar, calendarIdx) in accounts.calendars"
                :key="calendarIdx"
                class="relative flex items-start py-4"
              >
                <div class="min-w-0 flex-1 text-sm leading-6">
                  <label
                    :for="`calendar-${calendarIdx}`"
                    class="select-none font-medium text-gray-900"
                    ><span
                      :style="'background-color:' + calendar.color + ';'"
                      class="mr-2 inline-block align-middle h-4 w-4 rounded-full ring-2 ring-white"
                    ></span
                    >{{ calendar.name }}</label
                  >
                </div>
                <div class="ml-3 flex h-6 items-center">
                  <input
                    :value="calendar.id"
                    v-model="form.selectedCalendars"
                    :id="`calendar-${calendarIdx}`"
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                  />
                </div>
              </div>
            </div>
          </fieldset>
          <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

            <Transition
              enter-from-class="opacity-0"
              leave-to-class="opacity-0"
              class="transition ease-in-out"
            >
              <p
                v-if="form.recentlySuccessful"
                class="text-sm text-gray-600 dark:text-gray-400"
              >
                Saved.
              </p>
            </Transition>
          </div>
        </div>
      </form>
    </section>
  </div>
</template>
