<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();

        $this->call('AllTablesSeeder');

        $this->command->info('All tables seeded!');
    }

}
