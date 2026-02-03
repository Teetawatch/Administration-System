<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>รายชื่อบุคลากร</title>
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
            font-family: "THSarabunNew";
            font-size: 16pt;
            line-height: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th,
        td {
            border: 1px solid #333;
            /* Slightly softer black */
            padding: 6px 8px;
            /* More comfortable padding */
            text-align: left;
            vertical-align: middle;
            /* Center vertically */
            line-height: 1.2;
        }

        th {
            background-color: #dfdff8ff;
            /* Light blue header */
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
        }

        td {
            font-size: 15pt;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 22pt;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 16pt;
        }

        .department-title {
            font-size: 18pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>รายชื่อกำลังพล โรงเรียนพลาธิการ กรมพลาธิการทหารเรือ</h2>
        <p>ข้อมูล ณ วันที่ {{ now()->addYears(543)->format('d/m/Y') }}</p>
    </div>

    @foreach($personnelByDepartment as $department => $personnelList)
        <div class="department-title">{{ $department }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">ลำดับ</th>
                    <th style="width: 25%;">ยศ ชื่อ-นามสกุล</th>
                    <th style="width: 50%;">ตำแหน่ง</th>
                    <th style="width: 15%;">เบอร์โทรศัพท์</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personnelList as $index => $person)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            {{ $person->rank }} {{ $person->first_name }} {{ $person->last_name }}
                        </td>
                        <td>{{ $person->position }}</td>
                        <td class="text-center">{{ $person->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>