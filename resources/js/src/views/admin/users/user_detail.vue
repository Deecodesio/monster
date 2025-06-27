 <template>
  <div>
    
    <b-tabs
      v-model="activeTab"
      :vertical="activeTab === 0"
      :nav-class="activeTab === 0 ? 'nav-left' : 'd-flex flex-row'"
      :nav-wrapper-class="activeTab === 0 ? 'col-md-3 col-12' : ''"
      :content-class="activeTab === 0 ? 'col-12 col-md-9 mt-1 mt-md-0' : 'd-none'"
      pills
    >
      <!-- General Tab -->
      <b-tab>
        <template #title>
          <feather-icon icon="UserIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t('General') }}</span>
        </template>

        <!-- Show only when General is selected -->
        <b-card v-if="activeTab === 0">
          <b-media no-body>
            <b-media-aside>
              <b-img rounded :src="user_detail.avatar" height="80" />
            </b-media-aside>
          </b-media>

          <b-form class="mt-2">
            <b-row>
              <b-col sm="6">
                <b-form-group label="Username">
                  <b-form-input v-model="user_detail.name" plaintext />
                </b-form-group>
              </b-col>
              <b-col sm="6">
                <b-form-group label="Email">
                  <b-form-input v-model="user_detail.email" plaintext />
                </b-form-group>
              </b-col>
              <b-col sm="6">
                <b-form-group label="Phone">
                  <b-form-input v-model="user_detail.phone" plaintext />
                </b-form-group>
              </b-col>
              <b-col sm="6">
                <b-form-group label="Wallet Balance">
                  <b-form-input v-model="user_detail.wallet_amount" plaintext />
                </b-form-group>
              </b-col>
              <b-col sm="6">
                <b-form-group label="Created At">
                  <b-form-input v-model="user_detail.create" plaintext />
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-card>
      </b-tab>

      <!-- Orders Tab -->
      <b-tab>
        <template #title>
          <feather-icon icon="ArchiveIcon" size="18" class="mr-50" />
          <span class="font-weight-bold">{{ $t('Orders') }}</span>
        </template>
      </b-tab>
    </b-tabs>

    <!-- Orders table shown below the tabs -->
    <div v-if="activeTab === 1">
      <b-card class="mt-2">
        <h4>{{ $t('Total Orders') }} : {{ totalRows }}</h4>
        <b-table
          responsive
          :items="orders"
          :fields="fields"
          :per-page="perPage"
          :current-page="currentPage"
          selectable
          @row-selected="onRowSelected"
        >
          <template #cell(index)="data">
            {{ data.index + 1 }}
          </template>
          <template #cell(bill_amount)="data">
            {{ setting.currency_symbol }} {{ data.value }}
          </template>
          <template #cell(status)="data">
            <b-badge :variant="status[1][data.value]">
              {{ status[0][data.value] }}
            </b-badge>
          </template>
          <template #cell(create)="data">
            {{ data.value }}
          </template>
        </b-table>

        <b-row class="mt-2">
          <b-col cols="6">
            <b-form-group label="Per page">
              <b-form-select
                v-model="perPage"
                :options="pageOptions"
                class="w-50"
              />
            </b-form-group>
          </b-col>
          <b-col cols="6">
            <b-pagination
              v-model="currentPage"
              :total-rows="totalRows"
              :per-page="perPage"
              align="center"
              size="sm"
            />
          </b-col>
        </b-row>
      </b-card>
    </div>
  </div>
</template>





<script>
import {
  BTabs,
  BTab,
  BForm,
  BFormGroup,
  BFormInput,
  BRow,
  BCol,
  BCard,
  BMedia,
  BMediaAside,
  BImg,
  BTable,
  BFormSelect,
  BBadge,
  BPagination,
} from "bootstrap-vue";

export default {
  components: {
    BTabs,
    BTab,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BMedia,
    BMediaAside,
    BImg,
    BTable,
    BFormSelect,
    BBadge,
    BPagination,
  },
  data() {
    return {
      activeTab: 0, // 👈 Added to track active tab (0 = General, 1 = Orders)
      user_detail: {},
      orders: [],
      setting: {},
      perPage: 10,
      pageOptions: [10, 15, 20],
      totalRows: 0,
      currentPage: 1,
      userData: JSON.parse(localStorage.getItem("userData")),
      fields: [
        "index",
        { key: "order_id", label: this.$t("Order Id") },
        { key: "restaurant_name", label: this.$t("Store") },
        { key: "status", label: this.$t("Order Status") },
        { key: "bill_amount", label: this.$t("Total") },
        { key: "create", label: this.$t("Ordered On") },
      ],
      status: [
        {
          0: this.$t("New"),
          1: this.$t("Processing"),
          2: this.$t("Processing"),
          3: this.$t("Pickup"),
          4: this.$t("Pickup"),
          5: this.$t("Pickup"),
          6: this.$t("Delivered"),
          7: this.$t("Delivered"),
          9: this.$t("Cancelled"),
          10: this.$t("Cancelled"),
          11: this.$t("Cancelled"),
          12: this.$t("Cancelled"),
          13: this.$t("Cancelled"),
          14: this.$t("Cancelled"),
          "-2": this.$t("Failed"),
          "-1": this.$t("Abandon"),
        },
        {
          0: "light-primary",
          1: "light-warning",
          2: "light-warning",
          3: "light-warning",
          4: "light-warning",
          5: "light-warning",
          6: "light-success",
          7: "light-success",
          9: "light-danger",
          10: "light-danger",
          11: "light-danger",
          12: "light-danger",
          13: "light-danger",
        },
      ],
    };
  },
  beforeCreate() {
    this.$http.get("/admin/user_data/" + this.$route.params.id).then((res) => {
      this.user_detail = res.data.user_detail;
      this.orders = res.data.orders;
      this.totalRows = res.data.orders.length;
    });

    this.$http.get("/admin/get_currency").then((res) => {
      this.setting = res.data;
    });
  },
  methods: {
    onRowSelected(items) {
      this.$router.push({ name: "view_order", params: { id: items[0].id } });
    },
  },
};
</script>
