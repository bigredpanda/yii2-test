<?php

namespace app\controllers;

use app\models\LoginForm;
use Yii;
use app\models\User;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * Class UserController
 * @package app\controllers
 */
class UserController extends Controller
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'logout' => ['POST']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['update', 'delete', 'create', 'view', 'index'],
                'rules' => [
                    [
                        'actions'       => ['update', 'delete', 'create'],
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => function () {
                            return (Yii::$app->user->identity->type == User::ADMIN);

                        }
                    ],
                    [
                        'actions'       => ['index', 'view'],
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => function () {
                            return (
                                Yii::$app->user->identity->type == User::TEACHER
                                || Yii::$app->user->identity->type == User::ADMIN
                            );
                        }
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $app = Yii::$app;
        $users = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $users,
        ]);

        $dependency = new DbDependency([
            'sql' => 'SELECT MAX(updated_at) FROM user'
        ]);

        $app->db->cache(function () use ($dataProvider) {
            $dataProvider->prepare();
        }, $app->params['cache_expire'], $dependency);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new User();

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $password = $post['User']['password'];
            $model->setPassword($password);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
