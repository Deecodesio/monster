<!-- <template>
  <div>
    <banner />
    <div class="container mb-2">
      <div v-html="rows[0].aboutus">

      </div>
    </div>
  </div>
</template> -->

<template>
  <div id="app" style="margin-top:80px;">
     <!-- <div class="mt-5 mx-3 py-6 container"> -->
     <div class="mt-5 py-6 container">
                <top_banners />
            </div>
    <!-- Section 1: Gurlean Delicious Food -->
    <section class="section-1">
       <h1 style="display: flex !important; color: black !important; justify-content: center; margin-bottom: 30px;">Annapoorna Foods</h1>
      <div class="container">       
        <div class="content-grid">
          <div class="text-content">
            <h1>Gurlean Delicious Food</h1>
            <h2>Catering to your requirements</h2>
            <p>Our store is more than just another ordinary online retailer. We are not only top-quality products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from one store to another, and then trying to find time to collect and organize your online orders. We will not only top-quality products, but give our customers a positive online shopping experience.</p>
            <!-- <p>Products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from one store to another, ordering online. We will not only top-quality products.</p> -->
          </div>
          <div class="image-placeholder">
             <b-img src="/Res_2.jpg" fluid alt="Restaurant Interior" class="restaurant-image"/>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 2: Midnight vision -->
    <!-- <section class="section-2">
      <div class="container">
        <h1>Midnight vision</h1>
        <h2>Catering to your requirements</h2>
        <div class="image-grid">
          <div class="image-placeholder">
             <b-img src="/Res_3.jpg" fluid alt="Kitchen Area" class="kitchen-image"/>
          </div>
          <div class="image-placeholder">
             <b-img src="/Res_4.jpg" fluid alt="Restaurant Interior" class="restaurant-interior"/>
          </div>
        </div>
        <div class="text-content">
          <p>Our store is more than just another ordinary online retailer. We are not only top-quality products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from one store to another, and then trying to find time to collect and organize your online orders. We will not only top-quality products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from one store to another, and then trying to find time to collect and organize your online orders.</p>
          <p>Products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from one store to another, ordering online. We will not only top-quality products.</p>
        </div>
      </div>
    </section> -->

    <!-- Section 3: Cocktails -->
    <!-- <section class="cocktail-section">
      <div class="container">
        <h1>Area with Cocktails</h1>
        <h2>Create Your Aperitif</h2>
        <div class="cocktail-info">
          <div class="info-box">
            <p>The delightful bar area near the entrance, is the right place if you really want an aperitif, appetizing or a drink outside by the municipality designed by professional bartenders.</p>
          </div>
          <div class="info-box">
            <p>The cocktail bar dedicated to the specialties that will serve to the table, has been designed to show you the preparation of the dishes that you can select from among those available.</p>
          </div>
        </div>
      </div>
    </section> -->

    <!-- Section 4: Buffet -->
    <!-- <section class="buffet-section">
      <div class="container">
        <div class="buffet-grid">
          <div class="text-content">
            <h1>Buffet delicious</h1>
            <h2>Goodness Without Borders</h2>
            <p>Our store is more than just another ordinary online retailer. We are not only top-quality products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from store to store, and then trying to find time to collect and organize your online orders. Forget about struggling to do everything at once: taking care of the family, running from store to store, and then trying to find time to collect and organize your online orders.</p>
            <p>Products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from store to store, and then trying to find time to collect and organize your online orders. We will not only top-quality products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from store to store, and then trying to find time to collect and organize your online orders.</p> -->
            <!-- <p>Products, but give our customers a positive online shopping experience. Forget about struggling to do everything at once: taking care of the family, running from store to store, and then trying to find time to collect and organize your online orders.</p>
          </div>
          <div class="buffet-images">
            <div class="image-placeholder">
               <b-img src="/Res_1.jpg" fluid alt="Restaurant View 1" class="buffet-image"/>
            </div>
            <div class="image-placeholder">
               <b-img src="/Res_2.jpg" fluid alt="Restaurant View 2" class="buffet-image"/>
            </div>
            <div class="image-placeholder">
              <b-img src="/Res_5.jpg" fluid alt="Restaurant View 3" class="buffet-image"/>
            </div>
            <div class="image-placeholder">
              <b-img src="/Res_6.jpg" fluid alt="Restaurant View 4" class="buffet-image"/>
            </div>
          </div>
        </div>
      </div>
    </section> -->

    <!-- Newsletter -->
    <!-- <section class="newsletter">
      <div class="container">
        <h3>Keep up with the latest restaurant news and events.</h3>
        <p>Enter your e-mail and subscribe to our newsletter.</p>
        <form class="newsletter-form" @submit.prevent="subscribeNewsletter">
          <input
            type="email"
            placeholder="Your email"
            v-model="email"
            required
          >
          <button type="submit">SUBSCRIBE</button>
        </form>
      </div>
    </section> -->

    <section class="container-fluid" style="padding-right: 0; padding-left: 0">
      <Ourshops />
    </section>
  </div>
</template>

<script>
import {
  BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BAlert, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import banner from './banner.vue'
import store from '@@@/store'
import Ourshops from '../business/ourshops.vue';
import Top_banners from "../business/top_banners.vue";


export default {
  components: {
    Top_banners,
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
    banner,
    Ourshops
  },
  directives: {
    Ripple,
  },

  data() {
    return {
      email: '',
      rows: {},
    }
  },
   methods: {
    subscribeNewsletter() {
      if (this.email) {
        alert('Thank you for subscribing with: ' + this.email);
        this.email = '';
      }
    },
  },
  created() {
    store.commit('deliware_cart/UPDATE_FOOTER', false)
    this.$http.get('/aboutus')
      .then(res => {

        this.rows = res.data.data
        store.commit('deliware_cart/UPDATE_FOOTER', true)


      })
  },

}
</script>
<style>
.pt-10 {
  padding-top: 10rem !important;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Image Placeholder Styles */
.image-placeholder {
  width: 100%;
  height: 100%;
  position: relative;
}

.placeholder-box {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
  border: 2px dashed #ccc;
  border-radius: 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #666;
  font-size: 1.1rem;
  text-align: center;
  transition: all 0.3s ease;
}

.placeholder-box:hover {
  background: linear-gradient(135deg, #e8e8e8, #d8d8d8);
  border-color: #d4af37;
}

.placeholder-box span {
  font-weight: bold;
  margin-bottom: 5px;
}

.placeholder-box small {
  font-size: 0.9rem;
  color: #999;
}

/* Image Styles */
.restaurant-image, .kitchen-image, .restaurant-interior, .buffet-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Section 1: Gurlean Delicious Food */
.section-1 {
  padding: 30px 0;
  background: #f8f9fa;
}

.section-1 h1 {
  font-size: 3rem;
  color: #d4af37;
  font-weight: bold;
  margin-bottom: 10px;
}

.section-1 h2 {
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 30px;
}

.content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: center;
}

.content-grid .image-placeholder {
  height: 300px;
}

.text-content p {
  margin-bottom: 15px;
  line-height: 1.8;
  color: #22292f;
}

/* Section 2: Midnight vision */
.section-2 {
  padding: 60px 0;
  background: white;
}

.section-2 h1 {
  font-size: 3rem;
  color: #d4af37;
  font-weight: bold;
  margin-bottom: 10px;
}

.section-2 h2 {
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 30px;
}

.image-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 30px;
}

.image-grid .image-placeholder {
  height: 200px;
}

/* Section 3: Cocktails */
.cocktail-section {
  padding: 80px 0;
  background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
              linear-gradient(135deg, #2c3e50, #34495e);
  background-size: cover;
  background-position: center;
  color: white;
  text-align: center;
}

.cocktail-section h1 {
  font-size: 3rem;
  color: #d4af37;
  font-style: italic;
  margin-bottom: 10px;
}

.cocktail-section h2 {
  font-size: 2.5rem;
  color: white;
  margin-bottom: 40px;
}

.cocktail-info {
  max-width: 600px;
  margin: 0 auto;
}

.info-box {
  background: rgba(255,255,255,0.1);
  padding: 20px;
  border-radius: 10px;
  margin-bottom: 20px;
  border-left: 4px solid #d4af37;
}

.info-box p {
  margin-bottom: 10px;
  line-height: 1.6;
}

/* Section 4: Buffet */
.buffet-section {
  padding: 20px 0;
  background: #f8f9fa;
}

.buffet-section h1 {
  font-size: 3rem;
  color: #d4af37;
  font-style: italic;
  margin-bottom: 10px;
}

.buffet-section h2 {
  font-size: 1.5rem;
  color: #333;
  margin-bottom: 30px;
}

.buffet-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: start;
}

.buffet-images {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.buffet-images .image-placeholder {
  margin-top: 20px;
  height: 215px;
}

/* Newsletter */
.newsletter {
  background: #FF006B;
  color: white;
  padding: 40px 0;
  text-align: center;
}

.newsletter h3 {
  color: white;
  margin-bottom: 10px;
  font-size: 1.5rem;
}

.newsletter p {
  color: white;
  margin-bottom: 20px;
}

.newsletter-form {
  display: flex;
  max-width: 500px;
  margin: 0 auto;
  gap: 10px;
}

.newsletter-form input {
  flex: 1;
  padding: 12px;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
}

.newsletter-form button {
  padding: 12px 25px;
  background: #d4af37;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
}

.newsletter-form button:hover {
  background: #b8941f;
}

/* Responsive Design */
@media (max-width: 768px) {
  .content-grid, .buffet-grid {
    grid-template-columns: 1fr;
  }

  .image-grid, .buffet-images {
    grid-template-columns: 1fr;
  }

  .newsletter-form {
    flex-direction: column;
  }

  .section-1 h1, .section-2 h1, .cocktail-section h1, .buffet-section h1 {
    font-size: 2rem;
  }

  .cocktail-section h2 {
    font-size: 1.8rem;
  }

  .content-grid .image-placeholder {
    height: 250px;
  }

  .image-grid .image-placeholder {
    height: 180px;
  }

  .buffet-images .image-placeholder {
    height: 120px;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 0 15px;
  }

  .section-1 h1, .section-2 h1, .cocktail-section h1, .buffet-section h1 {
    font-size: 1.8rem;
  }

  .cocktail-section h2 {
    font-size: 1.5rem;
  }

  .section-1 h2, .section-2 h2, .buffet-section h2 {
    font-size: 1.2rem;
  }
}
</style>
