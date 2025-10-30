<template>
  <div>

    <div class="row mt-1">
      <div class="col-12">
        <h1 class="mt-3 mb-3 d-inline-flex">
          Верификация
        </h1>
        <Spinner v-if="!isPageLoaded" :size="36" :stroke="4" color="#42b883" :overlay="false" label="Загрузка…" />
      </div>
      <div class="col-12" v-if="isPageLoaded">
        <div class="input-group mb-3">
          <input
              class="form-control"
              v-imask="'+7 (000) 000-00-00'"
              v-model="phone"
              @input="onPhoneInput"
              :disabled="timer > 0 || loading"
              placeholder="+7 (___) ___-__-__"
              type="tel"
          />
          <button
              class="btn btn-primary"
              @click="onSendCode"
              :disabled="timer > 0 || loading || !isValid"
          >
            {{ timer > 0 ? `Отправить повторно через ${timer} сек` : 'Отправить код' }}
          </button>
        </div>
      </div>

      <div class="col-12" v-if="isPageLoaded">
        <div class="input-group mb-3">
          <input
              class="form-control"
              v-model="code"
              maxlength="4"
              :disabled="!isSend"
              placeholder="Код из SMS"
          >

          <button
              class="btn btn-outline-secondary"
              @click="onVerifyCode"
              :disabled="loading || !isSend"
          >
            Подтвердить
          </button>
        </div>
      </div>
    </div>

    <p v-if="loading">Загрузка...</p>
    <p v-if="success" class="alert alert-success">Готово!</p>
    <p v-if="error" class="alert alert-danger" >{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useSmsVerification } from '~/composables/useSmsVerification'

const phone = ref('')
const code = ref('')
const isValid = ref(false)
const isSend = ref(false)

let debounceTimeout: any = null

const { sendCode, verifyCode, startTimer, loading, error, success, timer, isPageLoaded } = useSmsVerification()


const onPhoneInput = (e) => {
  const raw = e.target.value.replace(/\D/g, '')
  isValid.value = raw.length >= 11;
  console.log(isValid.value);
}

const onSendCode = async () => {
  await sendCode(phone.value)
  if (!error.value) {
    isSend.value = true;
    timer.value = 60
    startTimer()
  }
}

const onVerifyCode = async () => {
  clearTimeout(debounceTimeout)
  debounceTimeout = setTimeout(async () => {
    await verifyCode(phone.value, code.value)
  }, 500)

}
</script>
