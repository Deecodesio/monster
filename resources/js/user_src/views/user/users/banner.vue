<template>
    <div>
     <div class="pb-2" style="margin-top:80px;">
          <b-img src="/more1.jpg" fluid alt="Scan" class="imght2"/>
      </div>
    </div>
</template>
      
      <script>
      import {
        BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
      } from 'bootstrap-vue'
      import Ripple from 'vue-ripple-directive'
 
      
      export default {
        components: {
          BButton,
          BForm,
          BImg,
          BFormFile,
          BFormGroup,
          BFormInput,
          BRow,
          BCol,
          BAlert,
          BCard,
          BCardText,
          BMedia,
          BMediaAside,
          BMediaBody,
          BLink,
        },
        directives: {
          Ripple,
        },
        
       
        
      }
      </script>
      <style>
       .pt-10{
        padding-top: 10rem !important;
      }
      </style>
      