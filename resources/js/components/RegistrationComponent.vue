<template>
  <main class="text-center px-3" style="min-width: 300px">
    <form @submit.prevent="submitForm" autocomplete="off">
      <h1 class="h3 fw-normal mb-3">
        Регистрация для участия в розыгрыше Hisense:
      </h1>

      <template v-if="successMessage">
        <div class="alert alert-success" role="alert" v-text="successMessage"></div>
      </template>
      <template v-else>

        <div class="form-floating mt-3">
          <input v-model="form.surname" type="text" class="form-control" id="surname" placeholder="Фамилия" :disabled="loading">
          <label for="surname">Фамилия</label>
        </div>

        <div class="form-floating mt-3">
          <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Имя" :disabled="loading">
          <label for="name">Имя</label>
        </div>

        <div class="form-floating mt-3">
          <input v-model="form.patronymic" type="text" class="form-control" id="patronymic" placeholder="Отчество" :disabled="loading">
          <label for="patronymic">Отчество</label>
        </div>

        <div class="form-floating mt-3">
          <input v-model="form.phone" type="text" class="form-control" id="phone" placeholder="Номер телефона" :disabled="loading">
          <label for="phone">Номер телефона</label>
        </div>

        <div class="form-floating mt-3">
          <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Email" :disabled="loading">
          <label for="email">Email</label>
        </div>

        <div class="row mt-3 g-2 mb-3">
          <div class="text-left" style="text-align: left">
            <label for="patronymic"><b>Дата рождения</b></label>
          </div>
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

        <div style="text-align: left">
          <div class="mb-3 form-check">
            <input v-model="form.a1" type="checkbox" class="form-check-input" id="a1">
            <label class="form-check-label" for="a1">Даю согласие на обработку персональных данных</label>
          </div>

          <div class="mb-3 form-check">
            <input v-model="form.a2" type="checkbox" class="form-check-input" id="a2">
            <label class="form-check-label" for="a2">Согласен с правилами проведения Конкурса</label>
          </div>
        </div>

        <button :disabled="btnDisabled" class="w-100 btn btn-lg btn-primary mt-3" type="submit">Отправить</button>
      </template>
    </form>
  </main>
</template>

<script>
import axios from "@plugin/axios";
import swal from 'sweetalert';

export default {
  name: "RegistrationComponent",
  data: () => ({
    form: {
      email: '',
      phone: '',
      surname: '',
      name: '',
      patronymic: '',
      day: null,
      month: null,
      year: null,
      a1: false,
      a2: false,
    },
    loading: false,
    submitted: false,
    successMessage: null,
  }),

  computed: {
    btnDisabled() {
      return !(this.form.a1 && this.form.a2 && this.form.email && this.form.phone && this.form.surname && this.form.name && this.form.patronymic && this.form.day && this.form.month && this.form.year && !this.loading)
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
            this.successMessage = "Отлично! Вы зарегистрированы, ожидайте розыгрыша призов в 18.00 у главной сцены."
          })
          .catch(({response}) => {
            let errors = response.data.errors

            if (errors) {
              swal({
                title: Object.values(errors).join('\n'),
                icon: 'error'
              })
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
.btn-primary.disabled, .btn-primary:disabled {
  color: #fff;
  background-color: #09A9A5;
  border-color: #09A9A5;
}

.btn.disabled, .btn:disabled, fieldset:disabled .btn {
  pointer-events: none;
  opacity: 1;
}

.form-select-lg {
  padding-top: .5rem;
  padding-bottom: .5rem;
  padding-left: 1rem;
  font-size: 1rem;
}
.btn-primary {
  color: #fff;
  background-color: #09A9A5;
  border-color: #09A9A5;
}
</style>