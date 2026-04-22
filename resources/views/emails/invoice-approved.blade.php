<div style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px;">
    @php
        $logoPath = public_path('assets/images/logoukm.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
        $adminNumber = preg_replace('/[^0-9]/', '', $adminWhatsApp ?? env('CONTACT_CP_BAND', ''));
        $confirmText = rawurlencode('Halo admin, saya telah melakukan pembayaran untuk pesanan band ' . $rental->kode_order . '. Berikut bukti transfer saya.');
    @endphp
    <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); padding: 30px; text-align: center; color: white;">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo UKM" style="width:54px;height:54px;object-fit:contain;margin:0 auto 12px;display:block;">
            @endif
            <h1 style="margin: 0; font-size: 28px; font-weight: bold;">✅ Permintaan Disetujui!</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Pesawaran Timur Student Orchestra</p>
        </div>

        <!-- Main Content -->
        <div style="padding: 30px;">
            <p style="margin: 0 0 20px 0; font-size: 14px; color: #666;">
                Halo <strong>{{ $rental->renter_name }}</strong>,
            </p>

            <p style="margin: 0 0 20px 0; font-size: 14px; color: #333; line-height: 1.6;">
                🎉 Kabar baik! Permintaan penyewaan band Anda telah <strong>DISETUJUI</strong> oleh admin kami. 
                Invoice dan detail penyewaan sudah kami siapkan dan terlampir dalam email ini.
            </p>

            <!-- Detail Box -->
            <div style="background-color: #f3f4f6; border-left: 4px solid #fbbf24; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 16px;">📋 Detail Penyewaan</h3>
                
                <table style="width: 100%; font-size: 13px; color: #555;">
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold; width: 40%;">Band:</td>
                        <td style="padding: 8px 0;">{{ $rental->band->band_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Tanggal Acara:</td>
                        <td style="padding: 8px 0;">{{ $rental->performance_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Waktu:</td>
                        <td style="padding: 8px 0;">{{ $rental->performance_start_time }} - {{ $rental->performance_end_time }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Lokasi/Alamat:</td>
                        <td style="padding: 8px 0;">{{ $rental->venue_address }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Durasi Main:</td>
                        <td style="padding: 8px 0;">{{ $rental->performance_duration_hours }} jam {{ $rental->performance_duration_minutes }} menit</td>
                    </tr>
                    <tr style="border-top: 2px solid #d1d5db;">
                        <td style="padding: 12px 0; font-weight: bold; color: #059669; font-size: 14px;">Harga Final:</td>
                        <td style="padding: 12px 0; font-weight: bold; color: #059669; font-size: 14px;">Rp {{ number_format($rental->harga_final, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; font-weight: bold;">Kode Pesanan:</td>
                        <td style="padding: 8px 0; background-color: #fef3c7; padding: 8px 12px; border-radius: 4px; font-weight: bold; color: #b45309;">{{ $rental->kode_order }}</td>
                    </tr>
                </table>
            </div>

            <!-- Payment Instructions -->
            <div style="background-color: #dcfce7; border-left: 4px solid #22c55e; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h3 style="margin: 0 0 12px 0; color: #15803d; font-size: 16px;">💰 Instruksi Pembayaran</h3>
                <p style="margin: 0 0 10px 0; font-size: 13px; color: #166534; line-height: 1.6;">
                    Silakan transfer ke rekening UKM berikut:
                </p>
                
                <div style="background-color: white; border: 2px solid #22c55e; padding: 12px; border-radius: 6px; font-size: 14px; margin: 10px 0; text-align: center;">
                    <p style="margin: 0 0 5px 0; font-size: 12px; color: #666;">Nomor Rekening (BCA):</p>
                    <p style="margin: 0; font-weight: bold; font-size: 16px; color: #22c55e; letter-spacing: 2px;">{{ env('UKM_BANK_ACCOUNT', '2141375549') }}</p>
                </div>

                <p style="margin: 10px 0 0 0; font-size: 12px; color: #166534;">
                    ⏰ Pastikan pembayaran dilakukan <strong>sebelum tanggal acara</strong>
                </p>
            </div>

            <!-- Confirmation Instructions -->
            <div style="background-color: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h3 style="margin: 0 0 12px 0; color: #1e40af; font-size: 16px;">📱 Konfirmasi Pembayaran</h3>
                <p style="margin: 0 0 10px 0; font-size: 13px; color: #1e40af; line-height: 1.6;">
                    Setelah Anda melakukan transfer, silakan hubungi admin kami via WhatsApp untuk konfirmasi:
                </p>
                <a href="https://wa.me/{{ $adminNumber }}?text={{ $confirmText }}" 
                   style="display: inline-block; background-color: #10b981; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 10px; font-size: 13px;">
                    💬 Konfirmasi Pembayaran via WhatsApp
                </a>
            </div>

            <!-- Footer Note -->
            <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 20px;">
                <p style="margin: 0 0 10px 0; font-size: 12px; color: #999;">
                    📎 Invoice PDF terlampir dalam email ini
                </p>
                <p style="margin: 0; font-size: 12px; color: #999; line-height: 1.5;">
                    Jika ada pertanyaan atau butuh informasi lebih lanjut, jangan ragu untuk menghubungi kami melalui WhatsApp di atas atau langsung ke email ini.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0;">© 2026 Pesawaran Timur Student Orchestra</p>
            <p style="margin: 5px 0 0 0;">Semua hak dilindungi</p>
        </div>
    </div>
</div>
