<template>
  <main @keyup.space="loadWinner" class="text-center p-1">
    <form @submit.prevent autocomplete="off">
      <h1 class="h3 fw-normal" style="font-size: 47px;color: #ffffff;font-weight: 400 !important;margin-bottom: 23px;">Розыгрыш</h1>

      <div class="d-flex justify-content-around winner-code mx-auto" style="width: fit-content">
        <div v-for="n in 10" class="winner-code__char">
          <transition name="fade" mode="out-in">
            <span v-if="dateChar(n)" v-text="dateChar(n)"></span>
          </transition>
        </div>
      </div>

      <img class="wp-image-17 size-full aligncenter" src="/assets/h.png" alt="" width="169" height="264" style="margin-top: -33px;margin-left: -29px;">

    </form>

    <div v-if="showPopup" id="popup-img-winner" class="popup-img-winner" data-show="true"></div>

    <div style="font-size: 34px;color: #ffffff;font-weight: 700;position: fixed;right: 52px;top: 82%;padding: 10px;line-height: 42px;">
      #hisenserussia <br>
      #Твойдомтвойстадион
    </div>
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
    showPopup: true,
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
              }, index * 1500)
            })

            setTimeout(() => {
              swal({
                title: this.winner.full_name,
                text: this.winner.birthday,
                icon: '/assets/fav.png',
                buttons: false,
              })

              this.showing = false
            }, 1500 * this.winner.birthday.split('').length)
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
        if (!this.loading && !this.showing && !this.showPopup) {
          this.loadWinner()
        }
      }

      if (e.key === "q" || e.key === "Q" || e.key === "й" || e.key === "Й") {
        this.showPopup = !this.showPopup
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

<style>
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

.swal-modal {
  background-color: #fffffffc;
  border-radius: 32px;
  box-shadow: 0 0 10px rgba(0,0,0,0.5);
}
.swal-button-container {
  display: none;
}

.winner-code {
  border-radius: 7px;
  background-color: #F8F8F8;
  border: 3px solid #000000;
}

.winner-code__char {
  border-right: 2px solid #000000;
  padding: .5rem .7rem;
  text-align: center;
  width: 61px;
  height: 88px;
  font-size: 48px;
  font-weight: 400;
}
</style>