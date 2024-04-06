<?php

namespace Database\Seeders;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = range(4, 10);

         Task::factory()
        ->count(10)
        ->make([
            'status_id' => 1,
        ])
        ->each(function ($task) use ($userIds) {
            $task->assignee_id = Arr::random($userIds);
            $task->due_date = Carbon::now()->addDays(rand(1, 90))->startOfDay();
            $task->save();
        });



    }



}
