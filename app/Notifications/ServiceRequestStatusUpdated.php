<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceRequestStatusUpdated extends Notification
{
    use Queueable;

    public $serviceRequest;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(ServiceRequest $serviceRequest, string $status)
    {
        $this->serviceRequest = $serviceRequest;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = '';
        $title = 'Request Update';

        switch ($this->status) {
            case 'accepted':
                $title = 'Request Accepted';
                $message = 'Your service request has been accepted by ' . $this->serviceRequest->provider->name;
                break;
            case 'rejected':
                $title = 'Request Rejected';
                $message = 'Your service request was rejected by ' . $this->serviceRequest->provider->name;
                break;
            case 'in_progress':
                $title = 'Service Started';
                $message = 'Your service request is now in progress!';
                break;
            case 'completed':
                $title = 'Service Completed';
                $message = 'Service completed! Please leave a review.';
                break;
            default:
                $message = 'Your service request status has been updated to ' . $this->status;
        }

        return [
            'title' => $title,
            'message' => $message,
            'service_name' => $this->serviceRequest->studentService->title,
            'request_id' => $this->serviceRequest->id,
            'action_url' => route('service-requests.show', $this->serviceRequest->id),
            'status' => $this->status,
            'type' => 'status_update'
        ];
    }
}
