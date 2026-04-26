<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Domain\Enrollment\Action\GenerateEnrollmentStatementAction;
use App\Models\Enrollment;

class EnrollmentDocumentController
{
    public function statement(
        Enrollment $enrollment, 
        GenerateEnrollmentStatementAction $generateEnrollmentStatement
    ){
        $statement = $generateEnrollmentStatement->execute($enrollment);
        return $statement->stream('statement.pdf'); 
    }
}
