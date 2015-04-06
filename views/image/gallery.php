<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\models\Image;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Галерея';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">

    <div class="row">
        <div class="col-md-12">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <?php
        if(count($images) > 0)
        {
            ?>
            <div class="row">
            
                <?php foreach($images as $rowNum => $image) { ?>
                
                    <?php if($rowNum % $columnCount == 0) { ?>
                        </div><div class="row">
                    <?php } ?>
                    
                    
                    <div class="col-md-<?= (int)(12 / $columnCount) ?>">
                        <div class="gallery_image">
                            <div class="text-center">
                                <a href="/image/view/?id=<?= $image['id'] ?>">
                                    <img src="<?= Image::$thumbnailURL .$image['path'] ?>"/>
                                </a>
                            </div>
                            <div class="rating">Рейтинг: <?= number_format($image['avg_rating'], 1) ?></div>
                        </div>
                    </div>
                <?php } ?>
            
            </div>
            
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
            
            <?php
        }
    ?>
</div>
