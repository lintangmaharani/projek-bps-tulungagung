<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifAdminVerifikasiJurnal extends Notification
{
    use Queueable;

    protected $jurnal;
    protected $status;
    protected $catatan;

    public function __construct($jurnal, $status, $catatan = null)
    {
        $this->jurnal = $jurnal;
        $this->status = $status;
        $this->catatan = $catatan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type'      => 'verifikasi_jurnal',
            'title'     => 'Status Jurnal Diperbarui',
            'message'   => "Jurnal Anda ('{$this->jurnal->kegiatan}') telah diverifikasi dengan status: {$this->status}.",
            'catatan'   => $this->catatan,
            'jurnal_id' => $this->jurnal->id,
        ];
    }
}