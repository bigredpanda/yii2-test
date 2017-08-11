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
                        'actions' => ['update', 'delete', 'create'],
                        'allow'   => true,
                        'roles'   => ['admin']
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow'   => true,
                        'roles'   => ['teacher']
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
        $dependency->reusable = true;

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
    public function actionCreate()
    {
        $model = new User();
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            $password = $post['User']['password'];
            $model->setPassword($password);

            if ($model->save()) {
                $model->setRole($post['role']);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $this->getRoles()
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model->setRole($post['role']);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => $this->getRoles()
            ]);
        }
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            $authManager = Yii::$app->authManager;
            $authManager->revokeAll($id);
        };

        return $this->redirect(['index']);
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
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * @return array
     */
    private function getRoles()
    {
        $roles = array_map(function ($r) {
            return $r->name;
        }, Yii::$app->authManager->getRoles());

        return $roles;
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
