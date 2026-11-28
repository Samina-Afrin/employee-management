<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PerformanceGoal extends Model {
    protected $guarded = [];

    public function review() { return $this->belongsTo(PerformanceReview::class, 'performance_review_id'); }

    protected static function booted() {
        static::saving(function ($goal) {
            if ($goal->target > 0) {
                $goal->score = round(($goal->achieved / $goal->target) * $goal->weight, 2);
            }
        });
    }
}