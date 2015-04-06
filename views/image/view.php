<?php

use yii\helpers\Html;
use app\models\Image;
use yii\widgets\Pjax;

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
                    Рейтинг: <?= $model->getAvgRating() ?>
                </div>
                <div class="author">
                    Загрузил: <?= Html::encode($model->user->name) ?>
                </div>
                <div class="clearfix"></div>
            </div>

            <?php if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->id == $model->user_id) { ?>
            
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
            
            <?php if ($model->description) { ?>
                <div class="description">
                    <?= Html::encode($model->description) ?>
                </div>
            <?php } ?>
        </div>
    </div>

</div>


<?php Pjax::begin(); ?>
<?php if (count($model->imageComments) > 0) { ?>

<div class="row">
    <div class="col-md-12">
        <h4>Комментарии:</h4>
        
        <?php
            $dataProvider = new yii\data\ActiveDataProvider([
                'query' => $model->getImageComments(),
                'pagination' => array('pageSize' => 20),
            ]);
            echo yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_comment',
                'layout' => "{summary}\n<div class=\"image_comment_container\">{items}</div>\n{pager}"
            ]);
        ?>
        
        <?php
            echo Html::button('Добавить комментарий', [
                'id' => 'add_comment',
                'class' => 'btn btn-success',
            ]);
        ?>
    </div>
</div>
    
<?php } ?>
<?php Pjax::end(); ?>
