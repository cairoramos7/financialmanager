<?php


use Faker\Provider\Base;
use Phinx\Seed\AbstractSeed;

class BillPaysSeeder extends AbstractSeed
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $categories;

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        /** @var \CROFin\Application $app */
        require __DIR__ . '/../bootstrap.php';
        $this->categories = \CROFin\Models\CategoryCost::all();

        $faker = \Faker\Factory::create('pt_BR');
        $faker->addProvider($this);

        $billPays = $this->table('bill_pays');
        $data = [];

        foreach(range(1,  20) as $item) {
            $userId = rand(1, 4);

            $data[] = [
                'date_launch' => $faker->date(),
                'name' => $faker->word,
                'user_id' => $userId,
                'category_cost_id' => $faker->categoryId($userId),
                'value' => $faker->randomFloat(2, 10, 1000),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $billPays->insert($data)->save();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function categoryId($userId)
    {
        $categories = $this->categories->where('user_id', $userId);
        $categories = $categories->pluck('id');
        return Base::randomElement($categories->toArray());
    }
}
