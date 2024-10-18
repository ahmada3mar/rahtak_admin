<?php

namespace Database\Seeders;

use App\Helpers\Sadad;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info(Permission::class);
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            User::class,
            Role::class,
            Permission::class,
            Transaction::class,
            Branch::class,
            Customer::class
            // ... // List all your Models you want to have Permissions for.
        ]);

        $analytics   = [
            'view top merchant count',
            'view total merchant count',
            'view total tries per day',
            'view total statues per day',
            'view total codes count',
            'view all merchants analytics'
        ];

        $adminEmail = env('ADMIN_EMAIL', '');

        $branch = Branch::updateOrCreate(['name'=>'طبربور']);

        if (!($adminEmail)) {
            throw new \InvalidArgumentException('Mobile parameter must be provided!');
        }


        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            $group = $this->getGroupName($item);
            $permission = $this->getPermissionName($item);

            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'view ' . $permission,
            ], [
                'description' => 'Allow the user to view a list of ' . strtolower($group) . ' as well as the details'
            ]);
            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'create ' . $permission,
            ], [
                'description' => 'Allow the user to add new ' . strtolower($group)
            ]);
            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'update ' . $permission,
            ], [
                'description' => 'Allow the user to update existing ' . strtolower($group)
            ]);
            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'delete ' . $permission,
            ], [
                'description' => 'Allow the user to delete ' . strtolower($group)
            ]);
            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'destroy ' . $permission,
            ], [
                'description' => 'Allow the user to destroy ' . strtolower($group)
            ]);
            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'restore ' . $permission,
            ], [
                'description' => 'Allow the user to restore ' . strtolower($group)
            ]);

            Permission::updateOrCreate([
                'group' => $group,
                'name' =>   'view deleted ' . $permission,
            ], [
                'description' => 'Allow the user to view deleted ' . strtolower($group)
            ]);
        });




        Permission::updateOrCreate([
            'group' => 'Services',
            'name' =>   'view sadad',
        ], [
            'description' => 'Allow the user to view sadad'
        ]);
        Permission::updateOrCreate([
            'group' => 'Services',
            'name' =>   'view cliq',
        ], [
            'description' => 'Allow the user to view cliQ'
        ]);
        Permission::updateOrCreate([
            'group' => 'Services',
            'name' =>   'view taxiF',
        ], [
            'description' => 'Allow the user to view taxiF'
        ]);

        Permission::updateOrCreate([
            'group' => 'Services',
            'name' =>   'view services',
        ], [
            'description' => 'Allow the user to view all services'
        ]);



        // Create an Admin Role and assign all Permissions
        $role = Role::updateOrCreate(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // Give User Admin Role
        $user = User::firstOrCreate(['mobile' => $adminEmail], ['password' => bcrypt(\env('ADMIN_PASSWORD', '1G`v)2iC4YJg')), 'name' => 'Ahmad E\'mar' , 'branch_id' => $branch->id]); // Change this to your email.
        $user->assignRole('admin');

        $this->seedServices();
    }

    public function seedServices()
    {
        $token = (new Sadad())->getToken();
        $response = Http::withToken($token)
            ->get("https://pay.sadad.jo/POS/Payment/GetServiceforSearch?deviceNo=xxx");



        foreach(array_chunk($response->json() , 500) as $chunk){
          $data_service =  \array_map(function($arr){
                return [
                    'id' => $arr['id'],
                    'name'=>$arr['serviceNameAr'],
                    'biller_code'=>$arr['billerCode'],
                    'biller_name'=>$arr['billerNameAr'],
                    'category'=>$arr['categoryNameAr'],
                ];
            },$chunk);

            Service::upsert($data_service , uniqueBy:['id']);
        };
    }

    /**
     * Get group name based on the model class provided
     *
     * @param $class
     *
     * @return string
     */
    private function getGroupName($class)
    {
        return Str::plural(Str::title(Str::snake(class_basename($class), ' ')));
    }

    /**
     * Get permission name based on the model class provided
     *
     * @param $class
     *
     * @return string
     */
    private function getPermissionName($class)
    {
        return Str::plural(Str::snake(class_basename($class), ' '));
    }
}
