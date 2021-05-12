<template>
  <main @keyup.space="loadWinner" class="text-center p-1">
    <form @submit.prevent autocomplete="off">
      <h1 class="h3 fw-normal">Розыгрыш</h1>

      <div class="d-flex justify-content-around winner-code mx-auto" style="width: fit-content">
        <div v-for="n in 5" class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="showCode(n - 1)" v-text="showCode(n - 1)"></span>
          </transition>
        </div>
      </div>

      <div v-if="loading" class="alert alert-info mt-3" role="alert">
        Поиск победителя
      </div>

      <transition name="fade" mode="out-in">
        <div v-if="winnerName" class="mt-3">
          <h1>Победитель!</h1>
          <h2 v-text="winnerName"></h2>
        </div>
      </transition>
    </form>
  </main>
</template>

<script>
import axios from "@plugin/axios";

const duration = 1000;

export default {
  name: "WinnerComponent",

  data: () => ({
    winner: null,
    codeParts: null,
    loading: false,
    winnerName: null,
  }),

  methods: {
    loadWinner() {
      this.loading = true
      this.winner = null
      this.winnerName = null
      this.codeParts = []

      axios.post('api/winner')
          .then(({data}) => {
            if (data.success === false) {
              alert("Победитель не найден")
            }
            this.winner = data.data
            let parts = this.winner.code.code.split('')
            parts.forEach((part, index) => {
              setTimeout(() => {
                this.codeParts.push(part)
              }, (index + 1) * duration)
            })

            setTimeout(() => {
              this.winnerName = this.winner.name
            }, (parts.length + 1) * duration)
          })
          .catch(() => {
            alert("При поиске победителя возникла ошибка")
          })
          .finally(() => {
            this.loading = false
          })
    },

    showCode (n) {
      if (!this.winner) {
        return null
      }

      return this.codeParts && this.codeParts[n] || null;
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
.winner-code {
  border-radius: .3rem;
  background-color: #e9ecef;
  border: 1px solid #ced4da;
}
.winner-code > div {
  border-right: 1px solid #ced4da;
  padding: .5rem 1rem;
  font-size: 1.25rem;
  height: 2.75rem;
  width: 3rem;
}
.winner-code > div:last-child {
  border-right: none;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity;
  transition-duration: .5s
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>