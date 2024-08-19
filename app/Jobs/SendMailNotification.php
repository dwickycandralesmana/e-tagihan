<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(public $dataOrder)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mail_host       = getSetting('mail_host');
        $mail_port       = getSetting('mail_port');
        $mail_encryption = getSetting('mail_encryption');
        $mail_username   = getSetting('mail_username');
        $mail_password   = getSetting('mail_password');
        $mail_from       = getSetting('mail_from_address');
        $mail_from_name  = getSetting('mail_from_name');

        config([
            'mail.mailers.smtp.host'       => $mail_host,
            'mail.mailers.smtp.port'       => $mail_port,
            'mail.mailers.smtp.encryption' => $mail_encryption,
            'mail.mailers.smtp.username'   => $mail_username,
            'mail.mailers.smtp.password'   => $mail_password,
            'mail.from.address'            => $mail_from,
        ]);

        $order     = $this->dataOrder;
        $email     = $order->email;
        $brandLogo = asset('assets/img/' . getSetting('brand_logo'));

        $pdf         = Pdf::loadView('pdf.receipt', compact('order', 'brandLogo'));
        $content     = $pdf->output();
        $name        = 'Bukti Pembayaran_' . date('YmdHis') . '_' . $order->code . '_' . $order->email . '.pdf';
        $path        = public_path('/receipt/' . $name);
        file_put_contents($path, $content);

        Mail::send('emails.payment_success', compact('order'), function ($message) use ($email, $order, $path, $mail_from_name, $mail_from) {
            $message->from($mail_from, $mail_from_name);
            $message->to($email);
            $message->subject('Pembayaran Berhasil - Order #' . $order->code);

            $message->attach($path, [
                'as'   => 'Bukti Pembayaran_' . $order->code . '.pdf',
                'mime' => 'application/pdf',
            ]);
        });
    }
}
