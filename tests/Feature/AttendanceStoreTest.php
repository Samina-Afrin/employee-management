<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AttendanceStoreTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('attendances');
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('attendance_date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_manual')->default(0);
            $table->unsignedBigInteger('corrected_by')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['employee_id', 'attendance_date']);
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('attendances');

        parent::tearDown();
    }

    public function test_store_updates_a_soft_deleted_attendance_instead_of_inserting_a_duplicate(): void
    {
        $attendance = Attendance::create([
            'employee_id' => 4,
            'attendance_date' => '2026-11-21',
            'status' => 3,
        ]);
        $attendance->delete();

        $response = $this->post(route('attendance.store'), [
            'attendance_date' => '2026-11-21',
            'attendance' => [
                4 => [
                    'check_in' => '09:00',
                    'check_out' => '17:05',
                    'status' => 1,
                    'remarks' => 'on time',
                ],
            ],
        ]);

        $response->assertRedirect(route('attendance.index'));
        $this->assertDatabaseCount('attendances', 1);
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'check_in' => '09:00',
            'check_out' => '17:05',
            'status' => 1,
            'remarks' => 'on time',
            'deleted_at' => null,
        ]);
    }
}
