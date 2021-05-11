<template>
  <main class="text-center">
    <form @submit.prevent="submitForm" autocomplete="off">
      <h1 class="h3 fw-normal mb-3">Регистрация в конкурсе</h1>

      <div v-if="successMessage" class="alert alert-success" role="alert" v-text="successMessage"></div>

      <div class="form-floating mt-3">
        <input v-model="form.name" type="text" class="form-control" :class="validName" id="name" placeholder="Ваше Имя, Фамилия, Отчество" :disabled="loading">
        <label for="name">Ваше Имя, Фамилия, Отчество</label>
      </div>

      <div class="form-floating mt-3">
        <input v-model="form.code" type="text" class="form-control" :class="validCode" id="code" placeholder="Ваш код" :disabled="loading">
        <label for="code">Ваш код</label>
      </div>

      <button :disabled="btnDisabled" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Отправить</button>
    </form>
  </main>
</template>

<script>
import axios from "@plugin/axios";

export default {
  name: "RegistrationComponent",
  data: () => ({
    form: {
      name: '',
      code: '',
    },
    loading: false,
    errors: [],
    submitted: false,
    successMessage: null,
  }),

  computed: {
    btnDisabled() {
      return !(this.form.name && this.form.code && !this.loading)
    },
    validName () {
      return this.submitted
          ? this.errors.hasOwnProperty('name')
              ? 'is-invalid'
              : 'is-valid'
          : ''
    },
    validCode () {
      return this.submitted
          ? this.errors.hasOwnProperty('code')
              ? 'is-invalid'
              : 'is-valid'
          : ''
    },
  },

  methods: {
    submitForm() {
      this.loading = true
      this.successMessage = null
      this.errors = []
      this.submitted = false

      axios.post('api/register', this.form)
          .then(() => {
            this.successMessage = "Регистрация успешно завершена"
            this.form.code = ''
            this.form.name = ''

          })
          .catch(({response}) => {
            let errors = response.data.errors
            if (errors) {
              this.errors = errors
            }
          })
          .finally(() => {
            this.submitted = true
            this.loading = false
          })
    },
  },
}
</script>

<style scoped>

</style>