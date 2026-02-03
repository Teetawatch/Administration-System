<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>คำสั่งโรงเรียน - {{ $schoolOrder->order_number }}</title>
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
        .header {
            margin-bottom: 30px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
            float: right;
            width: 8cm;
        }
    </style>
</head>
<body>
    <div class="text-center">
        <img src="{{ public_path('images/garuda.png') }}" class="garuda" alt="Garuda">
        <div class="font-bold" style="font-size: 20pt;">คำสั่งโรงเรียนพลาธิการ กรมพลาธิการทหารเรือ</div>
        <div class="font-bold" style="font-size: 16pt;">ที่ {{ $schoolOrder->order_number }}</div>
        <div class="font-bold" style="font-size: 16pt;">เรื่อง {{ $schoolOrder->subject }}</div>
    </div>
    
    <div class="text-center" style="margin-top: 10px; margin-bottom: 10px;">
        ------------------------------------------
    </div>

    <div class="content">
        {!! nl2br(e($schoolOrder->content)) !!}
    </div>

    <div class="content" style="margin-top: 20px;">
        ทั้งนี้ ตั้งแต่วันที่ {{ \Carbon\Carbon::parse($schoolOrder->effective_date ?? $schoolOrder->order_date)->locale('th')->isoFormat('D MMMM PPPP') }} เป็นต้นไป
    </div>

    <div class="text-center" style="margin-top: 20px;">
        สั่ง ณ วันที่ {{ \Carbon\Carbon::parse($schoolOrder->order_date)->locale('th')->isoFormat('D MMMM PPPP') }}
    </div>

    <div class="signature">
        <br><br>
        (....................................................)<br>
        ผู้บัญชาการโรงเรียนพลาธิการ กรมพลาธิการทหารเรือ
    </div>
</body>
</html>
