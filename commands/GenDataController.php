<?php
namespace app\commands;

use Faker\Factory;
use Faker\Generator;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class GenDataController extends Controller
{
    /**
     * @param $count
     * @return int
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */
    public function actionIndex($count, $step)
    {
        $faker = Factory::create();
//        $step = 500;
        do {
            $step = $step <= $count ? $step : $count;
            $rows = [];
            foreach ($this->genRecord($faker, $step) as $row) {
                $rows[] = $row;
            }

            Yii::$app->db->createCommand()->batchInsert('user', [
                'email'        ,
                'password'     ,
                'auth_key'     ,
                'surname'      ,
                'first_name'   ,
                'age'          ,
                'sex'          ,
                'interests'    ,
                'city'         ,
            ], $rows)->execute();
            $count -= $step;
            echo "count: $step\n";
        } while($count >= $step);

        return ExitCode::OK;
    }

    /**
     * @return int
     * @throws \yii\db\Exception
     */
    public function actionClear()
    {
        Yii::$app->db->createCommand()->truncateTable('user')->execute();
        return ExitCode::OK;
    }

    /**
     * @param Generator $faker
     * @param $count
     * @return \Generator
     * @throws \yii\base\Exception
     */
    protected function genRecord(Generator $faker, $count)
    {
        while ($count !== 0) {
            $rows = [
                $faker->email,
                Yii::$app->getSecurity()->generatePasswordHash(1),
                \Yii::$app->getSecurity()->generateRandomString(10),
                $faker->name,
                $faker->firstName,
                $faker->numberBetween(0, 100),
                (int)$faker->boolean,
                $faker->sentence(),
                $faker->city,
            ];

            --$count;
            yield $rows;
        }
    }
}
