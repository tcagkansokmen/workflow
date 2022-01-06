<?php declare(strict_types = 1);
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // lets define our groups.
        $groups = [
            // root group
            [
              'id' => 1,
              'name' => 'super admin',
              'description' => 'ana kullanıcı',
              'redirect' => '/',
              'properties' => array(
                array('field' => 'dashboard', 'action' => 'list'),
                array('field' => 'records', 'action' => 'list'),
                array('field' => 'customers', 'action' => 'list'),
                array('field' => 'customers', 'action' => 'add'),
                array('field' => 'customers', 'action' => 'detail'),
                array('field' => 'customers', 'action' => 'delete'),
                array('field' => 'customers', 'action' => 'edit'),

                array('field' => 'projects', 'action' => 'list'),
                array('field' => 'projects', 'action' => 'add'),
                array('field' => 'projects', 'action' => 'detail'),
                array('field' => 'projects', 'action' => 'delete'),
                array('field' => 'projects', 'action' => 'edit'),

                array('field' => 'workflow', 'action' => 'list'),
                array('field' => 'production', 'action' => 'list'),
                array('field' => 'production', 'action' => 'add'),
                array('field' => 'production', 'action' => 'detail'),
                array('field' => 'production', 'action' => 'delete'),
                array('field' => 'production', 'action' => 'edit'),

                array('field' => 'assembly', 'action' => 'list'),
                array('field' => 'assembly', 'action' => 'add'),
                array('field' => 'assembly', 'action' => 'detail'),
                array('field' => 'assembly', 'action' => 'delete'),
                array('field' => 'assembly', 'action' => 'edit'),

                array('field' => 'printing', 'action' => 'list'),
                array('field' => 'printing', 'action' => 'add'),
                array('field' => 'printing', 'action' => 'detail'),
                array('field' => 'printing', 'action' => 'delete'),
                array('field' => 'printing', 'action' => 'edit'),

                array('field' => 'exploration', 'action' => 'list'),
                array('field' => 'exploration', 'action' => 'add'),
                array('field' => 'exploration', 'action' => 'detail'),
                array('field' => 'exploration', 'action' => 'delete'),
                array('field' => 'exploration', 'action' => 'edit'),
                array('field' => 'exploration', 'action' => 'status'),

                array('field' => 'satin_alma', 'action' => 'list'),
                array('field' => 'product', 'action' => 'list'),
                array('field' => 'product', 'action' => 'add'),
                array('field' => 'product', 'action' => 'detail'),
                array('field' => 'product', 'action' => 'delete'),
                array('field' => 'product', 'action' => 'edit'),

                array('field' => 'supplier', 'action' => 'list'),
                array('field' => 'supplier', 'action' => 'add'),
                array('field' => 'supplier', 'action' => 'detail'),
                array('field' => 'supplier', 'action' => 'delete'),
                array('field' => 'supplier', 'action' => 'edit'),

                array('field' => 'purchase', 'action' => 'list'),
                array('field' => 'purchase', 'action' => 'add'),
                array('field' => 'purchase', 'action' => 'detail'),
                array('field' => 'purchase', 'action' => 'delete'),
                array('field' => 'purchase', 'action' => 'edit'),

                array('field' => 'expense', 'action' => 'list'),
                array('field' => 'expense', 'action' => 'add'),
                array('field' => 'expense', 'action' => 'detail'),
                array('field' => 'expense', 'action' => 'delete'),
                array('field' => 'expense', 'action' => 'edit'),
                array('field' => 'expense', 'action' => 'confirmation'),

                array('field' => 'surec', 'action' => 'list'),
                array('field' => 'cheque', 'action' => 'list'),
                array('field' => 'cheque', 'action' => 'add'),
                array('field' => 'cheque', 'action' => 'detail'),
                array('field' => 'cheque', 'action' => 'delete'),
                array('field' => 'cheque', 'action' => 'edit'),

                array('field' => 'briefs', 'action' => 'list'),
                array('field' => 'briefs', 'action' => 'add'),
                array('field' => 'briefs', 'action' => 'detail'),
                array('field' => 'briefs', 'action' => 'delete'),
                array('field' => 'briefs', 'action' => 'edit'),

                array('field' => 'offers', 'action' => 'list'),
                array('field' => 'offers', 'action' => 'add'),
                array('field' => 'offers', 'action' => 'detail'),
                array('field' => 'offers', 'action' => 'delete'),
                array('field' => 'offers', 'action' => 'edit'),

                array('field' => 'contracts', 'action' => 'list'),
                array('field' => 'contracts', 'action' => 'add'),
                array('field' => 'contracts', 'action' => 'detail'),
                array('field' => 'contracts', 'action' => 'delete'),
                array('field' => 'contracts', 'action' => 'edit'),

                array('field' => 'bills', 'action' => 'list'),
                array('field' => 'bills', 'action' => 'add'),
                array('field' => 'bills', 'action' => 'detail'),
                array('field' => 'bills', 'action' => 'delete'),
                array('field' => 'bills', 'action' => 'edit'),

                array('field' => 'firm', 'action' => 'list'),
                array('field' => 'users', 'action' => 'list'),
                array('field' => 'users', 'action' => 'add'),
                array('field' => 'users', 'action' => 'detail'),
                array('field' => 'users', 'action' => 'delete'),
                array('field' => 'users', 'action' => 'edit'),

                array('field' => 'yonetim', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'add'),
                array('field' => 'cost', 'action' => 'detail'),
                array('field' => 'cost', 'action' => 'delete'),
                array('field' => 'cost', 'action' => 'edit'),

                array('field' => 'vehicles', 'action' => 'list'),
                array('field' => 'vehicles', 'action' => 'add'),
                array('field' => 'vehicles', 'action' => 'detail'),
                array('field' => 'vehicles', 'action' => 'delete'),
                array('field' => 'vehicles', 'action' => 'edit'),

                array('field' => 'employee', 'action' => 'index'),
                array('field' => 'employee', 'action' => 'add'),
                array('field' => 'employee', 'action' => 'edit'),
                array('field' => 'employee', 'action' => 'update'),
                array('field' => 'employee', 'action' => 'delete'),
                array('field' => 'employee', 'action' => 'save'),
                array('field' => 'employee', 'action' => 'status'),
                array('field' => 'employee', 'action' => 'detail'),
              )
            ],
            [
              'id' => 2,
              'name' => 'üretim',
              'description' => 'üretim müdürü kullanıcı',
              'redirect' => '/production',
              'properties' => array(
                array('field' => 'workflow', 'action' => 'list'),
                array('field' => 'exploration', 'action' => 'list'),
                array('field' => 'exploration', 'action' => 'add'),
                array('field' => 'exploration', 'action' => 'detail'),
                array('field' => 'exploration', 'action' => 'edit'),
                array('field' => 'exploration', 'action' => 'status'),

                array('field' => 'printing_meta', 'action' => 'list'),
                array('field' => 'printing_meta', 'action' => 'add'),
                array('field' => 'production', 'action' => 'list'),
                array('field' => 'production', 'action' => 'detail'),
                array('field' => 'production', 'action' => 'status'),

                array('field' => 'assembly', 'action' => 'list'),
                array('field' => 'assembly', 'action' => 'detail'),
                array('field' => 'assembly', 'action' => 'status'),

                array('field' => 'printing', 'action' => 'list'),
                array('field' => 'printing', 'action' => 'detail'),
                array('field' => 'printing', 'action' => 'status'),

                array('field' => 'yonetim', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'add'),
                array('field' => 'cost', 'action' => 'detail'),
                array('field' => 'cost', 'action' => 'delete'),
                array('field' => 'cost', 'action' => 'edit'),
              )
            ],
            [
              'id' => 3,
              'name' => 'satın alma',
              'description' => 'satın alma müdürü kullanıcı',
              'redirect' => '/purchases',
              'properties' => array(

                array('field' => 'yonetim', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'add'),
                array('field' => 'cost', 'action' => 'detail'),
                array('field' => 'cost', 'action' => 'delete'),
                array('field' => 'cost', 'action' => 'edit'),

                array('field' => 'satin_alma', 'action' => 'list'),
                array('field' => 'product', 'action' => 'list'),
                array('field' => 'product', 'action' => 'add'),
                array('field' => 'product', 'action' => 'detail'),
                array('field' => 'product', 'action' => 'delete'),
                array('field' => 'product', 'action' => 'edit'),

                array('field' => 'supplier', 'action' => 'list'),
                array('field' => 'supplier', 'action' => 'add'),
                array('field' => 'supplier', 'action' => 'detail'),
                array('field' => 'supplier', 'action' => 'delete'),
                array('field' => 'supplier', 'action' => 'edit'),

                array('field' => 'purchase', 'action' => 'list'),
                array('field' => 'purchase', 'action' => 'add'),
                array('field' => 'purchase', 'action' => 'detail'),
                array('field' => 'purchase', 'action' => 'delete'),
                array('field' => 'purchase', 'action' => 'edit'),

                array('field' => 'expense', 'action' => 'list'),
                array('field' => 'expense', 'action' => 'add'),
                array('field' => 'expense', 'action' => 'detail'),
                array('field' => 'expense', 'action' => 'delete'),
                array('field' => 'expense', 'action' => 'edit'),
              )
            ],
            [
              'id' => 4,
              'name' => 'müşteri temsilcisi',
              'description' => 'müşteri temsilcisi kullanıcı',
              'redirect' => '/customers',
              'properties' => array(
                array('field' => 'records', 'action' => 'list'),
                array('field' => 'customers', 'action' => 'list'),
                array('field' => 'customers', 'action' => 'add'),
                array('field' => 'customers', 'action' => 'detail'),
                array('field' => 'customers', 'action' => 'delete'),
                array('field' => 'customers', 'action' => 'edit'),

                array('field' => 'projects', 'action' => 'list'),
                array('field' => 'projects', 'action' => 'add'),
                array('field' => 'projects', 'action' => 'detail'),
                array('field' => 'projects', 'action' => 'delete'),
                array('field' => 'projects', 'action' => 'edit'),

                array('field' => 'workflow', 'action' => 'list'),
                array('field' => 'production', 'action' => 'list'),
                array('field' => 'production', 'action' => 'add'),
                array('field' => 'production', 'action' => 'detail'),
                array('field' => 'production', 'action' => 'delete'),
                array('field' => 'production', 'action' => 'edit'),

                array('field' => 'assembly', 'action' => 'list'),
                array('field' => 'assembly', 'action' => 'add'),
                array('field' => 'assembly', 'action' => 'detail'),
                array('field' => 'assembly', 'action' => 'delete'),
                array('field' => 'assembly', 'action' => 'edit'),

                array('field' => 'printing', 'action' => 'list'),
                array('field' => 'printing', 'action' => 'add'),
                array('field' => 'printing', 'action' => 'detail'),
                array('field' => 'printing', 'action' => 'delete'),
                array('field' => 'printing', 'action' => 'edit'),

                array('field' => 'exploration', 'action' => 'list'),
                array('field' => 'exploration', 'action' => 'add'),
                array('field' => 'exploration', 'action' => 'detail'),
                array('field' => 'exploration', 'action' => 'delete'),
                array('field' => 'exploration', 'action' => 'edit'),
                array('field' => 'exploration', 'action' => 'status'),

                array('field' => 'surec', 'action' => 'list'),
                array('field' => 'briefs', 'action' => 'list'),
                array('field' => 'briefs', 'action' => 'add'),
                array('field' => 'briefs', 'action' => 'detail'),
                array('field' => 'briefs', 'action' => 'delete'),
                array('field' => 'briefs', 'action' => 'edit'),

                array('field' => 'offers', 'action' => 'list'),
                array('field' => 'offers', 'action' => 'add'),
                array('field' => 'offers', 'action' => 'detail'),
                array('field' => 'offers', 'action' => 'delete'),
                array('field' => 'offers', 'action' => 'edit'),

                array('field' => 'contracts', 'action' => 'list'),
                array('field' => 'contracts', 'action' => 'add'),
                array('field' => 'contracts', 'action' => 'detail'),
                array('field' => 'contracts', 'action' => 'delete'),
                array('field' => 'contracts', 'action' => 'edit'),

                array('field' => 'bills', 'action' => 'list'),
                array('field' => 'bills', 'action' => 'add'),
                array('field' => 'bills', 'action' => 'detail'),
                array('field' => 'bills', 'action' => 'delete'),
                array('field' => 'bills', 'action' => 'edit'),

                array('field' => 'yonetim', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'add'),
                array('field' => 'cost', 'action' => 'detail'),
                array('field' => 'cost', 'action' => 'delete'),
                array('field' => 'cost', 'action' => 'edit'),
              )
            ],
            [
              'id' => 5,
              'name' => 'muhasebe müdürü',
              'description' => 'muhasebe müdürü kullanıcı',
              'redirect' => '/customers',
              'properties' => array(
                array('field' => 'records', 'action' => 'list'),
                array('field' => 'customers', 'action' => 'list'),
                array('field' => 'customers', 'action' => 'add'),
                array('field' => 'customers', 'action' => 'detail'),
                array('field' => 'customers', 'action' => 'delete'),
                array('field' => 'customers', 'action' => 'edit'),

                array('field' => 'projects', 'action' => 'list'),
                array('field' => 'projects', 'action' => 'add'),
                array('field' => 'projects', 'action' => 'detail'),
                array('field' => 'projects', 'action' => 'delete'),
                array('field' => 'projects', 'action' => 'edit'),

                array('field' => 'surec', 'action' => 'list'),

                array('field' => 'bills', 'action' => 'list'),
                array('field' => 'bills', 'action' => 'add'),
                array('field' => 'bills', 'action' => 'detail'),
                array('field' => 'bills', 'action' => 'delete'),
                array('field' => 'bills', 'action' => 'edit'),

                array('field' => 'yonetim', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'add'),
                array('field' => 'cost', 'action' => 'detail'),
                array('field' => 'cost', 'action' => 'delete'),
                array('field' => 'cost', 'action' => 'edit'),

                array('field' => 'cheque', 'action' => 'list'),
                array('field' => 'cheque', 'action' => 'add'),
                array('field' => 'cheque', 'action' => 'detail'),
                array('field' => 'cheque', 'action' => 'delete'),
                array('field' => 'cheque', 'action' => 'edit'),

                array('field' => 'satin_alma', 'action' => 'list'),
                array('field' => 'expense', 'action' => 'list'),
                array('field' => 'expense', 'action' => 'detail'),
                array('field' => 'expense', 'action' => 'edit'),
                array('field' => 'expense', 'action' => 'paid'),

                array('field' => 'firm', 'action' => 'list'),
                array('field' => 'vehicles', 'action' => 'list'),
                array('field' => 'vehicles', 'action' => 'add'),
                array('field' => 'vehicles', 'action' => 'detail'),
                array('field' => 'vehicles', 'action' => 'delete'),
                array('field' => 'vehicles', 'action' => 'edit'),
              )
            ],
            [
              'id' => 6,
              'name' => 'baskı operatörü',
              'description' => 'baskı operatörü kullanıcı',
              'redirect' => '/printing',
              'properties' => array(
                array('field' => 'workflow', 'action' => 'list'),
                array('field' => 'printing_meta', 'action' => 'list'),
                array('field' => 'printing_meta', 'action' => 'add'),

                array('field' => 'printing', 'action' => 'list'),
                array('field' => 'printing', 'action' => 'detail'),
                array('field' => 'printing', 'action' => 'status'),

                array('field' => 'yonetim', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'list'),
                array('field' => 'cost', 'action' => 'add'),
                array('field' => 'cost', 'action' => 'detail'),
                array('field' => 'cost', 'action' => 'delete'),
                array('field' => 'cost', 'action' => 'edit'),
              )
            ],
            [
              'id' => 7,
              'name' => 'tasarımcı',
              'description' => 'tasarımcı kullanıcı',
              'redirect' => '/briefs',
              'properties' => array(
                  array('field' => 'surec', 'action' => 'list'),
                  array('field' => 'briefs', 'action' => 'list'),
                  array('field' => 'briefs', 'action' => 'add'),
                  array('field' => 'briefs', 'action' => 'detail'),
                  array('field' => 'briefs', 'action' => 'delete'),
                  array('field' => 'briefs', 'action' => 'edit'),

                  array('field' => 'yonetim', 'action' => 'list'),
                  array('field' => 'cost', 'action' => 'list'),
                  array('field' => 'cost', 'action' => 'add'),
                  array('field' => 'cost', 'action' => 'detail'),
                  array('field' => 'cost', 'action' => 'delete'),
                  array('field' => 'cost', 'action' => 'edit'),
              )
            ],
            [
              'id' => 8,
              'name' => 'standard',
              'description' => 'standard kullanıcı',
              'redirect' => '/user/profil',
              'properties' => array(
                  array('field' => 'yonetim', 'action' => 'list'),
                  array('field' => 'cost', 'action' => 'list'),
                  array('field' => 'cost', 'action' => 'add'),
                  array('field' => 'cost', 'action' => 'detail'),
                  array('field' => 'cost', 'action' => 'delete'),
                  array('field' => 'cost', 'action' => 'edit'),
              )
            ],
        ];

        $yetkileriyoket = DB::table('user_powers')->delete();
        foreach ($groups as $group) {
            $new = [];
            $check = null;
            $check = DB::table('user_groups')->where('id', $group['id'])->first();
            $new['created_at'] = now();
            $new['updated_at'] = now();
            $new['updated_at'] = now();
            $new['name'] = $group['name'];
            $new['description'] = $group['description'];
            $new['redirect'] = $group['redirect'];
            if ($check === null) {
                DB::table('user_groups')->insert($new);
                $check = DB::table('user_groups')->where('name', $group['name'])->first();
            } else {
                DB::table('user_groups')->where('id', $check->id)->update($new);
            }

            foreach($group['properties'] as $p){
                DB::table('user_powers')->insert(array(
                  'group_id' => $check->id,
                  'field' => $p['field'],
                  'action' => $p['action'],
                  'type' => 1,
                  'created_at' => now(),
                  'updated_at' => now()
                ));
              }

        }
    }
}
