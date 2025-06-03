<template>
    <div
        class="banner"
        style="padding-right: 0% !important; padding-left: 0% !important"
    >
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
  height: 365px;
  overflow: hidden;
  margin-bottom: 20px;
}

.banner img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
/* Default: Desktop view (≥1024px) */
.banner img {
    height: 100%;
}

/* Tablet view (≥768px and <1024px) */
@media (min-width: 768px) and (max-width: 1023px) {
    .banner img {
        height: 100%;
    }
}

/* Mobile view (≤480px) */
@media (max-width: 480px) {
    .banner img {
        height: 100%;
    }
}

</style>
