<?php
namespace app\controllers;

use app\models\{LoginForm, RegistrationForm};
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
    public $layout = 'main_auth.php';

    /**
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('index', compact('model'));
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->singUp()) {
            return $this->goHome();
        }

        return $this->render('registration', [
            'model' => $model,
        ]);
    }
}
