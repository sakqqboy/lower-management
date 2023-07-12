<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'kt-generator' => [
            'class' => 'frontend\modules\KTGenerator\KTGenerator',
        ],
        'setting' => [
            'class' => 'frontend\modules\setting\setting',
        ],
        'job' => [
            'class' => 'frontend\modules\job\job',
        ],
        'client' => [
            'class' => 'frontend\modules\client\client',
        ],
        'chat' => [
            'class' => 'frontend\modules\chat\chat',
        ],
        'sales' => [
            'class' => 'frontend\modules\sales\sales',
        ],
        'mms' => [
            'class' => 'frontend\modules\mms\mms',
        ],
        'profile' =>  [
            'class' => 'frontend\modules\profile\profile',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'kpi' => [
            'class' => 'frontend\modules\kpi\kpi',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'enableCsrfValidation' => false
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'setting/employee/update-employee/<hash>' => 'setting/employee/update-employee',
                'setting/employee/employee-detail/<hash>' => 'setting/employee/employee-detail',
                'setting/job-structure/search-job-type-result/<hash>' => 'setting/job-structure/search-job-type-result',
                'setting/job-structure/search-result/<hash>' => 'setting/job-structure/search-result',
                'job/detail/job-detail/<hash>' => 'job/detail/job-detail',
                'job/detail/edit-complete-job/<hash>' => 'job/detail/edit-complete-job',
                'job/detail/next-target/<hash>' => 'job/detail/next-target',
                'job/detail/complete-job/<hash>' => 'job/detail/complete-job',
                'job/detail/show-result/<hash>' => 'job/detail/show-result',
                'job/detail/show-result2/<hash>' => 'job/detail/show-result2',
                'job/detail/export-job/<hash>' => 'job/detail/export-job',
                'job/job-summarize/list-job-type/<hash>' => 'job/job-summarize/list-job-type',
                'job/job-summarize/show-result/<hash>' => 'job/job-summarize/show-result',
                'job/job-summarize/branch-summary/<hash>' => 'job/job-summarize/branch-summary',
                'job/carlendar/show-result/<hash>' => 'job/carlendar/show-result',
                'job/carlendar/job-date/<hash>' => 'job/carlendar/job-date',
                'job/clone/create/<hash>' => 'job/clone/create',
                'client/default/client-detail/<hash>' => 'client/default/client-detail',
                'client/default/update-client/<hash>' => 'client/default/update-client',
                'client/default/search-job/<hash>' => 'client/default/search-job',
                'mms/default/show-graph/<hash>' => 'mms/default/show-graph',
                'mms/default/edit-graph/<hash>' => 'mms/default/edit-graph',
                'mms/analysis/filter/<hash>' => 'mms/analysis/filter',
                'mms/analysis/filter-yearly/<hash>' => 'mms/analysis/filter-yearly',
                'mms/analysis/detail1-monthly/<hash>' => 'mms/analysis/detail1-monthly',
                'mms/analysis/detail1-monthly-day/<hash>' => 'mms/analysis/detail1-monthly-day',
                'mms/analysis/detail-monthly-on-process/<hash>' => 'mms/analysis/detail-monthly-on-process',
                'mms/analysis/detail-yearly/<hash>' => 'mms/analysis/detail-yearly',
                'mms/analysis/detail-yearly-day/<hash>' => 'mms/analysis/detail-yearly-day',
                'mms/analysis/detail-yearly-onprocess/<hash>' => 'mms/analysis/detail-yearly-onprocess',
                'mms/analysis/filter-job-type/<hash>' => 'mms/analysis/filter-job-type',
                'mms/analysis/detail-job-type-step/<hash>' => 'mms/analysis/detail-job-type-step',
                'sales/default/show-carlendar/<hash>' => 'sales/default/show-carlendar',
                'kpi/default/create-kpi/<hash>' => 'kpi/default/create-kpi',
                'kpi/default/kgi-detail/<hash>' => 'kpi/default/kgi-detail',
                'kpi/default/update-kgi/<hash>' => 'kpi/default/update-kgi',
                'kpi/update/personal-update/<hash>' => 'kpi/update/personal-update',
                'kpi/default/update-kgi-group/<hash>' => 'kpi/default/update-kgi-group',
                'kpi/update/show-carlendar/<hash>' => 'kpi/update/show-carlendar',
                'kpi/employee-kpi/search-result/<hash>' => 'kpi/employee-kpi/search-result',
                'kpi/employee-kpi/employee-kpi/<hash>' => 'kpi/employee-kpi/employee-kpi',
                'kpi/kpi/kpi-progress/<hash>' => 'kpi/kpi/kpi-progress'

            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => 'AIzaSyDpQTxS6XVvAG0RbsoTu-WflOEqYO016us', // ใส่ API key ตรงนี้ครับ
                        'language' => 'en',
                        'version' => '3.1.18'
                    ]
                ],
                'kartik\form\ActiveFormAsset' => [
                    //'bsDependencyEnabled' => false,
                    //'bsDependencyEnabled' => false,
                    // 'bsDependencyEnabled' => false, // do not load bootstrap assets for a specific asset bundle
                    'bsVersion' => '4.*'
                ],

            ],
            //            'forceCopy' => TRUE
        ],

    ],
    'params' => $params,
];
