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
  /* margin-bottom: 20px; */
}

Default image styles
.banner img {
  width: 100%;
  height: auto;
  display: block;
  object-fit: cover;
}

@media (min-width: 1024px) {
  .banner {
    height: 365px;
  }
  .banner img {
    width: 100%;
    height: 100%;
  }
}


@media (min-width: 768px) and (max-width: 1023px) {
  .banner {
    height: 280px;
  }
  .banner img {
    width: 100%;
    height: 100%;
  }
}

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

<!-- updated code new  -->
<!-- <template>
  <section class="why-company container-fluid py-5">
    <div class="row d-flex justify-content-start">
      <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
        <h3 class="fw-bold text-white title-text">
          Why <span class="company-name">Company Name</span>
        </h3>
        <div class="underline mb-4 mx-auto mx-md-0"></div>
        <p class="description text-white mb-0">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc vulputate libero et velit interdum,
          ac aliquet odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra,
          per inceptos himenaeos.
        </p>
      </div>

      <div class="col-lg-4 col-md-12 mb-4 mb-lg-0 px-5 d-flex justify-content-center">
        <ul class="features list-unstyled mb-0 mx-auto text-center text-md-start">
          <li class="d-flex align-items-center mb-3">
            <span class="check-icon" aria-hidden="true">&#10003;</span>
            <span class="ms-3 mx-2">Superfast Delivery</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <span class="check-icon" aria-hidden="true">&#10003;</span>
            <span class="ms-3 mx-2">Best Prices & Offers</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <span class="check-icon" aria-hidden="true">&#10003;</span>
            <span class="ms-3 mx-2">Easy Returns & Refund</span>
          </li>
        </ul>
      </div>


      <div class="col-lg-4 col-md-12 d-flex justify-content-end">
        <img
          src="/TakeAway-pana1.png"
          alt="Fast Delivery Service"
          class="img-fluid why-company-img"
          loading="lazy"
          width="400"
          height="300"
        >
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "WhyCompanySplit",
};
</script>

<style scoped>
.why-company {
  background: #ff007a;
  color: white;
  padding: 3rem 3.75rem;
}

.company-name {
  color: #ffffff;
}

.underline {
  width: 50px;
  height: 4px;
  background: #ffe600;
  border-radius: 4px;
}

.title-text {
  font-size: clamp(1.625rem, 3vw, 2.3125rem);
  line-height: 1.2;
}

.description {
  font-size: clamp(1.125rem, 2vw, 1.5625rem) !important;
  line-height: 1.4 !important;
}


.features {
  margin-top: 1.25rem;
}

.features li {
  font-size: clamp(1.25rem, 2.5vw, 2.1875rem);
  font-weight: 700;
}

.check-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #ffe600;
  color: #ff007a;
  border-radius: 50%;
  font-size: 1rem;
  min-width: 2rem;
  min-height: 2rem;
  flex-shrink: 0;
}

.why-company-img {
  max-width: 100%;
  height: auto;
  object-fit: contain;
}


@media (max-width: 991.98px) {
  .why-company {
    padding: 1.875rem 1.25rem;
    text-align: center;
  }

  .underline {
    margin-left: auto;
    margin-right: auto;
  }

  .features {
    text-align: left;
    padding-left: 1rem;
  }

  .why-company-img {
    max-width: 80%;
    margin-top: 1.5rem;
  }
}

@media (max-width: 767.98px) {
  .features li {
    font-size: 1.375rem;
  }

  .check-icon {
    font-size: 0.875rem;
    min-width: 1.75rem;
    min-height: 1.75rem;
  }
}

@media (max-width: 575.98px) {
  .why-company {
    padding: 1.25rem 0.9375rem;
  }

  .title-text {
    font-size: 1.625rem;
  }

  .description {
    font-size: 1.125rem !important;
  }

  .features li {
    font-size: 1.25rem;
  }
}

@media (max-width: 991.98px) and (min-width: 768px) {
  .features {
    max-width: 80%;
    margin-left: auto;
    margin-right: auto;
  }

  .why-company-img {
    max-width: 60%;
  }
}

@media (max-width: 767.98px) {
  .features li {
    font-size: 1.25rem;
    justify-content: center;
  }
}
</style> -->
