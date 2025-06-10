<template>
  <div class="box">
  
    <div v-if="showToast" class="toast-success">
      <i class="fa fa-check-circle"></i>
      Added Sucessfully
      <span class="close-btn" @click="showToast = false">&times;</span>
    </div>

     
    <form @submit.prevent="submitForm">
      <div class="form-grid">
        <!-- Left Column -->
        <div class="column">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" v-model="form.name" required />
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" v-model="form.email" required />
          </div>
          <div class="form-group">
            <label>Wallet Balance</label>
            <input type="number" class="form-control" v-model="form.walletBalance" />
          </div>
        </div>

        <!-- Right Column -->
        <div class="column">
          <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" v-model="form.phone" />
          </div>
          <div class="form-group">
            <label>Created At</label>
            <input type="date" class="form-control" v-model="form.createdAt" />
          </div>
        </div>
      </div>

      <button class="save-btn" type="submit">Save</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "AddConsumer",
  data() {
    return {
      form: {
        name: '',
        phone: '',
        email: '',
        createdAt: '',
        walletBalance: ''
      },
      showToast: false  
    };
  },
  methods: {
    submitForm() {
      console.log("Form Submitted:", this.form);

       
      this.showToast = true;

      setTimeout(() => {
        this.showToast = false;
      }, 3000);
    }
  }
};
</script>

<style scoped>
 .toast-success {
  width: 315px;
  height: 55px;
  position: fixed;
  top: 20px;
  right:15px;
  transform: translateX(calc(100% + 30px));  
  background-color: #ffffff;
  color: #34a853;
  padding: 12px 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 1000;
  animation: slideIn 0.5s forwards;
}

@keyframes slideIn {
  to {
    transform: translateX(0);
  }
}

.toast-success i {
  font-size: 18px;
}

.close-btn {
  margin-left: auto;
  cursor: pointer;
  font-size: 18px;
  color: #888;
}

/* Existing Styles */
.box {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: auto;
  margin: 0 auto;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.form-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.column {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 6px;
  font-size: 13px;
  color: #333;
  font-weight: 500;
}

.form-control {
  border: 1px solid #E01764;
  border-radius: 4px;
  padding: 6px 10px;
  font-size: 14px;
}

.save-btn {
  margin-top: 20px;
  background-color: #E01764;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}
</style>
