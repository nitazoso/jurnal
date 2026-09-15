<?php

namespace App\Notifications;

use App\Models\Dispen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DispenDiajukanNotification extends Notification
{
    use Queueable;

    public function __construct(public Dispen $dispen) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $phone = preg_replace('/\D+/', '', (string) $notifiable->no_wa);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $approvalUrl = route('kesiswaan.dispen.show', $this->dispen);

        return [
            'title' => 'Pengajuan dispen baru',
            'message' => 'Pengajuan dispen '.$this->dispen->siswa->nama_siswa.' menunggu persetujuan.',
            'url' => $approvalUrl,
            'whatsapp_url' => $phone
                ? 'https://wa.me/'.$phone.'?text='.urlencode('Pengajuan dispen menunggu persetujuan: '.$approvalUrl)
                : null,
            'dispen_id' => $this->dispen->id_dispen,
        ];
    }
}
