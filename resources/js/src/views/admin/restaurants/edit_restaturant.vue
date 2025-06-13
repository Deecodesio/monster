<template>
  <b-row>
    <b-col cols="12">
      <div>
        <b-card>

          <b-tabs pills>

            <b-tab active>
              <template #title>
                <feather-icon icon="UserIcon" size="16" class="mr-0 mr-sm-50" />
                <span class="d-none d-sm-inline">{{ $t('Store') }} {{ $t('details') }} </span>
                <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
              </template>
              <div>
                <validation-observer ref="accountRules" tag="form">
                  <b-row>
                    <b-form @submit.prevent="restaurant">
                      <input id="banner_image" hidden="hidden" ref="onFileChange" type="text" class="d-none"
                        v-b-modal.modal-xl />
                      <input id="logo_image" hidden="hidden" ref="onFileChange1" type="text" class="d-none"
                        v-b-modal.modal-xl1 />
                      <b-col md="12">
                        <div id="user-profile">
                          <b-card id="cover_img" class="profile-header mb-2"
                            :img-src="url ? url : require('@/assets/images/banner/empty-banner.png')" img-top
                            alt="cover photo" body-class="p-0" rounded fluid>
                            <div class="position-relative">
                              <div class="profile-img-container d-flex align-items-center">
                                <div class="profile-img">
                                  <b-form-input id="banner_id" hidden="hidden" type="text" value="1" />
                                  <b-form-input id="logo_id" hidden="hidden" type="text" value="1" />
                                  <input id="res_id" name="res_id" hidden="hidden" type="text" />
                                  <b-img id="profile"
                                    :src="url1 ? url1 : require('@/assets/images/banner/empty-logo.png')" rounded fluid
                                    alt="profile photo" @click="$refs.onFileChange1.click()" />
                                </div>
                              </div>
                            </div>
                            <div class="profile-header-nav">
                              <b-navbar toggleable="md" type="light">
                                <b-navbar-toggle class="ml-auto" target="nav-text-collapse">
                                  <feather-icon icon="AlignJustifyIcon" size="21" />
                                </b-navbar-toggle>
                                <b-collapse id="nav-text-collapse" is-nav>
                                  <b-tabs pills class="profile-tabs mt-1 mt-md-0" nav-class="mb-0">
                                    <template #tabs-end>
                                      <!-- <b-dropdown id="dropdown-dropleft" v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                        :text="$t('edit') + ' ' + $t('cover') + ' ' + $t('photo')" variant="primary"
                                        class="ml-auto">
                                        <b-dropdown-item>
                                          <a @click="$refs.onFileChange.click()">{{ $t('update') }} {{ $t('photo') }} </a>
                                        </b-dropdown-item>
                                      </b-dropdown> -->
                                      <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                        @click="$refs.onFileChange.click()" class="ml-auto">
                                        <feather-icon icon="PlusIcon" class="mr-25" />
                                        <span>{{ $t('Update') + ' ' + $t('cover') + ' ' + $t('photo') }}</span>
                                      </b-button>
                                    </template>
                                  </b-tabs>
                                </b-collapse>
                              </b-navbar>
                            </div>
                          </b-card>
                        </div>
                      </b-col>
                      <b-col md="12" v-if="setting.lang.value == 1">
                        <b-tabs>
                          <b-tab active @click="onTabChange(1)">
                            <template #title>
                              <i class="fa fa-language" aria-hidden="true"></i>
                              <span class="d-none d-sm-inline">{{ setting.primary.value }}</span>
                            </template>
                          </b-tab>
                          <!-- Tab: Information -->
                          <b-tab @click="onTabChange(2)">
                            <template #title>
                              <i class="fa fa-language" aria-hidden="true"></i>
                              <span class="d-none d-sm-inline">{{ setting.secondary.value }}</span>
                            </template>
                          </b-tab>
                        </b-tabs>
                      </b-col>
                      <b-col lg="12" md="12" sm="12">
                        <b-row>
                          <b-col lg="6" md="12" sm="12">
                            <b-row>
                              <b-col md="12" v-if="business_type.length > 1">
                                <b-form-group :label="$t('Business') + ' ' + $t('type')" label-for="name">
                                  <v-select v-model="restaurants.business_type" :options="this.business_type" label="name"
                                    :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('Business')"
                                    @input="business($event)" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

                                </b-form-group>
                              </b-col>
                              <b-col md="12" id="primary" v-if="primary_name">
                                <b-form-group :label="$t('Store') + ' ' + $t('name')" label-for="name">
                                  <validation-provider #default="{ errors }" name="Name" rules="required"
                                    :custom-messages="{ required: $t('The Name field is required') }">
                                    <b-form-input id="name" v-model="restaurants.restaurant_name" type="text"
                                      :state="errors.length > 0 ? false : null"
                                      :placeholder="$t('enter') + ' ' + $t('Store') + ' ' + $t('name')" />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>
                              <b-col md="12" id="secondary" v-if="!primary_name">
                                <b-form-group :label="$t('Store') + ' ' + $t('name')" label-for="name">
                                  <b-form-input id="name" v-model="restaurants.restaurant_secondary_name" type="text"
                                    :placeholder="$t('enter') + ' ' + $t('Store') + ' ' + $t('name')" />
                                </b-form-group>
                              </b-col>
                              <b-col md="12">
                                <b-form-group :label="$t('email') + '*'">
                                  <validation-provider #default="{ errors }" name="email" rules="required|email"
                                    :custom-messages="{ required: $t('The email field is required') }">
                                    <b-form-input id="email" v-model="restaurants.email" type="email"
                                      :state="errors.length > 0 ? false : null"
                                      :placeholder="$t('enter') + ' ' + $t('email')" />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>
                              <b-col md="12">
                                <b-form-group :label="$t('password')" label-for="password">
                                  <validation-provider #default="{ errors }" name="Password" vid="Password"
                                    :custom-messages="{ required: $t('The Password field is required') }"
                                    rules="required">
                                    <b-form-input id="password" v-model="restaurants.password" type="text"
                                      :state="errors.length > 0 ? false : null" :placeholder="$t('password')"
                                      autocomplete="off" />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>
                              <b-col md="12">
                                <validation-provider #default="{ errors }" name="City" rules="required">
                                  <b-form-group :label="$t('select') + ' ' + $t('city') + '*'" label-for="city"
                                    :state="errors.length > 0 ? false : null">
                                    <v-select id="city" v-model="restaurants.city"
                                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="cities" label="city"
                                      :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('city')"
                                      autocomplete="off" @input="onChange($event)">
                                      <template #list-header>
                                        <li @click="open_citymodal"
                                          class="add-new-client-header d-flex align-items-center my-50">
                                          <feather-icon icon="PlusIcon" size="16" />
                                          <span class="align-middle ml-25">{{ $t('Add New City') }}</span>
                                        </li>
                                      </template>
                                    </v-select>
                                    <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                                      {{ errors[0] }}
                                    </b-form-invalid-feedback>
                                  </b-form-group>
                                </validation-provider>
                              </b-col>
                              <b-col md="12">
                                <validation-provider #default="{ errors }" name="Area" rules="required">
                                  <b-form-group :label="$t('select') + ' ' + $t('area') + '*'" label-for="area"
                                    :state="errors.length > 0 ? false : null">
                                    <v-select id="area" v-model="restaurants.area"
                                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="areas" label="area"
                                      :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('area')">
                                      <template #list-header>
                                        <li @click="open_areamodal"
                                          class="add-new-client-header d-flex align-items-center my-50">
                                          <feather-icon icon="PlusIcon" size="16" />
                                          <span class="align-middle ml-25">{{ $t('Add New Area') }}</span>
                                        </li>
                                      </template>
                                    </v-select>
                                    <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                                      {{ errors[0] }}
                                    </b-form-invalid-feedback>
                                  </b-form-group>
                                </validation-provider>
                              </b-col>
                              <b-col md="12">
                                <b-form-group :label="$t('mobile') + ' ' + $t('number') + '*'">
                                  <validation-provider #default="{ errors }" name="mobile number" rules="required"
                                    :custom-messages="{ required: $t('The number field is required') }">
                                    <VuePhoneNumberInput id="phone1" no-example @update="updatePhoneNumber"
                                      v-model="restaurants.phone" :state="errors[0] ? false : (valid ? true : null)"
                                      :fetch-country=true :default-country-code="restaurants.phone_country_code">
                                    </VuePhoneNumberInput>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>

                              <b-col md="12">
                                <b-form-group :label="$t('contact') + ' ' + $t('number') + '*'">
                                  <validation-provider #default="{ errors }" name="contact number" rules="required"
                                    :custom-messages="{ required: $t('The number field is required') }">
                                    <VuePhoneNumberInput id="phone1" no-example @update="updatePhoneNumber2"
                                      v-model="restaurants.contact" :state="errors[0] ? false : (valid ? true : null)"
                                      :fetch-country=true :default-country-code="restaurants.contact_country_code">
                                    </VuePhoneNumberInput>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>
                              <b-col md="12">
                                <validation-provider #default="{ errors }" name="Delivery Time" rules="required">
                                  <b-form-group
                                    :label="$t('estimated') + ' ' + $t('delivery') + ' ' + $t('Time') + '(' + $t('mins') + ')' + '*'"
                                    label-for="deliveryTime" :state="errors.length > 0 ? false : null">
                                    <v-select id="deliveryTime" v-model="restaurants.estimated_delivery_time"
                                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="deliveryTime"
                                      label="text" :reduce="sel => sel.text" />
                                    <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                                      {{ errors[0] }}
                                    </b-form-invalid-feedback>
                                  </b-form-group>
                                </validation-provider>
                              </b-col>
                              <b-col md="12" v-if="isLayout_package_types">
                                <b-form-group :label="$t('packaging') + ' ' + $t('charge') + '(%)*'">
                                  <validation-provider #default="{ errors }" name="Packaging charge" rules="required"
                                    :custom-messages="{ required: $t('The Packaging charge field is required') }">
                                    <b-form-input v-model="restaurants.packaging_charge"
                                      :state="errors.length > 0 ? false : null" type="number" />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>
                              <b-col md="12">
                                <b-form-group :label="$t('geofence') + ' ' + $t('radius')">
                                  <validation-provider #default="{ errors }" name="Geofence Radius" rules="required"
                                    :custom-messages="{ required: $t('The Geofence Radius field is required') }">
                                    <b-form-input v-model="restaurants.GeofenceRadius"
                                      :state="errors.length > 0 ? false : null" type="number" />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                  </validation-provider>
                                </b-form-group>
                              </b-col>
                              <b-col md="12" v-if="isLayout_deliver_types">
                                <b-form-group :label="$t('delivery') + ' ' + $t('type') + '*'">
                                  <b-form-checkbox style="margin-top: 2%;" v-for="option in options_check"
                                    :key="option.value" :value="option.value" :id="'del' + option.value"
                                    v-model="restaurants.delivery_type" name="delivery_name">{{ option.text
                                    }}</b-form-checkbox>
                                </b-form-group>
                              </b-col>
                              <b-col md="12" v-if="isLayout_video_type">
                                <b-form-group :label="$t('Video') + ' ' + $t('Reviews')">
                                  <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                                    @submit.prevent="repeateAgain" style="margin-top: 2%;">
                                    <b-row v-for="(item, index) in items" :key="index" ref="row">
                                      <b-col md="5">
                                        <b-form-group :label="$t('video') + ' ' + $t('titles')">
                                          <b-form-input :id="'title' + index" type="text"
                                            v-model="SelectedTitle[index]" />
                                        </b-form-group>
                                      </b-col>
                                      <b-col md="5">
                                        <b-form-group :label="$t('video') + ' ' + $t('url')">
                                          <validation-provider #default="{ errors }" name="Video URL" rules="url">
                                            <b-form-input :id="'url' + index" type="text" v-model="SelectedUrl[index]"
                                              :state="errors.length > 0 ? false : null" />
                                            <small class="text-danger">{{ errors[0] }}</small>
                                          </validation-provider>
                                        </b-form-group>
                                      </b-col>
                                      <b-col lg="2" md="3" class="mb-50">
                                      </b-col>
                                      <b-col cols="12">
                                        <hr>
                                      </b-col>
                                    </b-row>
                                  </b-form>
                                </b-form-group>
                                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                  @click="repeateAgain">
                                  <feather-icon icon="PlusIcon" class="mr-25" />
                                  <span>{{ $t('add') }} {{ $t('video') }}</span>
                                </b-button>
                                <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="danger" @click="removeItem()">
                                  <feather-icon icon="XIcon" class="mr-25" />
                                  <span>{{ $t('remove') }} {{ $t('video') }}</span>
                                </b-button>
                              </b-col>
                            </b-row>
                          </b-col>
                          <b-col lg="6" md="12" sm="12">
                            <b-col md="12">
                              <b-form-group :label="$t('address') + '*'">
                                <validation-provider #default="{ errors }" name="Address" rules="required"
                                  :custom-messages="{ required: $t('The Address field is required') }">
                                  <b-form-input v-model="restaurants.address" :state="errors.length > 0 ? false : null"
                                    :placeholder="$t('enter') + ' ' + $t('address')" id="searchMadminnput"
                                    autocomplete="off" />
                                  <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                                <br />
                                <input type="hidden" id="latitude" name="latitude" :value="def_lat">
                                <input type="hidden" id="longitude" name="longitude" :value="def_lng">
                                <div id="gmap"></div>
                              </b-form-group>
                            </b-col>
                            <b-col md="12">
                            </b-col>
                            <b-col md="12">
                              <b-form-group :label="$t('admin') + ' ' + $t('commision') + '%'">
                                <validation-provider #default="{ errors }" name="Commision" rules="required"
                                  :custom-messages="{ required: $t('The Commision field is required') }">
                                  <b-form-input v-model="restaurants.admin_commision"
                                    :state="errors.length > 0 ? false : null" placeholder="0" type="number" />
                                  <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                              </b-form-group>
                            </b-col>
                            <b-col md="12" v-if="isLayout_brand_types">
                              <b-form-group :label="$t('brand')">
                                <v-select v-model="restaurants.brands" :options="this.brand" label="name"
                                  :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('brand')"
                                  class="brand_name" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
                                  <template #list-header>
                                    <li @click="open_brandmodal"
                                      class="add-new-client-header d-flex align-items-center my-50">
                                      <feather-icon icon="PlusIcon" size="16" />
                                      <span class="align-middle ml-25">{{ $t('Add New Brand') }}</span>
                                    </li>
                                  </template>
                                </v-select>
                              </b-form-group>
                            </b-col>
                            <b-col md="12">
                              <b-form-group :label="$t('status') + '*'">
                                <v-select v-model="restaurants.status" :options="options" label="text"
                                  :reduce="sel => sel.value" :placeholder="$t('select status')"
                                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                              </b-form-group>
                            </b-col>

                            <b-col md="6" v-if="isLayout_cuisines">
                              <b-form-group :label="$t('cuisines') + '*'">
                                <b-form-checkbox style="margin-top: 2%;" v-for="cuisines1 in cuisines" :key="cuisines1.id"
                                  :value="cuisines1.id" :id="'cus' + cuisines1.id" name="cuisines_name"
                                  v-model="restaurants.cuisine_ids">{{ cuisines1.name }}
                                </b-form-checkbox>
                              </b-form-group>
                            </b-col>
                            <b-col md="12" v-if="isLayout_Carrier_type">
                              <b-form-group :label="$t('Carrier Type')">
                                <v-select v-model="restaurants.carrier_type" :options="this.carrier_type"
                                  label="package_name" :reduce="sel => sel.id"
                                  :placeholder="$t('select') + ' ' + $t('Carrier Type')" class="carrier_type" multiple
                                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                              </b-form-group>
                            </b-col>
                            <b-col md="12" v-if="isLayout_tax">
                              <b-form-group :label="$t('Tax') + '%'">
                                <validation-provider #default="{ errors }" name="Tax">
                                  <b-form-input v-model="restaurants.tax" :state="errors.length > 0 ? false : null"
                                    placeholder="0" type="number" />
                                  <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                              </b-form-group>
                            </b-col>
                            <b-col md="12">
                              <b-form-group :label="$t('Cart') + '*'">
                                <v-select v-model="restaurants.cart" :options="cart_options" label="text"
                                  :reduce="sel => sel.value" :placeholder="$t('select Cart status')"
                                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                              </b-form-group>
                            </b-col>
                            <!-- <b-col md="12">
                              <validation-provider #default="{ errors }" name="Delivering States" rules="required">
                                <b-form-group :label="$t('select') + ' ' + $t('Delivering States') + '*'" label-for="city"
                                  :state="errors.length > 0 ? false : null">
                                  <v-select id="city" v-model="restaurants.delivery_states"
                                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="state_list"
                                    label="state" :reduce="sel => sel.id"
                                    :placeholder="$t('select') + ' ' + $t('Delivering States')" autocomplete="off"
                                    multiple>

                                  </v-select>
                                  <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                                    {{ errors[0] }}
                                  </b-form-invalid-feedback>
                                </b-form-group>
                              </validation-provider>
                            </b-col> -->


                          </b-col>
                        </b-row>
                        <b-row class="mt-2">
                          <b-col style="text-align: end;">
                            <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" type="submit">
                              {{ $t('save') }}
                            </b-button>
                          </b-col>
                        </b-row>
                      </b-col>
                    </b-form>
                  </b-row>
                </validation-observer>
              </div>
            </b-tab>
            <b-tab>
              <template #title>
                <feather-icon icon="ClockIcon" size="16" class="mr-0 mr-sm-50" />
                <span class="d-none d-sm-inline">{{ $t('Store') }} {{ $t('timing') }}</span>
                <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
              </template>
              <div>
                <validation-observer ref="infoRules" tag="form">
                  <b-form @submit.prevent="Restaurant_Timing">
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group :label="$t('day')" style="font-weight: bold;">
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <b-form-group :label="$t('Store') + ' ' + $t('opens') + '*'" style="font-weight: bold;">
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <b-form-group :label="$t('Store') + ' ' + $t('closes') + '*'" style="font-weight: bold;">
                        </b-form-group>
                      </b-col>
                      <b-col md="2">
                        <b-form-group :label="$t('is') + ' ' + $t('holiday') + '?'" style="font-weight: bold;">
                        </b-form-group>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_1') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder11'" style="margin-bottom: 3%;" v-model="opening_time[0]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder31'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[0]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder21'" style="margin-bottom: 3%;" v-model="closing_time[0]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder41'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[0]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday1'" value="1" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_2') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder12'" style="margin-bottom: 3%;" v-model="opening_time[1]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder32'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[1]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder22'" style="margin-bottom: 3%;" v-model="closing_time[1]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder42'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[1]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday2'" value="2" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_3') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder13'" style="margin-bottom: 3%;" v-model="opening_time[2]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder33'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[2]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder23'" style="margin-bottom: 3%;" v-model="closing_time[2]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder43'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[2]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday3'" value="3" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_4') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder14'" style="margin-bottom: 3%;" v-model="opening_time[3]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder34'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[3]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder24'" style="margin-bottom: 3%;" v-model="closing_time[3]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder44'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[3]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday4'" value="4" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_5') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder15'" style="margin-bottom: 3%;" v-model="opening_time[4]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder35'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[4]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder25'" style="margin-bottom: 3%;" v-model="closing_time[4]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder45'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[4]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday5'" value="5" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_6') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder16'" style="margin-bottom: 3%;" v-model="opening_time[5]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder36'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[5]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder26'" style="margin-bottom: 3%;" v-model="closing_time[5]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder46'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[5]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday6'" value="6" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row style="margin: 2%;">
                      <b-col md="2">
                        <b-form-group>
                          <label> {{ $t('weekday_7') }}</label>
                        </b-form-group>
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder17'" style="margin-bottom: 3%;" v-model="opening_time[6]"
                          class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder37'" style="margin-bottom: 3%;"
                          v-model="opening_second_time[6]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="4">
                        <flat-pickr :id="'timepicker-placeholder27'" style="margin-bottom: 3%;" v-model="closing_time[6]"
                          class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                        <flat-pickr :id="'timepicker-placeholder47'" style="margin-bottom: 3%;"
                          v-model="closing_second_time[6]" class="form-control"
                          :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
                          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                      </b-col>
                      <b-col md="2">
                        <b-form-checkbox :id="'holiday7'" value="7" name="holidays">
                        </b-form-checkbox>
                      </b-col>
                    </b-row>
                    <b-row class="mt-2">
                      <b-col style="text-align: end;">
                        <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" type="submit">
                          {{ $t('save') }}
                        </b-button>
                      </b-col>
                    </b-row>
                  </b-form>
                </validation-observer>
              </div>
            </b-tab>
            <b-tab>
              <template #title>
                <feather-icon icon="FileTextIcon" size="16" class="mr-0 mr-sm-50" />
                <span class="d-none d-sm-inline">{{ $t('document') }}</span>
                <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
              </template>
              <div>
                <validation-observer ref="addressRules" tag="form">
                  <b-form @submit.prevent="Document">
                    <div v-for="item in document_view" :key="item.id">
                      <b-row v-if="item.document_for == 2">
                        <b-col md="6">
                          <b-form-group :label="item.document_name">
                            <b-form-file :id="'document' + item.id" @change="onFileChange3($event, item.id)"
                              accept=".jpg, .png, .jpeg" />
                          </b-form-group>
                          <b-form-group>
                            <img :id="'document_img' + item.id" rounded fluid class="banner-img" alt="banner photo"
                              src="/no_image.png" />
                          </b-form-group>
                        </b-col>
                        <b-col md="6" v-if="item.expiry_date_needed == 1">
                          <b-form-group :label="item.document_name + 'Expiry Date'">
                            <b-form-datepicker :id="'dates' + item.id" v-model="doc_date[item.id]" />
                            <b-form-input :id="'date' + item.id" hidden="hidden" :value=doc_date[item.id]
                              :key="item.id" />
                          </b-form-group>
                        </b-col>
                      </b-row>
                    </div>
                    <b-row class="mt-2">
                      <b-col style="text-align: end;">
                        <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" type="submit">
                          {{ $t('save') }}
                        </b-button>
                      </b-col>
                    </b-row>
                  </b-form>
                </validation-observer>
              </div>
            </b-tab>
            <b-tab>
              <template #title>
                <feather-icon icon="LinkIcon" size="16" class="mr-0 mr-sm-50" />
                <span class="d-none d-sm-inline">{{ $t('bank') }} {{ $t('details') }}</span>
                <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
              </template>
              <div>
                <validation-observer ref="accountRules3" tag="form">
                  <b-form @submit.prevent="bank_details">
                    <b-row>
                      <b-col md="6">
                        <b-form-group :label="$t('account') + ' ' + $t('name') + '*'">
                          <validation-provider #default="{ errors }" name="Account Name" rules="required"
                            :custom-messages="{ required: $t('The Account Name field is required') }">
                            <b-form-input v-model="bank.account_name"
                              :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('name')"
                              :state="errors.length > 0 ? false : null" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('bank') + ' ' + $t('name') + '*'">
                          <validation-provider #default="{ errors }" name="Bank Name" rules="required"
                            :custom-messages="{ required: $t('The Bank Name field is required') }">
                            <b-form-input v-model="bank.bank_name" :state="errors.length > 0 ? false : null"
                              :placeholder="$t('enter') + ' ' + $t('bank') + ' ' + $t('name')" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('account') + ' ' + $t('address') + '*'">
                          <validation-provider #default="{ errors }" name="Account Address" rules="required"
                            :custom-messages="{ required: $t('The Account Address field is required') }">
                            <b-form-input v-model="bank.account_address"
                              :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('address')"
                              :state="errors.length > 0 ? false : null" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('branch') + ' ' + $t('name') + '*'">
                          <validation-provider #default="{ errors }" name="Branch Name" rules="required"
                            :custom-messages="{ required: $t('The Branch Name field is required') }">
                            <b-form-input v-model="bank.branch_name" :state="errors.length > 0 ? false : null"
                              :placeholder="$t('enter') + ' ' + $t('branch') + ' ' + $t('name')" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('Account Number') + ' / ' + $t('IBAN Number') + '*'">
                          <validation-provider #default="{ errors }" name="Account Number / IBAN Number" rules="required"
                            :custom-messages="{ required: $t('The Account Number / IBAN Number field is required') }">

                            <b-form-input v-model="bank.account_no"
                              :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('number') + ' / ' + $t('IBAN Number')"
                              :state="errors.length > 0 ? false : null" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('branch') + ' ' + $t('address') + '*'">
                          <validation-provider #default="{ errors }" name="Branch Address" rules="required"
                            :custom-messages="{ required: $t('The Branch Address field is required') }">
                            <b-form-input id="Address" v-model="bank.branch_address"
                              :state="errors.length > 0 ? false : null"
                              :placeholder="$t('enter') + ' ' + $t('branch') + ' ' + $t('address')" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('swift') + ' ' + $t('code')">
                          <b-form-input v-model="bank.swift_code"
                            :placeholder="$t('enter') + ' ' + $t('swift') + ' ' + $t('code')" />
                        </b-form-group>
                      </b-col>
                      <b-col md="6">
                        <b-form-group :label="$t('routing') + ' ' + $t('number')">
                          <b-form-input v-model="bank.routing_no"
                            :placeholder="$t('enter') + ' ' + $t('routing') + ' ' + $t('number')" />
                        </b-form-group>
                      </b-col>
                    </b-row>
                    <b-row class="mt-2">
                      <b-col style="text-align: end;">
                        <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" type="submit">
                          {{ $t('save') }}
                        </b-button>
                      </b-col>
                    </b-row>
                  </b-form>
                </validation-observer>
              </div>
            </b-tab>
            <b-tab>
              <template #title>
                <feather-icon icon="DollarSignIcon" size="16" class="mr-0 mr-sm-50" />
                <span class="d-none d-sm-inline">{{ $t('delivery') }} {{ $t('charge') }}</span>
                <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
              </template>
              <div>
                <b-form @submit.prevent="delivery_charge">
                  <b-row>
                    <b-col md="12" v-if="isLayout_delivery_charge">
                      <b-form-group :label="$t('delivery') + ' ' + $t('charge') + ' ' + $t('based') + ' ' + $t('on')">
                        <b-form-radio-group v-model="dev_change.deliveryChargeBasedOn" :options="charge" />
                      </b-form-group>
                    </b-col>
                    <b-col md="12" v-if="!isLayout_delivery_charge">
                      <b-form-group :label="$t('delivery') + ' ' + $t('charge') + ' ' + $t('based') + ' ' + $t('on')">
                        <b-form-radio-group v-model="dev_change.deliveryChargeBasedOn" :options="charge1" />
                      </b-form-group>
                    </b-col>
                    <!-- <b-col md="12">
                    <label class="col-form-label">{{ $t("Taxes") }}</label>
                    <b-row>
                      <b-col lg="6" md="6" sm="12" v-for="(tx, tx_index) in taxes" :key="tx_index">
                        <b-form-group :label="tx.name">
                          <validation-provider #default="{ errors }" rules="required|regex:^([.0-9]+)$" :name="tx.name">
                            <b-form-input :id="tx.id" :placeholder="tx.tax" :state="errors.length > 0 ? false : null"
                              v-model="selectedtax[tx.id]" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                    </b-row>
                  </b-col> -->
                    <b-col md="12" v-if="isLayout_delivery_charge">
                      <label class="col-form-label">{{ $t("Delivery Charges") }}</label>
                      <div>
                        <b-form ref="form1" :style="{ height: trHeight1 }" class="repeater-form1">
                          <b-row v-for="(items, index) in dc_limits" :id="items.id" :key="items.id" ref="row1">
                            <b-col md="4">
                              <b-form-group :label="$t('minimum')">
                                <b-form-input :id="'minimum' + index" type="text" :placeholder="$t('minimum')"
                                  @keyup.native="blockSpecialChar_one(index)" v-model="items.Minimum" />
                              </b-form-group>
                            </b-col>
                            <b-col md="2">
                              <b-form-group :label="$t('maximum')">
                                <b-form-input :id="'maximum' + index" type="number" :placeholder="$t('maximum')" min="0"
                                  @keyup.native="blockSpecialChar(index)" v-model="items.Maximum" />
                              </b-form-group>
                            </b-col>
                            <b-col md="2">
                              <b-form-group :label="$t('value')">
                                <b-form-input :id="'value' + index" type="number"
                                  :placeholder="$t('enter') + ' ' + $t('value')" v-model="items.Value" />
                              </b-form-group>
                            </b-col>
                            <b-col lg="2" md="1">
                              <b-form-group :label="$t('type')">
                                <b-form-select :id="'type' + index" :options="type_delivery"
                                  :placeholder="$t('select') + ' ' + $t('type')" :value="type_delivery.value"
                                  v-model="items.Type" />
                              </b-form-group>
                            </b-col>
                            <b-col lg="2" md="3" class="mb-50">
                            </b-col>
                            <b-col cols="12">
                              <hr>
                            </b-col>
                          </b-row>
                        </b-form>
                      </div>
                      <div class="deliveryChargeMsg" id="deliveryChargeMsg" style="display: none;">
                        <span style="color:red">{{ $t('maximum') }} {{ $t('value') }} {{ $t('must') }} {{ $t('be') }}
                          {{ $t('greater') }} {{ $t('than') }} {{ $t('minimum') }} {{ $t('value') }}<br>
                        </span>
                      </div>
                      <div class="deliveryChargeMsg" id="deliveryChargeMsg1" style="display: none;">
                        <span style="color:red">{{ $t('minimum') }} {{ $t('value') }} {{ $t('must') }} {{ $t('be') }}
                          {{ $t('greater') }} {{ $t('than') }} {{ $t('before') }} {{ $t('maximum') }} {{ $t('value')
                          }}<br>
                        </span>
                      </div>
                      <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain_new"
                        id="add">
                        <feather-icon icon="PlusIcon" class="mr-25" />
                        <span>{{ $t('add') }} {{ $t('new') }} {{ $t('row') }}</span>
                      </b-button>
                      <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="danger" @click="removeItem_new(index)">
                        <feather-icon icon="XIcon" class="mr-25" />
                        <span> {{ $t('remove') }} {{ $t('last') }} {{ $t('row') }}</span>
                      </b-button>
                    </b-col>
                    <b-col md="12" v-if="!isLayout_delivery_charge">
                      <b-tabs align="left" content-class="mt-1" justified v-if="options_carrier.length !== 0">
                        <div v-for="(carrier, index) in options_carrier" :id="carrier.id" :key="carrier.id">
                          <b-tab :title="carrier.package_name">
                            <b-col md="12">
                              <div>
                                <b-form ref="form1" :style="{ height: trHeight1 }" class="repeater-form1">
                                  <b-row v-for="(items, index) in carrier.invoice" :id="items.id" :key="items.id"
                                    ref="row1">
                                    <b-col md="4">
                                      <b-form-group :label="$t('minimum')">
                                        <b-form-input :id="'minimum' + carrier.id + index" type="text"
                                          :placeholder="$t('minimum')" @keyup.native="blockSpecialChar_one(index)"
                                          v-model="items.Minimum" />
                                      </b-form-group>
                                    </b-col>
                                    <b-col md="2">
                                      <b-form-group :label="$t('maximum')">
                                        <b-form-input :id="'maximum' + carrier.id + index" type="number"
                                          :placeholder="$t('maximum')" min="0" @keyup.native="blockSpecialChar(index)"
                                          v-model="items.Maximum" />
                                      </b-form-group>
                                    </b-col>
                                    <b-col md="2">
                                      <b-form-group :label="$t('value')">
                                        <b-form-input :id="'value' + carrier.id + index" type="number"
                                          :placeholder="$t('enter') + ' ' + $t('value')" v-model="items.Value" />
                                      </b-form-group>
                                    </b-col>
                                    <b-col lg="2" md="1">
                                      <b-form-group :label="$t('type')">
                                        <b-form-select :id="'type' + carrier.id + index" :options="type_delivery"
                                          :placeholder="$t('select') + ' ' + $t('type')" :value="type_delivery.value"
                                          v-model="items.Type" />
                                      </b-form-group>
                                    </b-col>
                                    <b-col lg="2" md="3" class="mb-50">
                                    </b-col>
                                    <b-col cols="12">
                                      <hr>
                                    </b-col>
                                  </b-row>
                                </b-form>
                              </div>
                              <div class="deliveryChargeMsg" id="deliveryChargeMsg" style="display: none;">
                                <span style="color:red">{{ $t('maximum') }} {{ $t('value') }} {{ $t('must') }} {{ $t('be')
                                }}
                                  {{ $t('greater') }} {{ $t('than') }} {{ $t('minimum') }} {{ $t('value') }}<br></span>
                              </div>
                              <div class="deliveryChargeMsg" id="deliveryChargeMsg1" style="display: none;">
                                <span style="color:red">{{ $t('minimum') }} {{ $t('value') }} {{ $t('must') }} {{ $t('be')
                                }}
                                  {{ $t('greater') }} {{ $t('than') }} {{ $t('before') }} {{ $t('maximum') }} {{
                                    $t('value') }}<br></span>
                              </div>
                              <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                @click="repeateAgain_new_1(carrier.id, index)" id="add">
                                <feather-icon icon="PlusIcon" class="mr-25" />
                                <span>{{ $t('add') }} {{ $t('new') }} {{ $t('row') }}</span>
                              </b-button>
                              <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="danger"
                                @click="removeItem_new_1(index)">
                                <feather-icon icon="XIcon" class="mr-25" />
                                <span> {{ $t('remove') }} {{ $t('last') }} {{ $t('row') }}</span>
                              </b-button>
                            </b-col>
                          </b-tab>
                        </div>
                      </b-tabs>
                      <b-tabs align="left" content-class="mt-1" justified id="carrier_id" style="display:none" v-else>
                        <div class="custom-search d-flex justify-content-start">
                          <b-button variant="primary" :to="{ name: 'add_carrier' }" style="margin-left: 10px;">
                            {{ $t('Please add carrier type') }}
                          </b-button>
                        </div>
                      </b-tabs>
                    </b-col>
                  </b-row>
                  <b-row class="mt-2">
                    <b-col style="text-align: end;">
                      <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" type="submit">
                        {{ $t('save') }}
                      </b-button>
                    </b-col>
                  </b-row>
                </b-form>
              </div>
            </b-tab>
            <b-tab>
              <template #title>
                <feather-icon icon="FileTextIcon" size="16" class="mr-0 mr-sm-50" />
                <span class="d-none d-sm-inline">{{ $t('Plan Details') }}</span>
                <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
              </template>
              <div>
                <validation-observer ref="addressRules" tag="form">
                  <b-form @submit.prevent="add_plan_func">
                    <b-col md="12">
                      <b-form-group :label="$t('Plan')">
                        <v-select v-model="add_plan.plan" :options="plans" label="name" :reduce="sel => sel.id"
                          :placeholder="$t('select plan')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                      </b-form-group>
                    </b-col>
                    <b-row class="mt-2">
                      <b-col style="text-align: end;">
                        <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" type="submit">
                          {{ $t('save') }}
                        </b-button>
                      </b-col>
                    </b-row>
                  </b-form>
                </validation-observer>
              </div>
            </b-tab>
          </b-tabs>
        </b-card>
      </div>
    </b-col>


    <!-- banner Image Upload -->
    <b-modal id="modal-xl" lazy scrollable :title="$t('Upload Store Banner')" size="xl">
      <b-tabs v-model="tabIndex" pills>
        <b-tab>
          <template #title>
            <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">{{ $t('Select File') }} </span>
          </template>
          <div>
            <b-row>
              <b-col cols=3 v-for="(banners, index) in banners" :key="index">
                <b-img :src="banners" rounded fluid style="width: 125%;padding: 1%" alt="banner photo"
                  @click="myMethod(banners)" id="lazy" />
              </b-col>
            </b-row>
          </div>
        </b-tab>
        <b-tab>
          <template #title>
            <feather-icon icon="FilePlusIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">{{ $t('Crop Image') }}</span>
          </template>
          <div>
            <b-row>
              <b-col md="4"></b-col>
              <b-col md="4">
                <label></label>
                <b-form-file id="new_img" v-model="file" :placeholder="$t('Choose a file or drop it here...')"
                  drop-placeholder="Drop file here..." @change="onFileChange_banner" accept=".jpg, .png, .jpeg" />
              </b-col>
              <b-col md="4"></b-col>
              <b-col md="12">
                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
                  <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="3 / 1" :restore="false"
                    :crop-box-movable="false" :crop-box-resizable="false" :toggle-drag-mode-on-dblclick="false"
                    :scalable="true" :background="true" :rotatable="true" :src="imgSrc" view-mode="1">
                  </vue-cropper>
                </div>
              </b-col>
            </b-row>
          </div>
        </b-tab>
      </b-tabs>
      <template #modal-footer v-if="tabIndex == 1">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1" @click="cropImage"
          id="crop_btn" style=" display: none;">{{ $t('Crop Image') }}
        </b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-1" @click="rotate"
          id="rotate_btn" style=" display: none;">{{ $t('Rotate') }}
        </b-button>
      </template>
    </b-modal>


    <!-- Logo Image Upload -->
    <b-modal id="modal-xl1" lazy scrollable :title="$t('Upload Store Logo')" size="xl">
      <b-tabs v-model="tabIndex" pills>
        <b-tab>
          <template #title>
            <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">{{ $t('Select File') }} </span>
          </template>
          <div>
            <b-row>
              <b-col cols="3" v-for="(images_logo, index) in images_logo" :key="index">
                <b-img :src="images_logo" rounded fluid style="width: 75%;padding: 1%" alt="banner photo"
                  @click="myMethod1(images_logo)" id="lazy" />
              </b-col>
            </b-row>
          </div>
        </b-tab>
        <b-tab>
          <template #title>
            <feather-icon icon="FilePlusIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">{{ $t('Crop Image') }}</span>
          </template>
          <div>
            <b-row>
              <b-col md="4"></b-col>
              <b-col md="4">
                <label></label>
                <b-form-file id="new_img" v-model="file" :placeholder="$t('Choose a file or drop it here...')"
                  drop-placeholder="Drop file here..." @change="onFileChange_image" accept=".jpg, .png, .jpeg" />
              </b-col>
              <b-col md="4"></b-col>
              <b-col md="12">
                <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
                  <vue-cropper ref='cropper' :guides="false" drag-mode="move" :aspectRatio="1 / 1" :restore="false"
                    :crop-box-movable="false" :crop-box-resizable="false" :toggle-drag-mode-on-dblclick="false"
                    :scalable="true" :background="true" :rotatable="true" :src="imgSrc" view-mode="1">
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
      <template #modal-footer v-if="tabIndex == 1">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
          @click="cropImage1" style=" display: none;" id="crop_btn">{{ $t('Crop Image') }}</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="rotate1"
          style=" display: none;" id="rotate_btn">{{ $t('Rotate') }}</b-button>
      </template>
    </b-modal>
    <b-modal id="modal-center" centered :title="$t('Add New Brand')" ok-only :ok-title="$t('Save')" ref="brand-modal"
      @hidden="resetsModal" @show="resetsModal" @ok="brand_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('brand name')">
            <b-form-input id="name" :placeholder="$t('brand name')" v-model="brand1.name" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select id="country" v-model="brand1.status" :options="options_brand" label="text"
              :reduce="sel => sel.value" :placeholder="$t('select status')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :required="!brand.status" />
          </b-form-group>
        </b-col>
      </b-row>
    </b-modal>

    <b-modal id="modal-center" centered :title="$t('Add New City')" ok-only :ok-title="$t('Save')" ref="city-modal"
      @hidden="resetsModal" @show="resetsModal" @ok="city_add">
      <b-row>
        <b-col md="12">
          <b-tabs>
            <b-tab active @click="onTabChange_city(1)">
              <template #title>
                <i class="fa fa-language" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">{{ setting.primary.value }}</span>
              </template>
            </b-tab>
            <b-tab @click="onTabChange_city(2)">
              <template #title>
                <i class="fa fa-language" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">{{ setting.secondary.value }}</span>
              </template>
            </b-tab>
          </b-tabs>
        </b-col>
        <b-col md="6" v-if="!isHidden2">
          <b-form-group :label="$t('city')">
            <b-form-input id="searchMadminnput" :placeholder="$t('city')" v-model="citys.city" />
          </b-form-group>
        </b-col>
        <b-col md="6" v-if="isHidden2">
          <b-form-group :label="$t('city')">
            <b-form-input id="searchMadminnput" :placeholder="$t('city')" v-model="citys.second_city" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select v-model="citys.status" :options="options_status" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('country')">
            <v-select id="country" v-model="citys.country" :options="country" label="country" :reduce="sel => sel.id"
              :placeholder="$t('select country')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              @input="onChange_city($event)">
              <template #list-header>
                <li @click="open_countrymodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New Country') }}</span>
                </li>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('state')">
            <v-select id="country" v-model="citys.state" :options="this.state" label="state" :reduce="sel => sel.id"
              :placeholder="$t('select state')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
              <template #list-header>
                <li @click="open_statemodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New State') }}</span>
                </li>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-modal>

    <b-modal id="modal-center" centered :title="$t('Add New Area')" ok-only :ok-title="$t('Save')" ref="area-modal"
      @hidden="resetsModal" @show="resetsModal" @ok="area_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('city')">
            <v-select v-model="areas1.city" :options="cities" label="city" :reduce="sel => sel.id"
              :placeholder="$t('select') + ' ' + $t('city')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'">
              <template #list-header>
                <li @click="open_citymodal" class="add-new-client-header d-flex align-items-center my-50">
                  <feather-icon icon="PlusIcon" size="16" />
                  <span class="align-middle ml-25">{{ $t('Add New City') }}</span>
                </li>
              </template>
            </v-select>
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('status')">
            <v-select v-model="areas1.status" :options="options_status1" label="text" :reduce="sel => sel.value"
              :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('area')">
            <b-form-input id="searchMadminnput_area" :placeholder="$t('area')" v-model="areas1.area" />
            <br />
            <input type="hidden" id="selectedadd">
            <input type="hidden" id="latitude_area" name="latitude" :value="def_lat">
            <input type="hidden" id="longitude_area" name="longitude" :value="def_lng">
            <div id="gmap1"></div>
          </b-form-group>
        </b-col>
      </b-row>
    </b-modal>

    <b-modal id="modal-center" centered :title="$t('Add New Country')" ok-only :ok-title="$t('Save')" ref="country-modal"
      @hidden="resetsModal" @show="resetsModal" @ok="country_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('country')">
            <b-form-input id="mc-first-name" :placeholder="$t('country')" v-model="citys_country.country" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('country code')">
            <b-form-input id="mc-first-name" :placeholder="$t('country code')" v-model="citys_country.country_code" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('currency code')">
            <b-form-input id="mc-first-name" :placeholder="$t('currency code')" v-model="citys_country.currency_code" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('currency symbol')">
            <b-form-input id="mc-first-name" :placeholder="$t('currency symbol')"
              v-model="citys_country.currency_symbol" />
          </b-form-group>
        </b-col>
      </b-row>
    </b-modal>

    <b-modal id="modal-center" centered :title="$t('Add New State')" ok-only :ok-title="$t('Save')" ref="state-modal"
      @hidden="resetsModal" @show="resetsModal" @ok="state_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('state')">
            <b-form-input id="mc-first-name" :placeholder="$t('state')" v-model="citys_state.state" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('country')">
            <v-select id="country" v-model="citys_state.country_id" :options="country" label="country"
              :reduce="sel => sel.id" :placeholder="$t('select country')"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
          </b-form-group>
        </b-col>
      </b-row>
    </b-modal>
  </b-row>
</template>

<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import BCardCode from '@core/components/b-card-code'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormSelect, BCardText, BFormFile, BFormTextarea, BFormDatepicker,
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BNavItem, BButton, BFormCheckbox,
  BForm, BFormTimepicker, BInputGroup, BInputGroupAppend, BMedia,
  BAvatar, BDropdown, BDropdownItem, BModal, VBModal, BTab,
  BDropdownForm, BFormRadioGroup,
  BDropdownDivider,
  BDropdownText,
  BFormRadio, BListGroup, BListGroupItem,
} from 'bootstrap-vue'
import { required, email } from '@validations'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@core/mixins/ui/transition'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'
import { avatarText } from '@core/utils/filter'
import { ref } from '@vue/composition-api'
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import flatPickr from 'vue-flatpickr-component'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
  components: {
    BMedia,
    BAvatar,
    BModal, VBModal,
    ValidationProvider,
    ValidationObserver,
    FormWizard,
    TabContent,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    vSelect,
    BFormInvalidFeedback,
    BFormDatepicker,
    BFormRadioGroup,
    ToastificationContent,
    BFormSelect, BCardText,
    BCardCode,
    BFormFile,
    BFormTextarea,
    BCard, BImg, BNavbar, BNavbarToggle,
    BCollapse, BTabs, BNavItem, BButton,
    BFormCheckbox,
    BForm,
    BFormTimepicker, BInputGroup, BInputGroupAppend,
    BDropdown, BDropdownItem, VueCropper, BTab,
    BDropdownForm,
    BDropdownDivider,
    BDropdownText,
    BFormRadio, BListGroup, BListGroupItem, flatPickr,
    VuePhoneNumberInput,
  },


  data() {
    return {
      url: null,
      url1: null,
      url2: '',
      image: [],
      doc_date: [],
      closing_second_time_1: [],
      closing_second_time_2: [],
      closing_second_time_3: [],
      closing_second_time_4: [],
      closing_second_time_5: [],
      closing_second_time_6: [],
      closing_second_time_7: [],
      opening_second_time_1: [],
      opening_second_time_2: [],
      opening_second_time_3: [],
      opening_second_time_4: [],
      opening_second_time_5: [],
      opening_second_time_6: [],
      opening_second_time_7: [],
      closing_time_1: [],
      closing_time_2: [],
      closing_time_3: [],
      closing_time_4: [],
      closing_time_5: [],
      closing_time_6: [],
      closing_time_7: [],
      opening_time_1: [],
      opening_time_2: [],
      opening_time_3: [],
      opening_time_4: [],
      opening_time_5: [],
      opening_time_6: [],
      opening_time_7: [],
      driver: {},
      driver_tab: {},
      driver_doc: {},
      profileData: {},
      restaurants: {},
      documents: {},
      video_title: [],
      video_url: [],
      restaurants_time: {},
      bank: {},
      cuisines: [],
      avatar: {},
      brand: [],
      rows: [],
      vehicles: [],
      cities: [],
      areas: [],
      images: [],
      document_view: [],
      checkedNames: [],
      selected_res: "",
      selected_vehicle: "",
      selected_city: "",
      selected_area: null,
      imgSrc: require('../../../assets/image.jpg'),
      cropImg: '',
      banners: {},
      images_logo: {},
      options: [
        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },
      ],
      selected_check: [],
      options_check: [
        { text: this.$t('home') + ' ' + this.$t('delivery'), value: 1 },
        { text: this.$t('pickup'), value: 2 },
        // { text: this.$t('dining'), value: 3 },
      ],
      options_brand: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
      ],
      brand1: {},
      items: [
        { text: this.$t('home') + ' ' + this.$t('delivery'), value: 1 },
      ],
      nextTodoId: 2,
      locale: this.$i18n.locale,
      setting: {},
      citys: {},
      isHidden1: false,
      isHidden2: false,
      isHidden3: false,
      country: [],
      areas1: {},
      state: [],
      citys_country: {},
      citys_state: {},
      options_status1: [
        { value: "1", text: this.$t('active') },
        { value: "2", text: this.$t('inactive') },
      ],
      options_status: [
        { value: "1", text: this.$t('active') },
        { value: "2", text: this.$t('inactive') },
      ],
      dev_change: {},
      charge: [
        { text: 'Price', value: 1 },
        { text: 'Distance (km/miles)', value: 2 },
      ],
      charge1: [
        { text: 'Distance (km/miles)', value: 2 },
      ],
      taxes: [],
      selectedtax: [],
      items_new: [{
        id_new: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      nextTodoId_new: 2,
      type_delivery: [
        { value: 1, text: 'Rate' },
        { value: 3, text: 'Each' },
      ],
      delivery_charge_minimum: [],
      delivery_charge_maximum: [],
      delivery_charge_value: [],
      delivery_charge_type: [],
      deliveryTime: [
        { value: '1', text: '15-20' },
        { value: '2', text: '20-30' },
        { value: '3', text: '30-40' },
        { value: '4', text: '40-50' },
        { value: '5', text: '50-60' },
      ],
      dc_limits: [{
        id_new: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      primary_name: true,
      SelectedUrl: [],
      SelectedTitle: [],
      finalurl: [],
      finaltitle: [],
      holiday: [],
      business_type: [],
      isLayout_cuisines: false,
      isLayout_deliver_types: false,
      isLayout_brand_types: false,
      isLayout_package_types: false,
      isLayout_video_type: false,
      isLayout_tax: false,
      isLayout_Carrier_type: false,
      isLayout_delivery_charge: true,
      carrier_type: [],
      disabled: true,
      options_carrier: [],
      formattedNumber: '',
      formattedCountryCode: '',
      formattedCountryCallingCode: '',
      formattedNumber2: '',
      formattedCountryCode2: '',
      formattedCountryCallingCode2: '',
      layout_type: '',
      def_lat: '',
      def_lng: '',
      opening_time: [],
      closing_time: [],
      opening_second_time: [],
      closing_second_time: [],
      country_code_phone: 'AE',
      isdeliverytime: true,
      tabIndex: 0,
      plans: [],
      add_plan: {},
      state_list: [],
      valid: false,
      file: '',
      trHeight1: '',
      cart_options: [

        { value: 1, text: this.$t('Enable') },
        { value: 0, text: this.$t('Disable') },



      ],

    }
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  mixins: [heightTransition],
  setup(props) {
    const roleOptions = [
      { label: 'Admin', value: 'admin' },
      { label: 'Author', value: 'author' },
      { label: 'Editor', value: 'editor' },
      { label: 'Maintainer', value: 'maintainer' },
      { label: 'Subscriber', value: 'subscriber' },
    ]
    const statusOptions = [
      { label: 'Pending', value: 'pending' },
      { label: 'Active', value: 'active' },
      { label: 'Inactive', value: 'inactive' },
    ]
    const permissionsData = [
      {
        module: 'Admin',
        read: true,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'Staff',
        read: false,
        write: true,
        create: false,
        delete: false,
      },
      {
        module: 'Author',
        read: true,
        write: false,
        create: true,
        delete: false,
      },
      {
        module: 'Contributor',
        read: false,
        write: false,
        create: false,
        delete: false,
      },
      {
        module: 'User',
        read: false,
        write: false,
        create: false,
        delete: true,
      },
    ]
    const refInputEl = ref(null)
    const previewEl = ref(null)
    const { inputImageRenderer } = useInputImageRenderer(refInputEl, base64 => {
      this.url = base64
    })
    return {
      avatarText,
      roleOptions,
      statusOptions,
      permissionsData,
      refInputEl,
      previewEl,
      inputImageRenderer,
    }
  },

  mounted() {

    // this.$http.get('/admin/get_default_lat_lng')
    //   .then(res => {
    //     this.def_lat = res.data.def_lat
    //     this.def_lng = res.data.def_lng
    //     // this.initMap()

    //   })
    this.initTrHeight()
    this.initTrHeight1()
    this.importAll(require.context('@/assets/images/banner/', true, /\.jpg$/));
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
    window.removeEventListener('resize', this.initTrHeight1)
  },

  watch: {
    locale(val) {
      this.$i18n.locale = val
    },
    checkedNames: {
      deep: true,
      handler: function (newValue) {
        console.log("Selected users changed", newValue)
      }
    },
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
    window.addEventListener('resize', this.initTrHeight1)
    this.$http.get("/admin/get_deltaxes").then((res) => {
      this.taxes = res.data;
      for (var i = 0; i < this.taxes.length; i++) {
        for (var j = 0; j < this.taxes[i].tax_for.length; j++) {
          console.log(this.taxes[i].tax_for[j]);
          if (this.taxes[i].tax_for[j] == 2) {
            this.selectedtax[this.taxes[i].id] =
              this.taxes[i].tax[j];
          }
        }
      }
    });
    this.$http.get('/admin/country_list')
      .then(res => {
        this.country = res.data
      })
    this.$http.get('/admin/cuisines_list')
      .then(res => {
        this.cuisines = res.data
      })
    this.$http.get('/admin/city_list1')
      .then(res => {
        this.cities = res.data
      })
    this.$http.get('/admin/document_view')
      .then(res => {
        this.document_view = res.data
      })
    this.$http.get('/admin/brands_list1')
      .then(res => {
        this.brand = res.data

      })

    this.$http.get('/plans')
      .then(res => {
        this.plans = res.data.plans

      })

    this.$http.get('/admin/get_geofence')
      .then(res => {

        this.restaurants.GeofenceRadius = res.data.value;

      })

    this.$http.get('/admin/get_banner_image')
      .then(res => {
        console.log(res)
        this.banners = res.data;

      })
    this.$http.get('/admin/get_logo_image')
      .then(res => {
        console.log(res)

        this.images_logo = res.data;

      })
    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
    this.$http.get('/admin/business_lists')
      .then(res => {

        this.business_type = res.data;

      })
    this.$http.get('/admin/carrier_list_1/' + this.$route.params.id)
      .then(res => { this.options_carrier = res.data })

    this.$http.get('/admin/carrier_list1')
      .then(res => {
        this.carrier_type = res.data

      })

    this.$http.get('/admin/state_list')
      .then(res => {

        this.state_list = res.data
      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_restaurant/' + this.$route.params.id)
        .then(res => {
          // this.initMap();
          console.log(res)
          this.restaurants = res.data.data;
          this.restaurants.city = parseInt(this.restaurants.city);
          this.restaurants.area = parseInt(this.restaurants.area);
          this.restaurants.delivery_states = this.restaurants.delivery_states;

          this.$http.post('/admin/area_list1', this.restaurants)
            .then(res => {

              this.areas = res.data
              if (this.areas.length > 0) {
                this.restaurants.area = this.areas[0].id;
              }
              else {

                this.restaurants.area = '';
              }
            })
          this.layout_type = res.data.business_type;
          if (this.layout_type == 2) {

            this.isLayout_cuisines = false;
            this.isLayout_deliver_types = true;
            this.isLayout_brand_types = true;
            this.isLayout_package_types = true;
            this.isLayout_video_type = false;
            this.isLayout_tax = false;
            this.isLayout_Carrier_type = false;
            this.isLayout_delivery_charge = true;
          }
          else if (this.layout_type == 3) {

            this.isLayout_cuisines = false;
            this.isLayout_deliver_types = false;
            this.isLayout_brand_types = false;
            this.isLayout_package_types = false;
            this.isLayout_video_type = false;
            this.isLayout_tax = true;
            this.isLayout_Carrier_type = true;
            this.isLayout_delivery_charge = false;

          }
          else {

            this.isLayout_cuisines = true;
            this.isLayout_deliver_types = true;
            this.isLayout_brand_types = true;
            this.isLayout_package_types = true;
            this.isLayout_video_type = false;
            this.isLayout_tax = false;
            this.isLayout_Carrier_type = false;
            this.isLayout_delivery_charge = true;
          }
          if (this.restaurants.brand_name != '') {
            this.restaurants.brands = parseInt(this.restaurants.brand_name);
          } else {
          }

          this.def_lat = this.restaurants.lat;
          this.def_lng = this.restaurants.lng;
          document.getElementById('latitude').value = this.restaurants.lat;
          document.getElementById('longitude').value = this.restaurants.lng;
          this.url = this.restaurants.banner;
          this.url1 = this.restaurants.image;

          this.formattedCountryCallingCode = this.restaurants.phone_country_calling_code;
          this.formattedCountryCode = this.restaurants.phone_country_code;
          this.formattedCountryCallingCode2 = this.restaurants.contact_country_calling_code;
          this.formattedCountryCode2 = this.restaurants.contact_country_code;

          this.opening_time[0] = this.restaurants.opening_time_1;
          this.closing_time[0] = this.restaurants.closing_time_1;
          this.opening_second_time[0] = this.restaurants.opening_second_time_1;
          this.closing_second_time[0] = this.restaurants.closing_second_time_1;

          this.opening_time[1] = this.restaurants.opening_time_2;
          this.closing_time[1] = this.restaurants.closing_time_2;
          this.opening_second_time[1] = this.restaurants.opening_second_time_2;
          this.closing_second_time[1] = this.restaurants.closing_second_time_2;

          this.opening_time[2] = this.restaurants.opening_time_3;
          this.closing_time[2] = this.restaurants.closing_time_3;
          this.opening_second_time[2] = this.restaurants.opening_second_time_3;
          this.closing_second_time[2] = this.restaurants.closing_second_time_3;

          this.opening_time[3] = this.restaurants.opening_time_4;
          this.closing_time[3] = this.restaurants.closing_time_4;
          this.opening_second_time[3] = this.restaurants.opening_second_time_4;
          this.closing_second_time[3] = this.restaurants.closing_second_time_4;

          this.opening_time[4] = this.restaurants.opening_time_5;
          this.closing_time[4] = this.restaurants.closing_time_5;
          this.opening_second_time[4] = this.restaurants.opening_second_time_5;
          this.closing_second_time[4] = this.restaurants.closing_second_time_5;



          this.opening_time[5] = this.restaurants.opening_time_6;
          this.closing_time[5] = this.restaurants.closing_time_6;
          this.opening_second_time[5] = this.restaurants.opening_second_time_6;
          this.closing_second_time[5] = this.restaurants.closing_second_time_6;



          this.opening_time[6] = this.restaurants.opening_time_7;
          this.closing_time[6] = this.restaurants.closing_time_7;
          this.opening_second_time[6] = this.restaurants.opening_second_time_7;
          this.closing_second_time[6] = this.restaurants.closing_second_time_7;

          if (this.restaurants.holidays != null) {
            this.restaurants.holidays.forEach(function (element) {

              document.getElementById('holiday' + element).checked = true;
            });
          }
          if (this.restaurants.restaurant_bank_details != null) {
            this.bank = this.restaurants.restaurant_bank_details;
          }

          if (this.restaurants.document.length > 0) {
            this.restaurants.document.forEach(function (element) {
              document.getElementById("document_img" + element.id).src = element.pivot.document;
              if (element.expiry_date_needed == 1) {

                document.getElementById('dates' + element.id).value = String(element.pivot.expiry_date);
              }
            });

          }



          if (!this.restaurants.video_title) {
            this.items = []
          } else {
            this.items = this.restaurants.video_title
          }
          if (!this.restaurants.video_url || !this.restaurants.video_url.length) {
            this.SelectedUrl = []
          } else {
            this.SelectedUrl = this.restaurants.video_url
          }
          if (!this.restaurants.video_title || !this.restaurants.video_title.length) {
            this.SelectedTitle = []
          } else {
            this.SelectedTitle = this.restaurants.video_title
          }
          this.dev_change.deliveryChargeBasedOn = this.restaurants.DeliveryChargeBasedOn;
          if (this.layout_type == 3) {
            this.options_carrier = res.data.delivery_charge_limits

          } else {
            this.dc_limits = res.data.delivery_charge_limits

          }

          // for (var i = 0; i < this.restaurants.tax_id.length; i++) {
          //           this.selectedtax[this.restaurants.tax_id[i]] = this.restaurants.tax_val[i];
          //       }


          const element = document.getElementById("gmap");
          const mapoptions = {
            zoom: 13,
            center: new google.maps.LatLng(this.restaurants.lat, this.restaurants.lng)
          };

          const map = new google.maps.Map(element, mapoptions);

          var lati = document.getElementById('latitude').value;
          var long = document.getElementById('longitude').value;
          var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
          var geocoder = new google.maps.Geocoder();
          var input = document.getElementById('searchMadminnput');
          // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

          var autocomplete = new google.maps.places.Autocomplete(input);
          autocomplete.bindTo('bounds', map);
          var infowindow = new google.maps.InfoWindow();
          var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            draggable: true

          });
          autocomplete.addListener('place_changed', function () {
            //infowindow.close();
            marker.setVisible(true);
            var place = autocomplete.getPlace();
            console.log(place)
            /* If the place has a geometry, then present it on a map. */
            if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
            } else {
              map.setCenter(place.geometry.location);
              map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
              address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
              ].join(' ');
            }
            // if (place.formatted_phone_number) {
            //     this.restaurants.contact = place.formatted_phone_number
            //     this.restaurants.phone = place.formatted_phone_number

            // }
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);

            /* Location details */
          });
          google.maps.event.addListener(marker, 'dragend',
            function (marker) {
              var latLng = marker.latLng;
              var currentLatitude = latLng.lat();
              var currentLongitude = latLng.lng();

              geocoder.geocode({
                'latLng': latLng
              }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                  if (results[0]) {
                    document.getElementById('searchMadminnput').value = results[0].formatted_address;
                    document.getElementById('latitude').value = currentLatitude;
                    document.getElementById('longitude').value = currentLongitude;
                    infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                    infowindow.open(map, marker);
                  }
                }
              });
            });
          this.add_plan.plan = res.data.plan.plan
        })

    }

  },






  methods: {
    trSetHeight1() {

    },
    resetsModal() {

    },
    updatePhoneNumber(data) {
      this.formattedNumber = data.nationalNumber
      this.formattedCountryCallingCode = data.countryCallingCode
      this.formattedCountryCode = data.countryCode
    },
    updatePhoneNumber2(data) {
      this.formattedNumber2 = data.nationalNumber
      this.formattedCountryCallingCode2 = data.countryCallingCode
      this.formattedCountryCode2 = data.countryCode
    },

    blockSpecialChar(val) {

      var one = document.getElementById('minimum' + val).value;
      var two = document.getElementById('maximum' + val).value;
      // alert(two);
      if (parseFloat(one) < parseFloat(two)) {
        document.getElementById('deliveryChargeMsg').style.display = "none";
        return 0;

      }
      else {
        document.getElementById('deliveryChargeMsg').style.display = "block";
      }


    },

    blockSpecialChar_one(val) {
      var id = parseFloat(val) - parseFloat(1);

      var two = document.getElementById('maximum' + id).value;
      var one = document.getElementById('minimum' + val).value;
      // alert(two);
      if (two) {
        var total = parseFloat(two) + parseFloat(0.1);
        document.getElementById('minimum' + val).value = total;
        document.getElementById('deliveryChargeMsg1').style.display = "none";
        return 0;
      }
      else {
        document.getElementById('deliveryChargeMsg1').style.display = "block";
      }


    },

    repeateAgain_new() {
      if (this.dc_limits.length > 0) {
        var i = this.dc_limits.length - 1
        var j = this.dc_limits.length
        var timer = setInterval(function () {
          var max = document.getElementById("maximum" + i).value
          if (document.getElementById('maximum' + j)) {
            var total = parseFloat(max) + parseFloat(0.1);
            document.getElementById('minimum' + j).value = total
            document.getElementById('minimum' + j).disabled = true;
            clearInterval(timer);
          }
        }, 500);
        var maxs = document.getElementById("maximum" + i).value;
        if (maxs == '') {
          this.$toast({
            component: ToastificationContent,
            position: 'bottom-right',
            props: {
              title: this.$t('Maximum field is required '),
              icon: 'CheckIcon',
              variant: 'danger',
            },
          })
        }
        else {
          this.dc_limits.push({
            id_new: this.nextTodoId_new += this.nextTodoId_new,
          })
          this.$nextTick1(() => {
            this.trAddHeight1(this.$refs.row1[0].offsetHeight)
          })
        }
        document.getElementById('minimum0').value = 0;
        document.getElementById('minimum0').disabled = true;
      } else {
        this.dc_limits.push({
          id_new: this.nextTodoId_new += this.nextTodoId_new,
        })
        this.$nextTick1(() => {
          this.trAddHeight1(this.$refs.row1[0].offsetHeight)
        })
      }



    },

    repeateAgain_new_1(id, index) {

      var carriers = this.options_carrier[index].invoice;
      var i = carriers.length - 1
      var j = carriers.length

      if (carriers.length !== 5) {
        var max = document.getElementById("maximum" + id + i).value;
        var timer = setInterval(function () {
          if (document.getElementById('maximum' + id + j)) {
            var total = parseFloat(max) + parseFloat(0.1);
            document.getElementById('minimum' + id + j).value = total
            document.getElementById('minimum' + id + j).disabled = true;
            clearInterval(timer);
          }

        }, 500);

        var maxs = document.getElementById("maximum" + id + i).value;
        document.getElementById('minimum' + id + '0').value = 0;
        document.getElementById('minimum' + id + '0').disabled = true;
        if (maxs == '') {
          this.$toast({
            component: ToastificationContent,
            position: 'bottom-right',
            props: {
              title: this.$t('Maximum field is required '),
              icon: 'CheckIcon',
              variant: 'danger',
            },
          })
        }
        else {

          this.options_carrier[index].invoice.push({
            id: this.nextTodoId_new += this.nextTodoId_new,

          })
          this.$nextTick1(() => {
            this.trAddHeight1(this.$refs.row1[0].offsetHeight)
          })
        }





      }

    },
    removeItem_new(index) {
      // if (this.items_new.length > 1) {
      //   this.items_new.splice(index, 1)
      //   this.trTrimHeight1(this.$refs.row1[0].offsetHeight)
      // }
      this.dc_limits.splice(this.dc_limits.length - 1, 1)
      this.trTrimHeight1(this.$refs.row1[0].offsetHeight)
    },

    removeItem_new_1(index) {

      var carriers = this.options_carrier[index].invoice;
      if (carriers.length > 1) {
        var lastPosition = carriers.length - 1;
        carriers.splice(lastPosition, 1)
        this.trTrimHeight1(this.$refs.row1[0].offsetHeight)
      }

    },
    initTrHeight1() {
      this.trSetHeight1(null)
      this.$nextTick1(() => {
        if (this.$refs.form1) {
        this.trSetHeight1(this.$refs.form1.scrollHeight)
          } else {
      console.warn('form1 ref is not available');
    }
      })
    },
    open_citymodal() {
      this.$refs["city-modal"].show();
      this.$refs["area-modal"].hide();
    },
    open_areamodal() {

      this.$refs["area-modal"].show();
      var timer = setInterval(function () {
        if (document.getElementById("gmap1")) {
          const element = document.getElementById("gmap1");
          const mapoptions = {
            zoom: 18,
            disableDefaultUI: true,

          };
          const map = new google.maps.Map(element, mapoptions);

          var lati = document.getElementById('latitude_area').value;
          var long = document.getElementById('longitude_area').value;

          var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
          var geocoder = new google.maps.Geocoder();

          var input = document.getElementById('searchMadminnput_area');

          var autocomplete = new google.maps.places.Autocomplete(input);
          autocomplete.bindTo('bounds', map);
          var infowindow = new google.maps.InfoWindow();
          var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            draggable: true

          });
          autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();



            var address = '';
            if (place.address_components) {
              address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
              ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
            var geocoder = new google.maps.Geocoder();

            var latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
            geocoder.geocode({
              'latLng': latlng
            },
              function (results, status) {

                if (results[0]) {
                  var add = results[0].formatted_address;
                  document.getElementById('selectedadd').value = results[0].formatted_address;
                  document.getElementById('latitude_area').value = place.geometry.location.lat();
                  document.getElementById('longitude_area').value = place.geometry.location.lng();

                } else {
                  alert("address not found");
                }

              }

            );


          });
          google.maps.event.addListener(marker, 'dragend',
            function (marker) {
              var latLng = marker.latLng;
              var currentLatitude = latLng.lat();
              var currentLongitude = latLng.lng();

              geocoder.geocode({
                'latLng': latLng
              }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                  if (results[0]) {
                    document.getElementById('searchMadminnput_area').value = results[0].formatted_address;
                    document.getElementById('selectedadd').value = results[0].formatted_address;

                    document.getElementById('latitude_area').value = currentLatitude;
                    document.getElementById('longitude_area').value = currentLongitude;
                    infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                    infowindow.open(map, marker);
                  }
                }
              });
            });
          clearInterval(timer);
        }
      }, 1000);

    },
    open_countrymodal() {
      this.$refs["city-modal"].hide();
      this.$refs["country-modal"].show();
    },
    open_statemodal() {
      this.$refs["city-modal"].hide();
      this.$refs["state-modal"].show();
    },
    open_brandmodal() {

      this.$refs["brand-modal"].show();
    },

    onTabChange(id) {
      if (id == 1) {
        this.primary_name = true
      }
      else {
        this.primary_name = false
      }
    },

    onTabChange1(id) {

      if (id == 1) {
        this.isHidden1 = false;
      }
      else {
        this.isHidden1 = true;
      }


    },
    onTabChange_city(id) {

      if (id == 1) {
        this.isHidden2 = false;
      }
      else {
        this.isHidden2 = true;
      }


    },

    onTabChange_state(id) {

      if (id == 1) {
        this.isHidden3 = false;
      }
      else {
        this.isHidden3 = true;
      }


    },

    brand_add() {
      let brand = new FormData();
      brand.append('id', this.brand1.id);
      brand.append('status', this.brand1.status);
      brand.append('name', this.brand1.name ? this.brand1.name : '');
      this.$http
        .post("/admin/store_brand", brand)
        .then(
          (response => {

            if (response.data.status == true) {
              this.$http.get('/admin/brands_list1')
                .then(res => {
                  this.brand = res.data

                })
              this.popToast(response, "CheckIcon", "success")
            } else {

              this.popToast(response, "AlertTriangleIcon", "danger")
            }


          })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    popToast(response, icon, variant) {

      this.$toast({
        component: ToastificationContent,
        position: 'bottom-right',
        props: {
          title: this.$t(response.data.message),
          icon: icon,
          variant: variant,
        },
      })
    },
    country_add() {
      let citys = new FormData();

      citys.append('country', this.citys_country.country);
      citys.append('country_code', this.citys_country.country_code);
      citys.append('currency_symbol', this.citys_country.currency_symbol);
      citys.append('currency_code', this.citys_country.currency_code);
      this.$http
        .post("/admin/save_country", this.citys_country)
        .then(
          response => {
            if (response.data.status == true) {
              this.$http.get('/admin/country_list')
                .then(res => {

                  this.country = res.data

                })
              this.$refs["city-modal"].show();
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })

            } else {
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'warning',
                },
              })

            }
          }



        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

    state_add() {
      let citys2 = new FormData();
      citys2.append('country_id', this.citys_state.country_id);
      citys2.append('state', this.citys_state.state);
      citys2.append('second_state', this.citys_state.second_state ? this.citys.second_state : '');

      this.$http
        .post("/admin/save_state", this.citys_state)
        .then(

          response => {
            if (response.data.status == true) {
              this.$refs["city-modal"].show();
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'success',
                },
              })

            } else {
              this.$toast({
                component: ToastificationContent,
                position: 'bottom-right',
                props: {
                  title: this.$t(response.data.message),
                  icon: 'CheckIcon',
                  variant: 'warning',
                },
              })

            }
          }

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    onChange_city(event) {

      this.$http.post('/admin/state_list_by_country', this.citys)
        .then(res => {

          this.state = res.data;


        })

    },
    city_add() {

      this.$http
        .post("/admin/city_add", this.citys)
        .then(
          response => {
            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
              this.$http.get('/admin/city_list1')
                .then(res => {

                  this.cities = res.data
                })

            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

    area_add() {
      var lati1 = document.getElementById('latitude_area').value;
      var long1 = document.getElementById('longitude_area').value;
      var area1 = document.getElementById('searchMadminnput_area').value;
      let data = new FormData();
      data.append('id', this.areas1.city);
      data.append('area', area1);
      data.append('status', this.areas1.status);
      data.append('latitude', lati1);
      data.append('longitude', long1);
      this.$http
        .post("/admin/area_add", data)
        .then(
          response => {
            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
              this.$http.get('/admin/area_list')
                .then(res => {

                  this.areas = res.data
                })

            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    change() {
      // this.imgSrc = this.imgSrc;
      // rebuild cropperjs with the updated source
      this.$refs.cropper.replace(this.imgSrc);
    },
    cropImage() {
      this.url = this.$refs.cropper.getCroppedCanvas({
        width: 1900,
        height: 500
      }).toDataURL();
      this.restaurants.banner = this.$refs.cropper.getCroppedCanvas({
        width: 1900,
        height: 500
      }).toDataURL();
      this.restaurants.existing = 2;
      this.tabIndex = 0;
      this.$bvModal.hide('modal-xl');
    },
    rotate() {
      // guess what this does :)
      this.$refs.cropper.rotate(90);
    },
    cropImage1() {
      // get image data for post processing, e.g. upload or setting image src
      this.url1 = this.$refs.cropper.getCroppedCanvas({
        width: 1200,
        height: 628
      }).toDataURL();
      this.restaurants.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.restaurants.existing1 = 2;
      this.tabIndex = 0;
      this.$bvModal.hide('modal-xl1');
    },
    rotate1() {
      // guess what this does :)
      this.$refs.cropper.rotate(90);
    },
    onFileChange_banner(e) {
      const file = e.target.files[0];
      var img_size = e.target.files[0].size;
      if (img_size < 20676814) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
          this.imgSrc = reader.result;
          this.change();
        };

        document.getElementById('crop_js').style.display = 'block';
        document.getElementById('crop_btn').style.display = 'block';
        document.getElementById('rotate_btn').style.display = 'block';
      } else {
        alert("It is too large size Image . allow only minimum 20MB ");
      }
      // this.$bvModal.hide('modal-xl');
    },
    onFileChange_banner1(e) {
      const file = e.target.files[0];
      this.restaurants.banner = file;
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url = reader.result;
      };
      this.restaurants.existing = 3;
      this.$bvModal.hide('modal-xl');

    },

    onFileChange_image(e) {
      const file = e.target.files[0];
      var img_size = e.target.files[0].size;
      if (img_size < 20676814) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
          this.imgSrc = reader.result;
          this.change();
        };

        document.getElementById('crop_js').style.display = 'block';
        document.getElementById('crop_btn').style.display = 'block';
        document.getElementById('rotate_btn').style.display = 'block';
      } else {
        alert("It is too large size Image . allow only minimum 20MB ");
      }
      // this.$bvModal.hide('modal-xl');
    },
    onFileChange_image1(e) {
      const file = e.target.files[0];
      this.restaurants.image = file;
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
      this.restaurants.existing1 = 3;
      this.$bvModal.hide('modal-xl1');

    },

    myMethod(image) {
      this.url = image;
      var filename = image.replace(/^.*[\\\/]/, '');
      this.restaurants.banner = filename;
      this.restaurants.existing = 1;
      this.$bvModal.hide('modal-xl');

    },
    myMethod1(image) {
      this.url1 = image;
      var filename = image.replace(/^.*[\\\/]/, '');
      this.restaurants.image = filename;
      this.restaurants.existing1 = 1;
      this.$bvModal.hide('modal-xl1');

    },
    formSubmitted() {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: this.$t('Form Submitted'),
          icon: 'EditIcon',
          variant: 'success',
        },
      })



    },
    initMap() {

      const element = document.getElementById("gmap");
      const mapoptions = {
        zoom: 13,
        center: new google.maps.LatLng(this.def_lat, this.def_lng)
      };

      const map = new google.maps.Map(element, mapoptions);
      var lati = this.def_lat;
      var long = this.def_lng;
      var myLatlng = new google.maps.LatLng(Number(lati), Number(long));
      var geocoder = new google.maps.Geocoder();
      var input = document.getElementById('searchMadminnput');
      // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);
      var infowindow = new google.maps.InfoWindow();
      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true

      });
      autocomplete.addListener('place_changed', function () {
        //infowindow.close();
        marker.setVisible(true);
        var place = autocomplete.getPlace();
        console.log(place)
        /* If the place has a geometry, then present it on a map. */
        if (place.geometry.viewport) {
          map.fitBounds(place.geometry.viewport);
        } else {
          map.setCenter(place.geometry.location);
          map.setZoom(17);
        }

        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
          address = [
            (place.address_components[0] && place.address_components[0].short_name || ''),
            (place.address_components[1] && place.address_components[1].short_name || ''),
            (place.address_components[2] && place.address_components[2].short_name || '')
          ].join(' ');
        }
        // if (place.formatted_phone_number) {
        //     this.restaurants.contact = place.formatted_phone_number
        //     this.restaurants.phone = place.formatted_phone_number

        // }
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);

        /* Location details */
      });
      google.maps.event.addListener(marker, 'dragend',
        function (marker) {
          var latLng = marker.latLng;
          var currentLatitude = latLng.lat();
          var currentLongitude = latLng.lng();

          geocoder.geocode({
            'latLng': latLng
          }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                document.getElementById('searchMadminnput').value = results[0].formatted_address;
                document.getElementById('latitude').value = currentLatitude;
                document.getElementById('longitude').value = currentLongitude;
                infowindow.setContent('<div>' + results[0].formatted_address + '<br>');
                infowindow.open(map, marker);
              }
            }
          });
        });
    },
    onChange(event) {

      this.$http.post('/admin/area_list1', this.restaurants)
        .then(res => {

          this.areas = res.data
          if (this.areas.length > 0) {
            this.restaurants.area = this.areas[0].id;
          }
          else {

            this.restaurants.area = '';
          }
        })

    },

    importAll(r) {
      r.keys().forEach(key => (this.images.push({ pathLong: r(key), pathShort: key })));

    },

    onFileChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        var d = reader.result;
        this.url = d;
        document.getElementById('cover_img').src = reader.result;
        document.getElementById('Banner1').value = this.url;
      };

      document.getElementById('banner_id').value = "1";

    },

    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        // alert(reader.result);
        this.url1 = reader.result;
        document.getElementById('profile').src = reader.result;
        document.getElementById('Image1').value = this.url1;
      };
      document.getElementById('logo_id').value = "1";
    },
    onFileChange3(e, id) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        document.getElementById("document_img" + id).src = reader.result;
      };
    },


    // mounted() {
    //   this.initTrHeight()
    // },
    repeateAgain() {
      console.log(this.nextTodoId)
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem() {
      console.log(this.items.length)
      this.items.splice(this.items.length - 1, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        if (this.$refs.form) {
        this.trSetHeight(this.$refs.form.scrollHeight)
        }
      })
    },
    add_plan_func() {
      let data = new FormData();
      var id = this.$route.params.id;
      data.append('id', id);
      data.append('plan', this.add_plan.plan);
      this.$http
        .post("/admin/add_to_restaurants_tab6", data)
        .then((response) => {

          if (response.data.status == false) {
            this.popToast(response, "CheckIcon", "danger")
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'danger',
              },
            })
          } else {
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: response.data.message,
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
          }

        })



    },


    restaurant() {

      return new Promise((resolve, reject) => {

        this.$refs.accountRules.validate().then(success => {
          if (success) {

            let checkboxes = document.querySelectorAll('input[name="cuisines_name"]:checked');

            let values = [];
            checkboxes.forEach((checkbox) => {
              values.push(checkbox.value);
            });
            this.restaurants.cuisines = values;

            let checkboxes1 = document.querySelectorAll('input[name="delivery_name"]:checked');

            let values1 = [];
            checkboxes1.forEach((checkbox) => {
              values1.push(checkbox.value);
            });

            this.restaurants.delivery_type = values1;

            let value_1 = [];
            let value_2 = [];
            // for (let i = 0; i < this.items.length; i++) {
            //   value_1.push(document.getElementById('title' + i).value);
            //   value_2.push(document.getElementById('url' + i).value);

            //   //  this.dev_change.delivery_charge_type = this.delivery_charge_type[i];

            // }
            // this.video_title = value_1;
            // this.video_url = value_2;
            this.finaltitle = []
            this.finalurl = []

            for (var i = 0; i < this.SelectedTitle.length; i++) {

              this.finaltitle[i] = this.SelectedTitle[i]
              this.finalurl[i] = this.SelectedUrl[i]


            }
            let data = new FormData();
            var id = this.$route.params.id;
            data.append('id', id);
            data.append('banner', this.restaurants.banner);
            data.append('image', this.restaurants.image);
            data.append('name', this.restaurants.restaurant_name);
            data.append('restaurant_secondary_name', this.restaurants.restaurant_secondary_name);
            data.append('password', this.restaurants.password);
            data.append('city', this.restaurants.city);
            data.append('area', this.restaurants.area);
            data.append('status', this.restaurants.status);
            data.append('estimated_delivery_time', this.restaurants.estimated_delivery_time);
            data.append('address', document.getElementById('searchMadminnput').value);
            data.append('packaging_charge', this.restaurants.packaging_charge);
            data.append('delivery_type', this.restaurants.delivery_type);
            data.append('cuisines', this.restaurants.cuisines);
            data.append('phone', this.restaurants.phone);
            data.append('email', this.restaurants.email);
            data.append('GeofenceRadius', this.restaurants.GeofenceRadius);
            data.append('shop_description', this.restaurants.shop_description);
            data.append('brands', this.restaurants.brands);
            data.append('admin_commision', this.restaurants.admin_commision);
            data.append('driver_commision', 0);
            data.append('video_title', JSON.stringify(this.finaltitle));
            data.append('video_url', JSON.stringify(this.finalurl));
            data.append('contact', this.restaurants.contact);
            data.append('latitude', document.getElementById('latitude').value);
            data.append('longitude', document.getElementById('longitude').value);
            data.append('existing1', this.restaurants.existing1);
            data.append('existing', this.restaurants.existing);

            data.append('tax', this.restaurants.tax);
            data.append('carrier_type', this.restaurants.carrier_type ? this.restaurants.carrier_type : '');
            data.append('business_type', this.restaurants.business_type);
            data.append('country_code', this.formattedCountryCode);
            data.append('country_calling_code', this.formattedCountryCallingCode);
            data.append('country_code_contact', this.formattedCountryCode2);
            data.append('country_calling_code_contact', this.formattedCountryCallingCode2);
            data.append('delivering_states', this.restaurants.delivery_states);
            data.append('cart', this.restaurants.cart);


            console.log(data);
            this.$http
              .post("/admin/add_to_restaurants_tab1", data)
              .then((response) => {
                // this.id = response.id;
                if (response.data.status == false) {
                  this.popToast(response, "CheckIcon", "danger")
                  reject()
                  this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: response.data.message,
                      icon: 'CheckIcon',
                      variant: 'danger',
                    },
                  })
                } else {
                  document.getElementById('res_id').value = response.data.id;
                  resolve(true)
                  this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: this.$t('Store Details Updated Successfully'),
                      icon: 'CheckIcon',
                      variant: 'success',
                    },
                  })
                }

              }
                // console.log(response.data)
              )
              .catch((error) => console.log(error))
              .finally(() => (this.loading = false));

          } else {


            reject()
          }
        })
      })



    },
    Restaurant_Timing() {
      return new Promise((resolve, reject) => {
        // resolve(true)
        // return
        let checkboxes2 = document.querySelectorAll('input[name="holidays"]:checked');
        let values2 = [];
        checkboxes2.forEach((checkboxes) => {
          values2.push(checkboxes.value);
        });
        this.restaurants_time.holidays = values2;
        var id = this.$route.params.id;
        let data = new FormData();
        data.append('id', id);
        data.append('opening_time_1', this.opening_time[0] || '');
        data.append('closing_time_1', this.closing_time[0] || '');
        data.append('opening_second_time_1', this.opening_second_time[0] || '');
        data.append('closing_second_time_1', this.closing_second_time[0] || '');

        data.append('opening_time_2', this.opening_time[1] || '');
        data.append('closing_time_2', this.closing_time[1] || '');
        data.append('opening_second_time_2', this.opening_second_time[1] || '');
        data.append('closing_second_time_2', this.closing_second_time[1] || '');

        data.append('opening_time_3', this.opening_time[2] || '');
        data.append('closing_time_3', this.closing_time[2] || '');
        data.append('opening_second_time_3', this.opening_second_time[2] || '');
        data.append('closing_second_time_3', this.closing_second_time[2] || '');

        data.append('opening_time_4', this.opening_time[3] || '');
        data.append('closing_time_4', this.closing_time[3] || '');
        data.append('opening_second_time_4', this.opening_second_time[3] || '');
        data.append('closing_second_time_4', this.closing_second_time[3] || '');

        data.append('opening_time_5', this.opening_time[4] || '');
        data.append('closing_time_5', this.closing_time[4] || '');
        data.append('opening_second_time_5', this.opening_second_time[4] || '');
        data.append('closing_second_time_5', this.closing_second_time[4] || '');

        data.append('opening_time_6', this.opening_time[5] || '');
        data.append('closing_time_6', this.closing_time[5] || '');
        data.append('opening_second_time_6', this.opening_second_time[5] || '');
        data.append('closing_second_time_6', this.closing_second_time[5] || '');

        data.append('opening_time_7', this.opening_time[6] || '');
        data.append('closing_time_7', this.closing_time[6] || '');
        data.append('opening_second_time_7', this.opening_second_time[6] || '');
        data.append('closing_second_time_7', this.closing_second_time[6] || '');
        data.append('holidays', this.restaurants_time.holidays);

        this.$http
          .post("/admin/add_to_restaurants_tab2", data)
          .then(
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t('Store Timing Updated Successfully'),
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
          )
          .catch((error) => console.log(error))
          .finally(() => (this.loading = false));

        resolve(true)

      })
    },
    Document() {

      return new Promise((resolve, reject) => {
        // resolve(true)
        // return
        var id = this.$route.params.id;
        let data = new FormData();
        data.append('id', id);
        this.document_view.forEach(function (number) {
          if (number.document_for == 2) {
            data.append('document[' + number.id + '][document]', document.getElementById('document' + number.id).files[0]);

            if (number.expiry_date_needed == 1) {
              data.append('document[' + number.id + '][date]', document.getElementById('date' + number.id).value);

            }

          }
        });
        this.$http
          .post("/admin/add_to_restaurants_tab3", data)
          .then(
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t('Document Updated Successfully'),
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
          )
          .catch((error) => console.log(error))
          .finally(() => (this.loading = false));

        resolve(true)

      })
    },
    bank_details() {

      return new Promise((resolve, reject) => {
        // resolve(true)
        // return
        this.$refs.accountRules3.validate().then(success => {
          if (success) {
            this.bank.id = this.$route.params.id;

            this.$http
              .post("/admin/add_to_restaurants_tab4", this.bank)
              .then(
                (response) => {
                  this.$toast({
                    component: ToastificationContent,
                    position: 'bottom-right',
                    props: {
                      title: this.$t('Bank Details Updated Successfully'),
                      icon: 'CheckIcon',
                      variant: 'success',
                    },
                  })

                }

              )
              .catch((error) => console.log(error))
              .finally(() => (this.loading = false));

            resolve(true)
          } else {
            reject()
          }
        })
      })
    },

    delivery_charge() {
      var id = this.$route.params.id;
      let value1 = [];
      let value2 = [];
      let value3 = [];
      let value4 = [];
      let value5 = [];
      if (this.layout_type == 3) {
        this.options_carrier.forEach(function (element, index) {
          for (let i = 0; i < element.invoice.length; i++) {
            value1.push(document.getElementById('minimum' + element.id + i).value);
            value2.push(document.getElementById('maximum' + element.id + i).value);
            value3.push(document.getElementById('value' + element.id + i).value);
            value4.push(document.getElementById('type' + element.id + i).value);
            value5.push(element.id);
            //  this.dev_change.delivery_charge_type = this.delivery_charge_type[i];

          }
        });
      }
      else {
        for (let i = 0; i < this.dc_limits.length; i++) {
          value1.push(document.getElementById('minimum' + i).value);
          value2.push(document.getElementById('maximum' + i).value);
          value3.push(document.getElementById('value' + i).value);
          value4.push(document.getElementById('type' + i).value);
          //  this.dev_change.delivery_charge_type = this.delivery_charge_type[i];

        }

      }

      this.delivery_charge_minimum = value1;
      this.delivery_charge_maximum = value2;
      this.delivery_charge_value = value3;
      this.delivery_charge_type = value4;
      let data = new FormData();

      // let sel_tax = [];
      // let sel_taxval = [];
      // for (var i = 0; i < this.selectedtax.length; i++) {
      //   if (this.selectedtax[i] != undefined) {
      //     sel_tax.push(i);
      //     sel_taxval.push(this.selectedtax[i]);
      //   }
      // }
      // this.restaurants.sel_tax = sel_tax;
      // this.restaurants.sel_taxval = sel_taxval;
      // data.append("sel_tax",JSON.stringify(this.restaurants.sel_tax));
      // data.append("sel_taxval",JSON.stringify(this.restaurants.sel_taxval));


      data.append('id', id);
      data.append('delivery_charge_minimum', this.delivery_charge_minimum);
      data.append('delivery_charge_maximum', this.delivery_charge_maximum);
      data.append('delivery_charge_value', this.delivery_charge_value);
      data.append('delivery_charge_type', this.delivery_charge_type);
      data.append('deliveryChargeBasedOn', this.dev_change.deliveryChargeBasedOn);
      data.append('carrier_type', value5);

      this.$http
        .post("/admin/add_to_restaurants_tab5", data)
        .then(
          (response) => {

            this.delivery_charge_minimum = '';
            this.delivery_charge_maximum = '';
            this.delivery_charge_value = '';
            this.delivery_charge_type = '';
            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t('Delivery Charge Details Updated Successfully'),
                icon: 'CheckIcon',
                variant: 'success',
              },
            })
          }
          // console.log(response.data)
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));

    },


    business(event) {
      this.$http.get('/admin/business_type/' + event)
        .then(res => {
          this.layout_type = res.data.layout_id
          if (res.data.layout_id == 2) {
            this.disabled = false;
            this.isLayout_cuisines = false;
            this.isLayout_deliver_types = true;
            this.isLayout_brand_types = true;
            this.isLayout_package_types = true;
            this.isLayout_video_type = false;
            this.isLayout_tax = false;
            this.isLayout_Carrier_type = false;
            this.isLayout_delivery_charge = true;
          }
          else if (res.data.layout_id == 3) {
            this.disabled = false;
            this.isLayout_cuisines = false;
            this.isLayout_deliver_types = false;
            this.isLayout_brand_types = false;
            this.isLayout_package_types = false;
            this.isLayout_video_type = false;
            this.isLayout_tax = true;
            this.isLayout_Carrier_type = true;
            this.isLayout_delivery_charge = false;

          }
          else {
            this.disabled = false;
            this.isLayout_cuisines = true;
            this.isLayout_deliver_types = true;
            this.isLayout_brand_types = true;
            this.isLayout_package_types = true;
            this.isLayout_video_type = false;
            this.isLayout_tax = false;
            this.isLayout_Carrier_type = false;
            this.isLayout_delivery_charge = true;
          }


        })

    },
  },




}

</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-wizard.scss';
@import '~@resources/scss/vue/libs/vue-select.scss';
@import '~@resources/scss/vue/pages/page-profile.scss';

.banner-img {
  width: 33%;
}

.repeater-form {
  overflow: hidden;
  transition: .35s height;
}

.wizard-icon-container {
  background-color: #117439 !important;
}

.stepTitle {
  color: #117439 !important;
}

.wizard-btn {
  background-color: #117439 !important;
  border-color: #117439 !important;
  color: white !important;
}

.add-new-client-header {
  padding: $options-padding-y $options-padding-x;
  color: $success;

  cursor: pointer;
}

.pac-container {

  z-index: 10000 !important;
}
</style>
<style scoped>
#gmap {
  height: 400px;
  width: 100%;
}
</style>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-flatpicker.scss';
</style>