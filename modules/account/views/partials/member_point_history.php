<?php use kartik\grid\GridView; ?>

<?= 

GridView::widget([
    'id' => $el_id,
    'options' => [
        'style' => 'font-size: 13px',
    ],
    'layout' => '{items} {summary} {pager}',
    'dataProvider' => $pointAjaxHistoryProvider,
    'pjax' => true,
    'pjaxSettings' => [
        'neverTimeout' => true,
    ],
    'columns' => [
        [
            'label' => 'Merchant',
            'format' => 'html',
            'value' => function($data) {
                if (!empty($data->merchant))
                    return $data->merchant->com_name;
            }
        ],
        [
            'label' => 'Type',
            'format' => 'html',
            'value' => function($data) {
                if (!empty($data->type)) {
                    return ucwords($data->type->lpe_name);
                } else {
                    return 'Snap & Earn';
                }
            }
        ],
        [
            'label' => 'Method',
            'format' => 'html',
            'value' => function($data) {
                return ($data->lph_type == 'C') ? 'Credit' : 'Debit';
            }
        ],
        [
            'label' => 'Amount',
            'format' => 'html',
            'value' => function($data) {
                return $data->lph_amount;
            }
        ],
        [
            'label' => 'Current Point',
            'format' => 'html',
            'value' => function($data) {
                return $data->lph_current_point;
            }
        ],
        [
            'label' => 'Total Point',
            'format' => 'html',
            'value' => function($data) {
                return $data->lph_total_point;
            }
        ],
        /*
        [
            'label' => 'Status',
            'format' => 'html',
            'value' => function($data) {
                return $data['status'] == 1 ? 'Approved' : null;
            }
        ],
        */
        [
            'label' => 'Date',
            'format' => 'html',
            'value' => function($data) {
                return Yii::$app->formatter->asDate($data->lph_datetime);
            },
        ],
    ],
    'tableOptions' => ['class' => 'table table-hover']
]);

?>