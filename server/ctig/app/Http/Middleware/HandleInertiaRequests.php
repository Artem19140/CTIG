<?php

namespace App\Http\Middleware;

use App\Enums\EmployeeRole;
use App\Models\Employee;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ForeignNational;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
            'auth.user' => fn () => $request->user() instanceof Employee ? 
                array_merge(
                    $request->user()->only('id', 'surname', 'name', 'email', 'center_id', 'job_title'),
                    [
                        'roles' => $request->user()->roles->pluck('name'),
                    ]
                )
                : null,  
            'auth.can'  => fn () => $request->user() instanceof Employee ?
                $this->menuPermissions($request->user())
                 : null
        ]);
    }

    protected function menuPermissions(Employee $employee):array{
        return [
            'foreignNationals' => $employee->can('viewAny', ForeignNational::class),
            'exams' => $employee->can('viewAny', Exam::class),
            'monitoring' => $employee->hasAnyRole(EmployeeRole::Examiner, EmployeeRole::SuperAdmin) ,
            'checking' => $employee->hasAnyRole(EmployeeRole::Examiner, EmployeeRole::SuperAdmin),
            'schedule' => $employee->can('viewAny', Exam::class),
            'center' =>  $employee->hasAnyRole(EmployeeRole::CenterAdmin, EmployeeRole::SuperAdmin),
            'adminPanel' => $employee->hasAnyRole(EmployeeRole::SuperAdmin)
        ];
    }
}
