<template>
  <main @keyup.space="loadWinner" class="text-center p-1">
    <form @submit.prevent autocomplete="off">
      <h1 class="h3 fw-normal">Розыгрыш</h1>

      <div class="d-flex justify-content-around winner-code mx-auto" style="width: fit-content">
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="day" v-text="day[0]"></span>
          </transition>
        </div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="day" v-text="day[1]"></span>
          </transition>
        </div>
        <div class="winner-code__char">-</div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="winner" v-text="month[0]"></span>
          </transition>
        </div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="winner" v-text="month[1]"></span>
          </transition>
        </div>
        <div class="winner-code__char">-</div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="winner" v-text="year[0]"></span>
          </transition>
        </div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="winner" v-text="year[1]"></span>
          </transition>
        </div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="winner" v-text="year[2]"></span>
          </transition>
        </div>
        <div class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="winner" v-text="year[3]"></span>
          </transition>
        </div>
      </div>

      <div v-if="loading" class="alert alert-info mt-3" role="alert">
        Поиск победителя
      </div>

      <transition name="fade" mode="out-in">
        <div v-if="winner" class="mt-3">
          <h1>Победитель!</h1>
          <h2 v-text="winner.name"></h2>
        </div>
      </transition>
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

  computed: {
    day () {
      if (!this.winner) {
        return null
      }
      return this.winner.day.toString().padStart(2, '0')
    },
    month () {
      if (!this.winner) {
        return null
      }
      return this.winner.month.toString().padStart(2, '0')
    },
    year () {
      if (!this.winner) {
        return null
      }
      return this.winner.year.toString()
    },
  },

  methods: {
    loadWinner() {
      this.loading = true
      this.winner = null
      this.codeParts = []

      axios.post('api/winner')
          .then(({data}) => {
            if (data.success === false) {
              alert("Победитель не найден")
            }
            this.winner = data.data
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

    if (!String.prototype.padStart) {
      String.prototype.padStart = function padStart(targetLength,padString) {
        targetLength = targetLength>>0; //floor if number or convert non-number to 0;
        padString = String(padString || ' ');
        if (this.length > targetLength) {
          return String(this);
        }
        else {
          targetLength = targetLength-this.length;
          if (targetLength > padString.length) {
            padString += padString.repeat(targetLength/padString.length); //append to original to ensure we are longer than needed
          }
          return padString.slice(0,targetLength) + String(this);
        }
      };
    }
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
.winner-code__char {
  border-right: 1px solid #ced4da;
  padding: .5rem .7rem;
  text-align: center;
}
.winner-code__char:last-child {
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