<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use Nirland\TaskyLand\Models\Project;
use Nirland\TaskyLand\Models\Task;
use Nirland\TaskyLand\Models\Progress;

/**
 * AllTablesSeeder
 *
 * @author Nirland
 */
class AllTablesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('users')->delete();
        DB::table('projects')->delete();
        DB::table('users_projects')->delete();
        DB::table('tasks')->delete();
        DB::table('progress')->delete();

        $nirlandUser = User::create(array('username' => 'Nirland',
                    'password' => Hash::make('123'),
                    'firstname' => 'Nir',
                    'surname' => 'Keltar',
                    'email' => 'nirland@example.com',
                    'role' => User::ROLE_ADMIN));

        $telinUser = User::create(array('username' => 'Telin',
                    'password' => Hash::make('123'),
                    'firstname' => 'Tel',
                    'surname' => 'Keltar',
                    'email' => 'telin@example.com',
                    'role' => User::ROLE_USER));

        $taskProject = Project::create(array('name' => 'TaskTracker',
                    'description' => 'Task tracker server api'));

        $taskclientProject = Project::create(array('name' => 'TaskTrackerClient',
                    'description' => 'Task tracker SPA client'));

        $taskProject->members()->attach($nirlandUser->id);
        $taskProject->members()->attach($telinUser->id);
        $taskclientProject->members()->attach($nirlandUser->id);
        $taskclientProject->members()->attach($telinUser->id);


        foreach (array($taskProject, $taskclientProject) as $project) {

            $taskCount = rand(5, 10);
            $progressCount = rand(3, 5);

            for ($i = 0; $i < $taskCount; $i++) {

                $taskTitle = 'Task ' . ($i + 1) . ' on ' . $project->name;
                $taskDescription = 'Description of task ' . ($i + 1) . ' on ' . $project->name;
                $taskKind = array(Task::KIND_IMPORTANT, Task::KIND_NORMAL)[rand(0, 1)];
                $taskStatus = array(Task::STATUS_OPENED, Task::STATUS_CLOSED, Task::STATUS_DELAYED)[rand(0, 2)];
                $taskDateEnd = Carbon::now()->addMonths(rand(0, 5));

                $task = new Task(array('title' => $taskTitle,
                    'description' => $taskDescription,
                    'kind' => $taskKind,
                    'status' => $taskStatus,
                    'date_end' => $taskDateEnd));
                $task->project()->associate($project);
                $task->creator()->associate(array($nirlandUser, $telinUser)[rand(0, 1)]);
                $task->save();

                for ($j = 0; $j < $progressCount; $j++) {

                    $progressTitle = 'Comment ' . ($j + 1);
                    $progressMessage = 'Working on ' . $task->title;
                    $progressRevision = rand(1000, 1100);
                    $progressHours = rand(0, 8);
                    $progressMinutes = rand(0, 59);
                    
                    $progress = new Progress(array('title' => $progressTitle,
                        'message' => $progressMessage,
                        'revision' => $progressRevision,
                        'hours' => $progressHours,
                        'minutes' => $progressMinutes));
                    $progress->worker()->associate(array($nirlandUser, $telinUser)[rand(0, 1)]);
                    $task->progress()->save($progress);
                }
            }
        }
    }

}
