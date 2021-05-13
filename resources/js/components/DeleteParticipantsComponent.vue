<template>
  <main class="text-center">

    <template v-if="success">
      <div class="alert alert-success" role="alert">
        Участники удалены
      </div>
    </template>
    <template v-else>
      <form autocomplete="off" @submit.prevent="deleteParticipants">
        <h1 class="h3 fw-normal mb-3">Удалить участников</h1>
        <button :disabled="loading" class="w-100 btn btn-lg btn-primary" type="submit">Удалить</button>
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
  }),

  methods: {
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
  }
}
</script>

<style scoped>

</style>