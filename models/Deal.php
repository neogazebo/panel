<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_deal".
 *
 * @property integer $del_id
 * @property integer $del_type
 * @property integer $del_com_id
 * @property string $del_cob_id
 * @property string $del_title
 * @property string $del_description
 * @property string $del_price
 * @property string $del_fineprint
 * @property string $del_photo
 * @property integer $del_start
 * @property integer $del_end
 * @property integer $del_det_id
 * @property integer $del_target_age_start
 * @property integer $del_target_age_end
 * @property string $del_target_gender
 * @property integer $del_target_cit_id
 * @property integer $del_target_reg_id
 * @property integer $del_target_cny_id
 * @property string $del_target_work_position_id
 * @property string $del_target_marital_status
 * @property string $del_target_interest
 * @property string $del_target_education
 * @property integer $del_target_range
 * @property integer $del_target_audience
 * @property integer $del_total_voucher
 * @property integer $del_lbs_voucher
 * @property integer $del_total_inside
 * @property integer $del_total_outside
 * @property integer $del_cost_per_voucher
 * @property integer $del_cost_total
 * @property string $col1
 * @property string $col2
 * @property string $col3
 * @property string $col4
 * @property string $col5
 * @property integer $del_open
 * @property integer $del_scope
 * @property integer $del_status
 * @property integer $del_datetime
 * @property integer $del_updated
 * @property integer $del_rating_sum
 * @property integer $del_rating_count
 * @property double $del_rating_average
 * @property string $del_tag
 * @property string $del_close
 * @property string $del_cost_type
 * @property integer $del_outstanding_credit
 * @property integer $del_redeem_option
 * @property integer $del_monday
 * @property string $del_monday_start
 * @property string $del_monday_end
 * @property integer $del_tuesday
 * @property string $del_tuesday_start
 * @property string $del_tuesday_end
 * @property integer $del_wednesday
 * @property string $del_wednesday_start
 * @property string $del_wednesday_end
 * @property integer $del_thursday
 * @property string $del_thursday_start
 * @property string $del_thursday_end
 * @property integer $del_friday
 * @property string $del_friday_start
 * @property string $del_friday_end
 * @property integer $del_saturday
 * @property string $del_saturday_start
 * @property string $del_saturday_end
 * @property integer $del_sunday
 * @property string $del_sunday_start
 * @property string $del_sunday_end
 * @property integer $del_limit_one_per_customer
 * @property integer $del_first_time_customers_only
 * @property integer $del_requires_reservation
 * @property integer $del_mobile_redemptions_only
 * @property integer $del_approval
 * @property integer $del_approval_datetime
 * @property integer $del_approval_admin_id
 * @property integer $del_draft_status
 * @property integer $del_beacon_link_status
 * @property integer $del_target_audience_status
 * @property integer $del_beacon_opening_hours
 * @property integer $del_exclusive
 * @property integer $del_bmn_id
 * @property integer $del_mall_app_show
 * @property integer $del_beacon_monday_status
 * @property string $del_beacon_monday_start
 * @property string $del_beacon_monday_end
 * @property integer $del_beacon_tuesday_status
 * @property string $del_beacon_tuesday_start
 * @property string $del_beacon_tuesday_end
 * @property integer $del_beacon_wednesday_status
 * @property string $del_beacon_wednesday_start
 * @property string $del_beacon_wednesday_end
 * @property integer $del_beacon_thursday_status
 * @property string $del_beacon_thursday_start
 * @property string $del_beacon_thursday_end
 * @property integer $del_beacon_friday_status
 * @property string $del_beacon_friday_start
 * @property string $del_beacon_friday_end
 * @property integer $del_beacon_saturday_status
 * @property string $del_beacon_saturday_start
 * @property string $del_beacon_saturday_end
 * @property integer $del_beacon_sunday_status
 * @property string $del_beacon_sunday_start
 * @property string $del_beacon_sunday_end
 * @property integer $del_mal_approval
 * @property integer $del_mal_approval_admin_id
 * @property integer $del_mal_approval_datetime
 * @property integer $del_loyalty
 * @property integer $del_slider
 */
class Deal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_deal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['del_type', 'del_com_id', 'del_datetime'], 'required'],
            [['del_type', 'del_com_id', 'del_start', 'del_end', 'del_det_id', 'del_target_age_start', 'del_target_age_end', 'del_target_cit_id', 'del_target_reg_id', 'del_target_cny_id', 'del_target_range', 'del_target_audience', 'del_total_voucher', 'del_lbs_voucher', 'del_total_inside', 'del_total_outside', 'del_cost_per_voucher', 'del_cost_total', 'del_open', 'del_scope', 'del_status', 'del_datetime', 'del_updated', 'del_rating_sum', 'del_rating_count', 'del_outstanding_credit', 'del_redeem_option', 'del_monday', 'del_tuesday', 'del_wednesday', 'del_thursday', 'del_friday', 'del_saturday', 'del_sunday', 'del_limit_one_per_customer', 'del_first_time_customers_only', 'del_requires_reservation', 'del_mobile_redemptions_only', 'del_approval', 'del_approval_datetime', 'del_approval_admin_id', 'del_draft_status', 'del_beacon_link_status', 'del_target_audience_status', 'del_beacon_opening_hours', 'del_exclusive', 'del_bmn_id', 'del_mall_app_show', 'del_beacon_monday_status', 'del_beacon_tuesday_status', 'del_beacon_wednesday_status', 'del_beacon_thursday_status', 'del_beacon_friday_status', 'del_beacon_saturday_status', 'del_beacon_sunday_status', 'del_mal_approval', 'del_mal_approval_admin_id', 'del_mal_approval_datetime', 'del_loyalty', 'del_slider'], 'integer'],
            [['del_description', 'del_fineprint', 'del_close'], 'string'],
            [['del_price', 'del_rating_average'], 'number'],
            [['del_monday_start', 'del_monday_end', 'del_tuesday_start', 'del_tuesday_end', 'del_wednesday_start', 'del_wednesday_end', 'del_thursday_start', 'del_thursday_end', 'del_friday_start', 'del_friday_end', 'del_saturday_start', 'del_saturday_end', 'del_sunday_start', 'del_sunday_end', 'del_beacon_monday_start', 'del_beacon_monday_end', 'del_beacon_tuesday_start', 'del_beacon_tuesday_end', 'del_beacon_wednesday_start', 'del_beacon_wednesday_end', 'del_beacon_thursday_start', 'del_beacon_thursday_end', 'del_beacon_friday_start', 'del_beacon_friday_end', 'del_beacon_saturday_start', 'del_beacon_saturday_end', 'del_beacon_sunday_start', 'del_beacon_sunday_end'], 'safe'],
            [['del_cob_id', 'del_title', 'del_target_work_position_id', 'col1', 'col2', 'col3', 'col4', 'col5'], 'string', 'max' => 300],
            [['del_photo'], 'string', 'max' => 150],
            [['del_target_gender', 'del_target_interest', 'del_cost_type'], 'string', 'max' => 1],
            [['del_target_marital_status'], 'string', 'max' => 5],
            [['del_target_education'], 'string', 'max' => 4],
            [['del_tag'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'del_id' => 'ID',
            'del_type' => 'Type',
            'del_com_id' => 'Merchant',
            'del_cob_id' => 'Cob ID',
            'del_title' => 'Title',
            'del_description' => 'Description',
            'del_price' => 'Price',
            'del_fineprint' => 'Fineprint',
            'del_photo' => 'Photo',
            'del_start' => 'Start',
            'del_end' => 'End',
            'del_det_id' => 'Type',
            'del_target_age_start' => 'Target Age Start',
            'del_target_age_end' => 'Target Age End',
            'del_target_gender' => 'Target Gender',
            'del_target_cit_id' => 'Target Cit ID',
            'del_target_reg_id' => 'Target Reg ID',
            'del_target_cny_id' => 'Target Cny ID',
            'del_target_work_position_id' => 'Target Work Position ID',
            'del_target_marital_status' => 'Target Marital Status',
            'del_target_interest' => 'Target Interest',
            'del_target_education' => 'Target Education',
            'del_target_range' => 'Target Range',
            'del_target_audience' => 'Target Audience',
            'del_total_voucher' => 'Total Voucher',
            'del_lbs_voucher' => 'Lbs Voucher',
            'del_total_inside' => 'Total Inside',
            'del_total_outside' => 'Total Outside',
            'del_cost_per_voucher' => 'Cost Per Voucher',
            'del_cost_total' => 'Cost Total',
            'col1' => 'Col1',
            'col2' => 'Col2',
            'col3' => 'Col3',
            'col4' => 'Col4',
            'col5' => 'Col5',
            'del_open' => 'Open',
            'del_scope' => 'Scope',
            'del_status' => 'Status',
            'del_datetime' => 'Datetime',
            'del_updated' => 'Updated',
            'del_rating_sum' => 'Rating Sum',
            'del_rating_count' => 'Rating Count',
            'del_rating_average' => 'Rating Average',
            'del_tag' => 'Tag',
            'del_close' => 'Close',
            'del_cost_type' => 'Cost Type',
            'del_outstanding_credit' => 'Outstanding Credit',
            'del_redeem_option' => 'Redeem Option',
            'del_monday' => 'Monday',
            'del_monday_start' => 'Monday Start',
            'del_monday_end' => 'Monday End',
            'del_tuesday' => 'Tuesday',
            'del_tuesday_start' => 'Tuesday Start',
            'del_tuesday_end' => 'Tuesday End',
            'del_wednesday' => 'Wednesday',
            'del_wednesday_start' => 'Wednesday Start',
            'del_wednesday_end' => 'Wednesday End',
            'del_thursday' => 'Thursday',
            'del_thursday_start' => 'Thursday Start',
            'del_thursday_end' => 'Thursday End',
            'del_friday' => 'Friday',
            'del_friday_start' => 'Friday Start',
            'del_friday_end' => 'Friday End',
            'del_saturday' => 'Saturday',
            'del_saturday_start' => 'Saturday Start',
            'del_saturday_end' => 'Saturday End',
            'del_sunday' => 'Sunday',
            'del_sunday_start' => 'Sunday Start',
            'del_sunday_end' => 'Sunday End',
            'del_limit_one_per_customer' => 'Limit One Per Customer',
            'del_first_time_customers_only' => 'First Time Customers Only',
            'del_requires_reservation' => 'Requires Reservation',
            'del_mobile_redemptions_only' => 'Mobile Redemptions Only',
            'del_approval' => 'Approval',
            'del_approval_datetime' => 'Approval Datetime',
            'del_approval_admin_id' => 'Approval Admin ID',
            'del_draft_status' => 'Draft Status',
            'del_beacon_link_status' => 'Beacon Link Status',
            'del_target_audience_status' => 'Target Audience Status',
            'del_beacon_opening_hours' => 'Beacon Opening Hours',
            'del_exclusive' => 'Exclusive',
            'del_bmn_id' => 'Bmn ID',
            'del_mall_app_show' => 'Mall App Show',
            'del_beacon_monday_status' => 'Beacon Monday Status',
            'del_beacon_monday_start' => 'Beacon Monday Start',
            'del_beacon_monday_end' => 'Beacon Monday End',
            'del_beacon_tuesday_status' => 'Beacon Tuesday Status',
            'del_beacon_tuesday_start' => 'Beacon Tuesday Start',
            'del_beacon_tuesday_end' => 'Beacon Tuesday End',
            'del_beacon_wednesday_status' => 'Beacon Wednesday Status',
            'del_beacon_wednesday_start' => 'Beacon Wednesday Start',
            'del_beacon_wednesday_end' => 'Beacon Wednesday End',
            'del_beacon_thursday_status' => 'Beacon Thursday Status',
            'del_beacon_thursday_start' => 'Beacon Thursday Start',
            'del_beacon_thursday_end' => 'Beacon Thursday End',
            'del_beacon_friday_status' => 'Beacon Friday Status',
            'del_beacon_friday_start' => 'Beacon Friday Start',
            'del_beacon_friday_end' => 'Beacon Friday End',
            'del_beacon_saturday_status' => 'Beacon Saturday Status',
            'del_beacon_saturday_start' => 'Beacon Saturday Start',
            'del_beacon_saturday_end' => 'Beacon Saturday End',
            'del_beacon_sunday_status' => 'Beacon Sunday Status',
            'del_beacon_sunday_start' => 'Beacon Sunday Start',
            'del_beacon_sunday_end' => 'Beacon Sunday End',
            'del_mal_approval' => 'Mal Approval',
            'del_mal_approval_admin_id' => 'Mal Approval Admin ID',
            'del_mal_approval_datetime' => 'Mal Approval Datetime',
            'del_loyalty' => 'Loyalty',
            'del_slider' => 'Slider',
        ];
    }
}
