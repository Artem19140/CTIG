<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ExamCounterService
{
    public static function get(int $examId, string $key): int
    {
        return DB::table('exam_counters')
            ->where('exam_id', $examId)
            ->where('key', $key)
            ->value('value') ?? 0;
    }

    public static function increment(int $examId, string $key, int $by = 1): void
    {
        $exists = DB::table('exam_counters')
            ->where('exam_id', $examId)
            ->where('key', $key)
            ->exists();

        if ($exists) {
            DB::table('exam_counters')
                ->where('exam_id', $examId)
                ->where('key', $key)
                ->increment('value', $by);
        } else {
            DB::table('exam_counters')->insert([
                'exam_id' => $examId,
                'key' => $key,
                'value' => $by,
            ]);
        }
    }

    public static function decrement(int $examId, string $key, int $by = 1): void
    {
        DB::table('exam_counters')
            ->where('exam_id', $examId)
            ->where('key', $key)
            ->decrement('value', $by);
    }
}