<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_member".
 *
 * @property integer $mem_id
 * @property integer $mem_usr_id
 * @property string $mem_fb_id
 * @property string $mem_nfc_tag
 * @property string $mem_screen_name
 * @property string $mem_first_name
 * @property string $mem_last_name
 * @property string $mem_address
 * @property string $mem_zipcode
 * @property string $mem_city
 * @property integer $mem_city_id
 * @property integer $mem_country_id
 * @property integer $mem_region_id
 * @property string $mem_phone
 * @property string $mem_mobile
 * @property string $mem_birthdate
 * @property string $mem_email
 * @property string $mem_gender
 * @property string $mem_nationality
 * @property string $mem_language
 * @property string $mem_url
 * @property string $mem_bank_name
 * @property string $mem_bank_account
 * @property string $mem_bank_iban_swift
 * @property string $mem_bank_account_holdersname
 * @property string $mem_bank_city
 * @property string $mem_photo
 * @property string $mem_banner
 * @property string $mem_freetext
 * @property integer $mem_freetext_changed
 * @property string $mem_status_update
 * @property integer $mem_status_update_datetime
 * @property integer $mem_credit
 * @property integer $mem_loyality_point
 * @property integer $mem_status
 * @property integer $mem_account_status
 * @property integer $mem_datetime
 * @property integer $mem_updated
 * @property string $mem_membership_type
 * @property integer $mem_lastfullaccess
 * @property string $mem_mobile_validation_code
 * @property integer $mem_mobile_validation_datetime_sent
 * @property string $mem_mobile_validated
 * @property integer $mem_mobile_validated_datetime
 * @property string $mem_email_validation_code
 * @property integer $mem_email_validation_datetime_sent
 * @property string $mem_email_validated
 * @property integer $mem_email_validated_datetime
 * @property integer $mem_subscribe_step
 * @property string $mem_ip_subscribe
 * @property string $mem_subscribe_from
 * @property double $mem_lasttime_lat
 * @property double $mem_lasttime_lng
 * @property integer $mem_lasttime_latlon
 * @property integer $mem_affiliate_id
 * @property string $mem_session_id
 * @property string $mem_online
 * @property integer $mem_online_checked
 * @property integer $mem_moderated
 * @property string $mem_last_changed
 * @property string $mem_suspicious
 * @property integer $mem_p_contact_type
 * @property integer $mem_p_height
 * @property integer $mem_p_weight
 * @property integer $mem_p_starsign
 * @property integer $mem_p_maritial_status
 * @property integer $mem_p_eye_color
 * @property integer $mem_p_hair_color
 * @property integer $mem_p_hair_cut
 * @property integer $mem_p_bodytype
 * @property string $mem_p_inspire_people
 * @property string $mem_p_favourite_quotation
 * @property integer $mem_p_children
 * @property integer $mem_p_function_id
 * @property integer $mem_p_brache_id
 * @property integer $mem_p_position_id
 * @property integer $mem_p_income_level
 * @property integer $mem_p_educational_level
 * @property integer $mem_p_smoking_habit
 * @property integer $mem_p_pets
 * @property integer $mem_p_glasses
 * @property integer $mem_p_body_hair
 * @property integer $mem_p_facial_hair
 * @property integer $mem_p_piercings
 * @property integer $mem_p_tattoos
 * @property integer $mem_p_drinking_habit
 * @property integer $mem_p_love_status
 * @property string $mem_p_hobbies
 * @property string $mem_p_music
 * @property string $mem_p_movie
 * @property string $mem_p_television
 * @property string $mem_p_book
 * @property string $mem_p_game
 * @property integer $mem_l_attracted_to
 * @property integer $mem_l_agefrom
 * @property integer $mem_l_agetill
 * @property integer $mem_l_country_id
 * @property integer $mem_l_region_id
 * @property integer $mem_l_city_id
 * @property integer $mem_l_gender
 * @property integer $mem_set_contact_only_with_picture
 * @property integer $mem_set_not_searchable
 * @property integer $mem_count_image
 * @property integer $mem_count_video
 * @property integer $mem_count_friend
 * @property integer $mem_count_chat
 * @property integer $mem_count_profile_visit
 * @property integer $mem_count_sms
 * @property integer $mem_count_review
 * @property integer $mem_visibility
 * @property integer $mem_not_message
 * @property integer $mem_not_validate
 * @property integer $mem_type
 * @property integer $mem_checkin_counter
 * @property double $mem_rating_average
 * @property integer $mem_rating_sum
 * @property integer $mem_rating_count
 * @property integer $mem_datetime_confirm
 * @property string $mem_currency
 * @property integer $mem_searchable
 * @property integer $mem_timezone
 * @property string $mem_verify_code
 * @property integer $mem_showintro
 * @property string $mem_ref_code
 * @property string $mem_register_country
 * @property integer $mem_email_activation_sent
 * @property string $mem_facebook_graph
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mem_usr_id', 'mem_city_id', 'mem_country_id', 'mem_region_id', 'mem_freetext_changed', 'mem_status_update_datetime', 'mem_credit', 'mem_loyality_point', 'mem_status', 'mem_account_status', 'mem_datetime', 'mem_updated', 'mem_lastfullaccess', 'mem_mobile_validation_datetime_sent', 'mem_mobile_validated_datetime', 'mem_email_validation_datetime_sent', 'mem_email_validated_datetime', 'mem_subscribe_step', 'mem_lasttime_latlon', 'mem_affiliate_id', 'mem_online_checked', 'mem_moderated', 'mem_p_contact_type', 'mem_p_height', 'mem_p_weight', 'mem_p_starsign', 'mem_p_maritial_status', 'mem_p_eye_color', 'mem_p_hair_color', 'mem_p_hair_cut', 'mem_p_bodytype', 'mem_p_children', 'mem_p_function_id', 'mem_p_brache_id', 'mem_p_position_id', 'mem_p_income_level', 'mem_p_educational_level', 'mem_p_smoking_habit', 'mem_p_pets', 'mem_p_glasses', 'mem_p_body_hair', 'mem_p_facial_hair', 'mem_p_piercings', 'mem_p_tattoos', 'mem_p_drinking_habit', 'mem_p_love_status', 'mem_l_attracted_to', 'mem_l_agefrom', 'mem_l_agetill', 'mem_l_country_id', 'mem_l_region_id', 'mem_l_city_id', 'mem_l_gender', 'mem_set_contact_only_with_picture', 'mem_set_not_searchable', 'mem_count_image', 'mem_count_video', 'mem_count_friend', 'mem_count_chat', 'mem_count_profile_visit', 'mem_count_sms', 'mem_count_review', 'mem_visibility', 'mem_not_message', 'mem_not_validate', 'mem_type', 'mem_checkin_counter', 'mem_rating_sum', 'mem_rating_count', 'mem_datetime_confirm', 'mem_searchable', 'mem_timezone', 'mem_showintro', 'mem_email_activation_sent'], 'integer'],
            [['mem_address', 'mem_gender', 'mem_freetext', 'mem_membership_type', 'mem_mobile_validated', 'mem_email_validated', 'mem_subscribe_from', 'mem_suspicious', 'mem_p_favourite_quotation', 'mem_p_music', 'mem_p_movie', 'mem_p_television', 'mem_p_book', 'mem_p_game', 'mem_facebook_graph'], 'string'],
            [['mem_birthdate', 'mem_last_changed'], 'safe'],
            [['mem_lasttime_lat', 'mem_lasttime_lng', 'mem_rating_average'], 'number'],
            [['mem_fb_id', 'mem_email', 'mem_url', 'mem_online'], 'string', 'max' => 50],
            [['mem_nfc_tag', 'mem_phone', 'mem_mobile', 'mem_nationality', 'mem_bank_account', 'mem_mobile_validation_code', 'mem_email_validation_code', 'mem_ref_code'], 'string', 'max' => 20],
            [['mem_screen_name', 'mem_first_name', 'mem_last_name'], 'string', 'max' => 25],
            [['mem_zipcode', 'mem_register_country'], 'string', 'max' => 10],
            [['mem_city', 'mem_photo', 'mem_banner', 'mem_status_update'], 'string', 'max' => 255],
            [['mem_language', 'mem_currency'], 'string', 'max' => 8],
            [['mem_bank_name', 'mem_session_id'], 'string', 'max' => 32],
            [['mem_bank_iban_swift'], 'string', 'max' => 30],
            [['mem_bank_account_holdersname', 'mem_bank_city'], 'string', 'max' => 40],
            [['mem_ip_subscribe'], 'string', 'max' => 16],
            [['mem_p_inspire_people'], 'string', 'max' => 300],
            [['mem_p_hobbies'], 'string', 'max' => 128],
            [['mem_verify_code'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mem_id' => 'ID',
            'mem_usr_id' => 'Usr ID',
            'mem_fb_id' => 'Fb ID',
            'mem_nfc_tag' => 'Nfc Tag',
            'mem_screen_name' => 'Screen Name',
            'mem_first_name' => 'First Name',
            'mem_last_name' => 'Last Name',
            'mem_address' => 'Address',
            'mem_zipcode' => 'Zipcode',
            'mem_city' => 'City',
            'mem_city_id' => 'City ID',
            'mem_country_id' => 'Country ID',
            'mem_region_id' => 'Region ID',
            'mem_phone' => 'Phone',
            'mem_mobile' => 'Mobile',
            'mem_birthdate' => 'Birthdate',
            'mem_email' => 'Email',
            'mem_gender' => 'Gender',
            'mem_nationality' => 'Nationality',
            'mem_language' => 'Language',
            'mem_url' => 'Url',
            'mem_bank_name' => 'Bank Name',
            'mem_bank_account' => 'Bank Account',
            'mem_bank_iban_swift' => 'Bank Iban Swift',
            'mem_bank_account_holdersname' => 'Bank Account Holdersname',
            'mem_bank_city' => 'Bank City',
            'mem_photo' => 'Photo',
            'mem_banner' => 'Banner',
            'mem_freetext' => 'Freetext',
            'mem_freetext_changed' => 'Freetext Changed',
            'mem_status_update' => 'Status Update',
            'mem_status_update_datetime' => 'Status Update Datetime',
            'mem_credit' => 'Credit',
            'mem_loyality_point' => 'Loyality Point',
            'mem_status' => 'Status',
            'mem_account_status' => 'Account Status',
            'mem_datetime' => 'Datetime',
            'mem_updated' => 'Updated',
            'mem_membership_type' => 'Membership Type',
            'mem_lastfullaccess' => 'Lastfullaccess',
            'mem_mobile_validation_code' => 'Mobile Validation Code',
            'mem_mobile_validation_datetime_sent' => 'Mobile Validation Datetime Sent',
            'mem_mobile_validated' => 'Mobile Validated',
            'mem_mobile_validated_datetime' => 'Mobile Validated Datetime',
            'mem_email_validation_code' => 'Email Validation Code',
            'mem_email_validation_datetime_sent' => 'Email Validation Datetime Sent',
            'mem_email_validated' => 'Email Validated',
            'mem_email_validated_datetime' => 'Email Validated Datetime',
            'mem_subscribe_step' => 'Subscribe Step',
            'mem_ip_subscribe' => 'Ip Subscribe',
            'mem_subscribe_from' => 'Subscribe From',
            'mem_lasttime_lat' => 'Lasttime Lat',
            'mem_lasttime_lng' => 'Lasttime Lng',
            'mem_lasttime_latlon' => 'Lasttime Latlon',
            'mem_affiliate_id' => 'Affiliate ID',
            'mem_session_id' => 'Session ID',
            'mem_online' => 'Online',
            'mem_online_checked' => 'Online Checked',
            'mem_moderated' => 'Moderated',
            'mem_last_changed' => 'Last Changed',
            'mem_suspicious' => 'Suspicious',
            'mem_p_contact_type' => 'P Contact Type',
            'mem_p_height' => 'P Height',
            'mem_p_weight' => 'P Weight',
            'mem_p_starsign' => 'P Starsign',
            'mem_p_maritial_status' => 'P Maritial Status',
            'mem_p_eye_color' => 'P Eye Color',
            'mem_p_hair_color' => 'P Hair Color',
            'mem_p_hair_cut' => 'P Hair Cut',
            'mem_p_bodytype' => 'P Bodytype',
            'mem_p_inspire_people' => 'P Inspire People',
            'mem_p_favourite_quotation' => 'P Favourite Quotation',
            'mem_p_children' => 'P Children',
            'mem_p_function_id' => 'P Function ID',
            'mem_p_brache_id' => 'P Brache ID',
            'mem_p_position_id' => 'P Position ID',
            'mem_p_income_level' => 'P Income Level',
            'mem_p_educational_level' => 'P Educational Level',
            'mem_p_smoking_habit' => 'P Smoking Habit',
            'mem_p_pets' => 'P Pets',
            'mem_p_glasses' => 'P Glasses',
            'mem_p_body_hair' => 'P Body Hair',
            'mem_p_facial_hair' => 'P Facial Hair',
            'mem_p_piercings' => 'P Piercings',
            'mem_p_tattoos' => 'P Tattoos',
            'mem_p_drinking_habit' => 'P Drinking Habit',
            'mem_p_love_status' => 'P Love Status',
            'mem_p_hobbies' => 'P Hobbies',
            'mem_p_music' => 'P Music',
            'mem_p_movie' => 'P Movie',
            'mem_p_television' => 'P Television',
            'mem_p_book' => 'P Book',
            'mem_p_game' => 'P Game',
            'mem_l_attracted_to' => 'L Attracted To',
            'mem_l_agefrom' => 'L Agefrom',
            'mem_l_agetill' => 'L Agetill',
            'mem_l_country_id' => 'L Country ID',
            'mem_l_region_id' => 'L Region ID',
            'mem_l_city_id' => 'L City ID',
            'mem_l_gender' => 'L Gender',
            'mem_set_contact_only_with_picture' => 'Set Contact Only With Picture',
            'mem_set_not_searchable' => 'Set Not Searchable',
            'mem_count_image' => 'Count Image',
            'mem_count_video' => 'Count Video',
            'mem_count_friend' => 'Count Friend',
            'mem_count_chat' => 'Count Chat',
            'mem_count_profile_visit' => 'Count Profile Visit',
            'mem_count_sms' => 'Count Sms',
            'mem_count_review' => 'Count Review',
            'mem_visibility' => 'Visibility',
            'mem_not_message' => 'Not Message',
            'mem_not_validate' => 'Not Validate',
            'mem_type' => 'Type',
            'mem_checkin_counter' => 'Checkin Counter',
            'mem_rating_average' => 'Rating Average',
            'mem_rating_sum' => 'Rating Sum',
            'mem_rating_count' => 'Rating Count',
            'mem_datetime_confirm' => 'Datetime Confirm',
            'mem_currency' => 'Currency',
            'mem_searchable' => 'Searchable',
            'mem_timezone' => 'Timezone',
            'mem_verify_code' => 'Verify Code',
            'mem_showintro' => 'Showintro',
            'mem_ref_code' => 'Ref Code',
            'mem_register_country' => 'Register Country',
            'mem_email_activation_sent' => 'Email Activation Sent',
            'mem_facebook_graph' => 'Facebook Graph',
        ];
    }
}
