<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap\Dropdown;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">

    <?php

    $pic_nav = \frontend\models\Page::find()->where(['user_id' => Yii::$app->user->identity->id, 'status' => 1])->asArray()->all();
    $pic_url = (empty($pic_nav)) ? 'uploads/def.jpg' : $pic_nav[0]['pic_name'];

    NavBar::begin([
        'brandLabel' => 'Social network',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
//        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
//        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {

        $menuItems = [
            ['label' => '<img  src="/'.$pic_url.'"  style="cursor: pointer; width: 27px; height: 25px;display: inline;margin: 0px 5px 0px 0px;border-radius: 2px;">'. Yii::$app->user->identity->first_name .'',
                'url' => ['/site/index', 'id'=>Yii::$app->user->identity->id],
                'options'=>['id'=>Yii::$app->user->identity->id]],
            ['label' => 'Massage','url' => ['/site/msglist']],
            ['label' => '<img src="/uploads/req.jpg"   style="cursor: pointer; width: 27px; height: 25px;display: inline;margin: 0px 5px 0px 0px;border-radius: 2px; color: #0a0a0a">',
                //'url' => ['#'],
                'linkOptions' => ['title' => 'Friend request','class'=>'fr_req','data-html'=>'true','data-toggle' => 'popover','data-placement' => 'bottom','data-content' => 'ContentContentContentContentContentvv',]
            ],
            ['label' => 'My pictures','url' => ['/site/upload']],
            ['label' => 'Search','url' => ['/user/index']],

            ['label' => 'Settings',
                //'url' => ['/site/index'],
                'items' => [
                    //['label' => 'My pictures', 'url' => '/site/upload'],
                    ['label' => 'Change password', 'url' => '/settings/changepassword'],
                    ['label' => 'Change settings', 'url' => '/settings/'],
                ],
            ],
            [
                'label' => 'Logout',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ],


        ];

    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,

    ]);
    NavBar::end();
    ?>


    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Social network made by Sargis Khachatryan <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
