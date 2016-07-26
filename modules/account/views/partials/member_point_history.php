<?

use kartik\grid\GridView;

echo GridView::widget([
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
                return $data['merchant'];
            }
        ],
        [
            'label' => 'Type',
            'format' => 'html',
            'value' => function($data) {
                return $data['type'] ? ucwords($data['type']) : null;
            }
        ],
        [
            'label' => 'Point',
            'format' => 'html',
            'value' => function($data) {
                return $data['point'];
            }
        ],
        [
            'label' => 'Method',
            'format' => 'html',
            'value' => function($data) {
                return ucwords($data['method']);
            }
        ],
        [
            'label' => 'Current Point',
            'format' => 'html',
            'value' => function($data) {
                return $data['current_point'];
            }
        ],
        [
            'label' => 'Total Point',
            'format' => 'html',
            'value' => function($data) {
                return $data['total_point'];
            }
        ],
        [
            'label' => 'Status',
            'format' => 'html',
            'value' => function($data) {
                return $data['status'] == 1 ? 'Approved' : null;
            }
        ],
        [
            'label' => 'Date',
            'format' => 'html',
            'value' => function($data) {
                return $data['created_date'];
            },
        ],
    ],
    'tableOptions' => ['class' => 'table table-hover']
]);

?>