<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><title>Certificat</title>
<style>body{font-family: DejaVu Sans, sans-serif;text-align:center;color:#1e293b} .box{border:4px solid #059669;padding:40px;margin:30px} h1{color:#047857} .qr{margin-top:20px}</style>
</head><body>
<div class="box">
    <p>{{ $issuer }}</p>
    <h1>{{ $title }}</h1>
    <p>Décerné à</p>
    <h2>{{ $user->name }}</h2>
    <p>{{ $subtitle ?? 'pour avoir complété le parcours FoodLab Academy.' }}</p>
    <p>Type : {{ $certificate->type === 'starter' ? 'Participation Starter' : 'Premium' }}</p>
    <p>Code : {{ $certificate->code }}</p>
    <p>Date : {{ $certificate->issued_at->format('d/m/Y') }}</p>
    <div class="qr">{!! $qrSvg !!}</div>
</div>
</body></html>
