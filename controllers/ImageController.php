<?php

namespace app\controllers;

use Yii;
use app\models\Image;
use app\models\ImageComment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'gallery', 'view'], 'roles' => ['?', '@']],
                    ['allow' => true, 'actions' => ['upload'], 'roles' => ['@']],
                    // можно было настроить RBAC, но здесь с использованием matchCallback получается проще и быстрее
                    ['allow' => true, 'actions' => ['delete'], 'roles' => ['@'], 'matchCallback' => [$this, 'isOwner']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->actionGallery();
    }
    
    public function actionGallery()
    {
        $query = new \yii\db\Query();
        
        // в дальнейшем для ускорения работы можно вынести средний рейтинг
        // в отдельное поле таблицы image и обновлять при добавлении комментариев
        $query
            ->select(['image.id', 'image.user_id', 'image.path', 'IFNULL(AVG(image_comment.rating), 0) AS avg_rating'])
            ->from('image')
            ->join('LEFT JOIN', 'image_comment', 'image_comment.image_id = image.id')
            ->groupBy('image.id')
            ->orderBy('avg_rating DESC, image.id');

        $columnCount = 6;
        $rowCount = 2;
        $pagination = new Pagination([
            'defaultPageSize' => $columnCount * $rowCount,
            'totalCount' => Image::find()->count(),
        ]);
        
        $images = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('gallery', [
            'columnCount' => $columnCount,
            'rowCount' => $rowCount,
            'images' => $images,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Displays a single Image model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $commentModel = new ImageComment();

        $commentModel->image_id = $id;
        if ($commentModel->load(Yii::$app->request->post()) && $commentModel->save()) {
            return $this->refresh();
        }
        
        return $this->render('view', [
            'model' => $model,
            'commentModel' => $commentModel,
        ]);
    }

    /**
     * Creates a new Image model.
     * @return mixed
     */
    public function actionUpload()
    {
        $model = new Image();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        foreach($model->imageComments as $comment)
        {
            $comment->delete();
        }
        $model->delete();

        return $this->redirect(['index']);
    }
    
    
    /**
     * Функция проверки, является ли пользователь владельцем модели
     * используется в правилах доступа
     */
    public function isOwner($rule, $action)
    {
        $get = \Yii::$app->request->get();
        $model = $this->findModel($get['id']);
        if(\Yii::$app->user->identity->id == $model->user_id)
        {
            return true;
        }
        
        return false;
    }
    

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
