<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ReportCreated extends Notification
{
    use Queueable;

    public function __construct(public Report $report)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $message = $this->report->user->name . ' ' . __('messages.report_notification_format') . $this->report->asset->name;
        
        \Log::info('Creating database notification', [
            'notifiable_id' => $notifiable->id,
            'report_id' => $this->report->id,
            'message' => $message,
        ]);
        
        return new DatabaseMessage([
            'report_id' => $this->report->id,
            'asset_name' => $this->report->asset->name,
            'problem' => $this->report->problem_description,
            'reported_by' => $this->report->user->name,
            'message' => $message,
        ]);
    }
}
