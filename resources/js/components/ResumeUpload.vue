<template>
  <div>
    <h1>Upload Job Description and Resumes</h1>
    <form @submit.prevent="uploadFiles">
      <div>
        <label for="job_description">Job Description:</label>
        <textarea v-model="jobDescription" id="job_description" required></textarea>
      </div>
      <div>
        <label for="resumes">Resumes:</label>
        <input type="file" ref="fileInput" multiple @change="handleFileUpload" />
      </div>

      <button type="submit" :disabled="loading">Upload Resumes</button>
    </form>

    <!-- Progress Bar -->
    <div v-if="loading" class="progress-bar">
      <p>Processing...</p>
      <div class="progress" :style="{ width: progress + '%' }"></div>
    </div>

    <!-- Error Message -->
    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>

    <!-- Results -->
    <div v-if="result">
      <h2>Results</h2>
      <div v-for="(item, index) in result" :key="index">
        <h3>Resume {{ index + 1 }}: {{ item.name }}</h3>
        <p><strong>Match Score:</strong> {{ item.matchScore }}%</p>
        <p><strong>Missing Skills:</strong> {{ item.missingSkills }}</p>
        <p><strong>Justification:</strong> {{ item.justification }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      jobDescription: "",
      files: [],
      result: null,
      loading: false,
      progress: 0,
      errorMessage: "", // New: Store errors
    };
  },
  methods: {
    handleFileUpload(event) {
      this.files = Array.from(event.target.files);
    },
    async uploadFiles() {
      this.errorMessage = ""; // Reset errors
      const formData = new FormData();
      formData.append("job_description", this.jobDescription);
      this.files.forEach((file) => {
        formData.append("resumes[]", file);
      });

      this.loading = true;

      try {
        const response = await axios.post("/upload-resumes", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
          onUploadProgress: (progressEvent) => {
            this.progress = Math.round((progressEvent.loaded / progressEvent.total) * 100);
          },
        });

        this.result = response.data.result;
        this.jobDescription = ""; // Reset form after success
        this.files = [];
        this.$refs.fileInput.value = ""; // Clear file input
      } catch (error) {
        console.error("Error uploading files:", error);
        this.errorMessage = "Upload failed. Please try again.";
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.progress-bar {
  background: #f3f3f3;
  margin-top: 20px;
  padding: 10px;
  border-radius: 5px;
}
.progress {
  height: 20px;
  background: green;
  transition: width 0.3s ease-in-out;
}
.error {
  color: red;
  font-weight: bold;
  margin-top: 10px;
}
</style>
