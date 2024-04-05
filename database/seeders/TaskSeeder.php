<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $parentTasks = Task::factory()
            ->count(10)
            ->create([
                'status_id' => 1,
                'due_date' => null,
            ]);

            $fiveTasks = $parentTasks->slice(-5);
        foreach ($fiveTasks as $parentTask) {
            $parentTask->children()->saveMany(
                Task::factory()
                    ->count(3)
                    ->make([
                        'status_id' => 1,
                        'due_date' => null,
                    ])
            );
        }
    }
}
