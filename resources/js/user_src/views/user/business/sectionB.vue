<!-- <template>
  <div class="banner">
    <img :src="bannerImage" alt="Banner image" />
  </div>
</template> -->

<!-- <script>
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

Default image styles .banner img {
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
</style> -->

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

          Annapurna Foods - Monster chicken serve to people chicken and egg with consciously buyer from broiler
          integrators and layer farms all over India. We constantly monitoring our farmers Healthy and Hygienic growth
          of birds feed with good quality raw materials.<br> 
          we tied up with Broiler farms and Broiler integrated for consistent supply of quality birds to our meat shops.<br>
          Further also tied up with Meat shops are hygienic, and the chickens processed there are cleaned with modern
          machines and we make hygienic and fresh chicken and eggs available to consumers.

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
        <img src="/TakeAway-pana1.png" alt="Fast Delivery Service" class="img-fluid why-company-img" loading="lazy"
          width="400" height="300">
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "WhyCompanySplit",
};
</script> -->

<!-- <style scoped>
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
<template>
  <section class="why-company container-fluid py-5">
    <div class="row d-flex justify-content-start align-items-center">
      <!-- Text Column -->
      <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
        
        <h3 class="fw-bold text-white title-text underline-parent">
          Why <span class="company-name">Company Name</span>
          <span class="underline"></span>
        </h3>

        <p class="description text-white mb-0" style="text-align: justify;">
          Annapurna Foods - Monster chicken serve to people chicken and egg with consciously buyer from broiler
          integrators and layer farms all over India. We constantly monitoring our farmers Healthy and Hygienic growth
          of birds feed with good quality raw materials.<br>
          we tied up with Broiler farms and Broiler integrated for consistent supply of quality birds to our meat
          shops.<br>
          Further also tied up with Meat shops are hygienic, and the chickens processed there are cleaned with modern
          machines and we make hygienic and fresh chicken and eggs available to consumers.
        </p>
      </div>

      <!-- Features Column -->
      <div class="col-lg-4 col-md-12 mb-4 mb-lg-0 px-lg-5 d-flex justify-content-center" style="padding: 0% !important;">
        <ul class="features list-unstyled mb-0 mx-auto text-center text-md-start">
          <li class="d-flex align-items-center mb-3">
            <span class="check-icon">&#10003;</span>
            <span class="ms-3 mx-2">Superfast Delivery</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <span class="check-icon">&#10003;</span>
            <span class="ms-3 mx-2">Best Prices & Offers</span>
          </li>
          <li class="d-flex align-items-center mb-3">
            <span class="check-icon">&#10003;</span>
            <span class="ms-3 mx-2">Easy Returns & Refund</span>
          </li>
        </ul>
      </div>

      <!-- Image Column -->
      <div class="col-lg-4 col-md-12 d-flex justify-content-lg-end justify-content-center">
        <img src="/TakeAway-pana1.png" alt="Fast Delivery Service" class="img-fluid why-company-img" loading="lazy"
          width="400" height="300">
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
  margin-right: 100px !important;
}

/* Tablet and below */
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

/* Small tablets */
@media (max-width: 767.98px) {
  .features li {
    font-size: 1.375rem;
    justify-content: center;
  }

  .check-icon {
    font-size: 0.875rem;
    min-width: 1.75rem;
    min-height: 1.75rem;
  }
}

/* Mobile */
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

/* Tablet landscape optimizations */
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
.underline-parent {
  display: inline-block; /* keeps underline sized to text */
  position: relative;
  padding-bottom: 0.5rem; /* space for underline */
}

.underline {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 50px; /* stays same width or can use % if you want it to match text width */
  height: 4px;
  background: #ffe600;
  border-radius: 4px;
}

/* Optional: scale underline with text size */
@media (max-width: 575.98px) {
  .underline {
    width: 35px;
  }
}
.features li {
  display: flex;
  align-items: center;
  justify-content: center; /* keep center alignment */
  gap: 0.75rem; /* space between icon and text */
}

.check-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #ffe600;
  color: #ff007a;
  border-radius: 50%;
  font-size: 0.875rem; /* adjust size for small screens */
  width: 1.75rem;
  height: 1.75rem;
  flex-shrink: 0;
}
.features li {
  display: flex;
  align-items: center; /* ensures perfect vertical alignment */
  justify-content: flex-start; /* left-align on all screens */
  gap: 0.75rem; /* consistent spacing between icon and text */
}

.check-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #ffe600;
  color: #ff007a;
  border-radius: 50%;
  font-size: 1rem; /* size of the check mark */
  width: 2rem; /* circle size */
  height: 2rem;
  flex-shrink: 0;
}

.features li span:last-child {
  font-size: clamp(1rem, 2vw, 1.375rem); /* responsive text size */
  font-weight: 700;
  line-height: 1.2;
}

</style>
