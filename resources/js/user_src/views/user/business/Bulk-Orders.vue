<template>
    <div>
        <div class="mt-5 mx-3 py-6">
            <top_banners />
            <h4 class="text-center mb-4" style="font-size: x-large">
                Bulk Orders
            </h4>
            <div class="container mt-5 form-box">
                <!-- <h5 class="text-center mb-4" style="font-weight: bolder;">SUBMIT FOR BULK ORDER</h5> -->

                <form @submit.prevent="handleSubmit">
                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label required"
                            >Name</label
                        >
                        <div class="col-sm-9">
                            <input
                                v-model="form.name"
                                type="text"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- State -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label required"
                            >State</label
                        >
                        <div class="col-sm-9">
                            <select v-model="form.state" class="form-control">
                                <option disabled value="">Select State</option>
                                <option
                                    v-for="(state, index) in states"
                                    :key="index"
                                    :value="state"
                                >
                                    {{ state }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- District -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label required"
                            >District</label
                        >
                        <div class="col-sm-9">
                            <select
                                v-model="form.district"
                                class="form-control"
                            >
                                <option disabled value="">
                                    Select District
                                </option>
                                <option
                                    v-for="(district, index) in districts"
                                    :key="index"
                                    :value="district"
                                >
                                    {{ district }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Taluk -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Taluk</label>
                        <div class="col-sm-9">
                            <input
                                v-model="form.taluk"
                                type="text"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Pin code -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pin code</label>
                        <div class="col-sm-9">
                            <input
                                v-model="form.pincode"
                                type="text"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Address</label>
                        <div class="col-sm-9">
                            <textarea
                                v-model="form.address"
                                class="form-control"
                                rows="2"
                            />
                        </div>
                    </div>

                    <!-- Phone1 -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Phone1</label>
                        <div class="col-sm-9">
                            <input
                                v-model="form.phone1"
                                type="text"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Phone2 -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Phone2</label>
                        <div class="col-sm-9">
                            <input
                                v-model="form.phone2"
                                type="text"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Mail ID -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Mail ID</label>
                        <div class="col-sm-9">
                            <input
                                v-model="form.mail"
                                type="email"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Message</label>
                        <div class="col-sm-9">
                            <textarea
                                v-model="form.message1"
                                class="form-control"
                                rows="2"
                            />
                        </div>
                    </div>

                    <!-- Mail ID (again) -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Mail ID</label>
                        <div class="col-sm-9">
                            <input
                                v-model="form.mail"
                                type="email"
                                class="form-control"
                            />
                        </div>
                    </div>

                    <!-- Product Category -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label required"
                            >Product Category</label
                        >
                        <div class="col-sm-9">
                            <select
                                v-model="form.category"
                                class="form-control"
                            >
                                <option disabled value="">
                                    Select Category
                                </option>
                                <option
                                    v-for="(cat, index) in categories"
                                    :key="index"
                                    :value="cat"
                                >
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label required"
                            >Products</label
                        >
                        <div class="col-sm-9">
                            <select
                                v-model="form.products"
                                class="form-control"
                            >
                                <option disabled value="">
                                    Select Product
                                </option>
                                <option
                                    v-for="(product, index) in products"
                                    :key="index"
                                    :value="product"
                                >
                                    {{ product }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Message (again) -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Message</label>
                        <div class="col-sm-9">
                            <textarea
                                v-model="form.message2"
                                class="form-control"
                                rows="2"
                            />
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            SAVE
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container-fluid" style="padding-top: 3rem; padding-left: 0">
            <Ourshops />
        </div>
    </div>
</template>

<script>
import Ourshops from "./ourshops.vue";
import Top_banners from "./top_banners.vue";
export default {
    components: {
        Ourshops,
        top_banners,
    },
    data() {
        return {
            form: {
                name: "",
                state: "",
                district: "",
                taluk: "",
                pincode: "",
                address: "",
                phone1: "",
                phone2: "",
                mail: "",
                message1: "",
                category: "",
                products: "",
                message2: "",
            },
            states: [],
            districts: [],
            categories: [],
            products: [],
        };
    },
    methods: {
        handleSubmit() {
            console.log("Form Submitted:", this.form);
        },
    },
};
</script>

<style scoped>
.required::after {
    content: " *";
    color: red;
}
.form-control {
    border-radius: 3px !important; /* smaller corner radius */
    border-width: 1px !important; /* thin border */
    box-shadow: none !important; /* no extra shadow */
}

.form-group {
    margin-bottom: 1rem;
}

/* Ensure the outer container has a clean, visible border */
.form-box {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 2rem;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
}
::v-deep .form-group label {
    font-size: 1.1rem;
    font-weight: 600;
    color: #222;
}
</style>
