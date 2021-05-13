<template>
  <main class="text-center px-3" style="min-width: 300px">
    <form @submit.prevent="submitForm" autocomplete="off">
      <h1 class="h3 fw-normal mb-3">Регистрация в конкурсе</h1>

      <template v-if="successMessage">
        <div class="alert alert-success" role="alert" v-text="successMessage"></div>
      </template>
      <template v-else>

        <div v-if="errors" class="alert alert-danger text-left" role="alert" style="text-align: left">
          <ul class="list-unstyled m-0">
            <li v-for="error in errors">
              {{ error.join("\n") }}
            </li>
          </ul>
        </div>

        <div class="form-floating mt-3">
          <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Ваше Фамилия, Имя, Отчество" :disabled="loading">
          <label for="name">Ваше Фамилия, Имя, Отчество</label>
        </div>

        <div class="row mt-3 g-2">
          <div class="col-4">
            <select v-model="form.day" class="form-select form-select-lg" :disabled="loading">
              <option :value="null" disabled>День</option>
              <option v-for="n in 31" :value="n" v-text="n"></option>
            </select>
          </div>
          <div class="col-4">
            <select v-model="form.month" class="form-select form-select-lg" :disabled="loading">
              <option :value="null" disabled>Месяц</option>
              <option v-for="n in Object.keys(months)" :value="n" v-text="months[n]"></option>
            </select>
          </div>
          <div class="col-4">
            <select v-model="form.year" class="form-select form-select-lg" :disabled="loading">
              <option :value="null" disabled>Год</option>
              <option v-for="n in years" :value="n" v-text="n"></option>
            </select>
          </div>
        </div>
        <button :disabled="btnDisabled" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Отправить</button>
      </template>
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
      day: null,
      month: null,
      year: null,
    },
    errors: null,
    loading: false,
    submitted: false,
    successMessage: null,
  }),

  computed: {
    btnDisabled() {
      return !(this.form.name && this.form.day && this.form.month && this.form.year && !this.loading)
    },
    months () {
      return {
        1: 'Январь',
        2: 'Февраль',
        3: 'Март',
        4: 'Апрель',
        5: 'Май',
        6: 'Июнь',
        7: 'Июль',
        8: 'Август',
        9: 'Сентябрь',
        10: 'Октябрь',
        11: 'Ноябрь',
        12: 'Декабрь',
      }
    },
    years () {
      let years = []
      for (let i = 2021 - 18; i > 1950; i--) {
        years.push(i)
      }
      return years
    }
  },

  methods: {
    submitForm() {
      this.loading = true
      this.successMessage = null
      this.errors = null
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