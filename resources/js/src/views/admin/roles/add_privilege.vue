<template>
  <b-card>
    <b-form @submit.prevent="role_add">
      <b-row>

        <b-col md="6">
          <b-form-group :label="$t('role') + ' ' + $t('name') + '*'">
            <b-form-input id="mc-last-name" :placeholder="$t('role') + ' ' + $t('name')" v-model="role.role_name" />
            <b-form-input id="mc-last-name" v-model="role.id" hidden="hidden" />
          </b-form-group>
        </b-col>

        <b-col md="6">

        </b-col>
        <b-col md="6">

          <b-form-group :label="$t('permissions') + '*'" />
          <table class="table ">
            <thead>
              <tr>
                <th scope="col">{{ $t('name') }}</th>
                <th scope="col">{{ $t('view') }}</th>
                <th scope="col">{{ $t('edit') }}</th>
              </tr>
            </thead>
            <tbody v-for="(name, index) in permissons_name" :key="index">
              <tr>

                <td>{{ name.text }}</td>
                <td> <b-form-checkbox :id="name.value + '-view'" v-model="Permissions" :key="name.value + '-view'"
                    :value="name.value + '-view'" name="flavour-3a"></b-form-checkbox></td>
                <td><b-form-checkbox :id="name.value + '-edit'" v-model="Permissions" :key="name.value + '-edit'"
                    :value="name.value + '-edit'" name="flavour-3a"></b-form-checkbox></td>
              </tr>

            </tbody>
          </table>




        </b-col>

        <b-col md="6">

        </b-col>
        <!-- submit and reset -->
        <b-col md="6" style="text-align: right">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary"
            class="mr-1 justify-content-end">
            {{ $t('save') }}
          </b-button>

        </b-col>
      </b-row>
    </b-form>


  </b-card>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BTooltip, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton, BCard,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'



export default {
  components: {
    BCardCode,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormSelect,
    BFormRadioGroup,
    BCard,
    vSelect,
    BTooltip,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      role: {},
      Permissions: [],
      selected: null,
      options: [
        { value: null, text: '--Document For--', disabled: true },
        { value: 1, text: this.$t('driver') },
        { value: 2, text: this.$t('restaurant') },
      ],
      selected_status: null,
      options_status: [

        { value: 1, text: this.$t('admin') },
        { value: 2, text: this.$t('restaurant') },
      ],
      selected_radio: 'No',
      options_radio: [
        { text: this.$t('yes'), value: 1 },
        { text: this.$t('no'), value: 2 },

      ],
      state: [],
      privileges: [],
      permissons_name: [
        { text: this.$t('dashboard'), value: 'dashboard' },
        { text: this.$t('order'), value: 'order' },
        { text: this.$t('Store'), value: 'restaurant' },
        { text: this.$t('city'), value: 'city' },
        { text: this.$t('vehicle'), value: 'vehicle' },
        { text: this.$t('driver'), value: 'driver' },
        { text: this.$t('document'), value: 'document' },
        { text: this.$t('promocode'), value: 'promocodes' },
        { text: this.$t('User'), value: 'user' },
        { text: this.$t('cuisines'), value: 'cuisines' },
        { text: this.$t('category'), value: 'category' },
        { text: this.$t('reports'), value: 'report' },
        { text: this.$t('carrier') ,value:'carrier'},
        { text: this.$t('package') ,value:'package'}, 
      ],
      permissons_view: [
        {
          id: 'view',
          name: 'View',

        },

        {
          id: 'edit',
          name: 'Edit',

        },
      ],
      permissons_edit: [
        {
          id: 'edit',
          name: 'Edit',

        },
      ]
    }
  },
  watch: {
    // whenever question changes, this function will run
    question(newQuestion, oldQuestion) {
      if (newQuestion.includes('?')) {
        this.getAnswer()
      }
    }
  },
  methods: {
    role_add() {
      let checkboxes = document.querySelectorAll('input[class="custom-control-input"]:checked');
      let values = [];
      checkboxes.forEach((checkbox) => {
        values.push(checkbox.value);
      });
      this.role.privilege = values;
      let data = new FormData();
      data.append('role_name', this.role.role_name);
      data.append('privilege', this.role.privilege);
      data.append('role_id', this.role.id);
      this.$http
        .post("/admin/privilege_add", data)
        .then(
            (response => {

            if (response.data.status == true) {
              this.$router.push({ name: "roles_list" })
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


          })

        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },

    onChange1(event) {


      this.$http.get('/admin/role_privilege/' + this.role.role_type)
        .then(res => {

          this.privileges = res.data
          var uncheck = document.getElementsByTagName('input');

          for (var i = 0; i < uncheck.length; i++) {
            if (uncheck[i].type == 'checkbox') {
              uncheck[i].checked = false;

            }
          }
          this.privileges.forEach(function (element) {

            //  alert(element.name);
            document.getElementById(element.name).checked = true;

          });
          // let checkboxes = document.querySelectorAll('input[class="custom-control-input"]:checked');
          // let values = [];
          // checkboxes.forEach((checkbox) => {
          //     values.push(checkbox.value);
          // });

          // this.role.privilege =   values;

        })

    },

  },
  created() {
    if (this.$route.params.id) {
      this.$http.get('/admin/edit_privilege/' + this.$route.params.id)
        .then(res => {
          this.privileges = res.data.privileges;
          this.role = res.data.role;
          var uncheck = document.getElementsByTagName('input');

          for (var i = 0; i < uncheck.length; i++) {
            if (uncheck[i].type == 'checkbox') {
              uncheck[i].checked = false;

            }
          }
          this.privileges.forEach(function (element) {

            //  alert(element.name);
            document.getElementById(element.name).checked = true;

          });
          // let checkboxes = document.querySelectorAll('input[class="custom-control-input"]:checked');
          // let values = [];
          // checkboxes.forEach((checkbox) => {
          //     values.push(checkbox.value);
          // });

          // this.role.privilege =   values;

        })
    }


  },
}
</script>
