// composables/useSmsVerification.ts
import { ref } from 'vue'

export const useSmsVerification = () => {
    const isPageLoaded = ref(false)
    const loading = ref(false)
    const error = ref<string | null>(null)
    const success = ref(false)
    let timer = ref(0)
    const guestId = useCookie('guest_id');

    const sendCode = async (phone: string) => {
        error.value = null
        loading.value = true

        if (timer.value <= 0) {
            try {
                const response = await $fetch('http://localhost/testSms/web/api/sms/send', {
                    method: 'POST',
                    body: { phone: phone, uuid: guestId.value }
                })
                console.log('sendCode', response)
                success.value = true
            } catch (e) {
                if (e.data && e.data.error) {
                    error.value = e.data.error
                } else if (e instanceof Error) {
                    error.value = e.message;
                } else {
                    error.value = String(e);
                }
            } finally {
                loading.value = false
            }
        }

    }

    const verifyCode = async (phone: string, code: string) => {
        error.value = null
        loading.value = true
        console.log(phone);
        try {
            const response = await $fetch('http://localhost/testSms/web/api/sms/verify-code', {
                method: 'POST',
                body: { phone: phone, code: code }
            })
            console.log('verifyCode', response)
            success.value = true
        } catch (e) {
            if (e.data && e.data.error) {
                error.value = e.data.error
            } else if (e instanceof Error) {
                error.value = e.message;
            } else {
                error.value = String(e);
            }

        } finally {
            loading.value = false
        }
    }

    const getSendingDelay = async () => {
        try {
            const response = await $fetch('http://localhost/testSms/web/api/sms/get-sending-delay', {
                method: 'POST',
                body: { uuid: guestId.value }
            })

            console.log('getSendingDelay', response)
            timer.value = response.data.delay;
            startTimer();
            isPageLoaded.value = true;
        } catch (e) {
            if (e.data && e.data.error) {
                error.value = e.data.error
            } else if (e instanceof Error) {
                error.value = e.message;
            } else {
                error.value = String(e);
            }

        }
    }

    const startTimer = () => {
        const interval = setInterval(() => {
            timer.value--
            if (timer.value <= 0) clearInterval(interval)
        }, 1000)
    }


    getSendingDelay();

    return { sendCode, verifyCode, startTimer, loading, error, success, timer, isPageLoaded }
}
