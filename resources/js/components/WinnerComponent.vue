<template>
  <main @keyup.space="loadWinner" class="text-center p-1">
    <form @submit.prevent autocomplete="off">
      <h1 class="h3 fw-normal">Розыгрыш</h1>

      <div v-if="loading" class="alert alert-info" role="alert">
        Поиск победителя
      </div>

      <div v-if="!codeParts" class="input-group input-group-lg justify-content-center mt-3">
        <input v-for="n in 5" aria-label="item"
               class="form-control text-center"
               style="max-width: 50px" readonly>
      </div>

      <div v-else class="input-group input-group-lg justify-content-center mt-3">
        <input v-for="n in codeParts"
               :value="n" aria-label="item"
               class="form-control text-center"
               style="max-width: 50px" readonly>
      </div>

      <div v-if="winner" class="mt-3">
        <h1>Победитель!</h1>
        <h2 v-text="winner.name"></h2>
      </div>
    </form>
  </main>
</template>

<script>
import axios from "@plugin/axios";

export default {
  name: "WinnerComponent",

  data: () => ({
    winner: null,
    codeParts: null,
    loading: false,
  }),

  methods: {
    loadWinner() {
      this.loading = true
      this.winner = null
      axios.post('api/winner')
          .then(({data}) => {
            if (data.success === false) {
              alert("Победитель не найден")
            }
            this.winner = data.data
            this.codeParts = this.winner.code.code.split('')
          })
          .catch(() => {
            alert("При поиске победителя возникла ошибка")
          })
          .finally(() => {
            this.loading = false
          })
    },

    doCommand(e) {
      if (e.code === 'Space') {
        if (!this.loading) {
          this.loadWinner()
        }
      }
    },
  },

  created() {
    window.addEventListener('keypress', this.doCommand);
  },
  destroyed() {
    window.removeEventListener('keypress', this.doCommand);
  },
}
</script>

<style scoped>

</style>