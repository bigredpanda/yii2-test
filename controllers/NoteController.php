<?php

namespace app\controllers;

use Yii;
use app\models\Note;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NoteController implements the CRUD actions for Note model.
 */
class NoteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@']
                    ]
                ],
            ],

        ];
    }

    /**
     * Lists all Note models.
     * @return mixed
     */
    public function actionIndex()
    {
        $app = Yii::$app;
        $user = $app->getUser();

        if ($user->can('admin')) {
            $notes = Note::find();
        } else {
            $notes = Note::find()->where(['author' => $user->getId()]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $notes,

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Note model.
     * @param int $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $noteModel = $this->findModel($id);
        $user = Yii::$app->user;

        if ($user->can('viewNote', ['note' => $noteModel])) {
            return $this->render('view', [
                'model' => $noteModel,
            ]);
        } else {
            throw new ForbiddenHttpException('You do not have permission to view this note');
        }

    }

    /**
     * Creates a new Note model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $noteModel = new Note();
        $user = Yii::$app->user;

        if ($user->can('createNote', ['note' => $noteModel])) {
            if ($noteModel->load(Yii::$app->request->post()) && $noteModel->save()) {
                return $this->redirect(['view', 'id' => $noteModel->id]);
            } else {
                return $this->render('create', [
                    'model' => $noteModel,
                ]);
            }
        } else {
            throw new ForbiddenHttpException('You do not have permission to view this note');
        }
    }

    /**
     * Updates an existing Note model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $noteModel = $this->findModel($id);
        $user = Yii::$app->user;

        if ($user->can('editAndDeleteNote', ['note' => $noteModel])) {
            if ($noteModel->load(Yii::$app->request->post()) && $noteModel->save()) {
                return $this->redirect(['view', 'id' => $noteModel->id]);
            } else {
                return $this->render('update', [
                    'model' => $noteModel,
                ]);
            }
        } else {
            throw new ForbiddenHttpException('You do not have permission to view this note');
        }
    }

    /**
     * Deletes an existing Note model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $noteModel = $this->findModel($id);
        $user = Yii::$app->user;

        if ($user->can('editAndDeleteNote', ['note' => $noteModel])) {
            $noteModel->delete();
        } else {
            throw new ForbiddenHttpException('You do not have permission to view this note');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Note model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Note the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Note::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
