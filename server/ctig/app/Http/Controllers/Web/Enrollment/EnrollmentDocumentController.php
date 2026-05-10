<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Domain\Enrollment\Action\GenerateEnrollmentStatementAction;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentDocumentController
{
    public function statement(
        Request $request,
        Enrollment $enrollment, 
        GenerateEnrollmentStatementAction $generateEnrollmentStatement
    ){
        $statement = $generateEnrollmentStatement->execute($enrollment, $request->user());
        return $statement->stream('statement.pdf'); 
    }
}
