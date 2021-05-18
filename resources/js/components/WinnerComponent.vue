<template>
  <main @keyup.space="loadWinner" class="text-center p-1">
    <form @submit.prevent autocomplete="off">
      <h1 class="h3 fw-normal">Розыгрыш</h1>

      <div class="d-flex justify-content-around winner-code mx-auto" style="width: fit-content">
        <div v-for="n in 10" class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="dateChar(n)" v-text="dateChar(n)"></span>
          </transition>
        </div>
      </div>

      <div v-if="loading" class="alert alert-info mt-3" role="alert">
        Поиск победителя
      </div>

    </form>
  </main>
</template>

<script>
import axios from "@plugin/axios";
import swal from 'sweetalert';

export default {
  name: "WinnerComponent",

  data: () => ({
    winner: null,
    loading: false,
    birthday: null,
    showing: false,
  }),

  methods: {
    dateChar (n) {
      if (!this.birthday) {
        return null
      }

      return this.birthday[n - 1] || null
    },
    loadWinner() {
      this.loading = true
      this.winner = null

      axios.post('api/winner')
          .then(({data}) => {
            if (data.success === false) {
              alert("Победитель не найден")
              return
            }

            this.showing = true

            this.winner = data.data

            this.birthday = []

            this.winner.birthday.split('').forEach((char, index) => {
              setTimeout(() => {
                this.birthday.push(char)
              }, index * 500)
            })

            setTimeout(() => {
              swal({
                title: this.winner.full_name,
                text: this.winner.birthday,
                icon: 'success'
              })
            }, 5500)

            setTimeout(() => {
              this.showing = false
            }, 6000)
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
        if (!this.loading && !this.showing) {
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
  width: 30px;
  height: 40px;
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