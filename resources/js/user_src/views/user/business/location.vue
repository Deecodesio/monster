<template>
    <div>
      <!-- modal trigger button -->
      <div class="demo-inline-spacing">
       
        
        <b-button
          v-ripple.400="'rgba(113, 102, 240, 0.15)'"
          v-b-modal.modal-no-backdrop
          variant="outline-primary"
        >
          Disabled Backdrop
        </b-button>
        
      </div>
  
     
  
  
      <!-- modal backdrop -->
      <b-modal
        id="modal-no-backdrop"
        hide-backdrop
        ok-only
        no-close-on-backdrop
        content-class="shadow"
       
        ok-title="Accept"
      >
        <b-button
          v-ripple.400="'rgba(113, 102, 240, 0.15)'"
         
          variant="outline-primary"
          @click="getlocation()"
        >
        {{$t("Get Current Location")}}
        </b-button>
      </b-modal>
    </div>
  </template>
  
  <script>
  import { BModal, BButton, VBModal, BAlert} from 'bootstrap-vue'
  import Ripple from 'vue-ripple-directive'
  
  export default {
    components: {
      BButton,
      BModal,
      BAlert,
    },
    directives: {
      'b-modal': VBModal,
      Ripple,
    },
    methods: {
      
      getlocation(){
        alert("hi");
      }
    },
  }
  </script>
  