<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Log an action into activity_logs table.
     *
     * @param string $activity Summary title of activity (e.g. 'User Login', 'Evaluation Submitted')
     * @param string|null $description Detailed description
     * @param string $status 'success' | 'failed' | 'warning'
     * @param \App\Models\User|null $user Optional user instance override
     * @return ActivityLog|null
     */
    public static function log(string $activity, ?string $description = null, string $status = 'success', $user = null): ?ActivityLog
    {
        try {
            $currentUser = $user ?? Auth::user();

            $userId = $currentUser ? $currentUser->id : null;
            $role = $currentUser ? ($currentUser->role ?? 'guest') : 'system/guest';

            if (!$userId) {
                // If user id is not present, we skip logging or record under guest/system
                $role = $role ?? 'guest';
            }

            $request = request();

            return ActivityLog::create([
                'user_id'    => $userId,
                'activity'   => $activity,
                'description'=> $description,
                'role'       => $role,
                'status'     => in_array($status, ['success', 'failed', 'warning']) ? $status : 'success',
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $request ? substr((string)$request->header('User-Agent'), 0, 250) : null,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to write Audit Log: ' . $e->getMessage());
            return null;
        }
    }
}
