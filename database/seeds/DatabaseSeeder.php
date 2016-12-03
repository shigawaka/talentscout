<?php
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('UserTableSeeder');

        Model::reguard();
    }
}

class UserTableSeeder extends Seeder
{
public function run()
{
    DB::table('user')->delete();
    User::create(['name' => 'admin','email' => 'iloveasher8@gmail.com','password' => '123456']);
}


}