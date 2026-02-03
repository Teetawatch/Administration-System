<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>หนังสือรับรอง - {{ $certificate->certificate_number }}</title>
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
        .mb-4 { margin-bottom: 20px; }
        .garuda { width: 3cm; height: auto; margin-bottom: 20px; }
        .certificate-number {
            position: absolute;
            top: 2cm;
            left: 3cm;
        }
        .content {
            margin-top: 50px;
            text-indent: 2.5cm;
            text-align: justify;
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
        <div class="font-bold" style="font-size: 24pt;">หนังสือรับรอง</div>
    </div>

    <div class="content">
        หนังสือฉบับนี้ให้ไว้เพื่อรับรองว่า {{ $certificate->personnel_name }}
        @if($certificate->position)
        ตำแหน่ง {{ $certificate->position }}
        @endif
        สังกัด โรงเรียนพลาธิการ กรมพลาธิการทหารเรือ
        
        @if($certificate->content)
        {{ $certificate->content }}
        @else
        เป็นข้าราชการในสังกัดจริง และมีความประพฤติเรียบร้อย
        @endif

        <br><br>
        ขอรับรองว่าข้อความข้างต้นเป็นความจริงทุกประการ
        <br>
        หนังสือฉบับนี้ออกให้ ณ วันที่ {{ \Carbon\Carbon::parse($certificate->issue_date)->locale('th')->isoFormat('D MMMM PPPP') }}
        เพื่อนำไปใช้{{ $certificate->purpose }}
    </div>

    <div class="signature">
        <br><br>
        (....................................................)<br>
        ตำแหน่ง ....................................................<br>
        โรงเรียนพลาธิการ กรมพลาธิการทหารเรือ
    </div>
</body>
</html>
