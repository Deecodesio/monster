<template>
  <b-card>
    <b-form @submit.prevent="faq_add">
      <b-row>
        <b-col md="6">
          <b-form-group :label="$t('titles')">
            <b-form-input :placeholder="$t('enter title')" v-model="faq.title" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group :label="$t('icon')">
            <b-form-input id="icon" :placeholder="$t('enter feather icon name')" v-model="faq.icon" />
            <b-button v-ripple.400="'rgba(113, 102, 240, 0.15)'" v-b-modal.modal-1 variant="outline-primary">
              {{ $t('choose icon') }}
            </b-button>
          </b-form-group>
        </b-col>



      </b-row>
      <!-- submit and reset -->
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" class="mr-1">
          {{ $t('save') }}
        </b-button>

      </b-col>

    </b-form>


    <b-modal id="modal-1" ok-only ok-variant="primary" ok-title="Accept" modal-class="modal-primary" centered size="xl"
      title="Icons" ref="my-modal">

      <b-card-text>
        <section id="feather-icons">
          <div class="icon-search-wrapper my-3 mx-auto">
            <b-form-group>
              <b-input-group class="input-group-merge">
                <b-input-group-prepend is-text>
                  <feather-icon icon="SearchIcon" />
                </b-input-group-prepend>
                <b-form-input id="icons-search" v-model="seachQuery" placeholder="Search Icons..." />
              </b-input-group>
            </b-form-group>
          </div>
          <div id="icons-container" class="d-flex flex-wrap">
            <b-card v-for="icon in filterIcons" :key="icon" v-b-tooltip.hover.top="icon"
              class="icon-card cursor-pointer text-center mb-2 mx-50" @click="copyIconName(icon)">
              <div class="icon-wrapper">
                <feather-icon :icon="icon" size="24" />
              </div>
              <b-card-text class="icon-name text-truncate mb-0 mt-1">
                {{ icon }}
              </b-card-text>
            </b-card>
          </div>
        </section>
      </b-card-text>
    </b-modal>

  </b-card>
</template>
  
<script>
import BCardCode from '@core/components/b-card-code'
import {
  BRow, BCol, BFormSelect, BFormRadioGroup, BTooltip, BFormGroup, BFormInput,
  BFormCheckbox, BForm, BButton, BCard, BInputGroupPrepend,
  BInputGroup, BCardText, VBTooltip, BModal, VBModal,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { useClipboard } from '@vueuse/core'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

import { useToast } from 'vue-toastification/composition'
import { ref } from '@vue/composition-api'

export default {
  components: {
    BCardCode,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox, BTooltip,
    BForm,
    BButton,
    BFormSelect,
    BFormRadioGroup,
    BCard,
    BInputGroupPrepend,
    BInputGroup,
    BCardText, BModal, VBModal,

    // eslint-disable-next-line vue/no-unused-components
    ToastificationContent,
  },
  directives: {
    'b-tooltip': VBTooltip,
    Ripple,
  },
  data() {
    return {
      faq: {},
      seachQuery: '',
      icons: [
        'ActivityIcon',
        'AirplayIcon',
        'AlertCircleIcon',
        'AlertOctagonIcon',
        'AlertTriangleIcon',
        'AlignCenterIcon',
        'AlignJustifyIcon',
        'AlignLeftIcon',
        'AlignRightIcon',
        'AnchorIcon',
        'ApertureIcon',
        'ArchiveIcon',
        'ArrowDownCircleIcon',
        'ArrowDownLeftIcon',
        'ArrowDownRightIcon',
        'ArrowDownIcon',
        'ArrowLeftCircleIcon',
        'ArrowLeftIcon',
        'ArrowRightCircleIcon',
        'ArrowRightIcon',
        'ArrowUpCircleIcon',
        'ArrowUpLeftIcon',
        'ArrowUpRightIcon',
        'ArrowUpIcon',
        'AtSignIcon',
        'AwardIcon',
        'BarChart2Icon',
        'BarChartIcon',
        'BatteryChargingIcon',
        'BatteryIcon',
        'BellOffIcon',
        'BellIcon',
        'BluetoothIcon',
        'BoldIcon',
        'BookOpenIcon',
        'BookIcon',
        'BookmarkIcon',
        'BoxIcon',
        'BriefcaseIcon',
        'CalendarIcon',
        'CameraOffIcon',
        'CameraIcon',
        'CastIcon',
        'CheckCircleIcon',
        'CheckSquareIcon',
        'CheckIcon',
        'ChevronDownIcon',
        'ChevronLeftIcon',
        'ChevronRightIcon',
        'ChevronUpIcon',
        'ChevronsDownIcon',
        'ChevronsLeftIcon',
        'ChevronsRightIcon',
        'ChevronsUpIcon',
        'ChromeIcon',
        'CircleIcon',
        'ClipboardIcon',
        'ClockIcon',
        'CloudDrizzleIcon',
        'CloudLightningIcon',
        'CloudOffIcon',
        'CloudRainIcon',
        'CloudSnowIcon',
        'CloudIcon',
        'CodeIcon',
        'CodepenIcon',
        'CodesandboxIcon',
        'CoffeeIcon',
        'ColumnsIcon',
        'CommandIcon',
        'CompassIcon',
        'CopyIcon',
        'CornerDownLeftIcon',
        'CornerDownRightIcon',
        'CornerLeftDownIcon',
        'CornerLeftUpIcon',
        'CornerRightDownIcon',
        'CornerRightUpIcon',
        'CornerUpLeftIcon',
        'CornerUpRightIcon',
        'CpuIcon',
        'CreditCardIcon',
        'CropIcon',
        'CrosshairIcon',
        'DatabaseIcon',
        'DeleteIcon',
        'DiscIcon',
        'DivideCircleIcon',
        'DivideSquareIcon',
        'DivideIcon',
        'DollarSignIcon',
        'DownloadCloudIcon',
        'DownloadIcon',
        'DribbbleIcon',
        'DropletIcon',
        'Edit2Icon',
        'Edit3Icon',
        'EditIcon',
        'ExternalLinkIcon',
        'EyeOffIcon',
        'EyeIcon',
        'FacebookIcon',
        'FastForwardIcon',
        'FeatherIcon',
        'FigmaIcon',
        'FileMinusIcon',
        'FilePlusIcon',
        'FileTextIcon',
        'FileIcon',
        'FilmIcon',
        'FilterIcon',
        'FlagIcon',
        'FolderMinusIcon',
        'FolderPlusIcon',
        'FolderIcon',
        'FramerIcon',
        'FrownIcon',
        'GiftIcon',
        'GitBranchIcon',
        'GitCommitIcon',
        'GitMergeIcon',
        'GitPullRequestIcon',
        'GithubIcon',
        'GitlabIcon',
        'GlobeIcon',
        'GridIcon',
        'HardDriveIcon',
        'HashIcon',
        'HeadphonesIcon',
        'HeartIcon',
        'HelpCircleIcon',
        'HexagonIcon',
        'HomeIcon',
        'ImageIcon',
        'InboxIcon',
        'InfoIcon',
        'InstagramIcon',
        'ItalicIcon',
        'KeyIcon',
        'LayersIcon',
        'LayoutIcon',
        'LifeBuoyIcon',
        'Link2Icon',
        'LinkIcon',
        'LinkedinIcon',
        'ListIcon',
        'LoaderIcon',
        'LockIcon',
        'LogInIcon',
        'LogOutIcon',
        'MailIcon',
        'MapPinIcon',
        'MapIcon',
        'Maximize2Icon',
        'MaximizeIcon',
        'MehIcon',
        'MenuIcon',
        'MessageCircleIcon',
        'MessageSquareIcon',
        'MicOffIcon',
        'MicIcon',
        'Minimize2Icon',
        'MinimizeIcon',
        'MinusCircleIcon',
        'MinusSquareIcon',
        'MinusIcon',
        'MonitorIcon',
        'MoonIcon',
        'MoreHorizontalIcon',
        'MoreVerticalIcon',
        'MousePointerIcon',
        'MoveIcon',
        'MusicIcon',
        'Navigation2Icon',
        'NavigationIcon',
        'OctagonIcon',
        'PackageIcon',
        'PaperclipIcon',
        'PauseCircleIcon',
        'PauseIcon',
        'PenToolIcon',
        'PercentIcon',
        'PhoneCallIcon',
        'PhoneForwardedIcon',
        'PhoneIncomingIcon',
        'PhoneMissedIcon',
        'PhoneOffIcon',
        'PhoneOutgoingIcon',
        'PhoneIcon',
        'PieChartIcon',
        'PlayCircleIcon',
        'PlayIcon',
        'PlusCircleIcon',
        'PlusSquareIcon',
        'PlusIcon',
        'PocketIcon',
        'PowerIcon',
        'PrinterIcon',
        'RadioIcon',
        'RefreshCcwIcon',
        'RefreshCwIcon',
        'RepeatIcon',
        'RewindIcon',
        'RotateCcwIcon',
        'RotateCwIcon',
        'RssIcon',
        'SaveIcon',
        'ScissorsIcon',
        'SearchIcon',
        'SendIcon',
        'ServerIcon',
        'SettingsIcon',
        'Share2Icon',
        'ShareIcon',
        'ShieldOffIcon',
        'ShieldIcon',
        'ShoppingBagIcon',
        'ShoppingCartIcon',
        'ShuffleIcon',
        'SidebarIcon',
        'SkipBackIcon',
        'SkipForwardIcon',
        'SlackIcon',
        'SlashIcon',
        'SlidersIcon',
        'SmartphoneIcon',
        'SmileIcon',
        'SpeakerIcon',
        'SquareIcon',
        'StarIcon',
        'StopCircleIcon',
        'SunIcon',
        'SunriseIcon',
        'SunsetIcon',
        'TabletIcon',
        'TagIcon',
        'TargetIcon',
        'TerminalIcon',
        'ThermometerIcon',
        'ThumbsDownIcon',
        'ThumbsUpIcon',
        'ToggleLeftIcon',
        'ToggleRightIcon',
        'ToolIcon',
        'Trash2Icon',
        'TrashIcon',
        'TrelloIcon',
        'TrendingDownIcon',
        'TrendingUpIcon',
        'TriangleIcon',
        'TruckIcon',
        'TvIcon',
        'TwitchIcon',
        'TwitterIcon',
        'TypeIcon',
        'UmbrellaIcon',
        'UnderlineIcon',
        'UnlockIcon',
        'UploadCloudIcon',
        'UploadIcon',
        'UserCheckIcon',
        'UserMinusIcon',
        'UserPlusIcon',
        'UserXIcon',
        'UserIcon',
        'UsersIcon',
        'VideoOffIcon',
        'VideoIcon',
        'VoicemailIcon',
        'Volume1Icon',
        'Volume2Icon',
        'VolumeXIcon',
        'VolumeIcon',
        'WatchIcon',
        'WifiOffIcon',
        'WifiIcon',
        'WindIcon',
        'XCircleIcon',
        'XOctagonIcon',
        'XSquareIcon',
        'XIcon',
        'YoutubeIcon',
        'ZapOffIcon',
        'ZapIcon',
        'ZoomInIcon',
        'ZoomOutIcon',
      ],
    }
  },
  computed: {
    filterIcons() {
      const seachQueryIcon = this.seachQuery.toLowerCase()
      return this.icons.filter(item => item.toLowerCase().includes(seachQueryIcon))
    },
  },
  setup() {
    const toast = useToast()
    const { copy } = useClipboard()

    const copyIconName = iconName => {
      copy(iconName)

      document.getElementById('icon').value = iconName;
      this.$refs['my-modal'].hide();
      toast({
        component: ToastificationContent,
        props: {
          title: 'Icon name copied',
          icon: 'CopyIcon',
          variant: 'success',
        },
      })

    }

    return {
      copyIconName,
    }
  },
  methods: {
    faq_add() {
      this.faq.id = this.faq.id;
      this.faq.icon = document.getElementById('icon').value;
      this.$http
        .post("/admin/add_faq", this.faq)
        .then(
          (response => {
            console.log(response);
            if (response.data.status == true) {
              this.$router.push({ name: "faq_list" })
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

  },
  created() {

    if (this.$route.params.id) {
      this.$http.get('/admin/edit_faq/' + this.$route.params.id)
        .then(res => {

          this.faq = res.data;
          this.faq.title = this.faq.name;

        })
    }

  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/pages/ui-feather.scss';
</style>