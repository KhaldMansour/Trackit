<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $users = User::inRandomOrder()->limit(5)->get();
        $statuses = Task::getStatuses();

        $tasks = [];
        foreach (range(1, 20) as $index) {
            $task = Task::create([
                'title' => "Task Title #$index",
                'description' => "Description for task #$index",
                'status' => $statuses[array_rand($statuses)],
                'due_date' => Date::createFromFormat('Y-m-d', '2025-05-20')->addDays(rand(0, 12)),
                'assignee_id' => $users->random()->id,
            ]);

            $tasks[] = $task;

            if (rand(0, 1)) {
                $dependencyCount = rand(1, 3);
                $dependencyCount = min($dependencyCount, count($tasks) - 1);
                $dependencies = collect($tasks)->except($task->id)->random($dependencyCount);

                foreach ($dependencies as $dependency) {
                    $task->dependencies()->attach($dependency->id);
                }
            }
        }
    }
}
