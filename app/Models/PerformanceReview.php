<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model {
    protected $guarded = [];
    protected $casts = ['review_date' => 'date', 'rating' => 'float'];

    public function employee() { return $this->belongsTo(User::class, 'user_id'); }
    public function manager()  { return $this->belongsTo(User::class, 'manager_id'); }
    public function goals()    { return $this->hasMany(PerformanceGoal::class); }

    public function recalcRating(): void {
        $goals = $this->goals;
        if ($goals->sum('weight') === 0) return;
        $this->rating = round(($goals->sum('score') / 100) * 5, 2);
        $this->save();
    }
}