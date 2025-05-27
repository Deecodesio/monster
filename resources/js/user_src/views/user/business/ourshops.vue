<template>
  <div>
    <div class="shope-container mb-5 text-center">
      <h3 class="shope-title">Our Shops</h3>

      <div class="container">
        <div v-for="(chunk, rowIndex) in chunkedShops" :key="rowIndex" class="row justify-content-center mb-2">
          <div
            v-for="(shop, colIndex) in chunk"
            :key="colIndex"
            class="col-md-2 d-flex justify-content-center mb-2"
          >
            <div class="shop-box">{{ shop }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
export default {
  data() {
    return {
      shops: ['Namakkal', 'Salem', 'Erode', 'Chennai', 'Trichy', 'Coimbatore', 'Madurai','dindigul','kallakurichi','paandicheery','perambular'], 
    };
  },
  computed: {
    chunkedShops() {
      const chunks = [];
      for (let i = 0; i < this.shops.length; i += 5) {
        chunks.push(this.shops.slice(i, i + 5));
      }
      return chunks;
    }
  }
};
</script>




<style scoped>
.shope-container {
  background-color: #FF81B633;
  padding: 40px 20px;
}

.shope-title {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 30px;
  color:#000000;
}

.shop-box {
  background-color: white;
font-size: 22px;
line-height: 100%;
  border-radius: 30px;
  height: 51px;
  width: 202px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 500;
  color: #263238;

}



</style>