<?php
namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\StaffSupportSchool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffCreationService
{
    public function create(array $data)
    {
        DB::beginTransaction();

        try {

            $role = Role::findOrFail($data['role_id']);

            $schoolId = $data['school_id']
                ?? Auth::user()->school_id
                ?? null;

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'mobile'     => $data['mobile'],
                'password'   => Hash::make($data['mobile']),
                'school_id'  => $schoolId,
                'status'     => 1,
                'two_factor_enabled' => 0,
                'two_factor_secret' => null,
                'two_factor_expires_at' => null,
            ]);

            $user->assignRole($role);

            // Leave permission
            if ($schoolId) {
                $user->givePermissionTo([
                    'leave-list',
                    'leave-create',
                    'leave-edit',
                    'leave-delete',
                ]);
            }

            $joining_date = isset($data['joining_date'])
                ? date('Y-m-d', strtotime($data['joining_date']))
                : null;

            $staff = Staff::create([
                'user_id' => $user->id,
                'salary' => $data['salary'] ?? 0,
                'joining_date' => $joining_date,
            ]);

            // StaffSupportSchool
            if ($schoolId) {
                StaffSupportSchool::updateOrCreate([
                    'user_id' => $user->id,
                    'school_id' => $schoolId
                ]);
            }

            DB::commit();

            return $staff;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}