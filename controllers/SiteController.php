<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use app\models\Todo;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'toggle-todo' => ['post'],
                    'update-todo' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (\yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $todo = new Todo();

        if ($todo->load(\yii::$app->request->post())) {
            $todo->user_id = \Yii::$app->user->id;
            $todo->status = Todo::STATUS_PENDING;
        

            if ($todo->save()) {
                \Yii::$app->session->setFlash('success', 'Todo Created.');
                return $this->refresh();
            }

            \Yii::$app->session->setFlash('error', 'Unable to create Todo.');
        }    

        $todos = Todo::find()
            ->where(['user_id' => \Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        
        return $this->render('index', [
            'model' => $todo,
            'todos' => $todos,
        ]);
    }

    public function actionToggleTodo($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
    
        $todo = Todo::find()
            ->where(['id' => (int)$id, 'user_id' => Yii::$app->user->id])
            ->one();
    
        if (!$todo) {
            Yii::$app->session->setFlash('error', 'Todo not found.');
            return $this->redirect(['site/index']);
        }
    
        if ((int)$todo->status === Todo::STATUS_PENDING) {
            $todo->status = Todo::STATUS_DONE;
            if ($todo->save(false)) {
                Yii::$app->session->setFlash('success', 'Todo marked as done.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update status.');
            }
        } else {
            Yii::$app->session->setFlash('info', 'Todo is already done.');
        }
    
        return $this->redirect(['site/index']);
    }

    public function actionUpdateTodo($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
    
        $todo = Todo::find()
            ->where(['id' => (int)$id, 'user_id' => Yii::$app->user->id])
            ->one();
    
        if (!$todo) {
            Yii::$app->session->setFlash('error', 'Todo not found.');
            return $this->redirect(['site/index']);
        }
    
        if ($todo->load(Yii::$app->request->post()) && $todo->save()) {
            Yii::$app->session->setFlash('success', 'Todo updated.');
        } else {
            Yii::$app->session->setFlash('error', 'Unable to update todo.');
        }
    
        return $this->redirect(['site/index']);
    }

    
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\models\SignupForm();

        if ($model->load(Yii::$app->request->post()) && ($user = $model->signup())) {
            Yii::$app->user->login($user);
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);

    }

    /**
     * Login action.
     *
     * @return Response|string
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

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
