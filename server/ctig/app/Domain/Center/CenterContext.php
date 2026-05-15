<?php

namespace App\Domain\Center;

class CenterContext{
    public function id():null | int{
        $employee = auth()->user();

        if (!$employee || $employee->isSuperAdmin()) {
            return null;
        }
        return $employee->center_id;
    }
}
