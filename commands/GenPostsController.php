<?php namespace app\commands;

use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;

class GenPostsController extends Controller
{
    /**
     * @param $id_user
     * @param $count
     * @return int
     * @throws Exception
     */
    public function actionIndex($id_user, $count)
    {
        $faker = Factory::create();
        $columns = [
            'message'    ,
            'iduser'     ,
        ];

        $rows = [];
        while ($count > 1) {
            $rows[] = [
                $faker->sentences(10, true),
                $id_user
            ];
            --$count;
        }
        Yii::$app->db->createCommand()->batchInsert('posts', $columns, $rows)->execute();

        return ExitCode::OK;
    }
}
