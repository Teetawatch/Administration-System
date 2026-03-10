<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>เอกสารรับ-ส่ง</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm; /* กำหนดขอบกระดาษหน้าละ 1.5 ซม. */
        }
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
            font-family: "THSarabunNew";
            font-size: 16pt;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            table-layout: fixed; /* จำกัดขนาดตารางไม่ให้ล้นหน้ากระดาษ */
        }
        th, td {
            border: 1px solid #333;
            padding: 4px 4px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word; /* ให้ขึ้นบรรทัดใหม่เมื่อข้อความยาวเกินไป */
            word-break: break-all;
        }
        th {
            background-color: #dfdff8; /* Light blue header */
            text-align: center;
            font-weight: bold;
            font-size: 15pt;
        }
        td {
            font-size: 14pt;
        }
        
        /* กำหนดความกว้างของคอลัมน์เพื่อความสมดุล */
        th:nth-child(1) { width: 18%; } /* เลขที่หนังสือ */
        th:nth-child(2) { width: 15%; } /* จาก */
        th:nth-child(3) { width: 15%; } /* ถึง */
        th:nth-child(4) { width: 32%; } /* ชื่อเรื่อง */
        th:nth-child(5) { width: 20%; } /* ผู้รับ/วัน/เดือน/ปี */

        .header {
            text-align: center;
            margin-bottom: 20px;
            width: 100%;
        }
        .header h2 {
            margin: 0;
            font-size: 22pt;
            font-weight: bold;
        }
        .header h3 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
        tr { page-break-inside: avoid; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>รับ-ส่ง เอกสาร</h2>
        <h3>เอกสาร และ ไปรษณีย์ กสน.สบ.ทร.</h3>
         <h3>รร.พธ.พธ.ทร. ถึง พธ.ทร. วันที่ {{ $documents->count() > 0 ? $documents->first()->created_at->locale('th')->addYears(543)->translatedFormat('j F Y') : now()->locale('th')->addYears(543)->translatedFormat('j F Y') }} </h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>เลขที่หนังสือ</th>
                <th>จาก</th>
                <th>ถึง</th>
                <th>ชื่อเรื่อง</th>
                <th>ผู้รับ/วัน/เดือน/ปี</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
            <tr>
                <td>กห ๐๕๒๘.๑๑/{{ str_replace(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'], $doc->document_number) }}</td>
                <td>{{ $doc->department ?? '-' }}</td>
                <td>{{ $doc->to_recipient }}</td>
                <td>{{ $doc->subject }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
