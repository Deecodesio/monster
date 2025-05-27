<template>
  <b-card>
    <b-tabs pills>
      <b-tab active>
        <template #title>
          <feather-icon icon="UserIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('Store') }} {{ $t('details') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>
          <div class="d-flex">
            <h4 class="mb-0 ml-50">
            </h4>
          </div>
          <b-form @submit.prevent="restaurant">
            <b-row>
              <input id="banner_image" ref="onFileChange" type="text" class="d-none" v-b-modal.modal-xl />
              <input id="logo_image" hidden="hidden" ref="onFileChange1" type="text" class="d-none" v-b-modal.modal-xl1 />
              <b-col md="12">
                <div id="user-profile">
                  <b-card id="cover_img" class="profile-header mb-2"
                    :img-src="url ? url : require('@/assets/images/banner/empty-banner.png')" img-top alt="cover photo"
                    body-class="p-0" rounded fluid>
                    <div class="position-relative">
                      <div class="profile-img-container d-flex align-items-center">
                        <div class="profile-img">
                          <b-form-input id="banner_id" hidden="hidden" type="text" value="1" />
                          <b-form-input id="logo_id" hidden="hidden" type="text" value="1" />
                          <input id="res_id" name="res_id" hidden="hidden" type="text" />
                          <b-img id="profile" :src="url1 ? url1 : require('@/assets/images/banner/empty-logo.png')"
                            rounded fluid alt="profile photo" @click="$refs.onFileChange1.click()" />
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
                              <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary"
                                @click="$refs.onFileChange.click()" class="ml-auto">
                                <feather-icon icon="PlusIcon" class="mr-25" />
                                <span>{{ $t('Update') + ' ' + $t('cover') + ' ' + $t('photo') }}</span>
                              </b-button>



                              <!-- <b-dropdown id="dropdown-dropleft" v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                :text="$t('edit') + ' ' + $t('cover') + ' ' + $t('photo')" variant="primary"
                                class="ml-auto">
                                <b-dropdown-item>
                                  <a @click="$refs.onFileChange.click()">{{ $t('update') }} {{ $t('photo') }} </a>
                                </b-dropdown-item>
                              </b-dropdown> -->
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
                      <b-col md="12">
                        <b-form-group :label="$t('Business') + ' ' + $t('type')" label-for="name">
                          <v-select v-model="restaurants.business_type" :options="this.business_type" label="name"
                            :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('Business')"
                            @input="business($event)" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" disabled />

                        </b-form-group>
                      </b-col>
                      <b-col md="12" id="primary" v-if="primary_name">
                        <b-form-group :label="$t('Store') + ' ' + $t('name')">
                          <validation-provider #default="{ errors }" name="name"
                            :custom-messages="{ required: $t('The name field is required') }" rules="required">
                            <b-form-input id="name" v-model="restaurants.restaurant_name" type="text"
                              :state="errors.length > 0 ? false : null"
                              :placeholder="$t('enter') + ' ' + $t('Store') + ' ' + $t('name')" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="12" id="secondary" v-if="!primary_name">
                        <b-form-group :label="$t('Store') + ' ' + $t('name')">
                          <b-form-input id="name" v-model="restaurants.restaurant_secondary_name" type="text"
                            :placeholder="$t('enter') + ' ' + $t('Store') + ' ' + $t('name')" />
                        </b-form-group>
                      </b-col>
                      <b-col md="12">
                        <b-form-group :label="$t('email') + '*'">
                          <validation-provider #default="{ errors }" name="email"
                            :custom-messages="{ required: $t('The email field is required') }" rules="required|email">
                            <b-form-input id="email" v-model="restaurants.email" type="email"
                              :state="errors.length > 0 ? false : null" :placeholder="$t('enter') + ' ' + $t('email')" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="12">
                        <b-form-group :label="$t('password')">
                          <validation-provider #default="{ errors }" name="Password"
                            :custom-messages="{ required: $t('The Password field is required') }" vid="Password">
                            <b-form-input id="password" v-model="restaurants.password" type="text"
                              :state="errors.length > 0 ? false : null" placeholder="Password" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="12">
                        <validation-provider #default="{ errors }" name="City" rules="required">
                          <b-form-group :label="$t('select') + ' ' + $t('city') + '*'">
                            <v-select v-model="restaurants.city" @input="onChange($event)" :options="cities" label="city"
                              :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('city')" id="city_list"
                              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" autocomplete="off" />
                            <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </validation-provider>
                      </b-col>
                      <b-col md="12">
                        <validation-provider #default="{ errors }" name="Area" rules="required">
                          <b-form-group :label="$t('select') + ' ' + $t('area') + '*'">
                            <v-select v-model="restaurants.area" :options="areas" label="area" :reduce="sel => sel.id"
                              :placeholder="$t('select') + ' ' + $t('area')"
                              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
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
                              :fetch-country=true>
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
                              :fetch-country=true>
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
                              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="deliveryTime" label="text"
                              :reduce="sel => sel.text" />
                            <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </validation-provider>
                      </b-col>
                      <b-col md="12" v-if="isLayout_package_types">
                        <b-form-group :label="$t('packaging') + ' ' + $t('charge') + '(%)*'">
                          <validation-provider #default="{ errors }" name="Packaging charge"
                            :custom-messages="{ required: $t('The Packaging charge field is required') }"
                            rules="required">
                            <b-form-input v-model="restaurants.packaging_charge" type="number"
                              :state="errors.length > 0 ? false : null" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="12">
                        <b-form-group :label="$t('geofence') + ' ' + $t('radius')">
                          <validation-provider #default="{ errors }" name="Geofence Radius"
                            :custom-messages="{ required: $t('The Geofence Radius field is required') }" rules="required">
                            <b-form-input v-model="restaurants.GeofenceRadius" type="number"
                              :state="errors.length > 0 ? false : null" placeholder="0" />
                            <small class="text-danger">{{ errors[0] }}</small>
                          </validation-provider>
                        </b-form-group>
                      </b-col>
                      <b-col md="12" v-if="isLayout_deliver_types">
                        <b-form-group :label="$t('delivery') + ' ' + $t('type') + '*'">
                          <b-form-checkbox style="margin-top: 2%;" v-for="option in options_check" :key="option.value"
                            :value="option.value" :id="'del' + option.value" v-model="restaurants.delivery_type"
                            name="delivery_name">{{ option.text }}</b-form-checkbox>
                        </b-form-group>
                      </b-col>
                      <!-- <b-col md="12" v-if="isLayout_video_type">
                        <b-form-group :label="$t('Video') + ' ' + $t('Reviews')">
                          <b-form ref="form" :style="{ height: trHeight }" class="repeater-form"
                            @submit.prevent="repeateAgain" style="margin-top: 2%;">
                            <b-row v-for="(item, index) in items" :key="index" ref="row">
                              <b-col md="5">
                                <b-form-group :label="$t('video') + ' ' + $t('titles')">
                                  <b-form-input :id="'title' + index" type="text" v-model="SelectedTitle[index]" />
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
                        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" @click="repeateAgain">
                          <feather-icon icon="PlusIcon" class="mr-25" />
                          <span>{{ $t('add') }} {{ $t('video') }}</span>
                        </b-button>
                        <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="danger" @click="removeItem()">
                          <feather-icon icon="XIcon" class="mr-25" />
                          <span>{{ $t('remove') }} {{ $t('video') }}</span>
                        </b-button>
                      </b-col> -->
                    </b-row>
                  </b-col>
                  <b-col lg="6" md="12" sm="12">
                    <b-col md="12">
                      <b-form-group :label="$t('address') + '*'">
                        <validation-provider #default="{ errors }" name="Address"
                          :custom-messages="{ required: $t('The Address field is required') }" rules="required">
                          <b-form-input v-model="restaurants.address" :state="errors.length > 0 ? false : null"
                            :placeholder="$t('enter') + ' ' + $t('address')" id="searchMadminnput" />
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
                        <validation-provider #default="{ errors }" name="Commision"
                          :custom-messages="{ required: $t('The Commision field is required') }" rules="required">
                          <b-form-input v-model="restaurants.admin_commision" type="number"
                            :state="errors.length > 0 ? false : null" placeholder="0" disabled />
                          <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                      </b-form-group>
                    </b-col>
                    <b-col md="12">
                      <b-form-group :label="$t('status') + '*'">
                        <v-select v-model="restaurants.status" :options="options" label="text" :reduce="sel => sel.value"
                          :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />
                      </b-form-group>
                    </b-col>
                    <b-col md="12" v-if="isLayout_cuisines">
                      <b-form-group :label="$t('cuisines') + '*'">
                        <b-form-checkbox style="margin-top: 2%;" v-for="cuisines1 in cuisines" :key="cuisines1.id"
                          :value="cuisines1.id" :id="'cus' + cuisines1.id" v-model="restaurants.cuisine_ids"
                          name="cuisines_name">{{ cuisines1.name }}</b-form-checkbox>
                      </b-form-group>
                    </b-col>
                  </b-col>
                </b-row>
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
          <feather-icon icon="ClockIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('Store') }} {{ $t('timing') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>
          <div class="d-flex">
            <h4 class="mb-0 ml-50">
            </h4>
          </div>
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
                  <h5> {{ $t('weekday_1') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder11'" style="margin-bottom: 3%;" v-model="opening_time_1"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder31'" style="margin-bottom: 3%;" v-model="opening_second_time_1"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder21'" style="margin-bottom: 3%;" v-model="closing_time_1"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder41'" style="margin-bottom: 3%;" v-model="closing_second_time_1"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
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
                  <h5> {{ $t('weekday_2') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder12'" style="margin-bottom: 3%;" v-model="opening_time_2"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder32'" style="margin-bottom: 3%;" v-model="opening_second_time_2"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder22'" style="margin-bottom: 3%;" v-model="closing_time_2"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder42'" style="margin-bottom: 3%;" v-model="closing_second_time_2"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
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
                  <h5> {{ $t('weekday_3') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder13'" style="margin-bottom: 3%;" v-model="opening_time_3"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder33'" style="margin-bottom: 3%;" v-model="opening_second_time_3"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder23'" style="margin-bottom: 3%;" v-model="closing_time_3"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder43'" style="margin-bottom: 3%;" v-model="closing_second_time_3"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
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
                  <h5> {{ $t('weekday_4') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder14'" style="margin-bottom: 3%;" v-model="opening_time_4"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder34'" style="margin-bottom: 3%;" v-model="opening_second_time_4"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder24'" style="margin-bottom: 3%;" v-model="closing_time_4"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder44'" style="margin-bottom: 3%;" v-model="closing_second_time_4"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
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
                  <h5> {{ $t('weekday_5') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder15'" style="margin-bottom: 3%;" v-model="opening_time_5"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder35'" style="margin-bottom: 3%;" v-model="opening_second_time_5"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder25'" style="margin-bottom: 3%;" v-model="closing_time_5"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder45'" style="margin-bottom: 3%;" v-model="closing_second_time_5"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
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
                  <h5> {{ $t('weekday_6') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder16'" style="margin-bottom: 3%;" v-model="opening_time_6"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder36'" style="margin-bottom: 3%;" v-model="opening_second_time_6"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder26'" style="margin-bottom: 3%;" v-model="closing_time_6"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder46'" style="margin-bottom: 3%;" v-model="closing_second_time_6"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
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
                  <h5> {{ $t('weekday_7') }}</h5>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder17'" style="margin-bottom: 3%;" v-model="opening_time_7"
                  class="form-control" :placeholder="$t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder37'" style="margin-bottom: 3%;" v-model="opening_second_time_7"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
              </b-col>
              <b-col md="4">
                <flat-pickr :id="'timepicker-placeholder27'" style="margin-bottom: 3%;" v-model="closing_time_7"
                  class="form-control" :placeholder="$t('close') + ' ' + $t('Time')"
                  :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i', }" />
                <flat-pickr :id="'timepicker-placeholder47'" style="margin-bottom: 3%;" v-model="closing_second_time_7"
                  class="form-control" :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')"
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
        </div>
      </b-tab>
      <b-tab>
        <template #title>
          <feather-icon icon="FileTextIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('document') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>
          <div class="d-flex">
            <h4 class="mb-0 ml-50">
            </h4>
          </div>
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
                    <b-form-datepicker :id="'dates' + item.id" v-model="doc_date[item.id]" :min="new Date()"
                      :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" />
                    <b-form-input :id="'date' + item.id" hidden="hidden" :value=doc_date[item.id] :key="item.id" />
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
        </div>
      </b-tab>
      <b-tab>
        <template #title>
          <feather-icon icon="LinkIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('bank') }} {{ $t('details') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>
          <div class="d-flex">
            <h4 class="mb-0 ml-50">
            </h4>
          </div>
          <b-form @submit.prevent="bank_details">
            <b-row>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Account Name"
                    :custom-messages="{ required: $t('The Account Name field is required') }" rules="required">
                    <b-form-input v-model="bank.account_name"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('name')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('bank') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Bank Name"
                    :custom-messages="{ required: $t('The Bank Name field is required') }" rules="required">
                    <b-form-input v-model="bank.bank_name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('bank') + ' ' + $t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Account Address"
                    :custom-messages="{ required: $t('The Account Address field is required') }" rules="required">
                    <b-form-input v-model="bank.account_address"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('address')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('branch') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Branch Name"
                    :custom-messages="{ required: $t('The Branch Name field is required') }" rules="required">
                    <b-form-input v-model="bank.branch_name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('branch') + ' ' + $t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('Account Number') + ' / ' + $t('IBAN Number') + '*'">
                  <validation-provider #default="{ errors }" name="Account Number / IBAN Number"
                    :custom-messages="{ required: $t('The Account Number / IBAN Numberfield is required') }"
                    rules="required">
                    <b-form-input v-model="bank.account_no"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('number') + ' / ' + $t('IBAN Number')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('branch') + ' ' + $t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Branch Address"
                    :custom-messages="{ required: $t('The Branch Address field is required') }" rules="required">
                    <b-form-input v-model="bank.branch_address" :state="errors.length > 0 ? false : null"
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
        </div>
      </b-tab>
    </b-tabs>
    <b-modal id="modal-xl" lazy scrollable title="Upload Store Banner" size="xl">
      <b-tabs v-model="tabIndex" pills>
        <b-tab active>
          <template #title>
            <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
            <span class="d-none d-sm-inline">{{ $t('Select File') }} </span>
          </template>
          <div>
            <b-row>
              <b-col cols="3" v-for="(banners, index) in banners" :key="index">
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
                    :scalable="true" :background="true" :rotatable="true" :src="imgSrc">
                  </vue-cropper>
                </div>
              </b-col>
            </b-row>
          </div>
        </b-tab>
      </b-tabs>
      <template #modal-footer v-if="tabIndex == 1">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1"
          @click="cropImage" style=" display: none;" id="crop_btn">{{ $t('Crop Image') }}</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="rotate"
          style=" display: none;" id="rotate_btn">{{ $t('Rotate') }}</b-button>
      </template>
    </b-modal>
    <b-modal id="modal-xl1" lazy scrollable title="Upload Store Logo" size="xl">
      <b-tabs v-model="tabIndex" pills>
        <b-tab active>
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
                    :scalable="true" :background="true" :rotatable="true" :src="imgSrc">
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
  </b-card>
</template>
<script>
import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import BCardCode from '@@core/components/b-card-code'
import ToastificationContent from '@@core/components/toastification/ToastificationContent.vue'
import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormSelect, BCardText, BFormFile, BFormTextarea, BFormDatepicker,
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BNavItem, BButton, BFormCheckbox,
  BForm, BFormTimepicker, BInputGroup, BInputGroupAppend, BMedia,
  BAvatar, BDropdown, BDropdownItem, BModal, VBModal, BTab, BAlert, BLink, BFormRadioGroup,
} from 'bootstrap-vue'
import { required, email } from '@@validations'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@@core/mixins/ui/transition'
import { useInputImageRenderer } from '@@core/comp-functions/forms/form-utils'
import { avatarText } from '@@core/utils/filter'
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
    BTab, BAlert, BLink, BFormRadioGroup,
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
    BDropdown, BDropdownItem, VueCropper, flatPickr, VuePhoneNumberInput
  },


  data() {
    return {
      url: null,
      url1: null,
      url2: null,
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
      dev_change: {},
      imgSrc: require('../../../assets/image.jpg'),
      cropImg: '',
      banners: {},
      images_logo: {},
      setting: {},
      delivery_charge_minimum: [],
      delivery_charge_maximum: [],
      delivery_charge_value: [],
      delivery_charge_type: [],
      options: [

        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },


      ],

      type_delivery: [
        { value: 1, text: 'Rate' },
        { value: 2, text: '%' },
        { value: 3, text: 'Each' },

      ],
      charge: [
        { text: 'Price', value: 1 },
        { text: 'Distance (km/miles)', value: 2 },

      ],
      selected_check: [],
      options_check: [
        { text: this.$t('home') + ' ' + this.$t('delivery'), value: '1' },
        { text: this.$t('pickup'), value: '2' },
        // { text: this.$t('dining'), value: '3' },
        // { text: this.$t('schedule') + ' ' + this.$t('order'), value: '4' },
      ],
      items: [{
        id: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],

      items_new: [{
        id_new: 1,
        selected: 'male',
        selected1: 'designer',
        prevHeight: 0,
      }],
      nextTodoId: 2,
      nextTodoId_new: 2,

      locale: this.$i18n.locale,
      deliveryTime: [
        { value: '1', text: '15-20' },
        { value: '2', text: '20-30' },
        { value: '3', text: '30-40' },
        { value: '4', text: '40-50' },
        { value: '5', text: '50-60' },

      ],
      primary_name: true,
      SelectedUrl: [],
      SelectedTitle: [],
      finalurl: [],
      finaltitle: [],
      holiday: [],

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
      business_type: [],
      tabIndex: 0,
      def_lat: '',
      def_lng: '',
      valid: '',
      file: [],

    }
  },

  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  mixins: [heightTransition],

  // props: {
  //   headerData: {
  //     type: Object,
  //     default: () => {},
  //   },
  // },

  setup(props) {

    // const { resolveUserRoleVariant } = useUsersList()

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

    // ? Demo Purpose => Update image on click of update
    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, base64 => {
      // eslint-disable-next-line no-param-reassign

      this.url = base64
      //  alert(this.url);

    })

    return {

      avatarText,
      roleOptions,
      statusOptions,
      permissionsData,

      //  ? Demo - Update Image on click of update button
      refInputEl,
      previewEl,
      inputImageRenderer,

    }
  },

  mounted() {
    // this.$http.get('/store/get_default_lat_lng')
    //   .then(res => {
    //     this.def_lat = res.data.def_lat
    //     this.def_lng = res.data.def_lng

    //   })
    // this.initMap()
    this.initTrHeight()
    this.importAll(require.context('@/assets/images/banner/', true, /\.jpg$/));
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  created() {


    window.addEventListener('resize', this.initTrHeight)

    this.$http.get('/store/cuisines_list1')
      .then(res => {
        this.cuisines = res.data
      })
    this.$http.get('/store/vehicle_list')
      .then(res => {

        this.vehicles = res.data

      })

    this.$http.get('/store/city_list')
      .then(res => {

        this.cities = res.data

      })

    this.$http.get('/store/document_view')

      .then(res => {
        this.document_view = res.data


      })
    this.$http.get('/store/get_banner_image')
      .then(res => {

        this.banners = res.data;

      })
    this.$http.get('/store/get_logo_image')
      .then(res => {

        this.images_logo = res.data;

      })
    this.$http.get('/store/get_lang')
      .then(res => {

        this.setting = res.data
      })

    this.$http.get('/store/business_lists')
      .then(res => {

        this.business_type = res.data;

      })
    this.$http.get('/store/edit_restaurant/' + localStorage.id)
      .then(res => {
        console.log(res.data.data)
        this.restaurants = res.data.data;
        if (this.restaurants.id) {
          document.getElementById('res_id').value = this.restaurants.id;
        }
        this.restaurants.city = parseInt(this.restaurants.city);
        this.restaurants.area = parseInt(this.restaurants.area);
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
        if (this.restaurants.lat) {
          document.getElementById('latitude').value = this.restaurants.lat;
        }
        if (this.restaurants.lng) {
          document.getElementById('longitude').value = this.restaurants.lng;
        }
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

        this.url = this.restaurants.banner;
        this.url1 = this.restaurants.image;
        this.def_lat = this.restaurants.lat;
        this.def_lng = this.restaurants.lng;
        document.getElementById('latitude').value = this.restaurants.lat;
        document.getElementById('longitude').value = this.restaurants.lng;
        this.opening_time_1 = this.restaurants.opening_time_1;
        this.closing_time_1 = this.restaurants.closing_time_1;
        this.opening_second_time_1 = this.restaurants.opening_second_time_1;
        this.closing_second_time_1 = this.restaurants.closing_second_time_1;

        this.opening_time_2 = this.restaurants.opening_time_2;
        this.closing_time_2 = this.restaurants.closing_time_2;
        this.opening_second_time_2 = this.restaurants.opening_second_time_2;
        this.closing_second_time_2 = this.restaurants.closing_second_time_2;

        this.opening_time_3 = this.restaurants.opening_time_3;
        this.closing_time_3 = this.restaurants.closing_time_3;
        this.opening_second_time_3 = this.restaurants.opening_second_time_3;
        this.closing_second_time_3 = this.restaurants.closing_second_time_3;

        this.opening_time_4 = this.restaurants.opening_time_4;
        this.closing_time_4 = this.restaurants.closing_time_4;
        this.opening_second_time_4 = this.restaurants.opening_second_time_4;
        this.closing_second_time_4 = this.restaurants.closing_second_time_4;

        this.opening_time_5 = this.restaurants.opening_time_5;
        this.closing_time_5 = this.restaurants.closing_time_5;
        this.opening_second_time_5 = this.restaurants.opening_second_time_5;
        this.closing_second_time_5 = this.restaurants.closing_second_time_5;



        this.opening_time_6 = this.restaurants.opening_time_6;
        this.closing_time_6 = this.restaurants.closing_time_6;
        this.opening_second_time_6 = this.restaurants.opening_second_time_6;
        this.closing_second_time_6 = this.restaurants.closing_second_time_6;



        this.opening_time_7 = this.restaurants.opening_time_7;
        this.closing_time_7 = this.restaurants.closing_time_7;
        this.opening_second_time_7 = this.restaurants.opening_second_time_7;
        this.closing_second_time_7 = this.restaurants.closing_second_time_7;

        if (this.restaurants.holidays != null) {
          this.restaurants.holidays.forEach(function (element) {
            document.getElementById('holiday' + element).checked = true;
          });
        }
        if (this.restaurants.restaurant_bank_details != null) {
          this.bank = this.restaurants.restaurant_bank_details;
        }

        if (this.restaurants.document.length > 0) {
          console.log("here")
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
        this.dc_limits = res.data.delivery_charge_limits
        for (var i = 0; i < this.restaurants.tax_id.length; i++) {
          this.selectedtax[this.restaurants.tax_id[i]] = this.restaurants.tax_val[i];
        }




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
      })



    this.$http.get('/admin/edit_restaurant/' + localStorage.id)
      .then(res => {
        this.initMap();
        this.video_title = res.data.data.video_title;
        this.video_url = res.data.data.video_url;
        if (this.video_title.length > 0) {
          this.video_title.forEach(function (element, index) {
            document.getElementById('title' + index).value = element;

          });
        }
        if (this.video_url.length > 0) {

          this.video_url.forEach(function (element, index) {
            document.getElementById('url' + index).value = element;

          });
        }


      })





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
  methods: {

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
    onTabChange(id) {
      if (id == 1) {
        this.primary_name = true
      }
      else {
        this.primary_name = false
      }
    },
    change() {
      // this.imgSrc = this.imgSrc;
      // rebuild cropperjs with the updated source
      this.$refs.cropper.replace(this.imgSrc);
    },
    cropImage() {
      // get image data for post processing, e.g. upload or setting image src
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
    initMap() {

      const element = document.getElementById("gmap");
      var lati = document.getElementById('latitude').value;
      var long = document.getElementById('longitude').value;
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

        this.url = reader.result;
        document.getElementById('Banner1').value = this.url;

      };


      document.getElementById('banner_id').value = "1";

    },
    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
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


    mounted() {
      this.initTrHeight()
    },
    repeateAgain() {
      console.log(this.nextTodoId)
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(index) {
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        // this.trSetHeight(this.$refs.form.scrollHeight)
      })
    },


    restaurant() {
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


      // let value_1 = [];
      // let value_2 = [];
      // for (let i = 0; i < this.items.length; i++) {
      //   value_1.push(document.getElementById('title' + i).value);
      //   value_2.push(document.getElementById('url' + i).value);


      // }
      this.finaltitle = []
      this.finalurl = []

      for (var i = 0; i < this.SelectedTitle.length; i++) {

        this.finaltitle[i] = this.SelectedTitle[i]
        this.finalurl[i] = this.SelectedUrl[i]


      }
      let data = new FormData();
      var id = document.getElementById('res_id').value;
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
      data.append('video_title', JSON.stringify(this.finaltitle));
      data.append('video_url', JSON.stringify(this.finalurl));
      data.append('contact', this.restaurants.contact);
      data.append('latitude', document.getElementById('latitude').value);
      data.append('longitude', document.getElementById('longitude').value);
      data.append('existing1', this.restaurants.existing1);
      data.append('existing', this.restaurants.existing);
      data.append('business_type', this.restaurants.business_type);
      data.append('country_code', this.formattedCountryCode);
      data.append('country_calling_code', this.formattedCountryCallingCode);
      data.append('country_code_contact', this.formattedCountryCode2);
      data.append('country_calling_code_contact', this.formattedCountryCallingCode2);

      console.log(data);
      this.$http
        .post("/store/add_to_restaurants_tab1", data)
        .then((response) => {
          document.getElementById('res_id').value = response.data.id;
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

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
    Restaurant_Timing() {

      let checkboxes2 = document.querySelectorAll('input[name="holidays"]:checked');
      let values2 = [];
      checkboxes2.forEach((checkboxes) => {
        values2.push(checkboxes.value);
      });
      this.restaurants_time.holidays = values2;
      var id = document.getElementById('res_id').value;
      let data = new FormData();
      data.append('id', id);
      data.append('opening_time_1', this.opening_time_1);
      data.append('closing_time_1', this.closing_time_1);
      data.append('opening_second_time_1', this.opening_second_time_1);
      data.append('closing_second_time_1', this.closing_second_time_1);

      data.append('opening_time_2', this.opening_time_2);
      data.append('closing_time_2', this.closing_time_2);
      data.append('opening_second_time_2', this.opening_second_time_2);
      data.append('closing_second_time_2', this.closing_second_time_2);

      data.append('opening_time_3', this.opening_time_3);
      data.append('closing_time_3', this.closing_time_3);
      data.append('opening_second_time_3', this.opening_second_time_3);
      data.append('closing_second_time_3', this.closing_second_time_3);

      data.append('opening_time_4', this.opening_time_4);
      data.append('closing_time_4', this.closing_time_4);
      data.append('opening_second_time_4', this.opening_second_time_4);
      data.append('closing_second_time_4', this.closing_second_time_4);

      data.append('opening_time_5', this.opening_time_5);
      data.append('closing_time_5', this.closing_time_5);
      data.append('opening_second_time_5', this.opening_second_time_5);
      data.append('closing_second_time_5', this.closing_second_time_5);

      data.append('opening_time_6', this.opening_time_6);
      data.append('closing_time_6', this.closing_time_6);
      data.append('opening_second_time_6', this.opening_second_time_6);
      data.append('closing_second_time_6', this.closing_second_time_6);

      data.append('opening_time_7', this.opening_time_7);
      data.append('closing_time_7', this.closing_time_7);
      data.append('opening_second_time_7', this.opening_second_time_7);
      data.append('closing_second_time_7', this.closing_second_time_7);
      data.append('holidays', this.restaurants_time.holidays);

      this.$http
        .post("/store/add_to_restaurants_tab2", data)
        .then(
          (response) => {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t('Store Timing Updated Successfully'),
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
    Document() {


      var id = document.getElementById('res_id').value;
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
        .post("/store/add_to_restaurants_tab3", data)
        .then(
          (response) => {

            this.$toast({
              component: ToastificationContent,
              position: 'bottom-right',
              props: {
                title: this.$t('Document Updated Successfully'),
                icon: 'CheckIcon',
                variant: 'success',
              },
            })

          }
          // console.log(response.data)
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));

      resolve(true)

    },
    bank_details() {


      this.bank.id = document.getElementById('res_id').value;
      //  let data = new FormData();
      //     data.append('file', this.driver.profile_pic);
      //     data.append('email', this.driver.email);
      this.$http
        .post("/store/add_to_restaurants_tab4", this.bank)
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
          // console.log(response.data)
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));

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

.repeater-form1 {
  overflow: hidden;
  transition: .35s height;
}

.wizard-icon-container {
  background-color: rgb(255 196 70) !important;
}

.stepTitle {
  color: rgb(255 212 119) !important;
}

.wizard-btn {
  background-color: rgb(255 210 113) !important;
  border-color: rgb(255 211 118) !important;
  color: white !important;
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