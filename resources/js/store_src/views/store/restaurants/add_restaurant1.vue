<template>
  <b-card>
    <b-tabs pills>

      <!-- Tab: Account -->
      <b-tab active>
        <template #title>
          <feather-icon icon="UserIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline" >{{ $t('Store') }} {{ $t('details') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="restaurant">

            <b-row>



              <input id="banner_image"  ref="onFileChange" type="text" class="d-none"
                  v-b-modal.modal-xl />


                  <input id="logo_image" hidden="hidden" ref="onFileChange1" type="text" class="d-none"
                v-b-modal.modal-xl1 />
              <b-col md="12">
                <div id="user-profile">

                  <b-card id="cover_img" class="profile-header mb-2"
                    :img-src="url ? url : require('@/assets/images/banner/empty-banner.png')" img-top alt="cover photo"
                    body-class="p-0" rounded fluid>

                    <!-- profile picture -->
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
                    <!--/ profile picture -->



                    <!-- profile navbar -->
                    <div class="profile-header-nav">
                      <b-navbar toggleable="md" type="light">
                        <!-- toggle button -->
                        <b-navbar-toggle class="ml-auto" target="nav-text-collapse">
                          <feather-icon icon="AlignJustifyIcon" size="21" />
                        </b-navbar-toggle>
                        <!--/ toggle button -->

                        <!-- collapse -->
                        <b-collapse id="nav-text-collapse" is-nav>
                          <b-tabs pills class="profile-tabs mt-1 mt-md-0" nav-class="mb-0">


                            <!-- edit buttons -->
                            <template #tabs-end>
                              <b-dropdown id="dropdown-dropleft" v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                                :text="$t('edit') + ' ' + $t('cover') + ' ' + $t('photo')" variant="primary"
                                class="ml-auto">
                                <b-dropdown-item>
                                  <a @click="$refs.onFileChange.click()">{{ $t('update') }} {{ $t('photo') }} </a>
                                </b-dropdown-item>

                              </b-dropdown>
                            </template>
                            <!-- edit buttons -->
                          </b-tabs>

                        </b-collapse>
                        <!--/ collapse -->
                      </b-navbar>
                    </div>
                    <!--/ profile navbar -->
                  </b-card>

                </div>
              </b-col>
              <b-col md="12">

<b-tabs>
    <b-tab active  @click="onTabChange(1)" >
      <template #title>
          <i class="fa fa-language" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">English</span>
      </template>
     
    </b-tab>

    <!-- Tab: Information -->
    <b-tab @click="onTabChange(2)">
      <template #title>
          <i class="fa fa-language" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">{{ setting.value }}</span>
      </template>
     
    
      

    </b-tab>

  </b-tabs>

</b-col>

              <b-col md="6">
                  <b-form-group :label="$t('Business')+' '+$t('type')" label-for="name">
                    <v-select v-model="restaurants.business_type" :options="this.business_type" label="name" :reduce="sel => sel.id"
                      :placeholder="$t('select')+' '+$t('Business')" 
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" disabled/>

                  </b-form-group>
                </b-col>

              <b-col md="6" id="primary" style="display: block;">
                  <b-form-group :label="$t('restaurant')+' '+$t('name')" label-for="name" v-if="naming">
                    <validation-provider #default="{ errors }" name="name" rules="required">
                      <b-form-input id="name" v-model="restaurants.restaurant_name" type="text"
                        :state="errors.length > 0 ? false : null" :placeholder="$t('enter')+' '+$t('restaurant')+' '+$t('name')" :disabled="disabled" required/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                  <b-form-group :label="$t('Store')+' '+$t('name')" label-for="name" v-else>
                    <validation-provider #default="{ errors }" name="name" rules="required">
                      <b-form-input id="name" v-model="restaurants.restaurant_name" type="text"
                        :state="errors.length > 0 ? false : null" :placeholder="$t('enter')+' '+$t('Store')+' '+$t('name')" :disabled="disabled" required/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col md="6" id="secondary" style="display: none;">
                  <b-form-group :label="$t('restaurant')+' '+$t('name')" label-for="name" v-if="naming">
                    <validation-provider #default="{ errors }" name="name" rules="required">
                      <b-form-input id="name" v-model="restaurants.restaurant_secondary_name" type="text"
                        :state="errors.length > 0 ? false : null" :placeholder="$t('enter')+' '+$t('restaurant')+' '+$t('name')" :disabled="disabled"/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                  <b-form-group :label="$t('Store')+' '+$t('name')" label-for="name" v-else>
                    <validation-provider #default="{ errors }" name="name" rules="required">
                      <b-form-input id="name" v-model="restaurants.restaurant_secondary_name" type="text"
                        :state="errors.length > 0 ? false : null" :placeholder="$t('enter')+' '+$t('Store')+' '+$t('name')" :disabled="disabled"/>
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

              <b-col md="6">
                <b-form-group :label="$t('email') + '*'">
                  <validation-provider #default="{ errors }" name="email" rules="required|email">
                    <b-form-input id="email" v-model="restaurants.email" type="email"
                      :state="errors.length > 0 ? false : null" :placeholder="$t('enter') + ' ' + $t('email')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6" style="display:block" id="package_types">
                <b-form-group :label="$t('packaging') + ' ' + $t('charge') + '(%)*'">
                  <validation-provider #default="{ errors }" name="Packaging charge" rules="required">
                    <b-form-input v-model="restaurants.packaging_charge" type="number"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('select') + ' ' + $t('city') + '*'">
                  <v-select v-model="restaurants.city" @input="onChange($event)" :options="this.cities" label="city"
                    :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('city')" id="city_list"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

                </b-form-group>
              </b-col>

              <b-col md="6">
                <b-form-group :label="$t('password')">
                  <validation-provider #default="{ errors }" name="Password" vid="Password">
                    <b-form-input id="password" v-model="restaurants.password" type="text"
                      :state="errors.length > 0 ? false : null" placeholder="Password" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>

              <b-col md="6">
                <b-form-group :label="$t('select') + ' ' + $t('area') + '*'">
                  <v-select v-model="restaurants.area" :options="this.areas" label="area" :reduce="sel => sel.id"
                    :placeholder="$t('select') + ' ' + $t('area')"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

                </b-form-group>
              </b-col>

              <b-col md="6">
                            <b-form-group :label="$t('mobile') + ' ' + $t('number') + '*'">
                              <validation-provider #default="{ errors }" name="mobile number" rules="required"
                              :custom-messages="{required: $t('The number field is required')}">
                              <VuePhoneNumberInput 
                                id="phone1"
                                no-example 
                                @update="updatePhoneNumber"
                                v-model="restaurants.phone"
                                :state="errors[0] ? false : (valid ? true : null)"
                                :fetch-country=true
                              >
                              </VuePhoneNumberInput>
                                <small class="text-danger">{{ errors[0] }}</small>
                              </validation-provider>
                            </b-form-group>
                          </b-col>
  
                          <b-col md="6">
                            <b-form-group :label="$t('contact') + ' ' + $t('number') + '*'">
                              <validation-provider #default="{ errors }" name="contact number" rules="required"
                              :custom-messages="{required: $t('The number field is required')}">
                              <VuePhoneNumberInput 
                                id="phone1"
                                no-example 
                                @update="updatePhoneNumber2"
                                v-model="restaurants.contact"
                                :state="errors[0] ? false : (valid ? true : null)"
                                :fetch-country=true
                              >
                              </VuePhoneNumberInput>
                                <small class="text-danger">{{ errors[0] }}</small>
                              </validation-provider>
                            </b-form-group>
                          </b-col>
              <b-col md="6">
                <b-form-group :label="$t('status') + '*'">
                  <v-select v-model="restaurants.status" :options="options" label="text" :reduce="sel => sel.value"
                    :placeholder="$t('select status')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group
                  :label="$t('estimated') + ' ' + $t('delivery') + ' ' + $t('Time') + '(' + $t('mins') + ')' + '*'">
                  <validation-provider #default="{ errors }" name="Estimated Delivery" rules="required">
                    <b-form-input v-model="restaurants.estimated_delivery_time" type="number"
                      :state="errors.length > 0 ? false : null" placeholder="15-20" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <!-- <b-col md="6">
    <b-form-group label="Brand">
      <v-select v-model="restaurants.brands" :options="this.brand" label="name" :reduce="sel => sel.id"
        placeholder="Select Brands" class="brand_name"
        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />


    </b-form-group>
  </b-col> -->
              <b-col md="6">
                <b-form-group :label="$t('admin') + ' ' + $t('commision') + '%'">
                  <validation-provider #default="{ errors }" name="Commision" rules="required">
                    <b-form-input v-model="restaurants.admin_commision" type="number"
                      :state="errors.length > 0 ? false : null" placeholder="0" disabled/>
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>


              <!-- 
  <b-col md="6">
    <b-form-group label="Driver Commission %">
      <validation-provider #default="{ errors }" name="DriverCommission" rules="required">
        <b-form-input v-model="restaurants.driver_commision" :state="errors.length > 0 ? false : null"
          placeholder="0" />
        <small class="text-danger">{{ errors[0] }}</small>
      </validation-provider>
    </b-form-group>
  </b-col> -->

              <b-col md="6">
                <b-form-group :label="$t('geofence') + ' ' + $t('radius')">
                  <validation-provider #default="{ errors }" name="Geofence Radius" rules="required">
                    <b-form-input v-model="restaurants.GeofenceRadius" type="number"
                      :state="errors.length > 0 ? false : null" placeholder="0" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6" style="display:block" id="column">

              </b-col>
              <b-col md="6"  style="display:block" id="deliver_types">
                <b-form-group :label="$t('delivery') + ' ' + $t('type') + '*'">

                  <b-form-checkbox style="margin-top: 2%;" v-for="option in options_check" :key="option.value"
                    :value="option.value" :id="option.value" name="delivery_name">{{ option.text }}</b-form-checkbox>


                </b-form-group>
              </b-col>
              <b-col md="6" style="display:block" id="cuisines_list">
                <b-form-group :label="$t('cuisines') + '*'">
                  <b-form-checkbox style="margin-top: 2%;" v-for="cuisines1 in cuisines" :key="cuisines1.id"
                    :value="cuisines1.id" :id="cuisines1.id" name="cuisines_name">{{ cuisines1.name }}</b-form-checkbox>



                </b-form-group>
              </b-col>
             

              <b-col md="6">
                <b-form-group :label="$t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Address" rules="required">
                    <b-form-input v-model="restaurants.address" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('address')" id="searchMadminnput" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                  <br />
                  <input type="hidden" id="latitude" name="latitude" value="12.9813658">
                  <input type="hidden" id="longitude" name="longitude" value="80.2199712">
                  <div id="gmap"></div>
                </b-form-group>
              </b-col>


              <b-col md="6" style="display:block" id="video_type">
                <div>
                  <b-form ref="form" :style="{ height: trHeight }" class="repeater-form" @submit.prevent="repeateAgain">

                    <!-- Row Loop -->
                    <b-row v-for="(item, index) in items" :id="item.id" :key="item.id" ref="row">

                      <!-- Item Name -->
                      <b-col md="5">
                        <b-form-group :label="$t('video') + ' ' + $t('titles')">
                          <b-form-input :id="'title' + index" type="text" />
                        </b-form-group>
                      </b-col>

                      <!-- Cost -->
                      <b-col md="5">
                        <b-form-group :label="$t('video') + ' ' + $t('url')">
                          <b-form-input :id="'url' + index" type="text" />
                        </b-form-group>
                      </b-col>



                      <!-- Remove Button -->
                      <b-col lg="2" md="3" class="mb-50">

                      </b-col>
                      <b-col cols="12">
                        <hr>
                      </b-col>
                    </b-row>

                  </b-form>
                </div>
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" id="add1" variant="primary" @click="repeateAgain">
                  <feather-icon icon="PlusIcon" class="mr-25" />
                  <span>{{ $t('add') }} {{ $t('video') }}</span>
                </b-button>
                <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="danger" @click="removeItem(index)">
                  <feather-icon icon="XIcon" class="mr-25" />
                  <span>{{ $t('remove') }} {{ $t('video') }}</span>
                </b-button>

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

      <!-- Tab: Information -->
      <b-tab>
        <template #title>
          <feather-icon icon="ClockIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('Store') }} {{ $t('timing') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="Restaurant_Timing">
            <b-row style="margin: 2%;">

              <b-col md="2">
                <b-form-group :label="$t('day')" style="font-weight: bold;">
                </b-form-group>


              </b-col>
              <b-col md="4">
      <b-form-group :label="$t('resturant')+' '+$t('opens')+'*'" style="font-weight: bold;"  v-if="naming">
      </b-form-group>
      <b-form-group :label="$t('Store')+' '+$t('opens')+'*'" style="font-weight: bold;"  v-else>
      </b-form-group>
    </b-col>
    <b-col md="4">
      <b-form-group :label="$t('resturant')+' '+$t('closes')+'*'" style="font-weight: bold;" v-if="naming">
      </b-form-group>
      <b-form-group :label="$t('Store')+' '+$t('closes')+'*'" style="font-weight: bold;" v-else>
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
                <b-form-timepicker :id="'timepicker-placeholder11'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_1" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder31'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_1" />
              </b-col>
              <b-col md="4">
               
                  <b-form-timepicker :id="'timepicker-placeholder21'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_1" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder41'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_1" />
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
                <b-form-timepicker :id="'timepicker-placeholder12'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_2" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder32'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_2" />
              </b-col>
              <b-col md="4">
               
                  <b-form-timepicker :id="'timepicker-placeholder22'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_2" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder42'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_2" />
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
                <b-form-timepicker :id="'timepicker-placeholder13'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_3" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder33'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_3" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder23'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_3" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder43'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_3" />
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
                <b-form-timepicker :id="'timepicker-placeholder14'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_4" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder34'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_4" />
              </b-col>
              <b-col md="4">
               
                  <b-form-timepicker :id="'timepicker-placeholder24'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_4" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder44'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_4" />
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
                <b-form-timepicker :id="'timepicker-placeholder15'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_5" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder35'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_5" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder25'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_5" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder45'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_5" />
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
                <b-form-timepicker :id="'timepicker-placeholder16'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_6" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder36'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_6" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder26'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_6" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder46'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_6" />
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
                <b-form-timepicker :id="'timepicker-placeholder17'" :placeholder="$t('open') + ' ' + $t('Time')"
                  local="en" v-model="opening_time_7" style="margin-bottom: 3%;"/>
                  <b-form-timepicker :id="'timepicker-placeholder37'"
                  :placeholder="$t('second') + ' ' + $t('open') + ' ' + $t('Time')" local="en"
                  v-model="opening_second_time_7" />
              </b-col>
              <b-col md="4">
                
                  <b-form-timepicker :id="'timepicker-placeholder27'" :placeholder="$t('close') + ' ' + $t('Time')"
                  local="en" v-model="closing_time_7" style="margin-bottom: 3%;"/>
                <b-form-timepicker :id="'timepicker-placeholder47'"
                  :placeholder="$t('second') + ' ' + $t('close') + ' ' + $t('Time')" local="en"
                  v-model="closing_second_time_7" />
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

      <!-- Tab: Social -->
      <b-tab>
        <template #title>
          <feather-icon icon="FileTextIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('document') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="Document">

            <div v-for="item in document_view">
              <b-row v-if="item.document_for == 2">


                <b-col md="6">
                  <b-form-group :label="item.document_name">

                    <b-form-file :id="'document' + item.id" @change="onFileChange3(item.id)" />
                  </b-form-group>
                  <b-form-group>
                    <img :id="'document_img' + item.id" rounded fluid class="banner-img" alt="banner photo" />
                  </b-form-group>
                </b-col>

                <b-col md="6" v-if="item.expiry_date_needed == 1">
                  <b-form-group :label="item.document_name">

                    <b-form-datepicker :id="'dates'+item.id" v-model="doc_date[item.id]" :min="new Date()"  :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"/>
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



      <!-- Tab: Social -->
      <b-tab>
        <template #title>
          <feather-icon icon="LinkIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('bank') }} {{ $t('details') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="bank_details">
            <b-row>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Account Name" rules="required">
                    <b-form-input v-model="bank.account_name"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('name')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('bank') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Bank Name" rules="required">
                    <b-form-input v-model="bank.bank_name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('bank') + ' ' + $t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Account Address" rules="required">
                    <b-form-input v-model="bank.account_address"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('address')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('branch') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Branch Name" rules="required">
                    <b-form-input v-model="bank.branch_name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('branch') + ' ' + $t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('number') + '*'">
                  <validation-provider #default="{ errors }" name="Account Number" rules="required">
                    <b-form-input v-model="bank.account_no"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('number')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('branch') + ' ' + $t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Branch Address" rules="required">
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



      <!-- Tab: Social -->











    </b-tabs>


    
     <!-- banner Image Upload -->
  <b-modal
      id="modal-xl"
      lazy
      scrollable
      title="Scrollable Content"
      cancel-title="Close"
      size="xl"
      cancel-variant="outline-secondary"
    >
    <b-tabs pills>

<!-- Tab: Account -->
<b-tab active>
  <template #title>
    <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
    <span class="d-none d-sm-inline">Select File </span>
  </template>
  <div >
    <b-card-text v-for="(banners,index) in banners"  :key="index">
      
      <b-img :src="banners" rounded fluid style="width: 20%;padding: 1%" alt="banner photo"
      @click="myMethod(banners)"     id="lazy"  />
    </b-card-text>
          
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
        <b-form-file
        id="new_img"
      v-model="file"
      placeholder="Choose a file or drop it here..."
      drop-placeholder="Drop file here..."
      @change="onFileChange_banner"
    />
  </b-col>
      <b-col md="4"></b-col>
      <b-col md="12">

        <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
            <!-- assign ref property to access the underlying functions -->
            <!-- all cropperjs options should be in kebab-case instead of camelCase -->
            <!-- see http://vuejs.org/guide/components.html#camelCase-vs-kebab-case -->
            <vue-cropper
                ref='cropper'
                :guides="false"
                drag-mode="move"
                :aspectRatio="3/1"
                :restore="false"
                :crop-box-movable="false"
                :crop-box-resizable="false"
                :toggle-drag-mode-on-dblclick="false"
                :scalable="true"
                :background="true"
                :rotatable="true"
                :src="imgSrc">
            </vue-cropper>
           
        </div>
        <b-col md="12" style="display: flex;justify-content: center;margin-top: 20px;">
          <br />
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="cropImage" style=" display: none;" id="crop_btn">Crop Image</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="rotate" style=" display: none;" id="rotate_btn">Rotate</b-button>
        <!-- <button @click="change">Change Image</button> -->
        </b-col>
        <!-- <img :src="cropImg" style="max-width: 300px;" /> -->
      


      </b-col>



     
    </b-row>
   
  </div>
</b-tab>

<!-- Tab: Information -->
<b-tab>
  <template #title>
    <feather-icon icon="FilePlusIcon" size="16" class="mr-0 mr-sm-50" />
    <span class="d-none d-sm-inline">Upload New</span>
   
  </template>
  <div>
    <b-row>
      <b-col md="4"></b-col>
      <b-col md="4">
        <label></label>
        <b-form-file
      v-model="file"
      placeholder="Choose a file or drop it here..."
      drop-placeholder="Drop file here..."
      @change="onFileChange_banner1"
    />
  </b-col>
      <b-col md="4"></b-col>
    </b-row>
   
  </div>
</b-tab>

</b-tabs>
    </b-modal>

<!-- Logo Image Upload -->


<b-modal
      id="modal-xl1"
      lazy
      scrollable
      title="Scrollable Content"
      cancel-title="Close"
      size="xl"
      cancel-variant="outline-secondary"
    >
    <b-tabs pills>

<!-- Tab: Account -->
<b-tab active>
  <template #title>
    <feather-icon icon="ImageIcon" size="16" class="mr-0 mr-sm-50" />
    <span class="d-none d-sm-inline">Select File </span>
  </template>
  <div >
    <b-card-text v-for="(images_logo,index) in images_logo"  :key="index">
      
      <b-img :src="images_logo" rounded fluid style="width: 12%;padding: 1%" alt="banner photo"
      @click="myMethod1(images_logo)"     id="lazy"  />
    </b-card-text>
          
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
        <b-form-file
        id="new_img"
      v-model="file"
      placeholder="Choose a file or drop it here..."
      drop-placeholder="Drop file here..."
      @change="onFileChange_image"
    />
  </b-col>
      <b-col md="4"></b-col>
      <b-col md="12">

        <div style="max-width: 1200px;max-height: 640px; display: none;margin: auto;" id="crop_js">
            <!-- assign ref property to access the underlying functions -->
            <!-- all cropperjs options should be in kebab-case instead of camelCase -->
            <!-- see http://vuejs.org/guide/components.html#camelCase-vs-kebab-case -->
            <vue-cropper
                ref='cropper'
                :guides="false"
                drag-mode="move"
                :aspectRatio="1/1"
                :restore="false"
                :crop-box-movable="false"
                :crop-box-resizable="false"
                :toggle-drag-mode-on-dblclick="false"
                :scalable="true"
                :background="true"
                :rotatable="true"
                :src="imgSrc">
            </vue-cropper>
           
        </div>
        <b-col md="12" style="display: flex;justify-content: center;margin-top: 20px;">
          <br />
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="cropImage1" style=" display: none;" id="crop_btn">Crop Image</b-button>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1" @click="rotate1" style=" display: none;" id="rotate_btn">Rotate</b-button>
        <!-- <button @click="change">Change Image</button> -->
        </b-col>
        <!-- <img :src="cropImg" style="max-width: 300px;" /> -->
      


      </b-col>



     
    </b-row>
   
  </div>
</b-tab>

<!-- Tab: Information -->
<b-tab>
  <template #title>
    <feather-icon icon="FilePlusIcon" size="16" class="mr-0 mr-sm-50" />
    <span class="d-none d-sm-inline">Upload New</span>
   
  </template>
  <div>
    <b-row>
      <b-col md="4"></b-col>
      <b-col md="4">
        <label></label>
        <b-form-file
      v-model="file"
      placeholder="Choose a file or drop it here..."
      drop-placeholder="Drop file here..."
      @change="onFileChange_image1"
    />
  </b-col>
      <b-col md="4"></b-col>
    </b-row>
   
  </div>
</b-tab>

</b-tabs>
    </b-modal>
  </b-card>

</template>

<script>


import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'

import 'vue-form-wizard/dist/vue-form-wizard.min.css'
// dropdown
import BCardCode from '@@core/components/b-card-code'
//form validation//
import ToastificationContent from '@@core/components/toastification/ToastificationContent.vue'
import {
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BFormSelect,  BFormFile, BFormTextarea, BFormDatepicker,
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
    BFormSelect, 
    BCardCode,
    BFormFile,
    BFormTextarea,
    BCard, BImg, BNavbar, BNavbarToggle,
    BCollapse, BTabs, BNavItem, BButton,
    BFormCheckbox,
    BForm,
    BFormTimepicker, BInputGroup, BInputGroupAppend,
    BDropdown, BDropdownItem,VueCropper,VuePhoneNumberInput,
  },


  data() {
    return {
      url: null,
      url1: null,
      url2: null,
      image: [],
      business_type:[],
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
      naming:true,
      delivery_charge_minimum: [],
      delivery_charge_maximum: [],
      delivery_charge_value: [],
      delivery_charge_type: [],
      options: [

        { value: 1, text: this.$t('active') },
        { value: 0, text: this.$t('inactive') },


      ],
      setting:{},
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
        { text: this.$t('dining'), value: '3' },
        { text: this.$t('schedule') + ' ' + this.$t('order'), value: '4' },
      ],
      options_check2: [
        { text:this.$t('home')+' '+this.$t('delivery') , value: 1 },
        { text:this.$t('pickup'), value: 2 },
        { text: this.$t('dining'), value: 3 },
        { text: this.$t('schedule')+' '+this.$t('order'), value: 4 },
      ],
      options_check1: [
        { text:this.$t('home')+' '+this.$t('delivery') , value: 1 },
        { text:this.$t('pickup'), value: 2 },
        { text: this.$t('schedule')+' '+this.$t('order'), value: 4 },
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
    this.initMap()
    this.initTrHeight()
    this.importAll(require.context('@/assets/images/banner/', true, /\.jpg$/));
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  created() {


    window.addEventListener('resize', this.initTrHeight)

    this.$http.get('/store/cuisines_list')
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
      this.$http.get('/store/get_banner_image')
      .then(res => {

        this.banners = res.data;
          
      })
      this.$http.get('/store/get_logo_image')
      .then(res => {

        this.images_logo = res.data;
          
      })
    this.$http.get('/store/document_view')

      .then(res => {
        this.document_view = res.data


      })
      this.$http.get('/store/business_lists')
      .then(res => {

        this.business_type = res.data;
          
      })
      this.$http.get('/store/get_lang')
      .then(res => {

        this.setting = res.data
      })

    this.$http.get('/store/edit_restaurant/' + localStorage.id)
      .then(res => {
        console.log(res.data.data)
        // this.restaurants=res.data.cuisine_ids;
        this.restaurants = res.data.data;
        this.url = this.restaurants.banner;
        this.url1 = this.restaurants.image;

        this.restaurants.password = this.restaurants.org_password;
        this.restaurants.brands = this.restaurants.brand_name;
       
        this.$http.get('/store/business_type_rest/'+this.restaurants.business_type)
           .then(res => {
            console.log(res.data);
            if(res.data.layout_id == 2)
              {
                this.naming = false;
                document.getElementById("cuisines_list").style.display = "none";
                document.getElementById("deliver_types").style.display = "block";
                document.getElementById("package_types").style.display = "block";
                document.getElementById("video_type").style.display = "block";
                document.getElementById("column").style.display = "none";
                this.options_check = this.options_check1;
              }
              else if(res.data.layout_id == 3)
              {
                this.naming = false;
                document.getElementById("cuisines_list").style.display = "none";
                document.getElementById("deliver_types").style.display = "none";
                document.getElementById("package_types").style.display = "none";
                document.getElementById("video_type").style.display = "none";
                
                this.charge = this.charge_one;
              }
              else
              {
                this.naming = true;
                document.getElementById("cuisines_list").style.display = "block";
                document.getElementById("package_types").style.display = "block";
                document.getElementById("video_type").style.display = "block";
                document.getElementById("deliver_types").style.display = "block";
                this.options_check = this.options_check2;
              }
            })
            if (this.restaurants.delivery_type.length > 0) {
          this.restaurants.delivery_type.forEach(function (element) {
            document.getElementById(element).checked = true;
          });
        }
        this.areas = res.data.area;
        this.restaurants.city = parseInt(this.restaurants.city);
        this.restaurants.area = parseInt(this.restaurants.area);

        if (this.restaurants.lat) {
          document.getElementById('latitude').value = this.restaurants.lat;
        }
        if (this.restaurants.lng) {
          document.getElementById('longitude').value = this.restaurants.lng;

        }


       
        if (res.data.cuisines_new.length > 0) {
          res.data.cuisines_new.forEach(function (element) {
            document.getElementById(element.cuisine_id).checked = true;
          });
        }
        if (this.restaurants.id) {
          document.getElementById('res_id').value = this.restaurants.id;
        }





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



        if (this.restaurants.restaurant_bank_details != null) {
          this.bank = this.restaurants.restaurant_bank_details;
        }

        if (this.restaurants.document.length > 0) {
          this.restaurants.document.forEach(function (element) {
            document.getElementById("document_img" + element.id).src = element.pivot.document;
            if(element.expiry_date_needed == 1)
            {
                  document.getElementById('dates'+element.id).value = element.pivot.expiry_date;
            }
          });

        }







        if (this.restaurants.video_title.length > 0) {
          for (let i = 0; i < this.restaurants.video_title.length - 1; i++) {
            document.getElementById("add1").click();
          }
        }


        if (this.restaurants.holidays != null) {
          this.restaurants.holidays.forEach(function (element) {
            console.log('holiday' + element);
            document.getElementById('holiday' + element).checked = true;
          });
        }






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

    onTabChange(id) {
        if(id == 1)
        {
          document.getElementById("primary").style.display = 'block';
          document.getElementById("secondary").style.display = 'none';
        }
        else{
          document.getElementById("primary").style.display = 'none';
          document.getElementById("secondary").style.display = 'block';
        }
   
  },
    formSubmitted() {
      this.$toast({
        component: ToastificationContent,
        props: {
          title: 'Form Submitted',
          icon: 'EditIcon',
          variant: 'success',
        },
      })



    },

    change () {
            // this.imgSrc = this.imgSrc;
            // rebuild cropperjs with the updated source
            this.$refs.cropper.replace(this.imgSrc);
        },
        cropImage () {
            // get image data for post processing, e.g. upload or setting image src
            this.url = this.$refs.cropper.getCroppedCanvas({width: 1900,
          height: 500}).toDataURL();
            this.restaurants.banner = this.$refs.cropper.getCroppedCanvas({width: 1900,
          height: 500}).toDataURL();
            this.restaurants.existing = 2;
            this.$bvModal.hide('modal-xl');
        },
        rotate () {
            // guess what this does :)
            this.$refs.cropper.rotate(90);
        },
        cropImage1 () {
            // get image data for post processing, e.g. upload or setting image src
            this.url1 = this.$refs.cropper.getCroppedCanvas({width: 1200,
          height: 628}).toDataURL();
            this.restaurants.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
            this.restaurants.existing1 = 2;
            this.$bvModal.hide('modal-xl1');
        },
        rotate1 () {
            // guess what this does :)
            this.$refs.cropper.rotate(90);
        },
    onFileChange_banner(e)
    {
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

    onFileChange_image(e)
    {
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

    myMethod(image)
    {
      this.url = image;
      var filename = image.replace(/^.*[\\\/]/, '');
      this.restaurants.banner = filename;
      this.restaurants.existing = 1;
      this.$bvModal.hide('modal-xl');

    },
    myMethod1(image)
    {
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
        center: new google.maps.LatLng(Number(lati), Number(long))
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

    onFileChange3(id, e) {
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
        this.trSetHeight(this.$refs.form.scrollHeight)
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
     

      let value_1 = [];
      let value_2 = [];
      for (let i = 0; i < this.items.length; i++) {
        value_1.push(document.getElementById('title' + i).value);
        value_2.push(document.getElementById('url' + i).value);

        //  this.dev_change.delivery_charge_type = this.delivery_charge_type[i];

      }
      this.video_title = value_1;
      this.video_url = value_2;
      this.restaurants.address =  document.getElementById('searchMadminnput').value;
      let data = new FormData();
      var id = document.getElementById('res_id').value;
      data.append('id', localStorage.id);
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
      data.append('video_title', this.video_title);
      data.append('video_url', this.video_url);
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
              title: response.data.message,
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
                title: response.data.message,
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
                title: response.data.message,
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
                title: response.data.message,
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
  background-color: #117439 !important;
}

.stepTitle {
  color: #117439 !important;}

.wizard-btn {
  background-color: #117439 !important;
  border-color: #117439 !important;
  color: white !important;
}
</style>
<style scoped>
#gmap {
  height: 400px;
  width: 100%;
}
</style>
