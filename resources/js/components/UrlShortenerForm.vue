<template>
    <div>
      <form @submit.prevent="submitUrl">
        <input type="text" v-model="originalUrl" placeholder="Enter URL" required />
        <button type="submit">Shorten URL</button>
      </form>
      <p v-if="shortUrl">Short URL: {{ shortUrl }}</p>
      <p v-if="errorMessage" style="color: red;">Error: {{ errorMessage }}</p>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  
  export default {
    setup() {
      const originalUrl = ref('');
      const shortUrl = ref('');
      const errorMessage = ref('');
      const submitUrl = async () => {
        try {
          const response = await axios.post('/shorten-url', { original_url: originalUrl.value });
          shortUrl.value = response.data.shortened_url;
        } catch (error) {
          console.error(error);
          shortUrl.value = null;
          errorMessage.value = 'An error occurred: ' + error;
          if (error.response) {
          switch (error.response.status) {
            case 400:
              errorMessage.value = 'Bad Request: The server could not understand the request due to invalid syntax.';
              break;
            case 404:
              errorMessage.value = 'Not Found: The server could not find the requested URL.';
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
  
      return { originalUrl, shortUrl, errorMessage, submitUrl };
    },
  };
  
  </script>

