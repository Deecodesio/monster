<template>
    <b-modal id="image-modal" scrollable hide-header ok-only ref="image-modal" body-class="body_class" :visible="value">
        <b-button variant="outline-primary" class="btn-icon rounded-circle " @click="close_img"
            style="background-color: #00000080 !important;border: 1px solid rgba(32, 33, 37, 0.08) !important;position: absolute !important;right: 20px;top:20px">
            <feather-icon icon="XIcon" style="color:#fff" />
        </b-button>
        <b-card-text>
            <img class="modal_img" :src="data.image[0]" id="img01">
            <div class="container">
                <div class="sticky-div mt-1">
                    <h4>
                        <div v-if="data.isveg">
                            {{ data.name }} <b-img-lazy src="/veg.svg" fluid alt="Scan" style="width: 12px;" />
                        </div>
                        <div v-else>
                            {{ data.name }} <b-img-lazy src="/non_veg.svg" fluid alt="Scan" style="width: 12px;" />
                        </div>
                    </h4>
                    <h6>{{ data.description }}</h6>
                </div>
                <div id="addonsModalBody" class="text-center"
                    v-if="data.food_quantity.length || data.add_ons.length || data.groups.length">
                    <div v-if="!data.food_quantity.length" class="cart-bord">
                        <div v-if="data.add_ons.length">
                            <div v-if="data.add_ons.length" style="text-align: left;">
                                <h4>{{ $t("Addons") }}</h4>
                                <table v-if="data.add_ons.length" width="100%">
                                    <tr v-for="(data4, index4) in data.add_ons" :key="index4">
                                        <td ALIGN="left" width="25%">
                                            <b-form-checkbox :name="'addonsOfItem_' + data.id"
                                                :id="'addon_' + data.id + '_' + data4.id" :data-name="data4.name"
                                                :data-price="data4.price" :value="data4.id">
                                            </b-form-checkbox>
                                        </td>
                                        <td ALIGN="left" width="55%">
                                            <label :for="'addon_' + data.id + '_' + data4.id" style="font-size:16px;">
                                                {{
                                                    data4.name }}
                                            </label>
                                        </td>
                                        <td ALIGN="left" width="20%">
                                            <label :for="'addon_' + data.id + '_' + data4.id" style="font-size:16px;"
                                                v-if="data4.price > 0">
                                                {{ $store.state['defaults'].currency }}{{ data4.price | priceformat }}
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div v-if="data.groups.length">
                            <div v-for="ga1 in data.groups" :key="ga1.id">
                                <!-- Checkbox -->
                                <div v-if="ga1.group_addons.length && ga1.allow_multiple !== 1"
                                    style="text-align: left;">
                                    <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                                    <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} should be
                                            selected</small></h5>
                                    <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max != 1"
                                        width="100%">
                                        <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                            <td ALIGN="left" width="25%">
                                                <b-form-checkbox :name="'faddon' + ga1.id"
                                                    :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                    :data-price="ga2.price" @change="validate_sizeaddons(ga1, ga2)"
                                                    :value="ga2.id">
                                                </b-form-checkbox>
                                            </td>
                                            <td ALIGN="left" width="55%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;">
                                                    {{ ga2.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                                    v-if="ga2.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ ga2.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                    <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max == 1"
                                        width="100%">
                                        <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                            <td ALIGN="left" width="25%">
                                                <b-form-radio :name="'faddon' + ga1.id"
                                                    :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                    :data-price="ga2.price" :value="ga2.id">
                                                </b-form-radio>
                                            </td>
                                            <td ALIGN="left" width="55%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;">
                                                    {{ ga2.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                                    v-if="ga2.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ ga2.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- Allow Multiple -->
                                <div v-else style="text-align: left;">
                                    <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                                    <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} should be
                                            selected</small></h5>
                                    <table v-if="ga1.group_addons.length && ga1.allow_multiple == 1" width="100%">
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
                                                    :id="'size_control_' + ga1.id + '_' + ga2.id" style="display:none">
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
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;">
                                                    {{ ga2.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                                    v-if="ga2.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ ga2.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="data.food_quantity.length" style="text-align: left;">
                        <h4>{{ $t("Size") }}</h4>
                    </div>

                    <!-- <b-row>
                        <div v-for="(data5, index5) in    data.food_quantity   " :key="index5">
                            <input type="radio" :name="'sizesOfItem_' + data.food_id"
                                :id="'size_' + data.food_id + '_' + data5.id" :data-name="data5.name"
                                :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                @change="check_size(data5, data.add_ons, data.groups, data.food_quantity)" checked />
                            <label :for="'size_' + data.food_id + '_' + data5.id" style="font-size:16px;"
                                @click="check_size(data5, data.add_ons, data.groups, data.food_quantity)">
                                <div class="circleBase type1" :style="{ background: data5.name }" :id="'circle' + data5.id">
                                </div>
                            </label>
                        </div>

                    </b-row> -->


                    <table v-if="data.food_quantity.length" width="100%">
                        <tr v-for="(data5, index5) in data.food_quantity" :key="index5">
                            <td v-if="data5.is_default" ALIGN="left" width="25%">
                                <b-form-radio :name="'sizesOfItem_' + data.id" :id="'size_' + data.id + '_' + data5.id"
                                    :data-name="data5.name" :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                    @change="check_size(data5, data.add_ons, data.groups)">
                                </b-form-radio>
                            </td>
                            <td ALIGN="left" v-else width="25%">
                                <b-form-radio :name="'sizesOfItem_' + data.id" :id="'size_' + data.id + '_' + data5.id"
                                    :data-name="data5.name" :data-price="data5.price" v-model="s_fq" :value="data5.id"
                                    @click="check_size(data5, data.add_ons, data.groups)">
                                </b-form-radio>
                            </td>
                            <td ALIGN="left" width="55%">
                                <label :for="'size_' + data.id + '_' + data5.id" style="font-size:16px;"
                                    @click="check_size(data5, data.add_ons, data.groups)"> {{ data5.name }} </label>
                            </td>
                            <td ALIGN="left" width="20%">
                                <label :for="'size_' + data.id + '_' + data5.id" style="font-size:16px;"
                                    @click="check_size(data5, data.add_ons, data.groups)" v-if="data5.price > 0">{{
                                        $store.state['defaults'].currency }}{{ data5.price | priceformat }} </label>
                            </td>
                        </tr>
                    </table>
                    <div v-if="data.food_quantity.length">
                        <!-- size Addons -->
                        <div v-for="(data6, index6) in data.food_quantity" :key="index6">
                            <div v-if="data6.groups && data6.groups.length">
                                <div v-for="(data7, index7) in data6.groups" :key="index7">
                                    <div v-if="data6.id == s_fq">
                                        <div v-if="data7.group_addons.length && data7.allow_multiple != 1"
                                            style="text-align: left;margin-top:30px;">
                                            <h4>{{ data7.name }}<sup v-if="data7.mandatory === 1" style="color:red">
                                                    *</sup></h4>
                                            <h5 v-if="data7.mandatory === 1"><small>Minimum {{ data7.min }} Should be
                                                    selected</small>
                                            </h5>
                                            <table
                                                v-if="data7.group_addons.length && data7.allow_multiple != 1 && data7.max != 1"
                                                width="100%">
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
                                                width="100%">
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
                                            <h5 v-if="data7.mandatory === 1"><small>Minimum {{ data7.min }} Should be
                                                    selected</small>
                                            </h5>
                                            <table v-if="data7.group_addons.length && data7.allow_multiple == 1"
                                                width="100%">
                                                <tr v-for="(data8, index8) in data7.group_addons" :key="index8">
                                                    <td ALIGN="left" width="25%">
                                                        <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                                                            variant="outline-primary"
                                                            class="btn-icon rounded-circle add_to_cart"
                                                            :id="'size_addon_' + data7.id + '_' + data8.id"
                                                            style="border: 2px solid #00A082 !important;"
                                                            @click="add_sizeaddon(data7, data8)" :data-name="data8.name"
                                                            :data-price="data8.price" :value="data8.id">
                                                            <feather-icon icon="PlusIcon" class="ico-color" size="15" />
                                                        </b-button>
                                                        <input type="hidden" :id="'hidden_' + data7.id" value="0" />
                                                        <div class="num-block skin-2"
                                                            :id="'size_control_' + data7.id + '_' + data8.id"
                                                            style="display:none">
                                                            <div class="num-in">
                                                                <span class="minus dis"
                                                                    :id="'size_minus' + data7.id + '_' + data8.id"
                                                                    @click="decrease_size(data7, data8)"></span>
                                                                <input type="text" class="in-num" value="0" readonly=""
                                                                    style="font-weight:800;"
                                                                    :id="'size_qty_' + data7.id + '_' + data8.id"
                                                                    :data-name="data8.name" :data-price="data8.price"
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
                                    <h4>{{ $t("Addons") }}</h4>
                                </div>
                                <table v-if="data6.id == s_fq" width="100%">
                                    <tr v-for="(d6, i6) in data6.add_ons" :key="i6">
                                        <td ALIGN="left" width="25%">
                                            <b-form-checkbox :name="'saddonsOfItem_' + data6.id"
                                                :id="'addon_' + data.id + '_' + d6.id" :data-name="d6.name"
                                                :data-price="d6.price" :value="d6.id">
                                            </b-form-checkbox>
                                        </td>
                                        <td ALIGN="left" width="55%">
                                            <label :for="'addon_' + data.id + '_' + d6.id" style="font-size:16px;"> {{
                                                d6.name }}
                                            </label>
                                        </td>
                                        <td ALIGN="left" width="20%">
                                            <label :for="'addon_' + data.id + '_' + d6.id" style="font-size:16px;"
                                                v-if="d6.price > 0">
                                                {{ $store.state['defaults'].currency }}{{ d6.price | priceformat }}
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- General Addons -->
                        <!-- Group Addons -->
                        <div v-if="data.groups.length">
                            <div v-for="ga1 in data.groups" :key="ga1.id">
                                <!-- Checkbox -->
                                <div v-if="ga1.group_addons.length && ga1.allow_multiple !== 1"
                                    style="text-align: left;margin-top:30px;">
                                    <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                                    <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} Should be
                                            selected</small></h5>
                                    <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max != 1"
                                        width="100%">
                                        <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                            <td ALIGN="left" width="25%">
                                                <b-form-checkbox :name="'faddon' + ga1.id"
                                                    :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                    :data-price="ga2.price" @change="validate_sizeaddons(ga1, ga2)"
                                                    :value="ga2.id">
                                                </b-form-checkbox>
                                            </td>
                                            <td ALIGN="left" width="55%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;">
                                                    {{ ga2.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                                    v-if="ga2.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ ga2.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                    <table v-if="ga1.group_addons.length && ga1.allow_multiple !== 1 && ga1.max == 1"
                                        width="100%">
                                        <tr v-for="ga2 in ga1.group_addons" :key="ga2.id">
                                            <td ALIGN="left" width="25%">
                                                <b-form-radio :name="'faddon' + ga1.id"
                                                    :id="'size_' + ga1.id + '_' + ga2.id" :data-name="ga2.name"
                                                    :data-price="ga2.price" :value="ga2.id">
                                                </b-form-radio>
                                            </td>
                                            <td ALIGN="left" width="55%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;">
                                                    {{ ga2.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                                    v-if="ga2.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ ga2.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- Allow Multiple -->
                                <div v-else style="text-align: left;margin-top:30px;">
                                    <h4>{{ ga1.name }}<sup v-if="ga1.mandatory === 1" style="color:red"> *</sup></h4>
                                    <h5 v-if="ga1.mandatory === 1"><small>Minimum {{ ga1.min }} Should be
                                            selected</small></h5>
                                    <table v-if="ga1.group_addons.length && ga1.allow_multiple == 1" width="100%">
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
                                                    :id="'size_control_' + ga1.id + '_' + ga2.id" style="display:none">
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
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;">
                                                    {{ ga2.name }}
                                                </label>
                                            </td>
                                            <td ALIGN="left" width="20%">
                                                <label :for="'size_' + ga1.id + '_' + ga2.id" style="font-size:16px;"
                                                    v-if="ga2.price > 0">
                                                    {{ $store.state['defaults'].currency }}{{ ga2.price | priceformat }}
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Simple Addons -->
                        <div v-if="data.add_ons.length">
                            <div v-if="data.add_ons.length" style="text-align: left;margin-top:30px;">
                                <h4>{{ $t("Addons") }}</h4>
                            </div>
                            <table v-if="data.add_ons.length" width="100%">
                                <tr v-for="(data4, index4) in data.add_ons" :key="index4">
                                    <td ALIGN="left" width="25%">
                                        <b-form-checkbox :name="'addonsOfItem_' + data.id"
                                            :id="'addon_' + data.id + '_' + data4.id" :data-name="data4.name"
                                            :data-price="data4.price" :value="data4.id">
                                        </b-form-checkbox>
                                    </td>
                                    <td ALIGN="left" width="55%">
                                        <label :for="'addon_' + data.id + '_' + data4.id" style="font-size:16px;"> {{
                                            data4.name }}
                                        </label>
                                    </td>
                                    <td ALIGN="left" width="20%">
                                        <label :for="'addon_' + data.id + '_' + data4.id" style="font-size:16px;"
                                            v-if="data4.price > 0">
                                            {{ $store.state['defaults'].currency }}{{ data4.price | priceformat }}
                                        </label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </b-card-text>
        <div slot="modal-footer" class="w-100 modal_footer">
            <!-- <b-alert v-model=" showDismissibleAlert " v-height-fade.appear variant="danger" dismissible class="mb-0">
                <div class="alert-body">
                    {{ alert_text }}
                </div>
            </b-alert> -->
            <b-button class="" variant="primary" id="addCustomizedProduct" @click="check_cart(data)"
                style="width:100%;font-size:16px;font-weight:800">
                {{ $t("Add to order") }}
            </b-button>
        </div>
    </b-modal>
</template>

<script>
import {
    BButton, BModal, VBModal, BForm, BFormInput, BFormGroup, BFormCheckbox, BFormRadio, BAlert, BImgLazy, BCardText, BRow
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import Ripple from 'vue-ripple-directive'
import addCustomizedProduct from './cartfunctions.js'
import goback from './cartfunctions.js'
import loadcart from './cartfunctions.js'
import single_restaurant from './cartfunctions.js'
import open_plus from './cartfunctions.js'
import removefromcart from './cartfunctions.js'
import check_cart from './cartfunctions.js'
import store from '@@@/store'

export default {
    components: {
        BButton,
        BModal,
        BForm,
        BFormInput,
        BFormGroup,
        vSelect, BFormCheckbox, BFormRadio, BAlert, BImgLazy, BCardText, BRow
    },
    directives: {
        'b-modal': VBModal,
        Ripple,
    },
    data() {
        return {
            s_fq: '',
            showDismissibleAlert: false,
            alert_text: '',
            cart: JSON.parse(localStorage.getItem("cart")) || [],
        }
    },
    // props: {
    //     value: {
    //         default: () => { },
    //     },
    //     data: {
    //         default: () => { },
    //     },

    // },
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        data: {
            type: Object,
            default: () => ({}),
        }
    },
    // watch: {
    //     value: function () {
    //         this.$refs['image-modal'].hide()
    //     },
    // },
    watch: {
        value: function (newVal) {
            if (!newVal) {
                this.$refs['image-modal'].hide()
            }
        },
    },

    methods: {
        ...goback,
        ...addCustomizedProduct,
        ...single_restaurant,
        ...open_plus,
        ...removefromcart,
        ...loadcart,
        close_img() {
            this.$refs['image-modal'].hide()
        },
        // check_size(data, adds, group, size) {

        //     for (var i = 0; i < size.length; i++) {

        //         var element = document.getElementById("circle" + size[i].id);
        //         element.classList.remove("sel_cir");
        //     }
        //     var element = document.getElementById("circle" + data.id);
        //     element.classList.add("sel_cir");
        // },
        check_size(data, adds, group, size) {

            size.forEach(s => {
                const el = document.getElementById("circle" + s.id)
                if (el) el.classList.remove("sel_cir")
            })

            const active = document.getElementById("circle" + data.id)
            if (active) active.classList.add("sel_cir")
        }
    }
}
</script>

<style lang="scss">
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
</style>