import { v4 as uuidv4 } from 'uuid';

export default defineNuxtRouteMiddleware((to, from) => {
    const guestId = useCookie('guest_id', { maxAge: 60 * 60 * 24 * 30 });
    if (!guestId.value) {
        guestId.value = uuidv4();
    }
});