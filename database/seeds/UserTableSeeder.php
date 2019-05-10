<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Faker\Provider\Uuid;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 清空表
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 用户
        $user = User::create([
            'username'  => 'root',
            'phone'     => '18908221080',
            'name'      => '超级管理员',
            'email'     => 'sorshion@gmail.com',
            'password'  => bcrypt('123456'),
            'uuid'      => Uuid::uuid(),
        ]);

        // 角色
        $role = Role::create([
            'name' => 'root',
            'display_name' => '超级管理员',
        ]);

        // 权限
        $permissions = [
            [
                'name' => 'system.manage',
                'display_name' => '系统管理',
                'route' => '',
                'icon_id' => '100',
                'child' => [
                    [
                        'name' => 'system.user',
                        'display_name' => '用户管理',
                        'route' => 'admin.user',
                        'icon_id' => '123',
                        'child' => [
                            ['name' => 'system.user.create', 'display_name' => '添加用户', 'route' => 'admin.user.store'],
                            ['name' => 'system.user.edit', 'display_name' => '编辑用户', 'route' => 'admin.user.update'],
                            ['name' => 'system.user.destroy', 'display_name' => '删除用户', 'route' => 'admin.user.destroy'],
                            ['name' => 'system.user.role', 'display_name' => '分配角色', 'route' => 'admin.user.assignRole'],
                            ['name' => 'system.user.permission', 'display_name' => '分配权限', 'route' => 'admin.user.assignPermission'],
                        ]
                    ],
                    [
                        'name' => 'system.role',
                        'display_name' => '角色管理',
                        'route' => 'admin.role',
                        'icon_id' => '121',
                        'child' => [
                            ['name' => 'system.role.create', 'display_name' => '添加角色', 'route' => 'admin.role.store'],
                            ['name' => 'system.role.edit', 'display_name' => '编辑角色', 'route' => 'admin.role.update'],
                            ['name' => 'system.role.destroy', 'display_name' => '删除角色', 'route' => 'admin.role.destroy'],
                            ['name' => 'system.role.permission', 'display_name' => '分配权限', 'route' => 'admin.role.assignPermission'],
                        ]
                    ],
                    [
                        'name' => 'system.permission',
                        'display_name' => '权限管理',
                        'route' => 'admin.permission',
                        'icon_id' => '12',
                        'child' => [
                            ['name' => 'system.permission.create', 'display_name' => '添加权限', 'route' => 'admin.permission.store'],
                            ['name' => 'system.permission.edit', 'display_name' => '编辑权限', 'route' => 'admin.permission.update'],
                            ['name' => 'system.permission.destroy', 'display_name' => '删除权限', 'route' => 'admin.permission.destroy'],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($permissions as $pem1) {
            // 生成一级权限
            $p1 = Permission::create([
                'name' => $pem1['name'],
                'display_name' => $pem1['display_name'],
                'route' => $pem1['route'] ?? '',
                'icon_id' => $pem1['icon_id'] ?? 1,
            ]);
            // 为角色添加权限
            $role->givePermissionTo($p1);
            // 为用户添加权限
            $user->givePermissionTo($p1);
            if (isset($pem1['child'])) {
                foreach ($pem1['child'] as $pem2) {
                    // 生成二级权限
                    $p2 = Permission::create([
                        'name' => $pem2['name'],
                        'display_name' => $pem2['display_name'],
                        'parent_id' => $p1->id,
                        'route' => $pem2['route'] ?? 1,
                        'icon_id' => $pem2['icon_id'] ?? 1,
                    ]);
                    // 为角色添加权限
                    $role->givePermissionTo($p2);
                    // 为用户添加权限
                    $user->givePermissionTo($p2);
                    if (isset($pem2['child'])) {
                        foreach ($pem2['child'] as $pem3) {
                            // 生成三级权限
                            $p3 = Permission::create([
                                'name' => $pem3['name'],
                                'display_name' => $pem3['display_name'],
                                'parent_id' => $p2->id,
                                'route' => $pem3['route'] ?? '',
                                'icon_id' => $pem3['icon_id'] ?? 1,
                            ]);
                            // 为角色添加权限
                            $role->givePermissionTo($p3);
                            // 为用户添加权限
                            $user->givePermissionTo($p3);
                        }
                    }
                }
            }
        }

        // 为用户添加角色
        $user->assignRole($role);

        // 初始化的角色
        $roles = [
            ['name' => 'admin', 'display_name' => '管理员'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
