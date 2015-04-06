<?php

use yii\helpers\Html;

$comment = $model;

?>
    <table class="image_comment">
    <tr>
        <td class="rating">
            Рейтинг: <?= $comment->rating ?>
        </td>
        <td class="user_info">
            <?= Html::encode($comment->user != null ? $comment->user->name : $comment->user_name) ?>
            (<?= Html::encode($comment->user != null ? $comment->user->email : $comment->user_email) ?>)
        </td>
        <td class="comment_date">
            <?= Html::encode(date('Y-m-d H:i', strtotime($comment->created_at))) ?>
        </td>
    </tr>
    <tr>
        <td class="comment_text" colspan="3">
            <?= ($comment->text ? Html::encode($comment->text) : '&nbsp;') ?>
        </td>
    </tr>
    </table>