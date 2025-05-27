<template>
    <div>
        <validation-observer ref="simpleRules">
            <b-form @submit.prevent="addProduct">
                <b-row>
                    <b-col md="12">
                        <b-col md="12" v-if="setting1.lang.value == 1">
                            <b-tabs>
                                <b-tab active @click="onTabChange(1)">
                                    <template #title>
                                        <i class="fa fa-language" aria-hidden="true"></i>
                                        <span class="d-none d-sm-inline">{{ setting1.primary.value }}</span>
                                    </template>
                                </b-tab>
                                <b-tab @click="onTabChange(2)">
                                    <template #title>
                                        <i class="fa fa-language" aria-hidden="true"></i>
                                        <span class="d-none d-sm-inline">{{ setting1.secondary.value }}</span>
                                    </template>
                                </b-tab>
                            </b-tabs>
                        </b-col>
                    </b-col>
                    <b-col lg="8" md="12" sm="12">
                        <b-row>
                            <b-col lg="6" md="12" sm="12">
                                <b-row>
                                    <b-col md="12" id="primary" style="display: block;">
                                        <b-form-group :label="$t('name')">
                                            <validation-provider #default="{ errors }" rules="required"
                                                :custom-messages="{ required: $t('The Product Name field is required') }"
                                                name="Product Name">
                                                <b-form-input id="name" :placeholder="$t('name')" v-model="product.name"
                                                    :state="errors.length > 0 ? false : null" />
                                                <small class="text-danger">{{ errors[0] }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="12" id="secondary" style="display: none;">
                                        <b-form-group :label="$t('name')">
                                            <b-form-input id="name" :placeholder="$t('name')"
                                                v-model="product.secondary_name" />
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="12" id="primary_des" style="display: block;">
                                        <b-form-group :label="$t('description')">
                                            <b-form-textarea v-model="product.description" id="textarea-default"
                                                :placeholder="$t('description')" rows="6" />
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="12" id="secondary_des" style="display: none;">
                                        <b-form-group :label="$t('description')">
                                            <b-form-textarea v-model="product.secondary_description"
                                                id="textarea-default" :placeholder="$t('description')" rows="6" />
                                        </b-form-group>
                                    </b-col>
                                    <!-- <b-col md="6">
                                        <b-form-group :label="$t('price')">
                                            <validation-provider #default="{ errors }"
                                                :custom-messages="{ required: $t('The Price field is required') }"
                                                rules="required|regex:^([.0-9]+)$" name="Price">
                                                <b-form-input id="Price" :placeholder="$t('price')" v-model="product.price"
                                                    :state="errors.length > 0 ? false : null" />
                                                <small class="text-danger">{{ errors[0] }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col> -->
                                    <!-- <b-col md="6">
                                        <b-form-group :label="$t('tax') + ' ' + $t('in') + '% *'">
                                            <validation-provider #default="{ errors }"
                                                :custom-messages="{ required: $t('The Tax field is required') }"
                                                rules="required|regex:^([.0-9]+)$" name="Tax">
                                                <b-form-input id="tax" :placeholder="$t('tax')" v-model="product.tax"
                                                    type="number" :state="errors.length > 0 ? false : null" />
                                                <small class="text-danger">{{ errors[0] }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col> -->
                                    <b-col md="6">
                                        <b-form-group :label="$t('category')">
                                            <v-select v-model="product.business_category" :options="this.bus_category"
                                                label="category_name" :reduce="(sel) => sel.id"
                                                :placeholder="$t('select') + ' ' + $t('category')"
                                                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                                @input="onChange($event)" />
                                        </b-form-group>
                                    </b-col>
                                    <!-- <b-col md="6" id="subcategory_list" v-if="layout_id != 1">
                                        <b-form-group :label="$t('subcategory')">
                                            <v-select v-model="product.category" :options="this.category"
                                                label="category_name" :reduce="(sel) => sel.id"
                                                :placeholder="$t('select') + ' ' + $t('category')"
                                                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                                                <template #list-header>
                                                    <li @click="open_catmodal"
                                                        class="add-new-client-header d-flex align-items-center my-50">
                                                        <feather-icon icon="PlusIcon" size="16" />
                                                        <span class="align-middle ml-25">
                                                            {{ $t('Add New Subcategory') }}</span>
                                                    </li>
                                                </template>
                                            </v-select>
                                        </b-form-group>
                                    </b-col> -->

                                </b-row>
                            </b-col>
                            <b-col lg="6" md="12" sm="12">
                                <b-col md="12">
                                    <span>Note: Upload only .jpg,.png,.jpeg files</span>
                                    <b-form-group :label="$t('Image')">
                                        <div v-if="url1.length">
                                            <b-row>
                                                <b-col cols="4" v-for="(imageItem, index) in url1" :key="index"
                                                    :id="'img' + index">
                                                    <div class="imgcontainer">
                                                        <b-img thumbnail
                                                            :src="url1[index] ? url1[index] : '/no_image.png'" rounded
                                                            fluid style="width: 125%;padding: 1%" alt="banner photo"
                                                            id="lazy"
                                                            :class="{ 'mt-1 featured': featured_image == index, 'mt-1': featured_image != index }"
                                                            class="mt-1" :id="'imgs' + index + 'r'" />
                                                        <div class="imgoverlay">
                                                            <div class="imgbutton">
                                                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                                                    variant="primary" class="mr-1"
                                                                    @click="feature_image(index)"
                                                                    v-if="featured_image != index">
                                                                    <feather-icon icon="CheckCircleIcon" size="16" />
                                                                </b-button>
                                                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                                                    variant="primary" class="mr-1"
                                                                    @click="remove_image(index)">
                                                                    <feather-icon icon="Trash2Icon" size="16" />
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-col>
                                            </b-row>
                                        </div>
                                        <div v-else>
                                            <b-img src="/no_image.png" rounded fluid id="profile-img"
                                                alt="profile photo" />
                                        </div>
                                    </b-form-group>
                                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" v-b-modal.modal-xl
                                        variant="primary" class="mr-1">
                                        {{ $t(' Choose a file') }}
                                    </b-button>
                                </b-col>
                                <b-col md="12" id="menu_list" v-if="layout_id == 1">
                                    <b-form-group :label="$t('menu')">
                                        <v-select v-model="product.menu" :options="this.menu" label="menu_name"
                                            :reduce="(sel) => sel.id" :placeholder="$t('select') + ' ' + $t('menu')"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                                            <template #list-header>
                                                <li @click="open_menumodal"
                                                    class="add-new-client-header d-flex align-items-center my-50">
                                                    <feather-icon icon="PlusIcon" size="16" />
                                                    <span class="align-middle ml-25">{{ $t('Add New Menu') }}</span>
                                                </li>
                                            </template>
                                        </v-select>
                                    </b-form-group>
                                </b-col>
                                <b-col md="12" id="pure_veg" v-if="layout_id == 1">
                                    <b-form-group :label="$t('pure') + ' ' + $t('veg')">
                                        <b-form-radio-group v-model="product.food_type" :options="options_veg"
                                            class="demo-inline-spacing mb-1" value-field="value" text-field="text" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="12" id="pharmacy" v-if="p_status == 1">
                                    <b-form-group :label="$t('Prescription')">
                                        <b-form-radio-group v-model="product.food_type" :options="options_veg"
                                            class="demo-inline-spacing mb-1" value-field="value" text-field="text" />
                                    </b-form-group>
                                </b-col>
                                <b-col md="12" v-if="tabIndex === 1">
                                    <b-form-group :label="$t('Grouped Attributes')">
                                        <div v-if="!list2.length">
                                            <draggable :list="list2" tag="ul" group="people"
                                                class="list-group list-group-flush cursor-move">
                                                <b-card class="text-center dotted">
                                                    <b-card-text>
                                                        Drag And Drop Here
                                                    </b-card-text>
                                                </b-card>
                                            </draggable>
                                        </div>
                                        <app-collapse accordion type="margin" v-else>
                                            <draggable :list="list2" tag="ul" group="people"
                                                class="list-group list-group-flush cursor-move">
                                                <div>
                                                    <app-collapse-item :title="listItem.name" v-for="(
                                                    listItem, index
                                                ) in list2" :key="index" tag="li">
                                                        <b-row>
                                                            <b-col md="12">
                                                                <b-form-group :label="$t('Minimum')">
                                                                    <b-form-input :id="'min' + listItem.id"
                                                                        :placeholder="$t('Minimum')"
                                                                        v-model="listItem.min" disabled />
                                                                </b-form-group>
                                                            </b-col>
                                                            <b-col md="12">
                                                                <b-form-group :label="$t('Maximum')">
                                                                    <b-form-input :id="'max' + listItem.id"
                                                                        :placeholder="$t('Maximum')"
                                                                        v-model="listItem.max" disabled />
                                                                </b-form-group>
                                                            </b-col>
                                                            <b-col md="12">
                                                                <h6>
                                                                    {{ $t("Attributes") }}
                                                                </h6>
                                                                <b-list-group>
                                                                    <b-list-group-item
                                                                        class="d-flex justify-content-between align-items-center"
                                                                        v-for="grs in listItem.group_addons"
                                                                        :key="grs.id">
                                                                        <span>{{ grs.name }}</span>
                                                                        <b-badge variant="primary" pill
                                                                            class="badge-round">
                                                                            {{ setting.value }}
                                                                            {{ grs.price }}
                                                                        </b-badge>
                                                                    </b-list-group-item>
                                                                </b-list-group>
                                                            </b-col>
                                                        </b-row>
                                                    </app-collapse-item>
                                                </div>
                                            </draggable>
                                        </app-collapse>
                                    </b-form-group>
                                </b-col>
                                <b-col md="12" v-if="tabIndex === 0">
                                    <b-form-group :label="$t('Simple Attributes')">
                                        <draggable :list="list3" tag="ul" group="people"
                                            class="list-group list-group-flush cursor-move">
                                            <div v-if="!list3.length">
                                                <b-card class="text-center dotted">
                                                    <b-card-text>
                                                        Drag And Drop Here
                                                    </b-card-text>
                                                </b-card>
                                            </div>
                                            <div v-else>
                                                <b-list-group-item v-for="(listItem, index) in list3" :key="index"
                                                    tag="li">
                                                    <div class="d-flex">
                                                        <div class="ml-25">
                                                            <b-card-text class="mb-0 font-weight-bold">
                                                                {{ listItem.name }}
                                                            </b-card-text>
                                                            <small>
                                                                {{ setting.value }}
                                                                {{
                listItem.price
            }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </b-list-group-item>
                                            </div>
                                        </draggable>
                                    </b-form-group>
                                </b-col>
                            </b-col>
                            <b-col lg="12" md="12" sm="12">
                                <b-card>
                                    <b-row>
                                        <b-col cols="9">
                                            <h4>{{ $t('Product Pricing') }} </h4>
                                        </b-col>
                                        <!-- <b-col cols="3">
                                            <b-form-group :label="$t('Split Payment')">
                                                <b-form-checkbox checked="true" class="custom-control-primary"
                                                    name="check-button" switch v-model="product.split_payment" value="1"
                                                    @change="calculate_ip($event)">
                                                    <span class="switch-icon-left">
                                                        <feather-icon icon="CheckIcon" />
                                                    </span>
                                                    <span class="switch-icon-right">
                                                        <feather-icon icon="XIcon" />
                                                    </span>
                                                </b-form-checkbox>
                                            </b-form-group>
                                        </b-col> -->
                                    </b-row>
                                    <b-row>


                                        <b-col md="6">
                                            <b-form-group :label="$t('Base') + ' ' + $t('price')">
                                                <validation-provider #default="{ errors }"
                                                    :custom-messages="{ required: $t('The Base Price field is required') }"
                                                    rules="required|regex:^([.0-9]+)$" name="Base Price">
                                                    <b-form-input id="Base Price" :placeholder="$t('Base price')"
                                                        v-model="product.bprice"
                                                        :state="errors.length > 0 ? false : null"
                                                        @input="calculate_ip($event)" />
                                                    <small class="text-danger">{{ errors[0] }}</small>
                                                </validation-provider>
                                            </b-form-group>

                                        </b-col>

                                        <b-col md="6">
                                            <b-form-group :label="$t('Selling') + ' ' + $t('price')">
                                                <validation-provider #default="{ errors }"
                                                    rules="required|between:0,@Base Price"
                                                    :custom-messages="{ required: $t('The Selling Price field is required') }"
                                                    name="Selling Price">
                                                    <b-form-input id="Selling Price" :placeholder="$t('Selling price')"
                                                        v-model="product.price"
                                                        :state="errors.length > 0 ? false : null"
                                                        @input="update_sp($event)" />
                                                    <small class="text-danger">{{ errors[0] }}</small>
                                                </validation-provider>
                                            </b-form-group>

                                        </b-col>
                                        <b-col md="6" v-if="product.split_payment == 1">
                                            <b-form-group :label="$t('Initial Payment')">
                                                <validation-provider #default="{ errors }"
                                                    :custom-messages="{ required: $t('The Initial Payment field is required') }"
                                                    rules="required|regex:^([.0-9]+)$" name="Initial Payment">
                                                    <b-form-input id="Price" :placeholder="$t('Initial Payment')"
                                                        v-model="product.initial_price"
                                                        :state="errors.length > 0 ? false : null" />
                                                    <small class="text-danger">{{ errors[0] }}</small>
                                                </validation-provider>
                                            </b-form-group>
                                        </b-col>
                                        <b-col md="6">
                                            <b-form-group :label="$t('tax') + ' ' + $t('in') + '% *'">
                                                <validation-provider #default="{ errors }"
                                                    :custom-messages="{ required: $t('The Tax field is required') }"
                                                    rules="required|regex:^([.0-9]+)$" name="Tax">
                                                    <b-form-input id="tax" :placeholder="$t('tax')"
                                                        v-model="product.tax" type="number"
                                                        :state="errors.length > 0 ? false : null" />
                                                    <small class="text-danger">{{ errors[0] }}</small>
                                                </validation-provider>
                                            </b-form-group>
                                        </b-col>
                                    </b-row>
                                </b-card>
                            </b-col>
                            <b-col lg="12" md="12" sm="12">
                                <b-col cols="12"></b-col>
                                <b-col cols="12" v-if="this.show === 0">
                                    <b-card>
                                        <b-list-group>
                                            <div v-for="(fn, index) in finalsizes" :key="index">
                                                <b-list-group-item
                                                    class="d-flex justify-content-between align-items-center">
                                                    <span>{{ fn.name }}</span>
                                                    <draggable :list="fn.size_addon" group="people"
                                                        class="mb-1 cursor-move">
                                                        <div v-if="!fn.size_addon.length">
                                                            <b-card class="text-center dotted">
                                                                <b-card-text>
                                                                    Drag And Drop Here
                                                                </b-card-text>
                                                            </b-card>
                                                        </div>
                                                        <div v-else>
                                                            <b-badge v-for="(listItem, index2) in fn.size_addon"
                                                                :key="index2" class="mr-25">
                                                                {{ listItem.name }}
                                                            </b-badge>
                                                        </div>
                                                    </draggable>
                                                    <b-badge variant="primary" pill class="badge-round">
                                                        {{ setting.value }} {{ fn.price }}
                                                    </b-badge>
                                                </b-list-group-item>
                                                <div v-if="fn.size_addon.length">
                                                    <b-card v-if="tabIndex === 0">
                                                        <b-form ref="form" class="repeater-form"
                                                            @submit.prevent="repeateAgain2">
                                                            <b-row v-for="(item2, index2) in fn.size_addon"
                                                                :id="item2.id" :key="item2.id" ref="row">
                                                                <b-col md="3">
                                                                    <b-form-group label="Variant"
                                                                        :label-for="'attribute' + index3">
                                                                        <b-form-input :id="'attribute' + index3"
                                                                            type="text"
                                                                            v-model="finalsizes[index].size_addon[index2].name"
                                                                            hidden />
                                                                        <span>{{ finalsizes[index].name }} - {{
                finalsizes[index].size_addon[index2].name
            }}</span>
                                                                    </b-form-group>
                                                                </b-col>
                                                                <b-col md="3">
                                                                    <b-form-group label="Variant Price"
                                                                        :label-for="'attributeprice' + index3">
                                                                        <b-form-input :id="'attributeprice' + index3"
                                                                            type="text"
                                                                            v-model="finalsizes[index].size_addon[index2].price" />
                                                                    </b-form-group>
                                                                </b-col>
                                                                <b-col md="3">
                                                                    <b-form-group label="Variant Stock"
                                                                        :label-for="'attributestock' + index3">
                                                                        <b-form-input :id="'attributestock' + index3"
                                                                            type="text"
                                                                            v-model="finalsizes[index].size_addon[index2].stock" />
                                                                    </b-form-group>
                                                                </b-col>
                                                            </b-row>
                                                        </b-form>
                                                    </b-card>
                                                    <b-card v-if="tabIndex === 1">
                                                        <b-form ref="form" class="repeater-form"
                                                            @submit.prevent="repeateAgain2"
                                                            v-for="(item, index2) in fn.size_addon" :id="item.id"
                                                            :key="item.id">
                                                            <b-row v-for="(item2, index3) in item.group_addons"
                                                                :id="item2.id" :key="item2.id" ref="row">
                                                                <b-col md="3">
                                                                    <b-form-group label="Variant"
                                                                        :label-for="'attribute' + index3">
                                                                        <b-form-input :id="'attribute' + index3"
                                                                            type="text"
                                                                            v-model="finalsizes[index].size_addon[index2].group_addons[index3].name"
                                                                            hidden />
                                                                        <span>{{ finalsizes[index].name }} - {{
                finalsizes[index].size_addon[index2].group_addons[index3].name
            }}</span>
                                                                    </b-form-group>
                                                                </b-col>
                                                                <b-col md="3">
                                                                    <b-form-group label="Variant Price"
                                                                        :label-for="'attributeprice' + index3">
                                                                        <b-form-input :id="'attributeprice' + index3"
                                                                            type="text"
                                                                            v-model="finalsizes[index].size_addon[index2].group_addons[index3].price" />
                                                                    </b-form-group>
                                                                </b-col>
                                                                <b-col md="3">
                                                                    <b-form-group label="Variant Stock"
                                                                        :label-for="'attributestock' + index3">
                                                                        <b-form-input :id="'attributestock' + index3"
                                                                            type="text"
                                                                            v-model="finalsizes[index].size_addon[index2].group_addons[index3].stock" />
                                                                    </b-form-group>
                                                                </b-col>
                                                            </b-row>
                                                        </b-form>
                                                    </b-card>
                                                </div>
                                            </div>
                                        </b-list-group>
                                        <b-col cols="12">
                                            <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'" variant="flat-success"
                                                class="btn-icon" style="float:right" @click="editsizes">
                                                <feather-icon icon="EditIcon" />
                                            </b-button>
                                        </b-col>
                                    </b-card>
                                </b-col>
                                <b-col md="12" v-if="this.show === 1">
                                    <div>
                                        <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                                            @submit.prevent="repeateAgain">
                                            <validation-observer ref="accountRules3" tag="form">
                                                <b-row v-for="(item, index) in items" :id="item.id" :key="item.id"
                                                    ref="row">
                                                    <b-col md="3">
                                                        <validation-provider #default="{ errors }" name="Size"
                                                            rules="required">
                                                            <b-form-group label="Size" :label-for="'size' + index">
                                                                <v-select :id="'size' + index"
                                                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                                                    label="name" :options="options"
                                                                    v-model="SelectedSize[index]">
                                                                    <template #list-header>
                                                                        <li @click="open_sizemodal"
                                                                            class="add-new-client-header d-flex align-items-center my-50">
                                                                            <feather-icon icon="PlusIcon" size="16" />
                                                                            <span class="align-middle ml-25">Add New
                                                                                Size</span>
                                                                        </li>
                                                                    </template>
                                                                </v-select>
                                                                <b-form-invalid-feedback
                                                                    :state="errors.length > 0 ? false : null">
                                                                    {{ errors[0] }}
                                                                </b-form-invalid-feedback>
                                                            </b-form-group>
                                                        </validation-provider>
                                                    </b-col>
                                                    <b-col md="2">
                                                        <validation-provider #default="{ errors }" name="Price"
                                                            rules="required">
                                                            <b-form-group label="Price"
                                                                :label-for="'food_price' + index">
                                                                <b-form-input :id="'food_price' + index" type="number"
                                                                    placeholder="Price"
                                                                    v-model="SelectedPrice[index]" />
                                                            </b-form-group>
                                                        </validation-provider>
                                                    </b-col>
                                                    <b-col md="4">
                                                        <b-form-group label="Description"
                                                            :label-for="'food_des' + index">
                                                            <b-form-input :id="'food_des' + index" type="text"
                                                                placeholder="Description"
                                                                v-model="SelectedDesc[index]" />
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col md="1">
                                                        <b-form-group label="Default" :label-for="'default' + index">
                                                            <b-form-radio :id="'default' + index"
                                                                v-model="SelectedDefault[index]" name="default"
                                                                :value=1>
                                                            </b-form-radio>
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col lg="2" md="3" class="mb-50">
                                                        <b-form-group>
                                                            <b-button v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                                                                variant="flat-success" class="btn-icon"
                                                                @click="removeItem(index)" style="margin-top: 10px;">
                                                                <feather-icon icon="XIcon" />
                                                            </b-button>
                                                        </b-form-group>
                                                    </b-col>
                                                </b-row>
                                            </validation-observer>
                                        </b-form>
                                    </div>
                                </b-col>
                                <b-col cols="12" v-if="this.show === 1">
                                    <hr />
                                </b-col>
                                <b-col cols="12" v-if="this.show === 1">
                                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                        @click="repeateAgain">
                                        <feather-icon icon="PlusIcon" class="mr-25" />
                                        <span v-if="items.length">Add Size</span>
                                        <span v-if="!items.length">Multiple Size</span>
                                    </b-button>
                                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                        style="float:right" @click="savesizes" v-if="items.length">
                                        <feather-icon icon="SaveIcon" class="mr-25" />
                                        <span>Save Sizes</span>
                                    </b-button>
                                </b-col>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col lg="4" md="12" sm="12">
                        <h4>{{ $t("Choices & Attributes") }}</h4>
                        <div class="mb-3">
                            <b-tabs align="left" v-model="tabIndex">
                                <b-tab title="Simple Attributes" @click="switch_tab(tabIndex)" id="Simple_Attributes">
                                    <draggable :list="addons" tag="ul" group="people"
                                        class="list-group list-group-flush cursor-move">
                                        <b-list-group-item v-for="(listItem, index) in addons" :key="index" tag="li">
                                            <div class="d-flex">
                                                <div class="ml-25">
                                                    <b-card-text class="mb-0 font-weight-bold">
                                                        {{ listItem.name }}
                                                    </b-card-text>
                                                    <small>
                                                        {{ setting.value }}
                                                        {{
                listItem.price
            }}</small>
                                                </div>
                                            </div>
                                        </b-list-group-item>
                                    </draggable>
                                </b-tab>
                                <b-tab title="Grouped Attributes" @click="switch_tab(tabIndex)" id="Grouped_Attributes">
                                    <app-collapse accordion type="margin">
                                        <draggable :list="addons_groups" tag="ul" group="people"
                                            class="list-group list-group-flush cursor-move">
                                            <app-collapse-item :title="listItem.name" v-for="(
                                                    listItem, index
                                                ) in addons_groups" :key="index" tag="li">
                                                <b-row>
                                                    <b-col md="12">
                                                        <dl class="row mt-1" v-if="listItem.mandatory === 0">
                                                            <dt class="col-sm-9">
                                                                {{ $t("Optional") }}
                                                            </dt>
                                                            <dd class="col-sm-3">
                                                                <span v-if="listItem.mandatory === 0">
                                                                    {{ $t("Yes") }}
                                                                </span>
                                                                <span v-else>
                                                                    {{ $t("No") }}
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                    </b-col>
                                                    <b-col md="12" v-if="listItem.mandatory === 1">
                                                        <b-form-group :label="$t('Minimum')">
                                                            <b-form-input :id="'min' + listItem.id"
                                                                :placeholder="$t('Minimum')" v-model="listItem.min"
                                                                disabled />
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col md="12" v-if="listItem.mandatory === 1">
                                                        <b-form-group :label="$t('Maximum')">
                                                            <b-form-input :id="'max' + listItem.id"
                                                                :placeholder="$t('Maximum')" v-model="listItem.max"
                                                                disabled />
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col md="12">
                                                        <h6>
                                                            {{ $t("Attributes") }}
                                                        </h6>
                                                        <b-list-group>
                                                            <b-list-group-item
                                                                class="d-flex justify-content-between align-items-center"
                                                                v-for="grs in listItem.group_addons" :key="grs.id">
                                                                <span>{{ grs.name }}</span>
                                                                <b-badge variant="primary" pill class="badge-round">
                                                                    {{ setting.value }}
                                                                    {{ grs.price }}
                                                                </b-badge>
                                                            </b-list-group-item>
                                                        </b-list-group>
                                                    </b-col>
                                                    <b-col md="12">
                                                        <dl class="row mt-1">
                                                            <dt class="col-sm-9">
                                                                {{ $t("Allow Adding Same Choice Multiple Times") }}
                                                            </dt>
                                                            <dd class="col-sm-3">
                                                                <span v-if="listItem.allow_multiple === 1">
                                                                    {{ $t("Yes") }}
                                                                </span>
                                                                <span v-else>
                                                                    {{ $t("No") }}
                                                                </span>
                                                            </dd>
                                                        </dl>
                                                    </b-col>
                                                </b-row>
                                            </app-collapse-item>
                                        </draggable>
                                    </app-collapse>
                                </b-tab>
                            </b-tabs>
                        </div>
                        <b-row class="mt-1">
                            <b-col md="12">
                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
                                    @click="open_addon">
                                    {{ $t("Add new Attributes") }}
                                </b-button>
                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1"
                                    v-if="tabIndex == 1" @click="open_groupmodal">
                                    {{ $t("Add new Group") }}
                                </b-button>
                            </b-col>
                        </b-row>
                    </b-col>
                    <!-- //Product specification -->
                    <b-col cols="12">
                        <hr>

                        <b-card>
                            <b-col md="12">
                                <b-form-group :label="$t('Tables')">
                                    <div>
                                        <div>
                                            <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                                                @submit.prevent="repeateAgain_tb">
                                                <b-row v-for="(item, index) in tables" :id="item.id" :key="item.id"
                                                    ref="row">
                                                    <b-col md="12">
                                                        <b-form-group label="Table Name" label-for="item-name">
                                                            <b-form-input id="item-name" type="text"
                                                                placeholder="Table Name" v-model="item.name" />
                                                        </b-form-group>
                                                    </b-col>
                                                    <b-col md="12">
                                                        <div>
                                                            <div>
                                                                <b-form ref="form" :style="{ height: trHeight }"
                                                                    class="repeater-form"
                                                                    @submit.prevent="repeateAgainps(index)">
                                                                    <b-row v-for="(item2, index2) in item.items"
                                                                        :id="item2.id" :key="item2.id" ref="row">
                                                                        <b-col md="4">
                                                                            <b-form-group label="Label"
                                                                                label-for="item-name">
                                                                                <b-form-input id="item-name" type="text"
                                                                                    placeholder="Label"
                                                                                    v-model="item2.label" />
                                                                            </b-form-group>
                                                                        </b-col>
                                                                        <b-col md="4">
                                                                            <b-form-group label="value"
                                                                                label-for="cost">
                                                                                <b-form-input id="cost" type="text"
                                                                                    placeholder="value"
                                                                                    v-model="item2.value" />
                                                                            </b-form-group>
                                                                        </b-col>
                                                                        <b-col lg="4" md="4" class="mb-50">
                                                                            <b-button
                                                                                v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                                                                                variant="outline-danger"
                                                                                class="mt-0 mt-md-2"
                                                                                @click="removeItem_ps(index, index2)">
                                                                                <feather-icon icon="XIcon"
                                                                                    class="mr-25" />
                                                                                <span>Delete</span>
                                                                            </b-button>
                                                                        </b-col>
                                                                        <b-col cols="12">
                                                                            <hr>
                                                                        </b-col>
                                                                    </b-row>
                                                                </b-form>
                                                            </div>
                                                            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                                                variant="primary" @click="repeateAgainps(index)">
                                                                <feather-icon icon="PlusIcon" class="mr-25" />
                                                                <span>Add New Row</span>
                                                            </b-button>
                                                        </div>
                                                    </b-col>
                                                    <b-col lg="2" md="3" class="mb-50">
                                                        <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                                                            variant="outline-danger" class="mt-0 mt-md-2"
                                                            @click="removeItem_table(index)">
                                                            <feather-icon icon="XIcon" class="mr-25" />
                                                            <span>Delete Table</span>
                                                        </b-button>
                                                    </b-col>
                                                    <b-col cols="12">
                                                        <hr>
                                                    </b-col>
                                                </b-row>
                                            </b-form>
                                        </div>
                                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                            @click="repeateAgain_tb">
                                            <feather-icon icon="PlusIcon" class="mr-25" />
                                            <span>Add New Table</span>
                                        </b-button>
                                    </div>
                                </b-form-group>
                            </b-col>
                        </b-card>
                    </b-col>
                    <b-col cols="12">
                        <hr>
                        <b-card>
                            <b-form-group :label="$t('FAQ')">
                                <div>
                                    <div>
                                        <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                                            @submit.prevent="repeateAgain_faq">
                                            <b-row v-for="(item, index) in items_faq" :id="item.id" :key="item.id"
                                                ref="row">
                                                <b-col md="12">
                                                    <b-form-group label="Question" label-for="item-name">
                                                        <b-form-input id="item-name" type="text" placeholder="Question"
                                                            v-model="items_faq[index].question" />
                                                    </b-form-group>
                                                </b-col>
                                                <b-col md="12">
                                                    <b-form-group label="Answer" label-for="cost">
                                                        <b-form-input id="cost" type="text" placeholder="Answer"
                                                            v-model="items_faq[index].answer" />
                                                    </b-form-group>
                                                </b-col>
                                                <b-col lg="4" md="4" class="mb-50">
                                                    <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                                                        variant="outline-danger" class="mt-0 mt-md-2"
                                                        @click="removeItem_faq(index)">
                                                        <feather-icon icon="XIcon" class="mr-25" />
                                                        <span>{{ $t("Delete") }} </span>
                                                    </b-button>
                                                </b-col>
                                                <b-col cols="12">
                                                    <hr>
                                                </b-col>
                                            </b-row>
                                        </b-form>
                                    </div>
                                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                        @click="repeateAgain_faq">
                                        <feather-icon icon="PlusIcon" class="mr-25" />
                                        <span>{{ $t("Add new FAQ") }} </span>
                                    </b-button>
                                </div>
                            </b-form-group>
                        </b-card>
                    </b-col>
                    <b-col class="mt-1">
                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
                            class="mr-1">
                            {{ $t("save") }}
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </validation-observer>
        <b-modal id="modal-center" centered :title="$t('Add New Size')" ok-only :ok-title="$t('Save')" ref="size-modal"
            @ok="sizem_add">
            <b-row>
                <b-col md="12">
                    <b-form-group>
                        <b-form-input id="name" :placeholder="$t('enter') +
                ' ' +
                $t('Size') +
                ' ' +
                $t('name')
                " v-model="sbrand.name" />
                        <!-- <colour-picker v-model="sbrand.name" :value="colour" label="Pick Colour" picker="sketch" /> -->

                    </b-form-group>
                </b-col>
            </b-row>
        </b-modal>
        <b-modal id="modal-center" centered :title="$t('Add New Attributes')" ok-only :ok-title="$t('Save')"
            ref="addon-modal" @ok="addon_add">
            <b-row>
                <b-col md="12">
                    <b-form-group :label="$t('Attributes') + ' ' + $t('name')">
                        <b-form-input id="name" :placeholder="$t('enter') +
                ' ' +
                $t('attributes') +
                ' ' +
                $t('name')
                " v-model="brand.name" />
                    </b-form-group>
                </b-col>
                <b-col md="12">
                    <b-form-group :label="$t('price')">
                        <b-form-input id="name" :placeholder="$t('enter') + ' ' + $t('price')" v-model="brand.price" />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-modal>
        <b-modal id="modal-center" centered :title="$t('Add New Group')" ok-only :ok-title="$t('Save')"
            ref="group-modal" @ok="group_add" @show="resetModal" @hidden="resetModal">
            <b-row>
                <b-col md="12">
                    <b-form-group :label="$t('Name')">
                        <b-form-input id="name" :placeholder="$t('Name')" v-model="abrand.name" />
                    </b-form-group>
                </b-col>
                <b-col md="12">
                    <b-form-group>
                        <b-form-radio-group v-model="optionalm" :options="optional" />
                    </b-form-group>
                </b-col>
                <b-col md="12" v-if="optionalm == 1">
                    <b-form-group :label="$t('Force Minimum')">
                        <b-form-input id="min" :placeholder="$t('Force Minimum')" v-model="abrand.min" />
                    </b-form-group>
                </b-col>
                <b-col md="12" v-if="optionalm == 1">
                    <b-form-group :label="$t('Force Maximum')">
                        <b-form-input id="max" :placeholder="$t('Force Maximum')" v-model="abrand.max" />
                    </b-form-group>
                </b-col>

                <b-col md="12">
                    <b-form-group :label="$t('Attributes')">
                        <b-form-checkbox style="margin-top: 2%" v-for="addon in addons" :key="addon.id"
                            :value="addon.id" :id="'a' + addon.id" name="gaddon_name">{{ addon.name }}
                        </b-form-checkbox>
                    </b-form-group>
                </b-col>
                <b-col md="12">
                    <b-form-group :label="$t('Allow Adding Same Choice Multiple Times')">
                        <b-form-radio-group v-model="abrand.multiple" :options="multiple" />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-modal>
        <b-modal id="modal-xl" lazy scrollable title="Select Image" size="xl">
            <b-tabs v-model="tabIndexcr" pills>
                <!-- Tab: Account -->
                <b-tab>
                    <template #title>
                        <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
                        <span class="d-none d-sm-inline">Select File </span>
                    </template>
                    <div>
                        <b-row>
                            <b-col cols="3" v-for="(img, index) in imgs" :key="index">
                                <b-img :src="img" rounded fluid style="width: 75%;padding: 1%;max-height:100px"
                                    alt="banner photo" @click="myMethod(img)" id="lazy" />
                            </b-col>
                        </b-row>
                    </div>
                </b-tab>
                <b-tab>
                    <template #title>
                        <feather-icon icon="FilePlusIcon" size="16" class="mr-0 mr-sm-50" />
                        <span class="d-none d-sm-inline">Crop Image</span>
                    </template>
                    <div>
                        <b-row>
                            <b-col md="4"></b-col>
                            <b-col md="4">
                                <label></label>
                                <b-form-file id="new_img" v-model="file" placeholder="Choose a file or drop it here..."
                                    drop-placeholder="Drop file here..." @change="onFileChange2"
                                    accept=".jpg, .png, .jpeg" />
                            </b-col>
                            <b-col md="4"></b-col>
                            <b-col md="12">
                                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;"
                                    id="crop_js">
                                    <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="1 / 1"
                                        :restore="false" :crop-box-movable="false" :crop-box-resizable="false"
                                        :toggle-drag-mode-on-dblclick="false" :scalable="true" :background="true"
                                        :rotatable="true" :src="imgSrc" view-mode="1">
                                    </vue-cropper>
                                </div>
                                <b-col md="12" style="display: flex;justify-content: center;margin-top: 20px;">
                                    <br />
                                </b-col>
                            </b-col>
                        </b-row>
                    </div>
                </b-tab>
            </b-tabs>
            <template #modal-footer v-if="tabIndexcr == 1">
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
                    @click="cropImage" style=" display: none;" id="crop_btn">Crop
                    Image</b-button>
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
                    @click="rotate" style=" display: none;" id="rotate_btn">Rotate</b-button>
            </template>
        </b-modal>
        <b-modal id="modal-center" centered :title="$t('Add New Menu')" ok-only :ok-title="$t('Save')" ref="menu-modal"
            @hidden="resetsModal" @show="resetsModal" @ok="menu_add">
            <b-row>
                <b-col md="12">
                    <b-form-group :label="$t('Menu Name')">
                        <b-form-input :placeholder="$t('Menu name')" v-model="smenu.menu_name" />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-modal>
        <b-modal id="modal-center" centered :title="$t('Add New Subcategory')" ok-only :ok-title="$t('Save')"
            ref="cat-modal" @hidden="resetsModal" @show="resetsModal" @ok="cat_add">
            <b-row>
                <b-col md="6">
                    <b-form-group :label="$t('category')">
                        <v-select v-model="scat.business_category_id" :options="this.bus_category" label="category_name"
                            :reduce="(sel) => sel.id" :placeholder="$t('select') + ' ' + $t('category')"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('Subcategory')">
                        <b-form-input :placeholder="$t('Subcategory')" v-model="scat.category_name" />
                    </b-form-group>
                </b-col>
                <b-col md="6">
                    <b-form-group :label="$t('sub category image')">
                        <b-form-file v-model="scat.category_image" placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..." accept=".jpg, .png, .jpeg" />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-modal>
    </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import AppCollapse from "@core/components/app-collapse/AppCollapse.vue";
import AppCollapseItem from "@core/components/app-collapse/AppCollapseItem.vue";
import draggable from "vuedraggable";
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import { heightTransition } from "@core/mixins/ui/transition";
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
    required, email, confirmed, url, between, alpha, integer, password, min, digits, alphaDash, length,
} from '@validations'
import {
    BRow,
    BCol,
    BFormGroup,
    BImg,
    BMedia,
    BFormTags,
    BFormTag,
    BFormRadioGroup,
    BAvatar,
    BFormInput,
    BFormCheckbox,
    BFormTextarea,
    BForm,
    BButton,
    BFormSelect,
    BCardText,
    BCard,
    BFormFile,
    BBadge,
    BListGroup,
    BListGroupItem,
    BTabs,
    BTab,
    BFormRadio, BFormInvalidFeedback,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import ColourPicker from 'vue-colour-picker'
export default {
    components: {
        BCardCode,
        BRow,
        BMedia,
        BAvatar,
        BFormTags,
        BFormTag,
        BImg,
        BFormTextarea,
        BCol,
        BFormGroup,
        BFormInput,
        BFormCheckbox,
        BForm,
        BFormRadioGroup,
        BButton,
        BFormSelect,
        BCardText,
        BCard,
        vSelect,
        BFormFile,
        AppCollapse,
        AppCollapseItem,
        BBadge,
        BListGroup,
        BListGroupItem,
        draggable,
        BTabs,
        BTab, BFormInvalidFeedback,
        BFormRadio, VueCropper, ValidationProvider, ValidationObserver, ColourPicker
    },
    computed: {
    },
    mounted() {
        this.initTrHeight();
    },
    destroyed() {
        window.removeEventListener("resize", this.initTrHeight);
        this.$store.commit(
            "verticalMenu/UPDATE_VERTICAL_MENU_COLLAPSED",
            false
        );
    },
    directives: {
        Ripple,
    },
    mixins: [heightTransition],
    data() {
        return {
            selectedItem: null,
            product: {
                'image': [],
                'existing': [],
                'split_payment': '0',
                'initial_price': 0,
                'price': 0,
                'bprice': 0,
            },
            rows: [],
            user_info: {},
            category: [],
            options_veg: [
                { text: "No", value: 0 },
                { text: "Yes", value: 1 },
            ],
            options_status: [
                { text: "Enable", value: 1 },
                { text: "Disable", value: 0 },
            ],
            options_stock: [
                { text: "In Stock", value: 0 },
                { text: "Out Of Stock", value: 1 },
            ],
            multiple: [
                { text: "Yes", value: 1 },
                { text: "No", value: 0 },
            ],
            optional: [
                { text: "Mandatory", value: 1 },
                { text: "Optional", value: 0 },
            ],
            inputText: [],
            addons: [],
            addons_groups: [],
            addon_name: [],
            bus_category: [],
            options: [],
            value: [],
            url1: [],
            menu: [],
            imgs: [],
            imgSrc: require('../../../assets/image.jpg'),
            cropImg: '',
            brand: {},
            sbrand: {},
            smenu: {},
            scat: {},
            setting: {},
            setting1: {},
            abrand: {},
            items1: [{
                id: 1,
                selected: 'male',
                selected1: 'designer',
                prevHeight: 0,
            }],
            items: [],
            items2: [],
            items_pr: [],
            items_faq: [],
            nextTodoId: 2,
            nextTodoId_pr: 0,
            nextTodoId_faq: 0,
            edit_gr: {},
            list1: [],
            list2: [],
            list3: [],
            size_addon: {},
            optionalm: 0,
            tabIndex: 0,
            Selected: 'A',
            SelectedSize: [],
            SelectedPrice: [],
            SelectedDesc: [],
            SelectedDefault: [],
            finalsizes: [],
            show: 1,
            file: '',
            layout_type: 1,
            aspectRatio: "1/1",
            p_status: 0,
            layout_id: 1,
            tabIndexcr: 0,
            featured_image: 0,
            ps: [{}],
            colour: '#000000',
            tables: [{
                name: '',
                items: [{
                    label: '',
                    value: ''
                }]
            }]
        };
    },
    methods: {
        update_sp(event) {
            if (this.product.split_payment == 1) {
                this.product.initial_price = (this.product.price) / 100 * this.split_value;
            }
        },
        calculate_ip(event) {
            this.product.price = this.product.bprice
            if (this.product.split_payment == 1) {
                this.product.initial_price = (this.product.bprice) / 100 * this.split_value;
            }

        },
        feature_image(image_id) {
            this.featured_image = image_id
        },
        remove_image(image_id) {
            console.log(image_id)
            if (this.featured_image == image_id) {
                this.featured_image = 0
            }
            this.url1.splice(image_id, 1);
            this.product.image.splice(image_id, 1);

        },
        resetsModal() {

        },
        change() {
            this.$refs.cropper.replace(this.imgSrc);
        },
        cropImage() {
            var ind = this.product.image.length
            this.url1.push(this.$refs.cropper.getCroppedCanvas({
                width: 1200,
                height: 628
            }).toDataURL());
            this.product.image.push(this.$refs.cropper.getCroppedCanvas().toDataURL());
            this.product.existing[ind] = 2;
            this.tabIndex = 0;
            this.$bvModal.hide('modal-xl');
        },
        rotate() {
            this.$refs.cropper.rotate(90);
        },
        open_sizemodal() {
            this.$refs["size-modal"].show();
        },
        sizem_add() {
            let brand3 = new FormData();
            brand3.append("name", this.sbrand.name);
            brand3.append("res_id", localStorage.id);

            this.$http
                .post("/store/store_size", brand3)
                .then((response) => {
                    if (response.data.status == true) {
                        this.$http.get("/store/food_quantity/" + localStorage.id).then((res) => {
                            this.options = res.data;
                        });
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "success",
                            },
                        });
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "danger",
                            },
                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
        editsizes() {
            this.show = 1
        },
        savesizes() {
            return new Promise((resolve, reject) => {
                this.$refs.accountRules3.validate().then(success => {
                    if (success) {
                        this.finalsizes = []
                        for (var i = 0; i < this.items.length; i++) {
                            this.finalsizes[i] = this.SelectedSize[i]
                            this.finalsizes[i].price = this.SelectedPrice[i]
                            this.finalsizes[i].desc = this.SelectedDesc[i]
                            if (!this.finalsizes[i].size_addon) {
                                this.finalsizes[i].size_addon = []
                            }
                        }
                        this.show = 0
                        resolve(true)
                    } else {
                        reject()
                    }
                })
            })
        },
        switch_tab(ind) {
            if (ind == 1) {
                this.list3 = [];
                if (this.finalsizes.length > 0) {
                    for (var i = 0; i < this.items.length; i++) {
                        this.finalsizes[i].size_addon = []
                    }
                }
                this.$http
                    .post("/store/get_addons_groups", this.user_info)
                    .then((res) => {
                        this.addons_groups = res.data;
                        if (this.addons_groups.length !== 0 && this.addons_groups.length > 5) {
                            document.getElementById("Grouped_Attributes").style = "height: 300px;overflow: scroll;"
                        }
                    });
            } else {
                this.list2 = [];
                if (this.finalsizes.length > 0) {
                    for (var i = 0; i < this.items.length; i++) {
                        this.finalsizes[i].size_addon = []
                    }
                }
                this.$http
                    .post("/store/addons_list", this.user_info)
                    .then((res) => {
                        this.addons = res.data;
                        if (this.addons.length !== 0 && this.addons.length > 5) {
                            document.getElementById("Simple_Attributes").style = "height: 300px;overflow: scroll;"
                        }
                    });
            }
        },
        resetModal() {
            this.abrand = {};
        },
        edit_group(gr) {
            this.$refs["group-modal"].show();
            this.abrand = gr;
            for (var i = 0; i < this.abrand.group_addons.length; i++) {
                let id = this.abrand.group_addons[i].id;
                var timer = setInterval(function () {
                    if (document.getElementById("a" + id)) {
                        document.getElementById("a" + id).checked = true;
                    }
                    clearInterval(timer);
                }, 1000);
            }
        },
        open_groupmodal() {
            if (this.addons.length <= 0) {
                this.$toast({
                    component: ToastificationContent,
                    position: "bottom-right",
                    props: {
                        title: "Add addons to create groups",
                        icon: "CheckIcon",
                        variant: "danger",
                    },
                });
                return
            }
            this.$refs["group-modal"].show();
            this.abrand.multiple = 0;
        },
        group_add() {
            let brand3 = new FormData();
            brand3.append("name", this.abrand.name);
            brand3.append("res_id", localStorage.id);
            brand3.append("min", this.abrand.min);
            brand3.append("max", this.abrand.max);
            brand3.append("multiple", this.abrand.multiple);
            brand3.append("mandatory", this.optionalm);
            let checkboxes = document.querySelectorAll(
                'input[name="gaddon_name"]:checked'
            );
            let values = [];
            checkboxes.forEach((checkbox) => {
                values.push(checkbox.value);
            });
            brand3.append("add_ons", values);
            this.$http
                .post("/store/store_agroups", brand3)
                .then((response) => {
                    if (response.data.status == true) {
                        this.$http
                            .post("/store/get_addons_groups", this.user_info)
                            .then((res) => {
                                if (!this.list2.length) {
                                    this.addons_groups = res.data;
                                    if (this.addons_groups.length !== 0 && this.addons_groups.length > 5) {
                                        document.getElementById("Grouped_Attributes").style = "height: 300px;overflow: scroll;"
                                    }
                                } else {
                                    for (var ii = 0; ii < this.list2.length; ii++) {
                                        for (var jj = 0; jj < res.data.length; jj++) {
                                            if (this.list2[ii].id == res.data[jj].id) {
                                                res.data.splice(jj, 1);
                                            }
                                        }
                                    }
                                    this.addons_groups = res.data;
                                    if (this.addons_groups.length !== 0 && this.addons_groups.length > 5) {
                                        document.getElementById("Grouped_Attributes").style = "height: 300px;overflow: scroll;"
                                    }
                                }
                            });
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "success",
                            },
                        });
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "danger",
                            },
                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
        open_addon() {
            this.$refs["addon-modal"].show();
        },
        open_menumodal() {
            this.$refs["menu-modal"].show();
        },
        open_catmodal() {
            this.$refs["cat-modal"].show();
        },
        cat_add() {
            this.scat.res_id = this.user_info.id;
            this.scat.category_secondaryname = '';
            this.scat.status = '1';
            this.scat.restaurant_id = this.user_info.id;
            this.$http
                .post("/store/store_cat", this.scat)
                .then((response) => {
                    if (response.data.status == true) {
                        this.$refs["cat-modal"].hide();
                        this.$http.get("/store/category_list/" + this.user_info.id).then((res) => {
                            this.category = res.data;
                        });
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "success",
                            },
                        });
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "warning",
                            },
                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
        menu_add() {
            this.smenu.res_id = this.user_info.id;
            this.smenu.secondary_menu_name = '';
            this.$http
                .post("/store/store_menu", this.smenu)
                .then((response) => {
                    if (response.data.status == true) {
                        this.$refs["menu-modal"].hide();
                        this.$http.post('/store/menu_list', this.user_info)
                            .then(res => {
                                this.menu = res.data.data;
                            })
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "success",
                            },
                        });
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "warning",
                            },
                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
        addon_add() {
            let brand2 = new FormData();
            brand2.append("id", this.brand.id);
            brand2.append("name", this.brand.name);
            brand2.append("restaurant", localStorage.id);
            brand2.append("price", this.brand.price);
            this.$http
                .post("/store/store_addons", brand2)
                .then((response) => {
                    if (response.data.status == true) {
                        this.$refs["addon-modal"].hide();
                        this.$http
                            .post("/store/addons_list", this.user_info)
                            .then((res) => {
                                if (!this.list3.length) {
                                    this.addons = res.data;
                                    if (this.addons.length !== 0 && this.addons.length > 5) {
                                        document.getElementById("Simple_Attributes").style = "height: 300px;overflow: scroll;"
                                    }
                                } else {
                                    for (var ii = 0; ii < this.list3.length; ii++) {
                                        for (var jj = 0; jj < res.data.length; jj++) {
                                            if (this.list3[ii].id == res.data[jj].id) {
                                                res.data.splice(jj, 1);
                                            }
                                        }
                                    }
                                    this.addons = res.data;
                                    if (this.addons.length !== 0 && this.addons.length > 5) {
                                        document.getElementById("Simple_Attributes").style = "height: 300px;overflow: scroll;"
                                    }
                                }
                            });
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "success",
                            },
                        });
                    } else {
                        this.$toast({
                            component: ToastificationContent,
                            position: "bottom-right",
                            props: {
                                title: response.data.message,
                                icon: "CheckIcon",
                                variant: "warning",
                            },
                        });
                    }
                })
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));
        },
        addProduct() {
            this.$refs.simpleRules.validate().then(success => {
                if (success) {
                    let product1 = new FormData();
                    let size = this.finalsizes;
                    for (var k = 0; k < this.finalsizes.length; k++) {
                        product1.append(
                            "food_quantity_id[" + k + "]", this.finalsizes[k].id
                        );
                        product1.append(
                            "food_quantity_price[" + k + "]", this.finalsizes[k].price
                        );
                        product1.append(
                            "food_quantity_desc[" + k + "]", this.finalsizes[k].desc
                        );
                        if (this.tabIndex == 1) {
                            let groups1 = this.finalsizes[k].size_addon;
                            let sgroups1 = [];
                            if (groups1 && groups1.length) {
                                groups1.forEach((group1) => {
                                    console.log(group1)
                                    sgroups1.push(JSON.stringify(group1));

                                });
                                product1.append("size_addons[" + k + "]", JSON.stringify(sgroups1));
                            } else {
                                product1.append("size_addons[" + k + "]", []);
                            }
                        } else if (this.tabIndex == 0) {
                            let groups11 = this.finalsizes[k].size_addon;
                            let values1 = [];
                            if (groups11 && groups11.length) {
                                groups11.forEach((group12) => {
                                    values1.push(JSON.stringify(group12));
                                });
                                product1.append("size_addons[" + k + "]", JSON.stringify(values1));
                            } else {
                                product1.append("size_addons[" + k + "]", []);
                            }
                        }
                    }
                    if (this.tabIndex == 1) {
                        let groups = this.list2;
                        let sgroups = [];
                        groups.forEach((group) => {
                            sgroups.push(group.id);
                        });
                        this.product.groups = sgroups;
                    } else if (this.tabIndex == 0) {
                        let groups2 = this.list3;
                        let values = [];
                        groups2.forEach((group) => {
                            values.push(group.id);
                        });
                        this.product.add_ons = values;
                    }
                    product1.append("res_id", localStorage.id);
                    product1.append("name", this.product.name);
                    product1.append("namesecondary", this.product.secondary_name ? this.product.secondary_name : '');
                    product1.append("description", this.product.description ? this.product.description : '');
                    product1.append("descriptionsecondary", this.product.secondary_description ? this.product.secondary_description : '');
                    product1.append("category", this.product.category ? this.product.category : '');
                    product1.append("business_category_id", this.product.business_category ? this.product.business_category : '');
                    product1.append("menu", this.product.menu ? this.product.menu : '');
                    product1.append("status", 1);
                    product1.append("stockstatus", 0);
                    product1.append("price", this.product.price);
                    product1.append("tax", this.product.tax);
                    product1.append("food_type", this.product.food_type);
                    product1.append("image", JSON.stringify(this.product.image));
                    product1.append("food_quantity", this.value);
                    product1.append("add_ons", this.product.add_ons);
                    product1.append("packaging_charge", 0);
                    product1.append("groups", this.product.groups);
                    product1.append("addon_type", this.tabIndex);
                    product1.append('existing', JSON.stringify(this.product.existing));
                    product1.append('featured_image', this.featured_image);
                    product1.append('specification', JSON.stringify(this.tables));
                    product1.append('faq', JSON.stringify(this.items_faq));
                    product1.append("bprice", this.product.bprice);

                    product1.append("initial_price", this.product.initial_price);
                    product1.append("split_payment", this.product.split_payment);

                    this.$http
                        .post("/store/add_to_product", product1)
                        .then((response) => {
                            if (response.data.status == true) {
                                this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: response.data.message,
                                        icon: 'BellIcon',
                                        variant: 'success',
                                    },
                                })
                                this.$router.push({ name: "product_list" })
                            } else {
                                this.$toast({
                                    component: ToastificationContent,
                                    props: {
                                        title: response.data.message,
                                        icon: 'BellIcon',
                                        variant: 'warning',
                                    },
                                })
                            }
                        })
                        .catch((error) => console.log(error))
                        .finally(() => (this.loading = false));
                }
            })
        },
        popToast(response) {
            const h = this.$createElement;
            const vNodesMsg = h("p", { class: ["text-center", "mb-0"] }, [
                response.data.message,
            ]);
            this.$bvToast.toast([vNodesMsg], {
                solid: false,
                variant: "info",
            });
        },
        cuisinesMethod() {
            this.product.addons = this.addon_name;
        },

        repeateAgain_tb() {
            this.tables.push({
                id: (this.nextTodoId_pr += this.nextTodoId_pr),
                items: [{
                    'label': '',
                    'value': ''
                }]
            });
            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        repeateAgainps(index) {
            this.tables[index].items.push({
                id: (this.nextTodoId_pr += this.nextTodoId_pr),
            });
            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        repeateAgain_faq() {
            this.items_faq.push({
                id: (this.nextTodoId_faq += this.nextTodoId_faq),
            });
            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        repeateAgain() {
            this.items.push({
                id: (this.nextTodoId += this.nextTodoId),
            });
            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        repeateAgain2() {
            this.items2.push({
                id: (this.nextTodoId += this.nextTodoId),
            });
            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight);
            });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.SelectedSize.splice(index, 1);
            this.SelectedPrice.splice(index, 1);
            this.SelectedDesc.splice(index, 1);
            this.SelectedDefault.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        removeItem_table(index) {
            this.tables.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        removeItem_ps(index, index2) {
            console.log(typeof this.tables[index].items[index2]);
            console.log(index2);

            this.tables[index].items.splice(index2, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        removeItem_faq(index) {
            this.items_faq.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
        initTrHeight() {
            this.trSetHeight(null);
            this.$nextTick(() => {
                this.trSetHeight(this.$refs.form.scrollHeight);
            });
        },
        onChange(event) {
            this.$http.get("/store/category_list_one/" + event + '/' + localStorage.id)
                .then((res) => {
                    this.category = res.data;
                });
        },
        onFileChange1(e) {
            const file = e.target.files[0];
            this.product.image = file;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.url1 = reader.result;
            };
            this.product.existing = 3;
            this.$bvModal.hide('modal-xl');
        },
        myMethod(image) {
            var ind = this.product.image.length
            this.url1.push(image);
            var filename = image.replace(/^.*[\\\/]/, '');
            this.product.image.push(image);
            this.product.existing[ind] = 1;
            this.$bvModal.hide('modal-xl');
        },
        onFileChange2(e) {
            const file = e.target.files[0];
            var img_size = e.target.files[0].size;
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.imgSrc = reader.result;
                this.change();
            };
            document.getElementById('crop_js').style.display = 'block';
            document.getElementById('crop_btn').style.display = 'block';
            document.getElementById('rotate_btn').style.display = 'block';
        },
        onTabChange(id) {
            if (id == 1) {
                document.getElementById("primary").style.display = 'block';
                document.getElementById("secondary").style.display = 'none';
                document.getElementById("primary_des").style.display = 'block';
                document.getElementById("secondary_des").style.display = 'none';
            }
            else {
                document.getElementById("primary").style.display = 'none';
                document.getElementById("secondary").style.display = 'block';
                document.getElementById("primary_des").style.display = 'none';
                document.getElementById("secondary_des").style.display = 'block';
            }
        },
        showModal(index) {
            this.items.splice(index, 1);
            this.trTrimHeight(this.$refs.row[0].offsetHeight);
        },
    },
    created() {
        this.$store.commit("verticalMenu/UPDATE_VERTICAL_MENU_COLLAPSED", true);
        this.$http.get('/store/get_lang')
            .then(res => {
                this.setting1 = res.data
            })
        if (this.$route.params.id) {
            this.$http
                .get("/store/edit_addons/" + this.$route.params.id)
                .then((res) => {
                    this.brand = res.data.message;
                });
        }
        this.user_info.id = localStorage.id;
        this.$http.post('/store/menu_list', this.user_info)
            .then(res => {
                this.menu = res.data.data;
            })
        this.$http.post("/store/addons_list", this.user_info).then((res) => {
            this.addons = res.data;
            if (this.addons.length !== 0 && this.addons.length > 5) {
                document.getElementById("Simple_Attributes").style = "height: 300px;overflow: scroll;"
            }
        });
        this.$http.get("/store/food_quantity/" + localStorage.id).then((res) => {
            var names = [];
            res.data.forEach(function (number) {
                names.push(number.name);
            });
            this.options = res.data;
        });
        this.$http
            .post("/store/get_addons_groups", this.user_info)
            .then((res) => {
                this.addons_groups = res.data;
                if (this.addons_groups.length !== 0 && this.addons_groups.length > 5) {
                    document.getElementById("Grouped_Attributes").style = "height: 300px;overflow: scroll;"
                }
            });
        this.$http.get("/store/business_category_list/" + localStorage.id).then((res) => {
            this.bus_category = res.data;
        });
        this.$http.get('/store/product_imgs/' + localStorage.id)
            .then(res => {
                this.imgs = res.data
                console.log(this.imgs);
            })
        this.$http.get('/store/business_type/' + localStorage.id)
            .then(res => {
                if (res.data.layout_id == 2) {
                    this.layout_id = res.data.layout_id
                    if (res.data.p_status == 1) {
                        this.p_status = 1
                    }
                }
                else {
                    this.layout_id = res.data.layout_id
                }
            })
    },
};
</script>
<style lang="scss">
@import "~@resources/scss/vue/libs/vue-wizard.scss";
@import "~@resources/scss/vue/libs/vue-select.scss";
@import "~@resources/scss/vue/pages/page-profile.scss";
@import '~@resources/scss/base/bootstrap-extended/_variables.scss';

#profile-img {
    height: 8.92rem;
    width: 8.92rem;
}

#banner-img {
    width: 9.92rem;
}

.add-new-client-header {
    padding: $options-padding-y $options-padding-x;
    color: $success;
    cursor: pointer;
}

.dotted {
    border-style: dotted;
}

.imgoverlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0);
    transition: background 0.5s ease;
    display: flex !important;
}

.imgcontainer:hover .imgoverlay {
    display: block;
    background: rgba(0, 0, 0, .3);
}

.imgbutton {
    width: 500px;
    height: 50px;
    justify-content: center;
    align-self: center;
    opacity: 0;
    transition: opacity 0.35s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.imgbutton a {
    width: 200px;
    padding: 12px 48px;
    text-align: center;
    color: white;
    border: solid 2px white;
    z-index: 1;
}

.imgcontainer:hover .imgbutton {
    opacity: 1;
}

.featured {
    border-style: solid !important;
    border-color: $primary !important;
    border-width: thick !important;
}
</style>
