<template>
  <main class="text-center">
    <form autocomplete="off" @submit.prevent="uploadCodes">
      <h1 class="h3 fw-normal mb-3">Загрузить коды</h1>

      <div v-if="successMessage" class="alert alert-success" role="alert" v-text="successMessage"></div>

      <div v-if="errorMessage" class="alert alert-danger" role="alert" v-text="errorMessage"></div>

      <input @change="setFile" class="form-control form-control-lg my-4" id="formFileLg" type="file">

      <button :disabled="!files && !loading" class="w-100 btn btn-lg btn-primary" type="submit">Отправить</button>
    </form>
  </main>
</template>

<script>
import axios from "@plugin/axios";

export default {
  name: "CodeUploaderComponent",

  data: () => ({
    files: null,
    successMessage: null,
    errorMessage: null,
    loading: false,
  }),

  methods: {
    setFile(e) {
      this.files = e.target.files
    },
    uploadCodes(e) {
      this.errorMessage = null
      this.successMessage = null

      this.loading = true

      let formData = new FormData();
      formData.append("file", this.files[0])

      axios.post('api/codes', formData)
          .then(() => {
            this.successMessage = 'Успешно загружено'
          })
          .catch(() => {
            this.errorMessage = 'Не удалось загрузить файл'
          })
          .finally(() => {
            e.target.reset()
            this.files = null
            this.loading = false
          })
    },
  },
}
</script>

<style scoped>

</style>