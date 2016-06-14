<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\EbizuActiveRecord;

class Company extends EbizuActiveRecord
{
    public $captcha;
    public $free_trial;
    public $fes_id;
    public $week;
    public $total;
    public $ebizu;
    public $tm;
    public $mall;
    public $exclusive;
    public $approved;
    public $idTemp;
    public $isMallManaged = false;
    public $mall_id;
    public $old_photo = null;
    public $old_banner = null;
    public $tag;
    public $totalBiz;
    public $id;
    public $text;
    public $mall_name;
    public $com_in_mall = true;

    CONST COM_STATUS_NOT_ACTIVE = 0;
    CONST COM_STATUS_ACTIVE = 1;
    CONST COM_STATUS_DACTIVED = 2;
    CONST COM_TYPE_MERCHANT = 0;
    CONST COM_TYPE_PARTNER = 1;
    CONST STATUS_OPEN_HOURS_CLOSE = 0;
    CONST STATUS_OPEN_HOURS_OPEN24H = 1;
    CONST STATUS_OPEN_HOURS_OPEN = 2;

    public static function tableName()
    {
        return 'tbl_company';
    }

    public function rules()
    {
        return [
            [['com_name', 'com_business_name'], 'required', 'on' => 'update-profile'],
            [['com_name', 'com_business_name', 'com_category_id', 'com_agent_code', 'com_par_id'], 'required', 'on' => 'partner'],
            [['com_par_id', 'com_email'], 'safe', 'on' => 'snapEarnUpdate'],
            [['com_email'], 'unique'],
            [['com_name', 'com_email'], 'required'],
            [['com_email'], 'email'],
            [['com_business_name'], 'required', 'on' => 'signup'],
            [['com_point'], 'required', 'on' => 'point'],
            ['com_status', 'required', 'on' => 'change_status'],
            [['com_photo', 'com_banner_photo'], 'validateGif'],
            [['com_joined', 'com_joined_datetime', 'com_joined_by'], 'required', 'on' => 'joined'],
            [['com_timezone', 'com_mac_id'], 'integer'],
            [[
                'com_name',
                'com_business_name',
                'com_id',
                'com_hq_id',
                'com_principal_id',
                'com_usr_id',
                'com_category_id',
                'com_country_id',
                'com_region_id',
                'com_city_id',
                'com_point',
                'com_status',
                'com_moderated',
                'com_level',
                'com_rating_sum',
                'com_rating_count',
                'com_nr_views',
                'com_nr_comments',
                'com_nr_media',
                'com_created_date',
                'com_created_by',
                'com_edited_date',
                'com_edited_by',
                'com_keywords',
                'com_membership_id',
                'com_credit',
                'com_contactp_last_name',
                'com_contactp_birthdate',
                'com_contactp_gender',
                'com_searchable',
                'com_show_start_tips',
                'com_type', 'com_in_mall',
                'com_snapearn',
                'com_snapearn_checkin',
                'com_reg_num',
                'com_description',
                'com_subcategory_id',
                'com_in_mall',
                'com_address',
                'com_city',
                'com_postcode',
                'com_phone',
                'com_fax',
                'com_website',
                'com_latitude',
                'com_longitude',
                'com_fb',
                'com_twitter',
                'com_size',
                'com_nbrs_employees',
                'com_agent_code', 'com_registered_to',
                'free_trial',
                'fes_id',
                'com_gst_id',
                'com_gst_enabled',
                'com_mem_id',
                'com_photo',
                'com_banner_photo',
                'com_open_monday',
                'com_close_monday',
                'com_status_monday',
                'com_open_tuesday',
                'com_close_tuesday',
                'com_status_tuesday',
                'com_open_wednesday',
                'com_close_wednesday',
                'com_status_wednesday',
                'com_open_thursday',
                'com_close_thursday',
                'com_status_thursday',
                'com_open_friday',
                'com_close_friday',
                'com_status_friday',
                'com_open_saturday',
                'com_close_saturday',
                'com_status_saturday',
                'com_open_sunday',
                'com_close_sunday',
                'com_status_sunday',
                'com_deposit',
                'com_prc_id',
                'com_bst_id',
                'com_sales_id',
                'com_sales_order',
                'com_par_id',
                'com_par_createdby',
                'tag',
            ], 'safe'],
            [['mall_id'], 'required', 'when' => function($model) {
                return $this ->com_in_mall == 1;
            }, 'whenClient' => "function (attribute, value) {
                return $('#company-com_in_mall').val() == 1;
            }"],
        ];
    }

    public function validateGif($data)
    {
        list($txt, $ext) = explode(".", $this->$data);
        if ($ext == 'gif') {
            $this->addError($data, Yii::t('app', 'Invalid image format.'));
            $this->$data = '';
        }
    }

    public function attributeLabels()
    {
        return [
            'com_id' => 'ID',
            'com_hq_id' => 'Hq',
            'com_principal_id' => 'Principal',
            'com_usr_id' => 'User',
            'com_name' => 'Outlet Name',
            'com_description' => 'Description',
            'com_category_id' => 'Category',
            'com_subcategory_id' => 'Category',
            'com_country_id' => 'Country',
            'com_region_id' => 'Region',
            'com_city_id' => 'City',
            'com_city' => 'Location',
            'com_address' => 'Address',
            'com_postcode' => 'POS Code',
            'com_phone' => 'Phone',
            'com_fax' => 'Fax',
            'com_email' => 'Email',
            'com_website' => 'Website',
            'com_latitude' => 'Latitude',
            'com_longitude' => 'Longitude',
            'com_point' => 'Point',
            'com_cellid' => 'Cellid',
            'com_size' => 'Size',
            'com_nbrs_employees' => 'Numbers of Employees',
            'com_houroperation' => 'Houroperation',
            'com_payment_accepted' => 'Payment Accepted',
            'com_photo' => 'Logo',
            'com_banner_photo' => 'Business Image',
            'com_status' => 'Status',
            'com_moderated' => 'Moderated',
            'com_level' => 'Level',
            'com_rating_average' => 'Rating Average',
            'com_rating_sum' => 'Rating Sum',
            'com_rating_count' => 'Rating Count',
            'com_nr_views' => 'Nr Views',
            'com_nr_comments' => 'Nr Comments',
            'com_nr_media' => 'Nr Media',
            'com_open_monday' => 'Open Monday',
            'com_close_monday' => 'Close Monday',
            'com_open_tuesday' => 'Open Tuesday',
            'com_close_tuesday' => 'Close Tuesday',
            'com_open_wednesday' => 'Open Wednesday',
            'com_close_wednesday' => 'Close Wednesday',
            'com_open_thursday' => 'Open Thursday',
            'com_close_thursday' => 'Close Thursday',
            'com_open_friday' => 'Open Friday',
            'com_close_friday' => 'Close Friday',
            'com_open_saturday' => 'Open Saturday',
            'com_close_saturday' => 'Close Saturday',
            'com_open_sunday' => 'Open Sunday',
            'com_close_sunday' => 'Close Sunday',
            'com_created_date' => 'Created On',
            'com_created_by' => 'Created By',
            'com_edited_date' => 'Edited Date',
            'com_edited_by' => 'Edited By',
            'com_keywords' => 'Keywords',
            'com_membership_id' => 'Membership',
            'com_extracity' => 'Extracity',
            'com_credit' => 'Credit',
            'com_contactp_first_name' => 'Contactp First Name',
            'com_contactp_last_name' => 'Contactp Last Name',
            'com_contactp_birthdate' => 'Contactp Birthdate',
            'com_contactp_mobile' => 'Contactp Mobile',
            'com_contactp_email' => 'Contactp Email',
            'com_contactp_gender' => 'Contactp Gender',
            'com_contactp_password' => 'Contactp Password',
            'com_product_category' => 'Product Category',
            'com_default_delivery' => 'Default Delivery',
            'com_default_payment' => 'Default Payment',
            'com_fb' => 'Facebook',
            'com_twitter' => 'Twitter',
            'com_tag' => 'Tag',
            'com_language' => 'Language',
            'com_currency' => 'Currency',
            'com_searchable' => 'Searchable',
            'com_show_start_tips' => 'Show Start Tips',
            'com_timezone' => 'Timezone',
            'com_type' => 'Type',
            'com_in_mall' => 'In The Mall',
            'com_reg_num' => 'Registered Number',
            'com_agent_code' => 'Agent Code',
            'com_business_name' => 'Business Name',
            'com_snapearn' => 'Snapearn',
            'com_snapearn_checkin' => 'Snapearn Checkin',
            'com_subscription_biling_status' => 'Exclusive',
            'com_registered_to' => 'Registered To',
            'fes_id' => 'Package',
            'free_trial' => 'Free Trial',
            'com_mac_id' => 'In Mall Category',
            'com_gst_id' => 'GST Reg No.',
            'com_gst_enabled' => 'Enable GST ?',
            'com_mem_id' => 'Member ID',
            'subscriptionCompany.amount' => 'Amount',
            'com_prc_id' => 'Principal',
            'com_bst_id' => 'Bussines Type',
            'com_premium' => 'Premium',
            'mall_id' => 'Mall Name',
            'com_tag' => 'Tags',
            'cam_tes' => 'Test',
            'com_sales_id' => 'Sales',
            'com_sales_order' => 'Sales Order',
            'com_account_service_type' => 'Account Service Type',
            'com_joined' => 'Joined',
            'com_joined_datetime' => 'Joined On',
            'com_joined_by' => 'Joined By',
            'totalBiz' => 'Branches'
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['com_created_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['com_edited_date'],
                ],
            ],
        ];
    }

    /*
     * this function used to clear cache photo & banner on the S3
     * Author : tajhul <tajhul@ebizu.com>
     */
    public function clearCacheImage()
    {
        // if the photo has been changed
        if ($this->old_photo !== null && ($this->old_photo != $this->com_photo)) {
            \app\components\helpers\Image::DeleteS3($this->old_photo);
        }

        // if the banner photo has been changed
        if ($this->old_banner !== null && ($this->old_banner != $this->com_banner_photo)) {
            \app\components\helpers\Image::DeleteS3($this->old_banner);
        }
    }

    public function getModelMallMerchant()
    {
        $model = MallMerchant::findOne(['mam_com_id' => $this->com_id]);
        $model->scenario= 'newMerchant';
        if ($model)
            return $model;
        return new MallMerchant();
    } 

    public function getTimeZoneListData()
    {
        return [
            '370' => 'SST (-11:00) Pacific/Midway',
            '281' => 'NUT (-11:00) Pacific/Niue',
            '31' => 'SST (-11:00) Pacific/Pago_Pago',
            '399' => 'HAST (-10:00) America/Adak',
            '401' => 'HST (-10:00) Pacific/Honolulu',
            '369' => 'HST (-10:00) Pacific/Johnston',
            '119' => 'CKT (-10:00) Pacific/Rarotonga',
            '287' => 'TAHT (-10:00) Pacific/Tahiti',
            '288' => 'MART (-09:30) Pacific/Marquesas',
            '394' => 'AKST (-09:00) America/Anchorage',
            '395' => 'AKST (-09:00) America/Juneau',
            '398' => 'AKST (-09:00) America/Nome',
            '396' => 'AKST (-09:00) America/Sitka',
            '397' => 'AKST (-09:00) America/Yakutat',
            '289' => 'GAMT (-09:00) Pacific/Gambier',
            '111' => 'PST (-08:00) America/Dawson',
            '393' => 'PST (-08:00) America/Los_Angeles',
            '400' => 'MeST (-08:00) America/Metlakatla',
            '266' => 'PST (-08:00) America/Santa_Isabel',
            '265' => 'PST (-08:00) America/Tijuana',
            '109' => 'PST (-08:00) America/Vancouver',
            '110' => 'PST (-08:00) America/Whitehorse',
            '295' => 'PST (-08:00) Pacific/Pitcairn',
            '390' => 'MST (-07:00) America/Boise',
            '104' => 'MST (-07:00) America/Cambridge_Bay',
            '262' => 'MST (-07:00) America/Chihuahua',
            '107' => 'MST (-07:00) America/Creston',
            '108' => 'MST (-07:00) America/Dawson_Creek',
            '389' => 'MST (-07:00) America/Denver',
            '103' => 'MST (-07:00) America/Edmonton',
            '264' => 'MST (-07:00) America/Hermosillo',
            '106' => 'MST (-07:00) America/Inuvik',
            '261' => 'MST (-07:00) America/Mazatlan',
            '263' => 'MST (-07:00) America/Ojinaga',
            '392' => 'MST (-07:00) America/Phoenix',
            '391' => 'MST (-07:00) America/Shiprock',
            '105' => 'MST (-07:00) America/Yellowknife',
            '267' => 'CST (-06:00) America/Bahia_Banderas',
            '83' => 'CST (-06:00) America/Belize',
            '257' => 'CST (-06:00) America/Cancun',
            '382' => 'CST (-06:00) America/Chicago',
            '129' => 'CST (-06:00) America/Costa_Rica',
            '344' => 'CST (-06:00) America/El_Salvador',
            '179' => 'CST (-06:00) America/Guatemala',
            '384' => 'CST (-06:00) America/Indiana/Knox',
            '383' => 'CST (-06:00) America/Indiana/Tell_City',
            '276' => 'CST (-06:00) America/Managua',
            '260' => 'CST (-06:00) America/Matamoros',
            '385' => 'CST (-06:00) America/Menominee',
            '258' => 'CST (-06:00) America/Merida',
            '256' => 'CST (-06:00) America/Mexico_City',
            '259' => 'CST (-06:00) America/Monterrey',
            '388' => 'CST (-06:00) America/North_Dakota/Beulah',
            '386' => 'CST (-06:00) America/North_Dakota/Center',
            '387' => 'CST (-06:00) America/North_Dakota/New_Salem',
            '100' => 'CST (-06:00) America/Rainy_River',
            '98' => 'CST (-06:00) America/Rankin_Inlet',
            '101' => 'CST (-06:00) America/Regina',
            '96' => 'CST (-06:00) America/Resolute',
            '102' => 'CST (-06:00) America/Swift_Current',
            '184' => 'CST (-06:00) America/Tegucigalpa',
            '99' => 'CST (-06:00) America/Winnipeg',
            '144' => 'GALT (-06:00) Pacific/Galapagos',
            '97' => 'EST (-05:00) America/Atikokan',
            '128' => 'COT (-05:00) America/Bogota',
            '216' => 'EST (-05:00) America/Cayman',
            '373' => 'EST (-05:00) America/Detroit',
            '348' => 'EST (-05:00) America/Grand_Turk',
            '143' => 'ECT (-05:00) America/Guayaquil',
            '130' => 'CST (-05:00) America/Havana',
            '376' => 'EST (-05:00) America/Indiana/Indianapolis',
            '379' => 'EST (-05:00) America/Indiana/Marengo',
            '380' => 'EST (-05:00) America/Indiana/Petersburg',
            '381' => 'EST (-05:00) America/Indiana/Vevay',
            '377' => 'EST (-05:00) America/Indiana/Vincennes',
            '378' => 'EST (-05:00) America/Indiana/Winamac',
            '94' => 'EST (-05:00) America/Iqaluit',
            '202' => 'EST (-05:00) America/Jamaica',
            '374' => 'EST (-05:00) America/Kentucky/Louisville',
            '375' => 'EST (-05:00) America/Kentucky/Monticello',
            '286' => 'PET (-05:00) America/Lima',
            '90' => 'EST (-05:00) America/Montreal',
            '79' => 'EST (-05:00) America/Nassau',
            '372' => 'EST (-05:00) America/New_York',
            '92' => 'EST (-05:00) America/Nipigon',
            '285' => 'EST (-05:00) America/Panama',
            '95' => 'EST (-05:00) America/Pangnirtung',
            '186' => 'EST (-05:00) America/Port-au-Prince',
            '93' => 'EST (-05:00) America/Thunder_Bay',
            '91' => 'EST (-05:00) America/Toronto',
            '121' => 'EASST (-05:00) Pacific/Easter',
            '407' => 'VET (-04:30) America/Caracas',
            '5' => 'AST (-04:00) America/Anguilla',
            '4' => 'AST (-04:00) America/Antigua',
            '46' => 'AST (-04:00) America/Aruba',
            '50' => 'AST (-04:00) America/Barbados',
            '89' => 'AST (-04:00) America/Blanc-Sablon',
            '75' => 'AMT (-04:00) America/Boa_Vista',
            '132' => 'AST (-04:00) America/Curacao',
            '140' => 'AST (-04:00) America/Dominica',
            '77' => 'AMT (-04:00) America/Eirunepe',
            '86' => 'AST (-04:00) America/Glace_Bay',
            '88' => 'AST (-04:00) America/Goose_Bay',
            '163' => 'AST (-04:00) America/Grenada',
            '175' => 'AST (-04:00) America/Guadeloupe',
            '182' => 'GYT (-04:00) America/Guyana',
            '85' => 'AST (-04:00) America/Halifax',
            '62' => 'AST (-04:00) America/Kralendijk',
            '61' => 'BOT (-04:00) America/La_Paz',
            '345' => 'AST (-04:00) America/Lower_Princes',
            '76' => 'AMT (-04:00) America/Manaus',
            '237' => 'AST (-04:00) America/Marigot',
            '249' => 'AST (-04:00) America/Martinique',
            '87' => 'AST (-04:00) America/Moncton',
            '251' => 'AST (-04:00) America/Montserrat',
            '360' => 'AST (-04:00) America/Port_of_Spain',
            '74' => 'AMT (-04:00) America/Porto_Velho',
            '296' => 'AST (-04:00) America/Puerto_Rico',
            '78' => 'AMT (-04:00) America/Rio_Branco',
            '141' => 'AST (-04:00) America/Santo_Domingo',
            '58' => 'AST (-04:00) America/St_Barthelemy',
            '212' => 'AST (-04:00) America/St_Kitts',
            '224' => 'AST (-04:00) America/St_Lucia',
            '409' => 'AST (-04:00) America/St_Thomas',
            '406' => 'AST (-04:00) America/St_Vincent',
            '172' => 'AST (-04:00) America/Thule',
            '408' => 'AST (-04:00) America/Tortola',
            '59' => 'AST (-04:00) Atlantic/Bermuda',
            '84' => 'NST (-03:30) America/St_Johns',
            '19' => 'ART (-03:00) America/Argentina/Buenos_Aires',
            '24' => 'ART (-03:00) America/Argentina/Catamarca',
            '20' => 'ART (-03:00) America/Argentina/Cordoba',
            '22' => 'ART (-03:00) America/Argentina/Jujuy',
            '25' => 'ART (-03:00) America/Argentina/La_Rioja',
            '27' => 'ART (-03:00) America/Argentina/Mendoza',
            '29' => 'ART (-03:00) America/Argentina/Rio_Gallegos',
            '21' => 'ART (-03:00) America/Argentina/Salta',
            '26' => 'ART (-03:00) America/Argentina/San_Juan',
            '28' => 'WARST (-03:00) America/Argentina/San_Luis',
            '23' => 'ART (-03:00) America/Argentina/Tucuman',
            '30' => 'ART (-03:00) America/Argentina/Ushuaia',
            '303' => 'PYST (-03:00) America/Asuncion',
            '69' => 'BRT (-03:00) America/Bahia',
            '64' => 'BRT (-03:00) America/Belem',
            '71' => 'AMST (-03:00) America/Campo_Grande',
            '165' => 'GFT (-03:00) America/Cayenne',
            '72' => 'AMST (-03:00) America/Cuiaba',
            '65' => 'BRT (-03:00) America/Fortaleza',
            '169' => 'WGT (-03:00) America/Godthab',
            '68' => 'BRT (-03:00) America/Maceio',
            '294' => 'PMST (-03:00) America/Miquelon',
            '341' => 'SRT (-03:00) America/Paramaribo',
            '66' => 'BRT (-03:00) America/Recife',
            '73' => 'BRT (-03:00) America/Santarem',
            '120' => 'CLST (-03:00) America/Santiago',
            '12' => 'CLST (-03:00) Antarctica/Palmer',
            '11' => 'ROTT (-03:00) Antarctica/Rothera',
            '155' => 'FKST (-03:00) Atlantic/Stanley',
            '67' => 'BRST (-02:00) America/Araguaina',
            '402' => 'UYST (-02:00) America/Montevideo',
            '63' => 'FNT (-02:00) America/Noronha',
            '70' => 'BRST (-02:00) America/Sao_Paulo',
            '178' => 'GST (-02:00) Atlantic/South_Georgia',
            '171' => 'EGT (-01:00) America/Scoresbysund',
            '301' => 'AZOT (-01:00) Atlantic/Azores',
            '131' => 'CVT (-01:00) Atlantic/Cape_Verde',
            '118' => 'GMT (+00:00) Africa/Abidjan',
            '167' => 'GMT (+00:00) Africa/Accra',
            '242' => 'GMT (+00:00) Africa/Bamako',
            '173' => 'GMT (+00:00) Africa/Banjul',
            '181' => 'GMT (+00:00) Africa/Bissau',
            '233' => 'WET (+00:00) Africa/Casablanca',
            '174' => 'GMT (+00:00) Africa/Conakry',
            '339' => 'GMT (+00:00) Africa/Dakar',
            '147' => 'WET (+00:00) Africa/El_Aaiun',
            '337' => 'GMT (+00:00) Africa/Freetown',
            '351' => 'GMT (+00:00) Africa/Lome',
            '227' => 'GMT (+00:00) Africa/Monrovia',
            '250' => 'GMT (+00:00) Africa/Nouakchott',
            '53' => 'GMT (+00:00) Africa/Ouagadougou',
            '343' => 'GMT (+00:00) Africa/Sao_Tome',
            '170' => 'GMT (+00:00) America/Danmarkshavn',
            '151' => 'WET (+00:00) Atlantic/Canary',
            '159' => 'WET (+00:00) Atlantic/Faroe',
            '300' => 'WET (+00:00) Atlantic/Madeira',
            '199' => 'GMT (+00:00) Atlantic/Reykjavik',
            '333' => 'GMT (+00:00) Atlantic/St_Helena',
            '192' => 'GMT (+00:00) Europe/Dublin',
            '166' => 'GMT (+00:00) Europe/Guernsey',
            '194' => 'GMT (+00:00) Europe/Isle_of_Man',
            '201' => 'GMT (+00:00) Europe/Jersey',
            '299' => 'WET (+00:00) Europe/Lisbon',
            '162' => 'GMT (+00:00) Europe/London',
            '142' => 'CET (+01:00) Africa/Algiers',
            '115' => 'WAT (+01:00) Africa/Bangui',
            '116' => 'WAT (+01:00) Africa/Brazzaville',
            '150' => 'CET (+01:00) Africa/Ceuta',
            '122' => 'WAT (+01:00) Africa/Douala',
            '113' => 'WAT (+01:00) Africa/Kinshasa',
            '275' => 'WAT (+01:00) Africa/Lagos',
            '161' => 'WAT (+01:00) Africa/Libreville',
            '8' => 'WAT (+01:00) Africa/Luanda',
            '176' => 'WAT (+01:00) Africa/Malabo',
            '349' => 'WAT (+01:00) Africa/Ndjamena',
            '273' => 'WAT (+01:00) Africa/Niamey',
            '57' => 'WAT (+01:00) Africa/Porto-Novo',
            '232' => 'CET (+01:00) Africa/Tripoli',
            '357' => 'CET (+01:00) Africa/Tunis',
            '335' => 'CET (+01:00) Arctic/Longyearbyen',
            '277' => 'CET (+01:00) Europe/Amsterdam',
            '1' => 'CET (+01:00) Europe/Andorra',
            '307' => 'CET (+01:00) Europe/Belgrade',
            '136' => 'CET (+01:00) Europe/Berlin',
            '336' => 'CET (+01:00) Europe/Bratislava',
            '52' => 'CET (+01:00) Europe/Brussels',
            '187' => 'CET (+01:00) Europe/Budapest',
            '137' => 'CET (+01:00) Europe/Busingen',
            '139' => 'CET (+01:00) Europe/Copenhagen',
            '168' => 'CET (+01:00) Europe/Gibraltar',
            '334' => 'CET (+01:00) Europe/Ljubljana',
            '230' => 'CET (+01:00) Europe/Luxembourg',
            '149' => 'CET (+01:00) Europe/Madrid',
            '252' => 'CET (+01:00) Europe/Malta',
            '234' => 'CET (+01:00) Europe/Monaco',
            '278' => 'CET (+01:00) Europe/Oslo',
            '160' => 'CET (+01:00) Europe/Paris',
            '236' => 'CET (+01:00) Europe/Podgorica',
            '135' => 'CET (+01:00) Europe/Prague',
            '200' => 'CET (+01:00) Europe/Rome',
            '338' => 'CET (+01:00) Europe/San_Marino',
            '49' => 'CET (+01:00) Europe/Sarajevo',
            '241' => 'CET (+01:00) Europe/Skopje',
            '331' => 'CET (+01:00) Europe/Stockholm',
            '6' => 'CET (+01:00) Europe/Tirane',
            '225' => 'CET (+01:00) Europe/Vaduz',
            '405' => 'CET (+01:00) Europe/Vatican',
            '32' => 'CET (+01:00) Europe/Vienna',
            '293' => 'CET (+01:00) Europe/Warsaw',
            '185' => 'CET (+01:00) Europe/Zagreb',
            '117' => 'CET (+01:00) Europe/Zurich',
            '255' => 'CAT (+02:00) Africa/Blantyre',
            '56' => 'CAT (+02:00) Africa/Bujumbura',
            '146' => 'EET (+02:00) Africa/Cairo',
            '81' => 'CAT (+02:00) Africa/Gaborone',
            '418' => 'CAT (+02:00) Africa/Harare',
            '416' => 'SAST (+02:00) Africa/Johannesburg',
            '326' => 'CAT (+02:00) Africa/Kigali',
            '114' => 'CAT (+02:00) Africa/Lubumbashi',
            '417' => 'CAT (+02:00) Africa/Lusaka',
            '270' => 'CAT (+02:00) Africa/Maputo',
            '228' => 'SAST (+02:00) Africa/Maseru',
            '347' => 'SAST (+02:00) Africa/Mbabane',
            '271' => 'WAST (+02:00) Africa/Windhoek',
            '203' => 'EET (+02:00) Asia/Amman',
            '223' => 'EET (+02:00) Asia/Beirut',
            '346' => 'EET (+02:00) Asia/Damascus',
            '297' => 'EET (+02:00) Asia/Gaza',
            '298' => 'EET (+02:00) Asia/Hebron',
            '193' => 'IST (+02:00) Asia/Jerusalem',
            '134' => 'EET (+02:00) Asia/Nicosia',
            '177' => 'EET (+02:00) Europe/Athens',
            '306' => 'EET (+02:00) Europe/Bucharest',
            '235' => 'EET (+02:00) Europe/Chisinau',
            '153' => 'EET (+02:00) Europe/Helsinki',
            '359' => 'EET (+02:00) Europe/Istanbul',
            '364' => 'EET (+02:00) Europe/Kiev',
            '47' => 'EET (+02:00) Europe/Mariehamn',
            '231' => 'EET (+02:00) Europe/Riga',
            '367' => 'EET (+02:00) Europe/Simferopol',
            '54' => 'EET (+02:00) Europe/Sofia',
            '145' => 'EET (+02:00) Europe/Tallinn',
            '365' => 'EET (+02:00) Europe/Uzhgorod',
            '229' => 'EET (+02:00) Europe/Vilnius',
            '366' => 'EET (+02:00) Europe/Zaporozhye',
            '152' => 'EAT (+03:00) Africa/Addis_Ababa',
            '148' => 'EAT (+03:00) Africa/Asmara',
            '363' => 'EAT (+03:00) Africa/Dar_es_Salaam',
            '138' => 'EAT (+03:00) Africa/Djibouti',
            '342' => 'EAT (+03:00) Africa/Juba',
            '368' => 'EAT (+03:00) Africa/Kampala',
            '330' => 'EAT (+03:00) Africa/Khartoum',
            '340' => 'EAT (+03:00) Africa/Mogadishu',
            '205' => 'EAT (+03:00) Africa/Nairobi',
            '18' => 'SYOT (+03:00) Antarctica/Syowa',
            '414' => 'AST (+03:00) Asia/Aden',
            '197' => 'AST (+03:00) Asia/Baghdad',
            '55' => 'AST (+03:00) Asia/Bahrain',
            '215' => 'AST (+03:00) Asia/Kuwait',
            '304' => 'AST (+03:00) Asia/Qatar',
            '327' => 'AST (+03:00) Asia/Riyadh',
            '308' => 'FET (+03:00) Europe/Kaliningrad',
            '82' => 'FET (+03:00) Europe/Minsk',
            '238' => 'EAT (+03:00) Indian/Antananarivo',
            '211' => 'EAT (+03:00) Indian/Comoro',
            '415' => 'EAT (+03:00) Indian/Mayotte',
            '198' => 'IRST (+03:30) Asia/Tehran',
            '48' => 'AZT (+04:00) Asia/Baku',
            '2' => 'GST (+04:00) Asia/Dubai',
            '284' => 'GST (+04:00) Asia/Muscat',
            '164' => 'GET (+04:00) Asia/Tbilisi',
            '7' => 'AMT (+04:00) Asia/Yerevan',
            '309' => 'MSK (+04:00) Europe/Moscow',
            '311' => 'SAMT (+04:00) Europe/Samara',
            '310' => 'VOLT (+04:00) Europe/Volgograd',
            '329' => 'SCT (+04:00) Indian/Mahe',
            '253' => 'MUT (+04:00) Indian/Mauritius',
            '305' => 'RET (+04:00) Indian/Reunion',
            '3' => 'AFT (+04:30) Asia/Kabul',
            '13' => 'MAWT (+05:00) Antarctica/Mawson',
            '220' => 'AQTT (+05:00) Asia/Aqtau',
            '219' => 'AQTT (+05:00) Asia/Aqtobe',
            '356' => 'TMT (+05:00) Asia/Ashgabat',
            '353' => 'TJT (+05:00) Asia/Dushanbe',
            '292' => 'PKT (+05:00) Asia/Karachi',
            '221' => 'ORAT (+05:00) Asia/Oral',
            '403' => 'UZT (+05:00) Asia/Samarkand',
            '404' => 'UZT (+05:00) Asia/Tashkent',
            '350' => 'TFT (+05:00) Indian/Kerguelen',
            '254' => 'MVT (+05:00) Indian/Maldives',
            '226' => 'IST (+05:30) Asia/Colombo',
            '195' => 'IST (+05:30) Asia/Kolkata',
            '279' => 'NPT (+05:45) Asia/Kathmandu',
            '16' => 'VOST (+06:00) Antarctica/Vostok',
            '217' => 'ALMT (+06:00) Asia/Almaty',
            '206' => 'KGT (+06:00) Asia/Bishkek',
            '51' => 'BDT (+06:00) Asia/Dhaka',
            '218' => 'QYZT (+06:00) Asia/Qyzylorda',
            '80' => 'BTT (+06:00) Asia/Thimphu',
            '312' => 'YEKT (+06:00) Asia/Yekaterinburg',
            '196' => 'IOT (+06:00) Indian/Chagos',
            '243' => 'MMT (+06:30) Asia/Rangoon',
            '112' => 'CCT (+06:30) Indian/Cocos',
            '14' => 'DAVT (+07:00) Antarctica/Davis',
            '352' => 'ICT (+07:00) Asia/Bangkok',
            '410' => 'ICT (+07:00) Asia/Ho_Chi_Minh',
            '245' => 'HOVT (+07:00) Asia/Hovd',
            '188' => 'WIT (+07:00) Asia/Jakarta',
            '315' => 'NOVT (+07:00) Asia/Novokuznetsk',
            '314' => 'NOVT (+07:00) Asia/Novosibirsk',
            '313' => 'OMST (+07:00) Asia/Omsk',
            '207' => 'ICT (+07:00) Asia/Phnom_Penh',
            '189' => 'WIT (+07:00) Asia/Pontianak',
            '222' => 'ICT (+07:00) Asia/Vientiane',
            '133' => 'CXT (+07:00) Indian/Christmas',
            '15' => 'WST (+08:00) Antarctica/Casey',
            '60' => 'BNT (+08:00) Asia/Brunei',
            '246' => 'CHOT (+08:00) Asia/Choibalsan',
            '125' => 'CST (+08:00) Asia/Chongqing',
            '124' => 'CST (+08:00) Asia/Harbin',
            '183' => 'HKT (+08:00) Asia/Hong_Kong',
            '127' => 'CST (+08:00) Asia/Kashgar',
            '316' => 'KRAT (+08:00) Asia/Krasnoyarsk',
            '268' => 'MYT (+08:00) Asia/Kuala_Lumpur',
            '269' => 'MYT (+08:00) Asia/Kuching',
            '247' => 'CST (+08:00) Asia/Macau',
            '190' => 'CIT (+08:00) Asia/Makassar',
            '291' => 'PHT (+08:00) Asia/Manila',
            '123' => 'CST (+08:00) Asia/Shanghai',
            '332' => 'SGT (+08:00) Asia/Singapore',
            '362' => 'CST (+08:00) Asia/Taipei',
            '244' => 'ULAT (+08:00) Asia/Ulaanbaatar',
            '126' => 'CST (+08:00) Asia/Urumqi',
            '44' => 'WST (+08:00) Australia/Perth',
            '45' => 'CWST (+08:45) Australia/Eucla',
            '355' => 'TLT (+09:00) Asia/Dili',
            '317' => 'IRKT (+09:00) Asia/Irkutsk',
            '191' => 'EIT (+09:00) Asia/Jayapura',
            '213' => 'KST (+09:00) Asia/Pyongyang',
            '214' => 'KST (+09:00) Asia/Seoul',
            '204' => 'JST (+09:00) Asia/Tokyo',
            '302' => 'PWT (+09:00) Pacific/Palau',
            '43' => 'CST (+09:30) Australia/Darwin',
            '17' => 'DDUT (+10:00) Antarctica/DumontDUrville',
            '319' => 'YAKT (+10:00) Asia/Khandyga',
            '318' => 'YAKT (+10:00) Asia/Yakutsk',
            '40' => 'EST (+10:00) Australia/Brisbane',
            '41' => 'EST (+10:00) Australia/Lindeman',
            '156' => 'CHUT (+10:00) Pacific/Chuuk',
            '180' => 'ChST (+10:00) Pacific/Guam',
            '290' => 'PGT (+10:00) Pacific/Port_Moresby',
            '248' => 'ChST (+10:00) Pacific/Saipan',
            '42' => 'CST (+10:30) Australia/Adelaide',
            '39' => 'CST (+10:30) Australia/Broken_Hill',
            '34' => 'MIST (+11:00) Antarctica/Macquarie',
            '321' => 'SAKT (+11:00) Asia/Sakhalin',
            '322' => 'VLAT (+11:00) Asia/Ust-Nera',
            '320' => 'VLAT (+11:00) Asia/Vladivostok',
            '36' => 'EST (+11:00) Australia/Currie',
            '35' => 'EST (+11:00) Australia/Hobart',
            '33' => 'LHST (+11:00) Australia/Lord_Howe',
            '37' => 'EST (+11:00) Australia/Melbourne',
            '38' => 'EST (+11:00) Australia/Sydney',
            '411' => 'VUT (+11:00) Pacific/Efate',
            '328' => 'SBT (+11:00) Pacific/Guadalcanal',
            '158' => 'KOST (+11:00) Pacific/Kosrae',
            '272' => 'NCT (+11:00) Pacific/Noumea',
            '157' => 'PONT (+11:00) Pacific/Pohnpei',
            '274' => 'NFT (+11:30) Pacific/Norfolk',
            '325' => 'ANAT (+12:00) Asia/Anadyr',
            '324' => 'PETT (+12:00) Asia/Kamchatka',
            '323' => 'MAGT (+12:00) Asia/Magadan',
            '361' => 'TVT (+12:00) Pacific/Funafuti',
            '240' => 'MHT (+12:00) Pacific/Kwajalein',
            '239' => 'MHT (+12:00) Pacific/Majuro',
            '280' => 'NRT (+12:00) Pacific/Nauru',
            '208' => 'GILT (+12:00) Pacific/Tarawa',
            '371' => 'WAKT (+12:00) Pacific/Wake',
            '412' => 'WFT (+12:00) Pacific/Wallis',
            '9' => 'NZDT (+13:00) Antarctica/McMurdo',
            '10' => 'NZDT (+13:00) Antarctica/South_Pole',
            '282' => 'NZDT (+13:00) Pacific/Auckland',
            '209' => 'PHOT (+13:00) Pacific/Enderbury',
            '354' => 'TKT (+13:00) Pacific/Fakaofo',
            '154' => 'FJST (+13:00) Pacific/Fiji',
            '358' => 'TOT (+13:00) Pacific/Tongatapu',
            '283' => 'CHADT (+13:45) Pacific/Chatham',
            '413' => 'WSDT (+14:00) Pacific/Apia',
            '210' => 'LINT (+14:00) Pacific/Kiritimati',
        ];
    }

    public function getCategoryListData($type = 1)
    {
        $data = [];
        $categori = CompanyCategory::findAll([
            'com_parent_category_id' => '0'
        ]);

        foreach ($categori as $row) {
            if($row->com_category_type == $type) {
                $subCategory = CompanyCategory::findAll([
                    'com_parent_category_id' => $row->com_category_id
                ]);

                $dataSub = [];
                foreach ($subCategory as $sub)
                    if ($sub->com_parent_category_id == $row->com_category_id)
                        $dataSub[$sub->com_category_id] = $sub->com_category;

                $data[$row->com_category] = $dataSub;
            }
        }
        return $data;
    }

    public function getFeatureSubscription()
    {
        $model = FeatureSubscription::find()->all();
        return \app\components\helpers\Html::listData($model, 'fes_id', 'fes_name');
    }

    public function getCompanySizeListData()
    {
        return [
            'Small Enterprise' => 'Small Enterprise',
            'Medium Enterprise' => 'Medium Enterprise',
            'Large Enterprise' => 'Large Enterprise',
            'Multinational Enterprise' => 'Multinational Enterprise'
        ];
    }

    public function getNumberEmployeeListData()
    {
        return [
            '1-5' => '1-5',
            '6-25' => '6-25',
            '26-50' => '26-50',
            '51-250' => '51-250',
            '251-500' => '251-500',
            '500+' => '500+',
        ];
    }

    public function getCategoryList()
    {
        $model = (new yii\db\Query())
            ->select('com_category_id AS cat_id, com_category AS category, (
                    SELECT com_category FROM tbl_company_category WHERE com_category_id = com_parent_category_id
                ) AS parent_id
            ')
            ->from('tbl_company_category')
            ->where('com_category_type = :type AND com_parent_category_id > :parent', [
                ':type' => 1,
                ':parent' => 0
            ])
            ->all();
        return \app\components\helpers\Html::listData($model, 'cat_id', 'category', 'parent_id');
    }    

    public static function find()
    {
        return new CompanyQuery(get_called_class());
    }

}
