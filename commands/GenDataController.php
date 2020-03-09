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
     * @param $step
     * @return int
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionIndex($count, $step)
    {
        $faker = Factory::create();
        $columns = [
            'email'        ,
            'password'     ,
            'surname'      ,
            'first_name'   ,
            'age'          ,
            'sex'          ,
            'interests'    ,
            'city'         ,
        ];
        do {
            $step = $step <= $count ? $step : $count;
            $rows = [];
            foreach ($this->genRecord($faker, $step) as $row) {
                $rows[] = $row;
            }

            Yii::$app->db->createCommand()->batchInsert('user', $columns, $rows)->execute();
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
            $row = [
                'test@test.com',
                '$2y$13$VhkbB/erFT1Y8jULlqXDG.93BVrjhYLpCeOZoDBx0hM7HDvInMScO',
                $faker->lastName,
                $faker->firstName,
                $faker->numberBetween(15, 100),
                (int)$faker->boolean,
                'Interests',
                $faker->city,
            ];

            --$count;
            yield $row;
        }
    }
}
