import { usePage } from "@inertiajs/inertia-vue3";
import { notifications } from "@/VueComposable/store";

export const useNotifications = () => {

  const addNotify = async (message) => {
    notifications.value = notifications.value.concat([message]);
    await delay(10000);
    deleteNotify(message?.id);
  };
  const deleteNotify = (id) => {
    notifications.value = notifications.value.filter((m) => m.id !== id);
  };

  const delay = ms => new Promise(res => setTimeout(res, ms));

  return {
    addNotify,
    deleteNotify
  }
}