<template>
  <div class="container">
    <h1 class="title">URL Shortener</h1>
    <div class="form">
      <label for="originalUrl" class="label">Enter URL:</label>
      <input type="text" id="originalUrl" v-model="originalUrl" class="input" placeholder="Enter URL" required>
      <button type="submit" @click="submitUrl" class="button">Shorten URL</button>
    </div>
    <div v-if="shortUrl" class="result">
      <p class="result-label">Short URL:</p>
      <a :href="shortUrl" class="result-link" target="_blank">{{ shortUrl }}</a>
    </div>
    <div v-if="errorMessage" class="error">
      <p>{{ errorMessage }}</p>
    </div>
    <div>
    <div>
    <h2>All URLs:</h2>

      
      <table>
        <thead>
          <tr>
            <th>Original URL</th>
            <th>Hash</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(url, index) in urls" :key="index">
            <td>{{ url.original_url }}</td>
            <td><a :href=url.hash target="_blank">{{ url.hash }}</a></td>
          
          </tr>
        </tbody>
      </table>
      <p v-if="urls.length === 0">No shortened URLs yet.</p>
    </div>
  </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  setup() {
    const originalUrl = ref('');
    const shortUrl = ref('');
    const errorMessage = ref('');
    const urls = ref('');
    const created = async() => {
      try {
        const response = await axios.get('/url');
        urls.value = response.data;
      } catch (error) {
        console.error(error);
      }
    };
    onMounted(created);
    const submitUrl = async () => {
      try {
        const response = await axios.post('/url', { original_url: originalUrl.value });
        shortUrl.value = response.data.shortened_url;
        if(response.status == 201){
          // Add the new URL to the urls array
            urls.value.unshift({
              original_url: originalUrl.value,
              hash: response.data.shortened_url
            });

        }
        errorMessage.value = null;
      } catch (error) {
        
        shortUrl.value = null;
        if (error.response) {
          switch (error.response.status) {
            case 422:
              errorMessage.value = 'The URL Provided is not valid';
              break;
            case 404:
              errorMessage.value = 'Not Found: The server could not find the requested URL.';
              case 403:
              errorMessage.value = 'The URL Provided is not safe';
              break;
            case 500:
              errorMessage.value = 'Internal Server Error: The server encountered an internal error and was unable to complete your request.';
              break;
            default:
              errorMessage.value = 'An error occurred: ' + error.message;
          }
        } else {
          errorMessage.value = 'An error occurred: ' + error.message;
        }
      }
    };

    return { originalUrl, shortUrl, errorMessage, submitUrl, urls};
  },
};
</script>

<style>
body {
  background-color: #ADD8E6; /* Light blue */
}

.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.title {
  font-size: 36px;
  margin-bottom: 20px;
  color: #333;
}

.form {
  background-color: #f5f5f5;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.label {
  font-size: 18px;
  margin-bottom: 10px;
  color: #333;
}

.input {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  margin-bottom: 20px;
}

.button {
  background-color: #333;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
}

.button:hover {
  background-color: #555;
}

.result {
  margin-top: 20px;
}

.result-label {
  font-size: 18px;
  margin-bottom: 10px;
  color: #333;
}

.result-link {
  font-size: 16px;
  color: #333;
  text-decoration: none;
  transition: color 0.2s ease-in-out;
}

.result-link:hover {
  color: #555;
}

.error {
  margin-top: 20px;
  color: red;
}

h2 {
  font-size: 24px;
  color: #333;
  margin-bottom: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

table, th, td {
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 15px;
}

th {
  background-color: #f5f5f5;
  color: #333;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

a {
  color: #333;
  text-decoration: none;
}

a:hover {
  color: #555;
}


</style>
