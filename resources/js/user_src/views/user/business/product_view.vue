<template>
    <div style="margin-top:50px;">
        <div v-if="isLoading" class=" mt-1 text-center"
            style=" display: flex; align-content: space-around; justify-content: space-between;    flex-direction: column;    flex-wrap: wrap;">
            <lottie-player src="/animations/loading.json" background="#FFFDFD" speed="1"
                style="width: 300px; height: 500px" loop autoplay></lottie-player>
        </div>
        <div class="container pt-8">
            <!-- <feather-icon icon="ChevronLeftIcon" class="mr-50" />
            <span class="align-middle" @click="goback()" style="cursor:pointer">{{ $t("Back") }}</span> -->
            <b-breadcrumb separator="|">
                <b-breadcrumb-item to="/">
                    <span class="bc_active bc_home"> HOME</span>
                </b-breadcrumb-item>
                <b-breadcrumb-item @click="nav_to(1)">
                    <span class="bc_active bc_home">{{ product.category_name }}</span>
                </b-breadcrumb-item>
                <!-- <b-breadcrumb-item @click="nav_to(2)">
                    <span class="bc_active bc_home"> {{ product.sub_category_name }}</span>
                </b-breadcrumb-item> -->
                <b-breadcrumb-item active>
                    <span class="bc_active"> {{ product.name }}</span>
                </b-breadcrumb-item>
            </b-breadcrumb>
            <b-row class="my-2 ">
                <b-col cols="12" md="7">
                    <div class="d-flex align-items-center justify-content-center" v-if="product.image.length <= 1">
                        <b-img :src="product.image[0]" class="product-img" fluid />
                    </div>
                    <gallery :prop="img_to_show" v-else />

                    <h4 class="mt-1">{{ $t("Product Description") }}</h4>
                    <b-card-text>{{ product.description }}</b-card-text>
                    <h4 v-if="faq.length" class="mt-1">{{ $t("Frequently Asked Question") }}</h4>
                    <app-collapse accordion type="margin" v-if="faq.length">
                        <app-collapse-item v-for="(faq_data, index) in faq" :key="index" :title="faq_data.question">
                            {{ faq_data.answer }}
                        </app-collapse-item>
                    </app-collapse>
                </b-col>
                <b-col cols="12" md="5" >
                    <h2>{{ product.name }}</h2>
                    <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                        <div class="strike" v-if="product.price < product.bprice">
                            {{ $store.state['defaults'].currency }} {{ product.bprice | priceformat
                            }}
                        </div>
                        <div class="price_text">
                            {{ $store.state['defaults'].currency }} {{ product.price | priceformat }}
                             <strong style="color: black;"> - 1kg</strong>
                        </div>
                        <div class="badge"  v-if="product.price < product.bprice">
                            {{ product.disc_value }} % Off
                        </div>
                    </div>
                    <b-card-text v-if="product.menu_available == 1">{{ $t("Available") }} - <span class="text-success">
                            {{ $t("In Stock") }}</span></b-card-text>
                    <b-card-text v-else>{{ $t("Not Available") }} - <span class="text-success">
                            {{ $t("Out of Stock") }}</span></b-card-text>


                    <div id="addonsModalBody" class="text-center"
                        v-if="product.food_quantity.length || product.add_ons.length || product.groups.length">
                        <div v-if="!product.food_quantity.length" class="cart-bord">
                            <div v-if="product.add_ons.length">
                                <div v-if="product.add_ons.length" style="text-align: left;">
                                    <h4>{{ $t("Addons") }}</h4>
                                    <table v-if="product.add_ons.length" width="50%">
                                        <tr v-for="(data4, index4) in product.add_ons" :key="index4">
                                            <td ALIGN="left" width="25%">
                                                <b-form-checkbox :name="'addonsOfItem_' + product.food_id"
                                                    :id="'addon_' + product.food_id + '_' + data4.id"
                                                    :data-name="data4.name" :data-price="data4.price" :value="data4.id">
                                                </b-form-checkbox>
                                            </td>
                                            <td ALIGN="left" width="55%">
                                                <label :for="'addon_' + product.food_id + '_' + data4.id"
                                                    style="font-size:16px;">
                                                    {{
                                                        data4.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'addon_' + product.food_id + '_' + data4.id"
                                                    style="font-size:16px;" v-if="data4.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ data4.price | priceformat
                                                    }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div v-if="product.groups.length">
                                <div v-for="ga1 in product.groups" :key="ga1.id">
                                    <div v-if="ga1.group_addons.length && ga1.allow_multiple !== 1"
                                        style="text-align: left;">
                                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup>
                                        </h4>
                                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} should be
                                                selected</small></h5>
                                        <table
                                            v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max != 1"
                                            width="50%">
                                            <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                                <td ALIGN="left" width="25%">
                                                    <b-form-checkbox :name="'faddon' + ga1.id"
                                                        :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                        :data-price="ga2.price" @change="validate_sizeaddons(ga1, ga2)"
                                                        :value="ga2.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td ALIGN="left" width="55%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;">
                                                        {{ ga2.name }}
                                                    </label>
                                                </td>
                                                <td ALIGN="left" width="20%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;" v-if="ga2.price > 0">
                                                        {{ $store.state['defaults'].currency }}{{ ga2.price |
                                                            priceformat }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                        <table
                                            v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max == 1"
                                            width="50%">
                                            <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                                <td ALIGN="left" width="25%">
                                                    <b-form-radio :name="'faddon' + ga1.id"
                                                        :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                        :data-price="ga2.price" :value="ga2.id">
                                                    </b-form-radio>
                                                </td>
                                                <td ALIGN="left" width="55%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"

                                                        style="font-size:16px;">
                                                        {{ ga2.name }}
                                                    </label>
                                                </td>
                                                <td ALIGN="left" width="20%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;" v-if="ga2.price > 0">
                                                        {{ $store.state['defaults'].currency }}{{ ga2.price |
                                                            priceformat }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div v-else style="text-align: left;">
                                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:black"> *</sup>
                                        </h4>
                                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} should be
                                                selected</small></h5>
                                        <table v-if="ga1.group_addons.length && ga1.allow_multiple == 1" width="50%">
                                            <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                                <td ALIGN="left" width="25%">
                                                    <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                                        variant="outline-primary"
                                                        class="btn-icon rounded-circle add_to_cart"
                                                        :id="'size_addon_' + ga1.id + '_' + ga2.id"
                                                        style="border: 2px solid #00A082 !important;"
                                                        @click="add_sizeaddon(ga1, ga2)" :data-name="ga2.name"
                                                        :data-price="ga2.price" :value="ga2.id">
                                                        <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                                                    </b-button>
                                                    <input type="hidden" :id="'hidden_' + ga1.id" value="0" />
                                                    <div class="num-block skin-2"
                                                        :id="'size_control_' + ga1.id + '_' + ga2.id"
                                                        style="display:none">
                                                        <div class="num-in">
                                                            <span class="minus dis"
                                                                :id="'size_minus' + ga1.id + '_' + ga2.id"
                                                                @click="decrease_size(ga1, ga2)"></span>
                                                            <input type="text" class="in-num" value="0" readonly=""
                                                                style="font-weight:800;"
                                                                :id="'size_qty_' + ga1.id + '_' + ga2.id"
                                                                :data-name="ga2.name" :data-price="ga2.price"
                                                                :data-value="ga2.id">
                                                            <span class="plus" :id="'size_plus' + ga1.id + '_' + ga2.id"
                                                                @click="increase_size(ga1, ga2)"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td ALIGN="left" width="55%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;">
                                                        {{ ga2.name }}
                                                    </label>
                                                </td>
                                                <td ALIGN="left" width="20%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;" v-if="ga2.price > 0">
                                                        {{ $store.state['defaults'].currency }}{{ ga2.price |
                                                            priceformat }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div v-if="product.food_quantity.length" style="text-align: left;">
                            <h4>{{ $t("Sizes") }}</h4>
                        </div> -->
                        <!-- <b-row>
                            <div v-for="(data5, index5) in    product.food_quantity   " :key="index5">
                                <input type="radio" :name="'sizesOfItem_' + product.food_id"
                                    :id="'size_' + product.food_id + '_' + data5.id" :data-name="data5.name"
                                    :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                    @change="check_size(data5, product.add_ons, product.groups, product.food_quantity)"
                                    checked />
                                <label :for="'size_' + product.food_id + '_' + data5.id" style="font-size:16px;"
                                    @click="check_size(data5, product.add_ons, product.groups, product.food_quantity)">
                                    <div class="circleBase type1" :style="{ background: data5.name }"
                                        :id="'circle' + data5.id">
                                    </div>
                                </label>
                            </div>

                        </b-row> -->



                        <!-- <tr v-for="(data5, index5) in    product.food_quantity   " :key="index5">
                            <td v-if="data5.is_default" ALIGN="left" width="25%" style="visibility:hidden">
                                <b-form-radio :name="'sizesOfItem_' + product.food_id"
                                    :id="'size_' + product.food_id + '_' + data5.id" :data-name="data5.name"
                                    :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                    @change="check_size(data5, product.add_ons, product.groups)">
                                </b-form-radio>
                            </td>
                            <td ALIGN="left" v-else width="25%" style="visibility:hidden">
                                <b-form-radio :name="'sizesOfItem_' + product.food_id"
                                    :id="'size_' + product.food_id + '_' + data5.id" :data-name="data5.name"
                                    :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                    @click="check_size(data5, product.add_ons, product.groups)">
                                </b-form-radio>
                            </td>
                            <td ALIGN="left" width="55%">
                                <label :for="'size_' + product.food_id + '_' + data5.id" style="font-size:16px;"
                                    @click="check_size(data5, product.add_ons, product.groups)">
                                    <div class="circleBase type1" :style="{ background: data5.name }">
                                    </div>
                                </label>
                            </td>
                        </tr> -->
                        <!-- <table v-if="product.food_quantity.length" width="50%">
                            <tr v-for="(data5, index5) in product.food_quantity" :key="index5">
                                <td v-if="data5.is_default" ALIGN="left" width="25%">

                                </td>
                                <td ALIGN="left" v-else width="25%">
                                    <b-form-radio :name="'sizesOfItem_' + product.food_id"
                                        :id="'size_' + product.food_id + '_' + data5.id" :data-name="data5.name"
                                        :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                        @click="check_size(data5, product.add_ons, product.groups)">
                                    </b-form-radio>
                                </td>
                                <td ALIGN="left" width="55%">
                                    <label :for="'size_' + product.food_id + '_' + data5.id" style="font-size:16px;"
                                        @click="check_size(data5, product.add_ons, product.groups)"> {{ data5.name }}
                                    </label>
                                </td>
                                <td ALIGN="left" width="20%">
                                    <label :for="'size_' + product.food_id + '_' + data5.id" style="font-size:16px;"
                                        @click="check_size(data5, product.add_ons, product.groups)"
                                        v-if="data5.price > 0">{{
                                            $store.state['defaults'].currency }}{{ data5.price | priceformat }} </label>
                                </td>
                            </tr>
                        </table> -->
                        <div v-if="product.food_quantity.length">
                            <div v-for="(data6, index6) in product.food_quantity" :key="index6">
                                <div v-if="data6.groups && data6.groups.length">
                                    <div v-for="(data7, index7) in data6.groups" :key="index7">
                                        <div v-if="data6.id == s_fq">
                                            <div v-if="data7.group_addons.length && data7.allow_multiple != 1"
                                                style="text-align: left;margin-top:30px;">
                                                <h4>{{ data7.name }}<sup v-if="data7.mandatory === 1" style="color:red">
                                                        *</sup></h4>
                                                <h5 v-if="data7.mandatory === 1"><small>Minimum {{ data7.min }} Should
                                                        be
                                                        selected</small>
                                                </h5>
                                                <table
                                                    v-if="data7.group_addons.length && data7.allow_multiple != 1 && data7.max != 1"
                                                    width="50%">
                                                    <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                                        <td ALIGN="left" width="25%">
                                                            <b-form-checkbox :name="'faddon' + data7.id"
                                                                :id="'size_' + data7.id + '_' + data8.id"
                                                                :data-name="data8.name" :data-price="data8.price"
                                                                @change="validate_sizeaddons(data7, data8)"
                                                                :value="data8.id">
                                                            </b-form-checkbox>
                                                        </td>
                                                        <td ALIGN="left" width="55%">
                                                            <label :for="'size_' + data7.id + '_' + data8.id"
                                                                style="font-size:16px;">
                                                                {{ data8.name }}
                                                            </label>
                                                        </td>
                                                        <td ALIGN="left" width="20%">
                                                            <label :for="'size_' + data7.id + '_' + data8.id"
                                                                style="font-size:16px;" v-if="data8.price > 0">
                                                                {{ $store.state['defaults'].currency }}{{ data8.price |
                                                                    priceformat }} </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table
                                                    v-if="data7.group_addons.length && data7.allow_multiple != 1 && data7.max == 1"
                                                    width="50%">
                                                    <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                                        <td ALIGN="left" width="25%">
                                                            <b-form-radio :name="'faddon' + data7.id"
                                                                :id="'size_' + data7.id + '_' + data8.id"
                                                                :data-name="data8.name" :data-price="data8.price"
                                                                :value="data8.id">
                                                            </b-form-radio>
                                                        </td>
                                                        <td ALIGN="left" width="55%">
                                                            <label :for="'size_' + data7.id + '_' + data8.id"
                                                                style="font-size:16px;">
                                                                {{ data8.name }}
                                                            </label>
                                                        </td>
                                                        <td ALIGN="left" width="20%">
                                                            <label :for="'size_' + data7.id + '_' + data8.id"
                                                                style="font-size:16px;" v-if="data8.price > 0">
                                                                {{ $store.state['defaults'].currency }}{{ data8.price |
                                                                    priceformat }} </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div v-else style="text-align: left;margin-top:30px;">
                                                <h4>{{ data7.name }}<sup v-if="data7.mandatory === 1" style="color:red">
                                                        *</sup></h4>
                                                <h5 v-if="data7.mandatory === 1"><small>Minimum {{ data7.min }} Should
                                                        be
                                                        selected</small>
                                                </h5>
                                                <table v-if="data7.group_addons.length && data7.allow_multiple == 1"
                                                    width="50%">
                                                    <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                                        <td ALIGN="left" width="25%">
                                                            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                                                variant="outline-primary"
                                                                class="btn-icon rounded-circle add_to_cart"
                                                                :id="'size_addon_' + data7.id + '_' + data8.id"
                                                                style="border: 2px solid #00A082 !important;"
                                                                @click="add_sizeaddon(data7, data8)"
                                                                :data-name="data8.name" :data-price="data8.price"
                                                                :value="data8.id">
                                                                <feather-icon icon="PlusIcon" class="ico-color"
                                                                    size="15" />
                                                            </b-button>
                                                            <input type="hidden" :id="'hidden_' + data7.id" value="0" />
                                                            <div class="num-block skin-2"
                                                                :id="'size_control_' + data7.id + '_' + data8.id"
                                                                style="display:none">
                                                                <div class="num-in">
                                                                    <span class="minus dis"
                                                                        :id="'size_minus' + data7.id + '_' + data8.id"
                                                                        @click="decrease_size(data7, data8)"></span>
                                                                    <input type="text" class="in-num" value="0"
                                                                        readonly="" style="font-weight:800;"
                                                                        :id="'size_qty_' + data7.id + '_' + data8.id"
                                                                        :data-name="data8.name"
                                                                        :data-price="data8.price"
                                                                        :data-value="data8.id">
                                                                    <span class="plus"
                                                                        :id="'size_plus' + data7.id + '_' + data8.id"
                                                                        @click="increase_size(data7, data8)"></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td ALIGN="left" width="55%">
                                                            <label :for="'size_' + data7.id + '_' + data8.id"
                                                                style="font-size:16px;">
                                                                {{ data8.name }}
                                                            </label>
                                                        </td>
                                                        <td ALIGN="left" width="20%">
                                                            <label :for="'size_' + data7.id + '_' + data8.id"
                                                                style="font-size:16px;" v-if="data8.price > 0">
                                                                {{ $store.state['defaults'].currency }}{{ data8.price |
                                                                    priceformat }} </label>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="data6.add_ons && data6.add_ons.length">
                                    <div v-if="data6.id == s_fq" style="text-align: left;">
                                        <h4>{{ $t("Ad   dons") }}</h4>
                                    </div>
                                    <table v-if="data6.id == s_fq" width="50%">
                                        <tr v-for="(d6, i6) in data6.add_ons" :key="i6">
                                            <td ALIGN="left" width="25%">
                                                <b-form-checkbox :name="'saddonsOfItem_' + data6.id"
                                                    :id="'addon_' + product.food_id + '_' + d6.id" :data-name="d6.name"
                                                    :data-price="d6.price" :value="d6.id">
                                                </b-form-checkbox>
                                            </td>
                                            <td ALIGN="left" width="55%">
                                                <label :for="'addon_' + product.food_id + '_' + d6.id"
                                                    style="font-size:16px;">
                                                    {{
                                                        d6.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'addon_' + product.food_id + '_' + d6.id"
                                                    style="font-size:16px;" v-if="d6.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ d6.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div v-if="product.groups.length">
                                <div v-for="ga1 in product.groups" :key="ga1.id">
                                    <div v-if="ga1.group_addons.length && ga1.allow_multiple !== 1"
                                        style="text-align: left;margin-top:30px;">
                                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup>
                                        </h4>
                                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} Should be
                                                selected</small></h5>
                                        <table
                                            v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max != 1"
                                            width="50%">
                                            <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                                <td ALIGN="left" width="25%">
                                                    <b-form-checkbox :name="'faddon' + ga1.id"
                                                        :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                        :data-price="ga2.price" @change="validate_sizeaddons(ga1, ga2)"
                                                        :value="ga2.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td ALIGN="left" width="55%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;">
                                                        {{ ga2.name }}
                                                    </label>
                                                </td>
                                                <td ALIGN="left" width="20%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;" v-if="ga2.price > 0">
                                                        {{ $store.state['defaults'].currency }}{{ ga2.price |
                                                            priceformat }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                        <table
                                            v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max == 1"
                                            width="50%">
                                            <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                                <td ALIGN="left" width="25%">
                                                    <b-form-radio :name="'faddon' + ga1.id"
                                                        :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                        :data-price="ga2.price" :value="ga2.id">
                                                    </b-form-radio>
                                                </td>
                                                <td ALIGN="left" width="55%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;">
                                                        {{ ga2.name }}
                                                    </label>
                                                </td>
                                                <td ALIGN="left" width="20%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;" v-if="ga2.price > 0">
                                                        {{ $store.state['defaults'].currency }}{{ ga2.price |
                                                            priceformat }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div v-else style="text-align: left;margin-top:30px;">
                                        <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup>
                                        </h4>
                                        <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} Should be
                                                selected</small></h5>
                                        <table v-if="ga1.group_addons.length && ga1.allow_multiple == 1" width="50%">
                                            <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                                <td ALIGN="left" width="25%">
                                                    <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                                        variant="outline-primary"
                                                        class="btn-icon rounded-circle add_to_cart"
                                                        :id="'size_addon_' + ga1.id + '_' + ga2.id"
                                                        style="border: 2px solid #00A082 !important;"
                                                        @click="add_sizeaddon(ga1, ga2)" :data-name="ga2.name"
                                                        :data-price="ga2.price" :value="ga2.id">
                                                        <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                                                    </b-button>
                                                    <input type="hidden" :id="'hidden_' + ga1.id" value="0" />
                                                    <div class="num-block skin-2"
                                                        :id="'size_control_' + ga1.id + '_' + ga2.id"
                                                        style="display:none">
                                                        <div class="num-in">
                                                            <span class="minus dis"
                                                                :id="'size_minus' + ga1.id + '_' + ga2.id"
                                                                @click="decrease_size(ga1, ga2)"></span>
                                                            <input type="text" class="in-num" value="0" readonly=""
                                                                style="font-weight:800;"
                                                                :id="'size_qty_' + ga1.id + '_' + ga2.id"
                                                                :data-name="ga2.name" :data-price="ga2.price"
                                                                :data-value="ga2.id">
                                                            <span class="plus" :id="'size_plus' + ga1.id + '_' + ga2.id"
                                                                @click="increase_size(ga1, ga2)"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td ALIGN="left" width="55%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;">
                                                        {{ ga2.name }}
                                                    </label>
                                                </td>
                                                <td ALIGN="left" width="20%">
                                                    <label :for="'size_' + ga1.id + '_' + ga2.id"
                                                        style="font-size:16px;" v-if="ga2.price > 0">
                                                        {{ $store.state['defaults'].currency }}{{ ga2.price |
                                                            priceformat }}
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div v-if="product.add_ons.length">
                                <table v-if="product.add_ons.length" width="50%">
                                    <tr v-for="(data4, index4) in product.add_ons" :key="index4">
                                        <td ALIGN="left" width="25%">
                                            <b-form-checkbox :name="'addonsOfItem_' + product.food_id"
                                                :id="'addon_' + product.food_id + '_' + data4.id"
                                                :data-name="data4.name" :data-price="data4.price" :value="data4.id">
                                            </b-form-checkbox>
                                        </td>
                                        <td ALIGN="left" width="55%">
                                            <label :for="'addon_' + product.food_id + '_' + data4.id"
                                                style="font-size:16px;">
                                                {{
                                                    data4.name }}
                                            </label>
                                        </td>
                                        <td ALIGN="left" width="20%">
                                            <label :for="'addon_' + product.food_id + '_' + data4.id"
                                                style="font-size:16px;" v-if="data4.price > 0">
                                                {{ $store.state['defaults'].currency }}{{ data4.price | priceformat }}
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- </div> -->
                        <!-- <div slot="modal-footer" class="w-100 modal_footer">
                        <b-alert v-model="showDismissibleAlert" v-height-fade.appear variant="danger" dismissible
                            class="mb-0">
                            <div class="alert-body">
                                {{ alert_text }}
                            </div>
                        </b-alert>
                        <b-button class="" variant="primary" id="addCustomizedProduct" @click="check_cart(data)"
                            style="width:100%;font-size:16px;font-weight:800">
                            {{ $t("Add to order") }}
                        </b-button>
                    </div> -->
                        <b-alert v-model="showDismissibleAlert" v-height-fade.appear variant="danger" dismissible
                            class="mb-0">
                            <div class="alert-body">
                                {{ alert_text }}
                            </div>
                        </b-alert>

                    </div>
                    <!-- <div class="d-flex align-items-center mt-2 flex-wrap" v-if="product.menu_available == 1 && isopen == 1">

  <div
    class="d-flex align-items-center border rounded "
    :id="'food_control_' + product.food_id + 'r'"
    v-if="cart_status == 1"
    style="height: 43px; overflow: hidden; margin-right: 20px;  font-weight: bold;  border: 4px solid #ccc;"
  >

    <span
      :id="'food_minus_' + product.food_id"
      @click="quantity > 1 ? quantity-- : null"

      class="d-flex justify-content-center align-items-center"
      style="width: 53px; height: 100%; border-right: 1px solid #ccc; font-size: 24px; font-weight: bold; cursor: pointer;"
    >−</span>

    <input
      type="text"
      class="text-center border-0"
      style="width: 50px; height: 100%; font-size: 18px;  font-weight: bold;"
      :value="quantity"
      readonly
      :id="'food_qty_' + product.food_id + 'r'"
    />


    <span
      :id="'food_plus_' + product.food_id"
       @click="quantity++"
      class="d-flex justify-content-center align-items-center"
      style="width: 53px; height: 100%; border-left: 1px solid #ccc; font-size: 24px;  font-weight: bold; cursor: pointer;"
    >+</span>
  </div>


<b-button
  class="d-flex align-items-center px-3 py-2 custom-add-btn"
  :id="'food_add_' + product.food_id + 'r'"
  :data-price="product.price"
  @click="check_cart(selected)"
>
  <b-img src="/monster/plate-white.svg" class="me-2" style="height: 20px;" />
  <span>Add to Cart</span>
</b-button>
                    </div> -->

                    <div class="d-flex flex-column flex-sm-row pt-1" v-if="product.menu_available == 1 && isopen == 1">
                        <b-button v-if="product.menu_available === 1 && cart_status == 1"
                            v-ripple.400="'rgba(113, 102, 240, 0.15)'" variant="primary"
                            class="btn-cart mr-0 mr-sm-1 mb-1 mb-sm-0" :id="'food_add_' + product.food_id + 'r'"
                            :data-price=product.price @click="check_cart(selected)">
                            <b-img src="/monster/plate-white.svg" class="mr-50 add_cart_btn_img"></b-img>

                            <span>{{ 'Add to Cart' }}</span>
                        </b-button>
                        <div class="num-block skin-2" :id="'food_control_' + product.food_id + 'r'" style="display:none"
                            v-if="product.menu_available === 1 && cart_status == 1">
                            <div class="num-in">
                                <span class="minus dis" :id="'food_minus_' + product.food_id"
                                    @click="removefromcart(product.price, product.item_tax, product.name, product.food_id, product.is_veg, product.add_ons, product.food_quantity, product.image)"></span>
                                <input type="text" class="in-num" value="1" readonly=""
                                    :id="'food_qty_' + product.food_id + 'r'">
                                <span class="plus" :id="'food_plus_' + product.food_id"
                                    @click="open_plus(selected)"></span>
                            </div>
                        </div>
                    </div>


         <strong class="mt-2" style=" font-size: large;">
  Total Price: {{ totalPrice }} {{ $store.state['defaults'].currency }}
         </strong>


                    <div class="d-flex justify-content-middle mt-2 pr-5 ">
  <img class= "rounded-lg" src="/uploads/7sct.png" alt="Flat 20% Off" width="610" height="132">
</div>
                    <!-- <div v-if="table.length">
                        <div v-for="(td, index_td) in table" :key="index_td">
                            <h4 class="mt-1">{{ td.name }}</h4>
                            <table width="50%" cellspacing="10" cellpadding="10">
                                <tr v-for="(datasp, indexsp) in td.items" :key="indexsp"
                                    style="border: 1px solid #EFEFEF;">
                                    <td ALIGN="left" width="25%" style="background-color:#EFEFEF; ">
                                        {{ datasp.label }}
                                    </td>
                                    <td ALIGN="left" width="25%">
                                        {{ datasp.value }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> -->


                </b-col>
            </b-row>
            <featured :prop="featured" :business_id="business_id" :isopen="isopen" :title="title2"
                v-if="featured.length" />
        </div>
    </div>
</template>
<script>

import store from '@@@/store'
import { ref } from '@vue/composition-api'
import {
    BCard, BCardBody, BRow, BCol, BImg, BCardText, BLink, BButton, BDropdown, BDropdownItem, BAlert, BFormRadio, BFormCheckbox, BFormGroup, BFormRadioGroup
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import jsfunctions from './jsfunctions.js'
import gallery from './gallery.vue'
import 'swiper/css/swiper.css'
import featured from './featured.vue'
import addCustomizedProduct from './cartfunctions.js'
import goback from './cartfunctions.js'
import loadcart from './cartfunctions.js'
import single_restaurant from './cartfunctions.js'
import open_plus from './cartfunctions.js'
import removefromcart from './cartfunctions.js'
import check_cart from './cartfunctions.js'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
import { BBreadcrumb, BBreadcrumbItem } from 'bootstrap-vue'

export default {
    directives: {
        Ripple,
    },
    components: {
        BCard,
        BCardBody,
        BRow,
        BCol,
        BImg,
        BCardText,
        BLink,
        BButton,
        BDropdown,
        BDropdownItem,
        BAlert, BFormRadio,
        gallery,
        featured, BFormCheckbox, AppCollapse,
        AppCollapseItem,
        BFormGroup, BFormRadioGroup, BBreadcrumb,
        BBreadcrumbItem,
    },
    data() {
        return {
            product: {
                'image': [],
                'food_quantity': [],
                'cart_quantity': 1,
                'isInCart': false
            },
            lat: localStorage.getItem('latitude'),
            lng: localStorage.getItem('longitude'),
            business_id: localStorage.getItem('single_business_id'),
            isopen: 0,
            featured: [],
            title2: "Related Products",
            selected: {},
            s_fq: '',
            isLoading: true,
            rows: {},
            img_to_show: [],
            showDismissibleAlert: false,
            alert_text: '',
            cart: JSON.parse(localStorage.getItem("cart")),
            specs: {},
            faq: {},
            bgc: {
                backgroundColor: '#E61212'
            },
            table: [],
            cart_status: 0,
            quantity: 1


        }
    },
    computed: {
  totalPrice() {
    return (this.product.price * this.quantity).toFixed(2);
  }
},

    watch: {
        $route(to, from) {
            if (this.$route.name == "product") {
                this.isLoading = true;
                this.get_product_details()
                this.loadcart()
            }
        }
    },

    created() {
        this.get_product_details()
        this.loadcart()

        this.$http.get('/single_restaurant_products/' + this.lat + '/' + this.lng + '/' + this.business_id)
            .then(res => {
                this.featured = res.data.featured
                this.isopen = res.data.restaurant_details.is_open
            })
    },
    methods: {
        ...goback,
        ...addCustomizedProduct,
        ...single_restaurant,
        ...open_plus,
        ...removefromcart,
        ...loadcart,
        nav_to(ty) {
            if (ty == 1) {
                var str = this.product.category_name
                str.replace(/\-/g, "")
                var str2 = str.replace(/[^A-Z0-9]+/ig, "-").toLowerCase();
                this.$router.push({ name: 'cat_list', params: { slug: this.product.slug } });
            }


            if (ty == 2)
                this.$router.push({ name: 'sub_cat_list', params: { slug: this.product.sub_slug } });

        },
        get_product_details() {
            store.commit('deliware_cart/UPDATE_FOOTER', false)

            const productSlug = this.$route.params.slug
            const productId = productSlug.substring(productSlug.lastIndexOf('-') + 1)

            this.$http.get('/get_product_details/' + productId)
                .then(res => {
                    this.product = res.data.product
                    console.log(this.product)
                    this.img_to_show = []
                    if (this.product.image.length > 0) {
                        for (var i = 0; i < this.product.image.length; i++) {
                            var obj = {};
                            obj['id'] = i;
                            obj['src'] = this.product.image[i];
                            obj['alt'] = this.product.name;
                            obj['thumbnail'] = this.product.image[i];
                            this.img_to_show.push(obj);
                        }
                    }
                    console.log(this.img_to_show)
                    if (this.product.food_quantity && this.product.food_quantity.length) {
                        for (var i = 0; i < this.product.food_quantity.length; i++) {
                            if (this.product.food_quantity[i].is_default == 1) {
                                this.s_fq = this.product.food_quantity[i].id
                            }
                        }
                    }
                    this.product.isInCart = false
                    this.selected.id = this.product.food_id
                    this.selected.isveg = this.product.is_veg
                    this.selected.taxperc = parseFloat(this.product.item_tax)
                    this.selected.price = parseFloat(this.product.price)
                    this.selected.add_ons = this.product.add_ons
                    this.selected.food_quantity = this.product.food_quantity
                    this.selected.image = this.product.image
                    this.selected.name = this.product.name
                    this.selected.groups = this.product.groups
                    this.selected.restaurant = res.data.restaurant.id
                    this.selected.restaurant_name = res.data.restaurant.name
                    this.selected.restaurant_address = res.data.restaurant.address
                    this.selected.restaurant_image = res.data.restaurant.logo
                    // this.checkproductincart(this.product)
                    this.isLoading = false;
                    this.specs = res.data.specs
                    this.faq = res.data.faq
                    this.table = res.data.table
                    this.cart_status = res.data.cart_status

                    this.single_restaurant()
                    store.commit('deliware_cart/UPDATE_FOOTER', true)

                })
        },
        check_size(data, adds, group, size) {

            for (var i = 0; i < size.length; i++) {

                var element = document.getElementById("circle" + size[i].id);
                element.classList.remove("sel_cir");
            }
            var element = document.getElementById("circle" + data.id);
            element.classList.add("sel_cir");
        },
    },

}
</script>
<style lang="scss" scoped>
@import "~@resources/scss/base/pages/app-ecommerce-details.scss";
@import '~@resources/scss/base/bootstrap-extended/_variables.scss';

.circleBase {
    border-radius: 50%;
    cursor: pointer
        /* remove if you don't care about IE8 */
}

.type1 {
    width: 30px;
    height: 30px;
    background: yellow;
}

.sel_cir {
    border: 3px solid $primary;

}

input[type="radio"] {
    visibility: hidden;
    height: 0;
    width: 0;
}
.img-fluid{
    width:540px !important;
    height:358px !important;
    margin-right: 22rem !important;
}
.custom-add-btn {
  background-color: #FF007A !important;
  border-color: #FF007A !important;
  color: #fff !important;
  border-radius: 10px;
  font-weight: 600;
}

.custom-add-btn:hover,
.custom-add-btn:focus,
.custom-add-btn:active {
  background-color: #e6006b !important; /* Slightly darker pink */
  border-color: #e6006b !important;
  box-shadow: none !important;
}


</style>
