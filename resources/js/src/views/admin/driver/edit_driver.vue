<template>
  <b-card>
    <b-tabs pills>

      <!-- Tab: Account -->
      <b-tab active>
        <template #title>
          <feather-icon icon="UserIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline"> {{ $t('driver') }} {{ $t('details') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="validationForm">

            <b-form-input id="res_id" v-model="driver.id" type="text" hidden="hidden" />
            <b-row>
              <b-col md="12" v-if="setting.lang.value == 1">

<b-tabs>
    <b-tab active  @click="onTabChange(1)" >
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
              <b-col md="4">
                <b-form-group :label="$t('Store') + ' ' + '(' + $t('optional') + ')'">
                  <v-select v-model="driver.restaurant_id" :options="rows" label="restaurant_name"
                    :reduce="sel => sel.id" :placeholder="$t('Select Store')"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />


                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('vehicle') + ' ' + $t('name') + '*'">
                  <v-select v-model="driver.vehicle_name" :options="vehicles" label="vehicle_name"
                    :reduce="sel => sel.id" :placeholder="$t('select') + ' ' + $t('vehicle')"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />


                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('email') + '*'">
                  <validation-provider #default="{ errors }" name="email" rules="required|email">
                    <b-form-input id="email" v-model="driver.email" type="email" :state="errors.length > 0 ? false : null"
                      placeholder="john.doe@email.com" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('work') + ' ' + $t('city')">
                  <v-select v-model="driver.city" :options="cities" label="city" :reduce="sel => sel.id"
                    :placeholder="$t('select') + ' ' + $t('city')"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" @input="onChange($event)">
                    <template #list-header>
                                                                    <li @click="open_citymodal"
                                                                        class="add-new-client-header d-flex align-items-center my-50">
                                                                        <feather-icon icon="PlusIcon" size="16" />
                                                                        <span class="align-middle ml-25">{{ $t('Add New City')}}</span>
                                                                    </li>
                                                                </template>
                                                              </v-select>

                </b-form-group>
              </b-col>
              <b-col md="4" v-if="!isHidden">
                <b-form-group :label="$t('driver') + ' ' + $t('name') + '*'">

                  <validation-provider #default="{ errors }" name="drivername"
                  :custom-messages="{required: $t('The Drivername field is required')}"
                  rules="required">
                    <b-form-input id="name" type="text" v-model="driver.name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4" v-if="isHidden">
                <b-form-group :label="$t('driver') + ' ' + $t('name') + '*'">


                    <b-form-input id="name" type="text" v-model="driver.second_driver_name"
                      :placeholder="$t('name')" />

                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('phone') + ' ' + $t('no') + '*'">
                  <validation-provider #default="{ errors }" name="number" rules="required">
                    <VuePhoneNumberInput
                      id="phone1"
                      no-example
                      @update="updatePhoneNumber"
                      v-model="driver.phone"
                      :state="errors[0] ? false : (valid ? true : null)"
                      :default-country-code = "driver.country_calling_code"

                    >
                    </VuePhoneNumberInput>
                    <!-- <b-form-input v-model="driver.phone" type="number" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('phone') + ' ' + $t('number')" /> -->
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('work') + ' ' + $t('area') + '(' + $t('optional') + ')'">
                  <v-select v-model="driver.area" :options="areas" label="area" :reduce="sel => sel.id"
                    :placeholder="$t('select') + ' ' + $t('area')"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" >
                    <template #list-header>
                                                                    <li @click="open_areamodal"
                                                                        class="add-new-client-header d-flex align-items-center my-50">
                                                                        <feather-icon icon="PlusIcon" size="16" />
                                                                        <span class="align-middle ml-25">{{ $t('Add New Area')}}</span>
                                                                    </li>
                                                                </template>
                                                              </v-select>

                </b-form-group>
              </b-col>
              <b-col md="4" v-if="!isHidden">
                <b-form-group :label="$t('address') + ' ' + $t('line') + '1 ' + '*'">
                  <validation-provider #default="{ errors }" name="Address"
                  :custom-messages="{required: $t('The Address field is required')}"
                  rules="required">
                    <b-form-input id="name" type="text" v-model="driver.address_line_1"
                      :state="errors.length > 0 ? false : null" :placeholder="$t('enter') + ' ' + $t('address')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4"  v-if="isHidden">
                <b-form-group :label="$t('address') + ' ' + $t('line') + '1 ' + '*'">

                    <b-form-input id="name" type="text" v-model="driver.second_address_line_1"
                      :placeholder="$t('enter') + ' ' + $t('address')" />

                </b-form-group>
              </b-col>
              <b-col md="4" v-if="!isHidden">
                <b-form-group :label="$t('address') + ' ' + $t('line') + '2'">
                  <b-form-input id="name" type="text" v-model="driver.address_line_2"
                    :placeholder="$t('enter') + ' ' + $t('address')" />
                </b-form-group>
              </b-col>
              <b-col md="4" v-if="isHidden">
                <b-form-group :label="$t('address') + ' ' + $t('line') + '2'">
                  <b-form-input id="name" type="text" v-model="driver.second_address_line_2"
                    :placeholder="$t('enter') + ' ' + $t('address')" />
                </b-form-group>
              </b-col>
              <b-col md="4" v-if="!isHidden">
                <b-form-group :label="$t('city') + '*'">
                  <validation-provider #default="{ errors }" name="City"
                  :custom-messages="{required: $t('The City field is required')}"
                  rules="required">
                    <b-form-input type="text" v-model="driver.address_city" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('city')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4" v-if="isHidden">
                <b-form-group :label="$t('city') + '*'">

                    <b-form-input type="text" v-model="driver.second_address_city"
                      :placeholder="$t('enter') + ' ' + $t('city')" />

                </b-form-group>
              </b-col>
              <b-col md="4" v-if="!isHidden">
                <b-form-group :label="$t('state/province') + '*'">
                  <validation-provider #default="{ errors }" name="State"
                  :custom-messages="{required: $t('The State field is required')}"
                  rules="required">
                    <b-form-input type="text" v-model="driver.state_province" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('state/province')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4" v-if="isHidden">
                <b-form-group :label="$t('state/province') + '*'">

                    <b-form-input type="text" v-model="driver.second_state_province"
                      :placeholder="$t('enter') + ' ' + $t('state/province')" />

                </b-form-group>
              </b-col>
              <b-col md="4" v-if="!isHidden">
                <b-form-group :label="$t('country') + '*'">
                  <validation-provider #default="{ errors }" name="Country"
                  :custom-messages="{required: $t('The Country field is required')}"
                  rules="required">
                    <b-form-input type="text" v-model="driver.country" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('country')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4" v-if="isHidden">
                <b-form-group :label="$t('country') + '*'">
                    <b-form-input type="text" v-model="driver.second_country"
                      :placeholder="$t('enter') + ' ' + $t('country')" />

                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('avatar') + '*'">

                  <b-form-file v-model="driver.profile_pic" drop-placeholder="Drag your image"
                    @change="onFileChange1" accept=".jpg, .png, .jpeg" />
                  <b-form-group>
                    <b-img :src="url1 ? url1 : driver.profile_pic" rounded fluid id="avator-img" alt="category photo"
                      v-if="driver.profile_pic" />
                    <b-img src="/no_image.png" id="avator-img" fluid alt="Scan" v-else />
                  </b-form-group>



                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('zip') + '' + $t('code') + '*'">
                  <validation-provider #default="{ errors }" name="Zip Code" rules="required">
                    <b-form-input type="text" v-model="driver.zip_code" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('zip') + ' ' + $t('code')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('Gender')">
               <b-form-radio-group v-model="driver.gender" :options="online_radio" />
                  </b-form-group>
                 </b-col>
                 <!-- <b-col md="6">
                <b-form-group :label="$t('Services Type')">
                  <b-form-checkbox
                    v-for="option1 in services"
                    :key="option1.id"
                    :value="option1.id"
                    :id="option1.id"
                    :aria-describedby="ariaDescribedby"
                    name="service_type"
                    inline
                  >
                  {{ option1.layout_name }}
                   </b-form-checkbox>



                  </b-form-group>
                 </b-col> -->

              <b-col md="12">
                <h5 class="mb-0">
                  {{ $t('security') }} {{ $t('settings') }}
                </h5>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('password')">
                  <validation-provider #default="{ errors }" name="Password" vid="Password" rules="required|password">
                    <b-form-input id="password" v-model="driver.password" type="text"
                      :state="errors.length > 0 ? false : null" :placeholder="$t('enter') + ' ' + $t('password')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('confirm') + ' ' + $t('password')">
                  <validation-provider #default="{ errors }" name="Password Confirm"
                    rules="required|confirmed:Password">
                    <b-form-input id="c-password" v-model="driver.cpassword" type="text"
                      :state="errors.length > 0 ? false : null" :placeholder="$t('re-type') + ' ' + $t('password')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="4">
                <b-form-group :label="$t('status') + '*'">
                  <v-select v-model="driver.status" :options="options" label="text" :reduce="sel => sel.value"
                    :placeholder="$t('select') + ' ' + $t('status')"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" />

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

      <!-- Tab: Information -->
      <b-tab>
        <template #title>
          <feather-icon icon="ClockIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline">{{ $t('document') }} {{ $t('settings') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="validationFormDocument">
            <input type="hidden" id="driver_id" name="driver_id">
            <b-row>
              <b-col cols="12" class="mb-2">
                <h5 class="mb-0">
                  {{ $t('document') }} {{ $t('settings') }}
                </h5>
                <small class="text-muted">{{ $t('add') }} {{ $t('document') }} {{ $t('details') }}</small>
              </b-col>
            </b-row>
            <div v-for="item in document_view">
              <b-row v-if="item.document_for == 1">

                <b-col md="6">
                  <b-form-group :label="item.document_name">

                    <b-form-file :id="'image' + item.id" @change="onFileChanges(item.id)" accept=".jpg, .png, .jpeg" />

                    <b-form-input :id="'doc_id' + item.id" hidden="hidden" :value="item.id" :key="item.id" />
                  </b-form-group>
                  <b-form-group>
                    <img rounded fluid class="document-img" :id="'document_img' + item.id" src="/no_image.png" alt="category photo" @click="open_image(item.id)" style="cursor: pointer;"/>

                  </b-form-group>
                </b-col>

                <b-col md="6" v-if="item.expiry_date_needed == 1">
                  <b-form-group :label="item.document_name">

                    <b-form-datepicker :id="'license_expirys' + item.id" locale="en" v-model="license_expiry[item.id]"
                      :min="new Date()" />
                    <b-form-input :id="'license_expiry' + item.id" hidden="hidden" :value=license_expiry[item.id]
                      :key="item.id" />
                    <b-form-input :id="'exp_id' + item.id" hidden="hidden" :value="item.id" :key="item.id" />
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
          <feather-icon icon="FileTextIcon" size="16" class="mr-0 mr-sm-50" />
          <span class="d-none d-sm-inline"> {{ $t('bank') }} {{ $t('details') }}</span>
          <feather-icon icon="ChevronRightIcon" size="16" class="mr-0 mr-sm-50" />
        </template>
        <div>

          <!-- Header: Personal Info -->
          <div class="d-flex">

            <h4 class="mb-0 ml-50">

            </h4>
          </div>

          <!-- Form: Personal Info Form -->
          <b-form @submit.prevent="validationFormBank">

            <b-form-input id="res_id" v-model="driver_tab.id" type="text" hidden="hidden" />
            <b-row>
              <b-col cols="12" class="mb-2">
                <h5 class="mb-0">
                  {{ $t('bank') }} {{ $t('details') }}
                </h5>
                <small class="text-muted">{{ $t('add') }} {{ $t('bank') }} {{ $t('details') }}</small>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Account Name" rules="required">
                    <b-form-input v-model="driver_tab.account_name"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('name')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('bank') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Bank Name" rules="required">
                    <b-form-input v-model="driver_tab.bank_name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('bank') + ' ' + $t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Account Address" rules="required">
                    <b-form-input v-model="driver_tab.account_address"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('address')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('branch') + ' ' + $t('name') + '*'">
                  <validation-provider #default="{ errors }" name="Branch Name" rules="required">
                    <b-form-input v-model="driver_tab.branch_name" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('branch') + ' ' + $t('name')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('account') + ' ' + $t('number') + '*'">
                  <validation-provider #default="{ errors }" name="Account Number" rules="required">
                    <b-form-input v-model="driver_tab.account_no"
                      :placeholder="$t('enter') + ' ' + $t('account') + ' ' + $t('number')"
                      :state="errors.length > 0 ? false : null" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('branch') + ' ' + $t('address') + '*'">
                  <validation-provider #default="{ errors }" name="Branch Address" rules="required">
                    <b-form-input v-model="driver_tab.branch_address" :state="errors.length > 0 ? false : null"
                      :placeholder="$t('enter') + ' ' + $t('branch') + ' ' + $t('address')" />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('swift') + ' ' + $t('code')">

                  <b-form-input v-model="driver_tab.swift_code"
                    :placeholder="$t('enter') + ' ' + $t('swift') + ' ' + $t('code')" />

                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group :label="$t('routing') + ' ' + $t('number')">

                  <b-form-input v-model="driver_tab.routing_no"
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


      <b-modal
      id="modal-center"
      centered
      :title="modal_title"
      hide-footer
      ok-only
      ok-title="Accept"
      ref="image-modal"
    >
      <b-card-text>
        <img class="modal-content" :src="modal_image" id="img01">
      </b-card-text>
    </b-modal>
    <b-modal id="modal-center" centered :title="$t('Add New City')" ok-only :ok-title="$t('Save')" ref="city-modal" @hidden="resetsModal"  @show="resetsModal"
              @ok="city_add">
              <b-row>



                  <b-col md="6" >
            <b-form-group :label="$t('city')">
              <b-form-input id="searchMadminnput" :placeholder="$t('city')" v-model="citys.city" />

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
                @input="onChange_city($event)" >
                <template #list-header>
                                                                      <li @click="open_countrymodal"
                                                                          class="add-new-client-header d-flex align-items-center my-50">
                                                                          <feather-icon icon="PlusIcon" size="16" />
                                                                          <span class="align-middle ml-25">{{ $t('Add New Country')}}</span>
                                                                      </li>
                                                                  </template>
                                                                </v-select>


            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group :label="$t('state')">

              <v-select id="country" v-model="citys.state" :options="this.state" label="state" :reduce="sel => sel.id"
                :placeholder="$t('select state')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" >
                <template #list-header>
                                                                      <li @click="open_statemodal"
                                                                          class="add-new-client-header d-flex align-items-center my-50">
                                                                          <feather-icon icon="PlusIcon" size="16" />
                                                                          <span class="align-middle ml-25">{{ $t('Add New State')}}</span>
                                                                      </li>
                                                                  </template>
                                                                </v-select>

            </b-form-group>
          </b-col>


              </b-row>
          </b-modal>

          <b-modal id="modal-center" centered :title="$t('Add New Area')" ok-only :ok-title="$t('Save')" ref="area-modal" @hidden="resetsModal"  @show="resetsModal"
              @ok="area_add">
              <b-row>



                <b-col md="6">
            <b-form-group :label="$t('city')">

              <v-select v-model="areas1.city" :options="cities" label="city" :reduce="sel => sel.id"
                        :placeholder="$t('select') + ' ' + $t('city')"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" >
                        <template #list-header>
                                                                      <li @click="open_citymodal"
                                                                          class="add-new-client-header d-flex align-items-center my-50">
                                                                          <feather-icon icon="PlusIcon" size="16" />
                                                                          <span class="align-middle ml-25">{{ $t('Add New City')}}</span>
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
              <input type="hidden" id="latitude_area" name="latitude" value="12.9813658">
              <input type="hidden" id="longitude_area" name="longitude" value="80.2199712">
              <div id="gmap"></div>
            </b-form-group>
          </b-col>


              </b-row>
          </b-modal>

          <b-modal id="modal-center" centered :title="$t('Add New Country')" ok-only :ok-title="$t('Save')" ref="country-modal" @hidden="resetsModal"  @show="resetsModal"
              @ok="country_add">
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
              <b-form-input id="mc-first-name" :placeholder="$t('currency symbol')" v-model="citys_country.currency_symbol" />
            </b-form-group>
          </b-col>



              </b-row>
          </b-modal>

          <b-modal id="modal-center" centered :title="$t('Add New State')" ok-only :ok-title="$t('Save')" ref="state-modal" @hidden="resetsModal"  @show="resetsModal"
              @ok="state_add">
              <b-row>




          <b-col md="6" >
            <b-form-group :label="$t('state')">
              <b-form-input id="mc-first-name" :placeholder="$t('state')" v-model="citys_state.state" />
            </b-form-group>
          </b-col>


          <b-col md="6">
            <b-form-group :label="$t('country')">

              <v-select id="country" v-model="citys_state.country_id" :options="country" label="country" :reduce="sel => sel.id"
                :placeholder="$t('select country')" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                />


            </b-form-group>
          </b-col>



              </b-row>
          </b-modal>
    </b-tabs>
  </b-card>


















</template>

<script>


import { FormWizard, TabContent } from 'vue-form-wizard'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'

import 'vue-form-wizard/dist/vue-form-wizard.min.css'
// dropdown
import BCardCode from '@core/components/b-card-code'
//form validation//
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';


import {
  BRow,
  BCol, BTab,BTabs,BCard,
  BFormGroup,BAlert,BLink, BFormRadioGroup,
  BFormInput,BForm,
  BFormInvalidFeedback,
  BFormSelect,BCardText,BFormFile,BFormTextarea,BFormDatepicker,
  BImg,BMedia,BButton,BFormRadio,BModal,VBModal,BFormCheckboxGroup,BFormCheckbox,
  BDropdownForm,
    BDropdownDivider,
    BDropdownText,
      BListGroup, BListGroupItem,
} from 'bootstrap-vue'

import { required, email } from '@validations'
import { ref } from '@vue/composition-api'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
    FormWizard,
    TabContent, BForm,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    vSelect,
    BFormInvalidFeedback,
    BFormDatepicker,
    // eslint-disable-next-line vue/no-unused-components
    ToastificationContent,
    BFormSelect, BCardText,
    BCardCode,
    BFormFile,
    BFormTextarea,BFormCheckboxGroup,
    BFormCheckbox,
    BImg, BMedia, BFormRadio,BModal,VBModal,VuePhoneNumberInput,
    BTab,BTabs,BCard,BAlert, BLink,BFormRadioGroup,BButton,
  BDropdownForm,
    BDropdownDivider,
    BDropdownText,
      BListGroup, BListGroupItem,
  },

  created() {

    this.$http.get('/admin/get_lang')
      .then(res => {

        this.setting = res.data
      })
    this.$http.get('/admin/res_list')
      .then(res => {

        this.rows = res.data
      })
      this.$http.get('/admin/vehicles_list_drive')
      .then(res => {

        this.vehicles = res.data
      })
    this.$http.get('/admin/city_list')
      .then(res => {

        this.cities = res.data
      })
      this.$http.get('/admin/area_list')
      .then(res => {

        this.areas = res.data
      })
    this.$http.get('/admin/document_view')

      .then(res => {
        this.document_view = res.data

      })
      this.$http.get('/admin/layout_driver')
            .then(res => {

             this.services=res.data;
        })
        this.$http.get('/admin/country_list')
      .then(res => {

        this.country = res.data

      })
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_delivery_partner/' + this.$route.params.id)
        .then(res => {

          this.driver = res.data.data;
      this.driver_tab = res.data.data;
      this.driver.vehicle_name = res.data.data.vehicle_name;
      if(res.data.data.city == 0)
      {
        this.driver.city ='';
      }
      else
      {
        this.driver.city =res.data.data.city;
      }
      this.formattedNumber = this.driver.phone;
      this.phone_country_code = this.driver.country_code;
      this.phone_country_calling_code = this.driver.country_calling_code;
      this.driver.password = this.driver.org_password;
      this.driver.cpassword = this.driver.org_password;

       if (this.driver.services.length > 0) {
            this.driver.services.forEach(function (element) {
              document.getElementById(element).checked = true;
            });
          }
          document.getElementById('driver_id').value = res.data.data.id;
          this.document = res.data.document;
          if (this.document.length > 0) {
            this.document.forEach(function (element) {

              // var img = document.querySelector('[id="' + 'document_img'+element.document_id + '"]') ;

              //     img.setAttribute("src",element.document);
              document.getElementById('document_img' + element.document_id).src = element.document;
              // document.getElementById('license_expirys'+element.id).src = element.document;
              // document.getElementById('license_expirys'+element.document_id).value = element.expiry_date;

            });


          }

          this.citys.state = res.data.message.state_id;

        })
    }
  },
  data() {
    return {
      url1: null,
      driver: {},
      driver_tab: {},
      driver_doc: {},
      rows: [],
      vehicles: [],
      cities: [],
      areas: [],
      license: [],
      licenses: [],
      document: [],
      doc_id: [],
      exp_id: [],
      driver_id: [],
      document_view: [],
      res_id: [],
      selected_res: "",
      selected_vehicle: "",
      selected_city: "",
      selected_area: null,
      modal_image:'',
      formattedNumber:'',
      country_calling_code:'',
      country_code:'',
      options: [
        { value: 1, text: this.$t('active') },
        { value: 2, text: this.$t('inactive') },
        { value: 3, text: this.$t('new') },


      ],
      online_radio: [
        { text: 'Male', value: 1 },
        { text: 'Female', value: 0 },

      ],
      services:[],
      license_expiry: {},
      setting:{},
      isHidden: false,
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
    }
  },
  methods: {
    onTabChange(id) {

if (id == 1) {
  this.isHidden = false;
}
else {
  this.isHidden = true;
}


},
    open_citymodal() {
            this.$refs["city-modal"].show();
            this.$refs["area-modal"].hide();
        },
        open_areamodal() {

            this.$refs["area-modal"].show();
            var timer = setInterval(function() {

          const element = document.getElementById("gmap");
          const mapoptions = {
            zoom: 18,
            disableDefaultUI: true,

          };
          const map = new google.maps.Map(element,mapoptions);

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
          autocomplete.addListener('place_changed', function() {
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
          function(results, status) {

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
          function(marker) {
              var latLng = marker.latLng;
              var currentLatitude = latLng.lat();
              var currentLongitude = latLng.lng();

              geocoder.geocode({
                  'latLng': latLng
              }, function(results, status) {
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
// onTabChange(id) {
// if (id == 1) {
//   document.getElementById("primary").style.display = 'block';
//   document.getElementById("secondary").style.display = 'none';
// }
// else {
//   document.getElementById("primary").style.display = 'none';
//   document.getElementById("secondary").style.display = 'block';
// }



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
  this.$http.get('/admin/city_list')
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
    onChange(event) {

this.$http.post('/admin/area_list1', this.driver)
  .then(res => {

    this.areas = res.data

  })

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
    updatePhoneNumber(data) {
      this.formattedNumber = data.nationalNumber
      this.country_calling_code = data.countryCode
      this.country_code = data.countryCallingCode
            },
    open_image(id){
      // this.$refs['image-modal'].show()
      var youtubeimgsrc = document.getElementById("document_img"+id).src;
      // this.modal_image = youtubeimgsrc
      window.open(youtubeimgsrc);
    },
    onFileChange1(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        this.url1 = reader.result;
      };
    },
    onFileChanges(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
         var img = document.querySelector('[id="' + 'document_img'+id + '"]') ;
         img.setAttribute("src",reader.result);
      };
    },


    validationForm() {
      let checkboxes1 = document.querySelectorAll('input[name="service_type"]:checked');
      let values1 = [];
      checkboxes1.forEach((checkbox) => {
        values1.push(checkbox.id);
      });
      this.driver.services = values1;

      let data = new FormData();
      data.append('id', this.$route.params.id);

      data.append('email', this.driver.email);
      data.append('password', this.driver.password);
      data.append('profile_pic', this.driver.profile_pic);
      data.append('restaurant', this.driver.restaurant_id);
      data.append('vehicle_name', this.driver.vehicle_name);
      data.append('city', this.driver.city);
      data.append('driver_name', this.driver.name);
      data.append('phone_no', this.formattedNumber );
      data.append('area', this.driver.area);
      data.append('address_line_1', this.driver.address_line_1);
      data.append('address_line_2', this.driver.address_line_2);
      data.append('state_province', this.driver.state_province);
      data.append('address_city', this.driver.address_city);
      data.append('country', this.driver.country);
      data.append('about', this.driver.about);
      data.append('zip_code', this.driver.zip_code);
      data.append('status', this.driver.status);
      data.append('gender', this.driver.gender);
      data.append('services', this. driver.services);
      data.append('phone_country_calling_code', this.country_calling_code);
      data.append('phone_country_code', this.country_code);

      data.append('second_driver_name', this.driver.second_driver_name ? this.driver.second_driver_name : '');
      data.append('second_address_line_1', this.driver.second_address_line_1 ? this.driver.second_address_line_1 : '');
      data.append('second_address_line_2', this.driver.second_address_line_2 ? this.driver.second_address_line_2 : '');
      data.append('second_state_province', this.driver.second_state_province ? this.driver.second_state_province : '');
      data.append('second_address_city', this.driver.second_address_city ? this.driver.second_address_city : '');
      data.append('second_country', this.driver.second_country ? this.driver.second_country : '');

      this.$http
        .post("/admin/add_driver", data)
        .then(
          response => {
            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
              document.getElementById('driver_id').value = response.data.id;


            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }
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
    validationFormBank() {

      let data2 = new FormData();

      data2.append('id', this.$route.params.id);
      data2.append('routing_no', this.driver_tab.routing_no);
      data2.append('swift_code', this.driver_tab.swift_code);
      data2.append('branch_address', this.driver_tab.branch_address);
      data2.append('account_no', this.driver_tab.account_no);
      data2.append('bank_name', this.driver_tab.bank_name);
      data2.append('branch_name', this.driver_tab.branch_name);
      data2.append('account_name', this.driver_tab.account_name);
      data2.append('account_address', this.driver_tab.account_address);
      // driver_tab.append(driver_id,document.getElementById('driver_id').value);
      // alert(this.driver_tab.driver_id);
      this.$http
        .post("/admin/add_driver_tab", data2)
        .then(
          response => {

            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")

              this.$router.push({ name: "driver_list" })
            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));



    },
    validationFormDocument() {

      let data = new FormData();

      data.append('id', this.$route.params.id);

      this.document_view.forEach(function (number) {

        if (number.document_for == 1) {

          var img = document.getElementById('image' + number.id).files[0];

          if (img !== undefined) {
            data.append('license[]', document.getElementById('image' + number.id).files[0]);
            data.append('doc_id[]', document.getElementById('doc_id' + number.id).value);
            if (number.expiry_date_needed == 1) {
              // Apply some style on paragraph
              data.append('license_expiry[]', document.getElementById('license_expiry' + number.id).value);
              data.append('exp_id[]', document.getElementById('exp_id' + number.id).value);

            }
          }




        }
      });
      this.$http
        .post("/admin/add_driver_tab1", data)
        .then(
          response => {
            if (response.data.status == true) {
              this.popToast(response, "CheckIcon", "success")
              document.getElementById('driver_id').value = response.data.id;

            } else {
              this.popToast(response, "AlertTriangleIcon", "danger")

            }
          }

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

#avator-img {
  width: 9.92rem;
}

.document-img {
  width: 9.92rem;
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


