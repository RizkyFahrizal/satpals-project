<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Invoice Disetujui</title>
        <style>
                body { margin: 0; padding: 0; background: #f9fafb; font-family: Arial, sans-serif; color: #111827; }
                .container { max-width: 640px; margin: 0 auto; padding: 24px; }
                .header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: #fff; padding: 28px; border-radius: 16px 16px 0 0; text-align: center; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { background: #fff; padding: 28px; border-radius: 0 0 16px 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
                .section { padding: 16px; border-radius: 12px; margin: 20px 0; }
                .payment-box { background: #dcfce7; border-left: 4px solid #22c55e; }
                .payment-info { background: #fff; border: 2px solid #22c55e; padding: 12px; border-radius: 8px; text-align: center; margin-top: 12px; }
                .bank-label { font-size: 12px; color: #6b7280; }
                .bank-value { font-size: 16px; font-weight: bold; color: #15803d; margin: 8px 0; }
                .btn { display: inline-block; background: #10b981; color: #fff; text-decoration: none; padding: 12px 18px; border-radius: 10px; font-weight: bold; }
        </style>
</head>
<body>
        @php
                $bankName = env('UKM_BANK_NAME', 'CIMB Niaga');
                $bankAccount = env('UKM_BANK_ACCOUNT', '2141375549');
                $bankAccountName = env('UKM_BANK_ACCOUNT_NAME', 'ZAHLUL NOER LAILY');
        @endphp
        
        <div class="container">
                <div class="header">
                        <h1>✅ Invoice Disetujui</h1>
                        <p>Satya Palapa</p>
                </div>

                <div class="content">
                        <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">Halo, invoice Anda sudah disetujui.</p>
                        <p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#374151;">PDF invoice terlampir untuk referensi Anda.</p>
                        
                        <div class="section payment-box">
                                <h3 style="margin:0 0 12px;font-size:16px;color:#15803d;">💰 Informasi Pembayaran</h3>
                                <p style="margin:0 0 10px;font-size:13px;color:#166534;">Silakan transfer ke rekening UKM:</p>
                                <div class="payment-info">
                                        <p class="bank-label">Nama Bank</p>
                                        <p class="bank-value">{{ $bankName }}</p>
                                        <p class="bank-label">Nomor Rekening</p>
                                        <p class="bank-value">{{ $bankAccount }}</p>
                                        <p class="bank-label">Atas Nama</p>
                                        <p class="bank-value">{{ $bankAccountName }}</p>
                                </div>
                        </div>

                        <p style="margin:0;font-size:14px;color:#374151;">Terima kasih,<br><strong>Satya Palapa</strong></p>
                </div>
        </div>
</body>
</html>