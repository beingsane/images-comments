<?php

use yii\helpers\Html;
use app\models\Image;

/* @var $this yii\web\View */
/* @var $model app\models\Image */

$this->title = 'Просмотр изображения';
$this->params['breadcrumbs'][] = ['label' => 'Галерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">

    <div class="col-md-12">
        <div class="image_detail_view">
            <div class="image">
                <a href="<?= Image::$galleryURL .$model->path ?>" target="_blank">
                    <img src="<?= Image::$galleryURL .$model->path ?>"/>
                </a>
            </div>
            
            <div>
                <div class="rating">
                    Рейтинг: <?= number_format($model->getAvgRating(), 1) ?>
                </div>
                <div class="author">
                    Загрузил: <?= Html::encode($model->user->name) ?>
                </div>
                <div class="clearfix"></div>
            </div>

            <?php if(!\Yii::$app->user->isGuest && \Yii::$app->user->identity->id == $model->user_id) { ?>
            
                <div class="text-right">
                    <?php
                        echo Html::a('Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Удалить изображение?',
                                'method' => 'post',
                            ],
                        ]);
                    ?>
                </div>
                
            <?php } ?>
            
            <?php if($model->description) { ?>
                <div class="description">
                    <?= Html::encode($model->description) ?>
                </div>
            <?php } ?>
        </div>
    </div>

</div>
