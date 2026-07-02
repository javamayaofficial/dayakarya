@extends('layouts.app')
@section('title', 'Transfer Manual — Dayakarya')
@section('body_class', 'page-support')
@section('content')
@php
    /** @var \App\Models\Payment $payment */
    $bankName = \App\Support\IntegrationSettings::get('payment.manual.bank_name', config('dayakarya.manual_payment.bank_name'));
    $bankAccount = \App\Support\IntegrationSettings::get('payment.manual.account', config('dayakarya.manual_payment.account'));
    $bankHolder = \App\Support\IntegrationSettings::get('payment.manual.holder', config('dayakarya.manual_payment.holder'));
    $supportNumber = preg_replace('/\D+/', '', \App\Support\IntegrationSettings::get('support.whatsapp_number', config('dayakarya.support.whatsapp_number')));
    $qrisImage = \App\Support\IntegrationSettings::get('payment.manual.qris_image_url', config('dayakarya.manual_payment.qris_image'));
    $existingProofUrl = $payment->proof ? \Illuminate\Support\Facades\Storage::disk('public')->url($payment->proof) : null;
    $prefilledMessage = rawurlencode(
        'Halo admin, saya sudah transfer top up Credit Dayakarya. '
        . 'Order ID: ' . $payment->order_id
        . ', nominal: Rp' . number_format($payment->amount_rupiah, 0, ',', '.')
        . '.'
    );
@endphp
<section class="section">
    <div class="container support-container support-container-narrow">
        <div class="support-panel card">
            <span class="section-kicker">Transfer Manual</span>
            <h1 class="support-title">Selesaikan pembayaran dengan langkah yang jelas.</h1>
            <div class="alert alert-success">Transfer ke rekening berikut, lalu kirim bukti pembayaran. Admin akan memverifikasi credit Anda.</div>
            <div class="bank-card">
                <div><strong>Order ID</strong><span>{{ $payment->order_id }}</span></div>
                <div><strong>Total Transfer</strong><span>Rp{{ number_format($payment->amount_rupiah, 0, ',', '.') }}</span></div>
                <div><strong>Credit</strong><span>{{ number_format($payment->credit_amount, 0, ',', '.') }} Credit</span></div>
            </div>
            <div class="bank-card">
                <div><strong>Bank</strong><span>{{ $bankName }}</span></div>
                <div><strong>No. Rekening</strong><span>{{ $bankAccount }}</span></div>
                <div><strong>Atas Nama</strong><span>{{ $bankHolder }}</span></div>
            </div>
            @if ($qrisImage)
                <div class="bank-card">
                    <div><strong>QRIS</strong><span>Gunakan QRIS berikut jika Anda membayar via scan.</span></div>
                    <div><a href="{{ $qrisImage }}" target="_blank" rel="noopener">Buka gambar QRIS</a></div>
                </div>
            @endif
            <div class="support-copy">Sertakan order ID agar verifikasi lebih cepat.</div>
            <div class="proof-panel bank-card">
                <div>
                    <strong>Unggah Bukti Transfer</strong>
                    <span>Upload screenshot atau foto bukti transfer untuk verifikasi admin.</span>
                </div>
                @if ($payment->status === 'paid')
                    <div class="alert alert-success">Pembayaran sudah diverifikasi. Credit Anda sudah masuk ke wallet.</div>
                @elseif ($payment->status === 'failed')
                    <div class="alert alert-error">Pembayaran ini ditandai gagal. Jika Anda sudah transfer, hubungi admin dengan order ID.</div>
                @else
                    <div id="proof-feedback" class="alert {{ $existingProofUrl ? 'alert-success' : 'alert-error' }} {{ $existingProofUrl ? '' : 'is-hidden' }}">
                        {{ $existingProofUrl ? 'Bukti transfer sudah terunggah. Anda masih bisa menggantinya selama status menunggu verifikasi.' : '' }}
                    </div>
                    <form id="proof-upload-form" class="proof-upload-form">
                        <label class="proof-upload-field" for="proof-file">
                            <span>Pilih file bukti transfer</span>
                            <input id="proof-file" name="proof" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" required>
                        </label>
                        <button type="submit" class="btn btn-dark" id="proof-submit">Upload Bukti</button>
                    </form>
                    <div class="support-copy">Format: JPG, PNG, atau WEBP. Maksimal 4 MB.</div>
                @endif
                <div id="proof-preview-wrap" class="proof-preview-wrap {{ $existingProofUrl ? '' : 'is-hidden' }}">
                    <img id="proof-preview" src="{{ $existingProofUrl }}" alt="Preview bukti transfer" class="proof-preview-image">
                </div>
            </div>
            <a href="https://wa.me/{{ $supportNumber }}?text={{ $prefilledMessage }}" class="btn btn-wa btn-block btn-gap-top">Kirim Bukti via WhatsApp</a>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('#proof-upload-form');
  if (!form) return;

  const fileInput = document.querySelector('#proof-file');
  const submitButton = document.querySelector('#proof-submit');
  const feedback = document.querySelector('#proof-feedback');
  const previewWrap = document.querySelector('#proof-preview-wrap');
  const preview = document.querySelector('#proof-preview');

  const setFeedback = (message, tone = 'success') => {
    feedback.textContent = message;
    feedback.classList.remove('is-hidden', 'alert-success', 'alert-error');
    feedback.classList.add(tone === 'success' ? 'alert-success' : 'alert-error');
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const token = localStorage.getItem('dk_token');
    if (!token) {
      setFeedback('Masuk dulu dengan akun pengguna Anda, lalu ulangi upload bukti transfer.', 'error');
      return;
    }

    if (!fileInput.files.length) {
      setFeedback('Pilih file bukti transfer terlebih dahulu.', 'error');
      return;
    }

    const formData = new FormData();
    formData.append('proof', fileInput.files[0]);

    submitButton.disabled = true;
    submitButton.textContent = 'Mengunggah...';

    try {
      const response = await fetch('/api/v1/payments/{{ $payment->id }}/proof', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token,
        },
        body: formData,
      });

      const data = await response.json();
      if (!response.ok) {
        setFeedback(data.message || 'Upload bukti transfer belum berhasil. Silakan coba lagi.', 'error');
        return;
      }

      if (data.proof_url) {
        preview.src = data.proof_url;
        previewWrap.classList.remove('is-hidden');
      }

      fileInput.value = '';
      setFeedback(data.message || 'Bukti transfer berhasil diunggah.');
    } catch (_) {
      setFeedback('Koneksi terputus saat upload bukti transfer. Silakan coba lagi.', 'error');
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = 'Upload Bukti';
    }
  });
});
</script>
@endpush
