<template>
  <div class="banner">
    <img :src="bannerImage" alt="Banner image" />
  </div>
</template>

<script>
export default {
  name: "Banner",
  data() {
    return {
      bannerImage: "/Frame-25.png",
    };
  },
};
</script>

<style scoped>
.banner {
  width: 100%;
  overflow: hidden;
  margin-bottom: 20px;
}

/* Default image styles */
.banner img {
  width: 100%;
  height: auto;
  display: block;
  object-fit: cover;
}

/* Desktop (≥1024px): Fixed height */
@media (min-width: 1024px) {
  .banner {
    height: 365px;
  }
  .banner img {
    height: 100%;
  }
}

/* Tablet (≥768px and <1024px): Adjust height */
@media (min-width: 768px) and (max-width: 1023px) {
  .banner {
    height: 280px;
  }
  .banner img {
    height: 100%;
  }
}

/* Mobile (≤767px): Full width, auto height */
@media (max-width: 767px) {
  .banner {
    height: auto;
  }
  .banner img {
    width: 100%;
    height: auto;
  }
}
</style>
