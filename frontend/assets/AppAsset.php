<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/user/login.css',
        'css/user/employee.css',
        'css/layout/layout.css',
        'css/layout/font.css',
        'css/component/table.css',
        'css/component/button.css',
        'css/component/label.css',
        'css/component/image.css',
        'css/component/carlendar.css',
        'css/component/modal.css',
        'css/component/form.css',
        'css/component/select.css',
        'css/home/home.css',
        'css/job/job.css',
        'css/client/client.css',
        'css/aditional-extension.css',
        'css/chat/chat.css',
        'css/sales/sales.css',
        'css/chart/chart.css',
        'css/job/summarize.css',
        'css/mms/mms.css',
        'css/kpi/kpi.css'
    ];
    public $js = [
        'js/home/home.js',
        'js/properties/properties.js',
        'js/properties/form.js',
        'js/structure/branch.js',
        'js/structure/position.js',
        'js/structure/section.js',
        'js/structure/team.js',
        'js/job-structure/category.js',
        'js/job-structure/field.js',
        'js/job-structure/job_type.js',
        'js/job-structure/step.js',
        'js/client/client.js',
        'js/employee/employee.js',
        'js/job/job.js',
        'js/job/jobTypeAnalysis.js',
        'js/job/carlendar.js',
        'js/chat/chat.js',
        'js/chart/chart.js',
        'js/mms/mms.js',
        'js/kpi/kpi.js'
    ];
    public $depends = [

        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'rmrevin\yii\fontawesome\CdnProAssetBundle'
    ];
}
