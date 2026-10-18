<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifPesertaUploadJurnal extends Notification
{
    use Queueable;

    protected $jurnal;
    protected $peserta;

    public function __construct($jurnal, $peserta)
    {
        $this->jurnal = $jurnal;
        $this->peserta = $peserta;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Mengambil nama peserta (sesuaikan jika kolom di tabel users bernama 'nama' atau 'name')
        $namaPeserta = $this->peserta->name ?? $this->peserta->nama;

        return [
            'type'       => 'upload_jurnal',
            'title'      => 'Jurnal Baru Diunggah',
            'message'    => "{$namaPeserta} telah mengunggah jurnal: '{$this->jurnal->kegiatan}'.",
            'jurnal_id'  => $this->jurnal->id,
            'peserta_id' => $this->peserta->id,
        ];
    }
}