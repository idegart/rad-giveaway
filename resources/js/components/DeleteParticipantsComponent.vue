<template>
  <main class="text-center" style="color: white;">

    <template v-if="success">
      <div class="alert alert-success" role="alert">
        Участники удалены
      </div>
    </template>
    <template v-else>
      <form autocomplete="off" @submit.prevent="deleteParticipants">
        <h1 class="h3 fw-normal mb-3">Удалить участников</h1>
        <h3 class="h4 mb-3">Всего: {{ total }}</h3>
        <button :disabled="loading" class="w-100 btn btn-lg btn-danger" type="submit">Удалить</button>
        <button @click="download" :disabled="loading" class="w-100 btn btn-lg btn-info mt-3" type="button">Скачать</button>
      </form>
    </template>
  </main>
</template>

<script>
import axios from "@plugin/axios";

export default {
  name: "DeleteParticipantsComponent",

  data: () => ({
    success: false,
    loading: false,
    total: null,
  }),

  methods: {
    load() {
      axios.get('/api/participants')
          .then(({data}) => {
            this.total = data.participants
          })
    },
    download() {
      axios.get('/api/download-participants')
          .then(({data}) => {
            if (data.success) {
              window.location.href = data.success
            }
          })
    },
    deleteParticipants() {
      if (!confirm('Вы уверены')) {
        return
      }

      this.success = false

      axios.post('/api/delete-participants')
          .then(() => {
            this.success = true
          })
          .catch(() => {
            alert("Произошла ошибка")
          })
          .finally(() => {
            this.loading = false
          })
    }
  },

  mounted() {
    this.load()
  }
}
</script>

<style scoped>

</style>