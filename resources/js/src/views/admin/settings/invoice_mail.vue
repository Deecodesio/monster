<template>
    <div class="bg" style="background-color:#fff;">
        <div class="r  pr-16 pl-16"
            style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="none"
                style="background:#fffffe;background-color:#fffffe;width:100%;">
                <tbody>
                    <tr>
                        <td style="border:none;direction:ltr;font-size:0;padding:16px 16px 16px 16px;text-align:left;">
                            <div class="xc568 ogf c"
                                style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="none"
                                    style="border:none;vertical-align:middle;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="i"
                                                style="font-size:0;padding:0;word-break:break-word;">
                                                <table border="0" cellpadding="0" cellspacing="0" role="none"
                                                    style="border-collapse:collapse;border-spacing:0;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:145px;"> <img alt="" height="auto"
                                                                    :src=rows_detail
                                                                    style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;"
                                                                    title="" width="145">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="r  pr-16 pl-16"
            style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="none"
                style="background:#fffffe;background-color:#fffffe;width:100%;">
                <tbody>
                    <tr>
                        <td style="border:none;direction:ltr;font-size:0;padding:32px 32px 32px 32px;text-align:left;">
                            <div class="xc536 ogf c"
                                style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="none"
                                    style="border:none;vertical-align:middle;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="center" class="x" style="font-size:0;word-break:break-word;">
                                                <div style="text-align:center;">
                                                    <p style="Margin:0;text-align:center;cursor:pointer"
                                                        @click="edit_content('1', 'content1')"><span
                                                            style="font-size:28px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#000000;line-height:114%;">{{
                                                            content[1] }}</span>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="r  pr-16 pl-16"
            style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="none"
                style="background:#fffffe;background-color:#fffffe;width:100%;">
                <tbody>
                    <tr>
                        <td style="border:none;direction:ltr;font-size:0;padding:32px 32px 32px 32px;text-align:left;">
                            <div class="xc536 ogf c"
                                style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="none"
                                    style="border:none;vertical-align:middle;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="x" style="font-size:0;word-break:break-word;">
                                                <div style="text-align:left;">
                                                    <p style="Margin:0;text-align:left;"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#3c3c3c;line-height:150%;cursor:pointer"
                                                            @click="edit_content('7', 'content7')">{{ content[7]
                                                            }}</span><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#3c3c3c;line-height:150%;">
                                                            Test,</span></p>
                                                    <p style="Margin:0;"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#3c3c3c;line-height:150%;">&nbsp;</span>
                                                    </p>
                                                    <p style="Margin:0;cursor:pointer"
                                                        @click="edit_content('3', 'content3')"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#3c3c3c;line-height:150%;">{{
                                                            content[3] }}</span>
                                                    </p>
                                                    <p style="Margin:0;"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#3c3c3c;line-height:150%;">&nbsp;</span>
                                                    </p>
                                                    <p style="Margin:0;cursor:pointer"
                                                        @click="edit_content('2', 'content2')"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#3c3c3c;line-height:150%;"></span><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:400;color:#3c3c3c;line-height:150%;">{{
                                                            content[2] }}</span>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-box r  pr-16 pl-16"
            style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;">
            <table cellpadding="0" cellspacing="0">

                <tr class="heading">
                    <td>Item</td>
                    <td>Quantity</td>

                    <td>Price</td>
                </tr>

                <tr class="item">
                    <td>Duo Burger Meal</td>
                    <td>1</td>
                    <td>$ 15</td>
                </tr>




                <tr class="">
                    <td></td>
                    <td>Item Total : </td>
                    <td>$ 15</td>
                </tr>
                <tr class="">
                    <td></td>
                    <td>Packing Charge: </td>
                    <td>$ 2.25</td>
                </tr>
                <tr class="">
                    <td></td>
                    <td>Delivery Fee: </td>
                    <td>$ 0</td>
                </tr>
                <tr class="">
                    <td></td>
                    <td>Tax: </td>
                    <td>$1.5.00</td>
                </tr>
                <tr class="">
                    <td></td>
                    <td>Tips : </td>
                    <td>$0</td>
                </tr>
                <tr class="">
                    <td></td>
                    <td>Total Discount : </td>
                    <td>$0</td>
                </tr>
                <tr class="">
                    <td></td>
                    <td>Total : </td>
                    <td>$ 18.75</td>
                </tr>
            </table>
        </div>
        <!-- <div class="r  pr-16 pl-16"
             style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;">
             <table align="center" border="0" cellpadding="0" cellspacing="0" role="none"
                 style="background:#fffffe;background-color:#fffffe;width:100%;">
                 <tbody>
                     <tr>
                         <td style="border:none;direction:ltr;font-size:0;padding:20px 16px 20px 16px;text-align:left;">
                             <div class="xc568 ogf c"
                                 style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
                                 <table border="0" cellpadding="0" cellspacing="0" role="none"
                                     style="border:none;vertical-align:middle;" width="100%">
                                     <tbody>
                                         <tr>
                                             <td align="center" vertical-align="middle" class="b  fw-1"
                                                 style="font-size:0;padding:0;word-break:break-word;">
                                                 <table border="0" cellpadding="0" cellspacing="0" role="none"
                                                     style="border-collapse:separate;width:183px;line-height:100%;">
                                                     <tbody>
                                                         <tr>
                                                             <td align="center" bgcolor="#80be3f" role="none"
                                                                 style="border:none;border-radius:60px 60px 60px 60px;cursor:auto;mso-padding-alt:12px 0px 12px 0px;background:#80be3f;cursor:pointer"
                                                                 valign="middle" @click="edit_content('4','content4')"> <a
                                                                     style="display:inline-block;width:183px;background:#80be3f;color:#ffffff;font-family:Inter,Arial,sans-serif;font-size:13px;font-weight:normal;line-height:100%;margin:0;text-decoration:none;text-transform:none;padding:12px 0px 12px 0px;mso-padding-alt:0;border-radius:60px 60px 60px 60px;"
                                                                     target="_blank"> <span
                                                                         style="font-size:14px;font-family:Inter,Arial,sans-serif;font-weight:700;color:#ffffff;line-height:121%;">{{content[4]}}</span></a>
                                                             </td>
                                                         </tr>
                                                     </tbody>
                                                 </table>
                                             </td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </div>

                         </td>
                     </tr>
                 </tbody>
             </table>
         </div> -->
        <div class="r  pr-16 pl-16"
            style="background:#fffffe;background-color:#fffffe;margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="none"
                style="background:#fffffe;background-color:#fffffe;width:100%;">
                <tbody>
                    <tr>
                        <td style="border:none;direction:ltr;font-size:0;padding:32px 32px 32px 32px;text-align:left;">
                            <div class="xc536 ogf c"
                                style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="none"
                                    style="border:none;vertical-align:middle;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="left" class="x" style="font-size:0;word-break:break-word;">
                                                <div style="text-align:left;">
                                                    <p style="Margin:0;text-align:left;cursor:pointer"
                                                        @click="edit_content('5', 'content5')"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:400;color:#777777;line-height:150%;">{{
                                                            content[5] }}</span>
                                                    </p>
                                                    <p style="Margin:0;cursor:pointer"
                                                        @click="edit_content('6', 'content6')"><span
                                                            style="font-size:16px;font-family:Inter,Arial,sans-serif;font-weight:400;color:#777777;line-height:150%;">{{
                                                            content[6] }}</span>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="r  pr-16 pl-16"
            style="background:#E01764;background-color:#E01764;margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="none"
                style="background:#E01764;background-color:#E01764;width:100%;">
                <tbody>
                    <tr>
                        <td style="border:none;direction:ltr;font-size:0;padding:16px 16px 16px 16px;text-align:left;">
                            <div class="xc568 ogf c"
                                style="font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%;">
                                <table border="0" cellpadding="0" cellspacing="0" role="none"
                                    style="border:none;vertical-align:middle;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td align="center" class="x" style="font-size:0;word-break:break-word;">
                                                <div style="text-align:center;">
                                                    <p style="Margin:0;text-align:center;"><span
                                                            style="font-size:13px;font-family:Inter,Arial,sans-serif;font-weight:400;color:#ffffff;line-height:123%;">©
                                                            2023 {{ alt }}. All Rights Reserved.</span></p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <b-modal id="modal-center" centered size="lg" hide-header hide-footer ref="size-modal">

            <b-form-group :label="$t('Content')">
                <b-form-textarea v-model="content[content_name]" id="textarea-default" :placeholder="$t('Content')"
                    rows="10" />
            </b-form-group>

            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" @click="save_content(content_name, file)"
                variant="primary" class="mr-1">
                {{ $t('save') }}
            </b-button>


        </b-modal>
    </div>
</template>

<script>
import {
    BFormFile,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
    BFormTags, BFormTextarea
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import { heightTransition } from "@core/mixins/ui/transition";
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
    components: {
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
        BFormTags, BFormTextarea
    },
    directives: {
        Ripple,
    },
    mixins: [heightTransition],
    data() {
        return {
            rows_detail: '',
            alt: '',
            content_name: '',
            file: '',
            content: [],
        };
    },



    mounted() {

    },
    destroyed() {

    },
    created() {

        this.$http.get('/get_site_logo/')
            .then(res => {

                this.rows_detail = res.data.logo

                this.alt = res.data.app_name


            })
        var page = "invoicemail"
        this.$http.get('/admin/get_mail_contents/' + page)
            .then(res => {

                console.log(res)
                this.content = res.data.contents
                this.content_name = "content1"
                console.log(this.content)

            })



    },
    methods: {

        edit_content(content_name, file) {
            console.log(this.content)

            this.content_name = content_name
            this.file = file
            this.$refs["size-modal"].show();
        },

        save_content(content_name, file) {

            console.log(this.content[content_name])
            console.log(file)
            let data = new FormData();
            data.append('content_value', this.content[content_name]);
            data.append('content_name', file);
            data.append('page', "invoicemail");


            this.$http.post('/admin/edit_mails', data)

                .then(
                    response => {
                        console.log(response.data.content)
                        this.content[this.content_name] = response.data.content
                        this.$refs["size-modal"].hide();
                    }
                )
                .catch((error) => console.log(error))
                .finally(() => (this.loading = false));

        }

    },
};
</script>

<style type="text/css">
#outlook a {
    padding: 0;
}

body {
    margin: 0;
    padding: 0;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
}

table,
td {
    border-collapse: collapse;
    mso-table-lspace: 0pt;
    mso-table-rspace: 0pt;
}

img {
    border: 0;
    height: auto;
    line-height: 100%;
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
}

p {
    display: block;
    margin: 0;
}
</style>

<style type="text/css"></style>
<style type="text/css">
@media only screen and (min-width:599px) {
    .xc568 {
        width: 568px !important;
        max-width: 568px;
    }

    .xc536 {
        width: 536px !important;
        max-width: 536px;
    }
}
</style>
<style media="screen and (min-width:599px)">
.moz-text-html .xc568 {
    width: 568px !important;
    max-width: 568px;
}

.moz-text-html .xc536 {
    width: 536px !important;
    max-width: 536px;
}
</style>
<style type="text/css">
@media only screen and (max-width:599px) {
    table.fwm {
        width: 100% !important;
    }

    td.fwm {
        width: auto !important;
    }
}
</style>
<style type="text/css">
u+.emailify .gs {
    background: #000;
    mix-blend-mode: screen;
    display: inline-block;
    padding: 0;
    margin: 0;
}

u+.emailify .gd {
    background: #000;
    mix-blend-mode: difference;
    display: inline-block;
    padding: 0;
    margin: 0;
}

u+.emailify a,
#MessageViewBody a,
a[x-apple-data-detectors] {
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}

span.MsoHyperlink {
    mso-style-priority: 99;
    color: inherit;
}

span.MsoHyperlinkFollowed {
    mso-style-priority: 99;
    color: inherit;
}

td.b .klaviyo-image-block {
    display: inline;
    vertical-align: middle;
}

@media only screen and (max-width:599px) {
    .emailify {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    u+.emailify .glist {
        margin-left: 1em !important;
    }

    td.ico.v>div.il>a.l.m,
    td.ico.v .mn-label {
        padding-right: 0 !important;
        padding-bottom: 16px !important;
    }

    td.x {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .fwm img {
        max-width: 100% !important;
        height: auto !important;
    }

    .aw img {
        width: auto !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .ah img {
        height: auto !important;
    }

    td.b.nw>table,
    td.b.nw a {
        width: auto !important;
    }

    td.stk {
        border: 0 !important;
    }

    td.u {
        height: auto !important;
    }

    br.sb {
        display: none !important;
    }

    .thd-1 .i-thumbnail {
        display: inline-block !important;
        height: auto !important;
        overflow: hidden !important;
    }

    .hd-1 {
        display: block !important;
        height: auto !important;
        overflow: visible !important;
    }

    .ht-1 {
        display: table !important;
        height: auto !important;
        overflow: visible !important;
    }

    .hr-1 {
        display: table-row !important;
        height: auto !important;
        overflow: visible !important;
    }

    .hc-1 {
        display: table-cell !important;
        height: auto !important;
        overflow: visible !important;
    }

    div.r.pr-16>table>tbody>tr>td,
    div.r.pr-16>div>table>tbody>tr>td {
        padding-right: 16px !important
    }

    div.r.pl-16>table>tbody>tr>td,
    div.r.pl-16>div>table>tbody>tr>td {
        padding-left: 16px !important
    }

    td.b.fw-1>table {
        width: 100% !important
    }

    td.fw-1>table>tbody>tr>td>a {
        display: block !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    td.b.fw-1>table {
        width: 100% !important
    }

    td.fw-1>table>tbody>tr>td {
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
}


.invoice-box table {
    width: 100%;
    line-height: inherit;
    text-align: left;
}

.invoice-box table td {
    padding: 5px;
    vertical-align: top;
}

.invoice-box table tr td:nth-child(2) {
    text-align: right;
}

.invoice-box table tr.top table td {
    padding-bottom: 20px;
}

.invoice-box table tr.top table td.title {
    font-size: 45px;
    line-height: 45px;
    color: #333;
}

.invoice-box table tr.information table td {
    padding-bottom: 40px;
}

.invoice-box table tr.heading td {
    background: #eee;
    border-bottom: 1px solid #ddd;
    font-weight: bold;
}

.invoice-box table tr.details td {
    padding-bottom: 20px;
}

.invoice-box table tr.item td {
    border-bottom: 1px solid #eee;
}

.invoice-box table tr.item.last td {
    border-bottom: none;
}

.invoice-box table tr.total td:nth-child(2) {
    border-top: 2px solid #eee;
    font-weight: bold;
}

@media only screen and (max-width: 600px) {
    .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
    }

    .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
    }
}

/** RTL **/
.invoice-box.rtl {
    direction: rtl;
    font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
}

.invoice-box.rtl table {
    text-align: right;
}

.invoice-box.rtl table tr td:nth-child(2) {
    text-align: left;
}
</style>
