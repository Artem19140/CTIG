<?php

// use App\Http\Controllers\Api\Address\AddressController;
// use App\Http\Controllers\Api\Attempt\AttemptController;
// use App\Http\Controllers\Api\ExamCode\ExamCodeController;
// use App\Http\Controllers\Api\Login\LoginController;
// use App\Http\Controllers\Api\Report\ReportController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\Student\StudentController;
// use App\Http\Controllers\Api\User\UserController;
// use App\Http\Controllers\Api\Exam\ExamController;
// use App\Http\Controllers\Api\ExamType\ExamTypeController;
// use App\Http\Controllers\Api\Task\TaskController;
// use App\Http\Controllers\Api\ExamStudent\ExamStudentController;
// use App\Http\Controllers\Api\Document\DocumentController;
// use App\Http\Controllers\Api\AttemptAnswer\AttemptAnswerController;
// use App\Enums\TokenAbilities;


// Route::middleware(['auth:sanctum'])->group(function (){//, "abilities:".TokenAbilities::SystemAccess->value
//     Route::get('attempts/to-check', [AttemptController::class, 'toCheck']);
//     Route::apiResource( 'users', UserController::class);

//     Route::apiResource('students', StudentController::class)
//         ->names(['index' => 'api.students.index',]);
        
//     Route::apiResource('exams', ExamController::class)
//         ->names(['index' => 'api.exams.index',]);
        
//     Route::apiResource('exam-types', ExamTypeController::class)
//         ->except('destroy', 'update', 'store');

//     Route::apiResource('documents', DocumentController::class);

//     Route::apiResource('tasks', TaskController::class)
//         ->except('update', 'store', 'destroy', 'index');

//     Route::apiResource('student-answers', AttemptAnswerController::class)
//         ->except('update');

//     Route::apiResource('addresses', AddressController::class);

//     Route::apiResource('attempts', AttemptController::class);

//     Route::prefix("exams")->group(function (){
//         Route::post('/{exam}/students', [ExamStudentController::class, "store"]);
//         Route::delete('/{exam}/students/{student}', [ExamStudentController::class, "destroy"]); 
//         Route::post('/{exam}/codes', [ExamCodeController::class, "store"]);
//         Route::get('/{exam}/state', [ExamController::class, "state"]);
//         Route::post('/{exam}/students/{student}/transfer', [ExamStudentController::class, 'transfer']);
//     });

//     Route::prefix("attempts")->group(function (){
//         Route::put('/{attempt}/ban', [AttemptController::class, 'ban']);
//         Route::get('/{attempt}/speaking-tasks', [AttemptController::class, 'speaking']);
//         Route::get('/{attempt}/answers-to-check', [AttemptController::class, 'answersToCheck']);
//         Route::get('/{attempt}/full', [AttemptController::class, 'full']);
//     });
    
//     Route::put('attempts-answers/{attemptAnswer}/rate', [AttemptAnswerController::class, 'rate']);
    

//     Route::post('reports/frdo', [ReportController::class, 'frdo']);
//     Route::post('reports/{exam}/statement', [ReportController::class, 'statement']);
// });


// Route::post('/login', [LoginController::class, 'login']);
// Route::post( 'exam-codes/verify', [ExamController::class, 'verifyCode']);


// Route::middleware(['auth:sanctum', "abilities:".TokenAbilities::ExamAccess->value])->group(function (){ //во время экзамена
//     Route::put('attempts-answers/{attemptAnswer}', [AttemptAnswerController::class, 'update']);
//     Route::put('attempts/{attempt}/finish', [AttemptController::class, 'finish']);
// }); 


// Route::post('attempts', [AttemptController::class, 'store'])
//     ->middleware(['auth:sanctum', "abilities:".TokenAbilities::ExamPrepare->value]);//exam:prepare только для начинания попытки

// Route::post('password-change', [LoginController::class, 'changePassword'])
//     ->middleware(['auth:sanctum', "abilities:".TokenAbilities::ChangePassword->value]);
    
// Route::post( 'users', [UserController::class, 'store']);

// use Illuminate\Support\Facades\DB;

// DB::listen(function ($query) {
//     dump([
//         'sql' => $query->sql,
//         'bindings' => $query->bindings,
//         'time' => $query->time,
//     ]);
// });