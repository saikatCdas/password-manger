<template>
    <div>
      <input type="file" ref="fileInput" @change="importCsv"/>
      <button @click="uploadCsv">Upload</button>
    </div>
</template>



<script>
import axiosClient from '../../axios'
export default {
  methods: {
    importCsv() {
      let input = this.$refs.fileInput
      let file = input.files[0]

      let formData = new FormData()
      formData.append('csv_file', file)

      axiosClient.post('/import', formData).then(response => {
        console.log(response.data)
      })
    }
  }
}
</script>
