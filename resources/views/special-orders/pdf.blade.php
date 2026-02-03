<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>คำสั่งโรงเรียน (เฉพาะ) - {{ $specialOrder->order_number }}</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ storage_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }
        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 16pt;
            line-height: 1.5;
            padding-top: 2cm;
            padding-left: 3cm;
            padding-right: 2cm;
            padding-bottom: 2cm;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .garuda { width: 3cm; height: auto; margin-bottom: 10px; }
        .content {
            margin-top: 20px;
            text-align: justify;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
            float: right;
            width: 8cm;
        }
        .classification-header, .classification-footer {
            color: red;
            font-weight: bold;
            font-size: 18pt;
            text-align: center;
            width: 100%;
        }
        .classification-header {
            position: absolute;
            top: 1cm;
            left: 0;
        }
        .classification-footer {
            position: absolute;
            bottom: 1cm;
            left: 0;
        }
    </style>
</head>
<body>
    @if($specialOrder->classification)
        <div class="classification-header">{{ $specialOrder->classification }}</div>
        <div class="classification-footer">{{ $specialOrder->classification }}</div>
    @endif

    <div class="text-center">
        <img src="{{ public_path('images/garuda.png') }}" class="garuda" alt="Garuda">
        <div class="font-bold" style="font-size: 20pt;">คำสั่งโรงเรียนพลาธิการ กรมพลาธิการทหารเรือ (เฉพาะ)</div>
        <div class="font-bold" style="font-size: 16pt;">ที่ {{ $specialOrder->order_number }}</div>
        <div class="font-bold" style="font-size: 16pt;">เรื่อง {{ $specialOrder->subject }}</div>
    </div>
    
    <div class="text-center" style="margin-top: 10px; margin-bottom: 10px;">
        ------------------------------------------
    </div>

    <div class="content">
        {!! nl2br(e($specialOrder->content)) !!}
    </div>

    <div class="content" style="margin-top: 20px;">
        ทั้งนี้ ตั้งแต่วันที่ {{ \Carbon\Carbon::parse($specialOrder->effective_date ?? $specialOrder->order_date)->locale('th')->isoFormat('D MMMM PPPP') }} เป็นต้นไป
    </div>

    <div class="text-center" style="margin-top: 20px;">
        สั่ง ณ วันที่ {{ \Carbon\Carbon::parse($specialOrder->order_date)->locale('th')->isoFormat('D MMMM PPPP') }}
    </div>

    <div class="signature">
        <br><br>
        (....................................................)<br>
        ผู้บัญชาการโรงเรียนพลาธิการ กรมพลาธิการทหารเรือ
    </div>
</body>
</html>
