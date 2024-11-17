<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnblockRequest extends Model
{
    use HasFactory;

    protected $table = 'unblock_request';

    public $timestamps = false;

    protected $fillable = [
        'decision_by_id',
        'requested_by_id',
        'status',
        'requested_at',
        'processed_at',
        'decision',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function decisionBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'decision_by_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_id');
    }

    public function approve(int $adminId, string $decision = 'Approved'): bool
    {
        $this->decision_by_id = $adminId;
        $this->status = 'approved';
        $this->decision = $decision;
        $this->processed_at = now();
        return $this->save();
    }

    public function reject(int $adminId, string $decision = 'Rejected'): bool
    {
        $this->decision_by_id = $adminId;
        $this->status = 'rejected';
        $this->decision = $decision;
        $this->processed_at = now();
        return $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

}
