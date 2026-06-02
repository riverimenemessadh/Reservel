<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

/**
 * Notification sent when a new report is created.
 *
 * Stores a database notification containing report details
 * for the relevant notifiable (e.g. admin or manager).
 */
class ReportCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Report  $report  The report that was just created.
     */
    public function __construct(public Report $report)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  object  $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  object  $notifiable
     * @return \Illuminate\Notifications\Messages\DatabaseMessage
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $message = $this->report->user->name . ' a signalé un problème sur : ' . $this->report->asset->name;

        return new DatabaseMessage([
            'report_id'   => $this->report->id,
            'asset_name'  => $this->report->asset->name,
            'problem'     => $this->report->problem_description,
            'reported_by' => $this->report->user->name,
            'message'     => $message,
        ]);
    }
}