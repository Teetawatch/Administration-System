<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>รายงานการเข้าร่วมกิจกรรม</title>
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #003366;
            color: #fff;
            padding: 8px 12px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 18pt;
        }
        .activity-header {
            background-color: #e8e8e8;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #000;
            margin-top: 15px;
        }
        .text-center { text-align: center; }
        .no-participant { color: #666; font-style: italic; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h2>รายงานการเข้าร่วมกิจกรรม</h2>
        <p>ข้อมูล ณ วันที่ {{ now()->addYears(543)->format('d/m/Y') }}</p>
    </div>

    {{-- Section 1: Activities with Participants --}}
    <div class="section-title">รายชื่อผู้เข้าร่วมกิจกรรม</div>
    
    @forelse($activities as $activity)
        <div class="activity-header">
            {{ $activity->activity_name }}
            @if($activity->start_date)
                ({{ $activity->start_date->locale('th')->translatedFormat('j M Y') }})
            @endif
            @if($activity->location)
                - {{ $activity->location }}
            @endif
        </div>
        
        @if($activity->participants->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">ลำดับ</th>
                        <th style="width: 35%;">ชื่อ-นามสกุล</th>
                        <th style="width: 30%;">ตำแหน่ง</th>
                        <th style="width: 25%;">ฝ่าย/แผนก</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activity->participants as $participant)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $participant->rank }} {{ $participant->first_name }} {{ $participant->last_name }}</td>
                        <td>{{ $participant->position }}</td>
                        <td>{{ $participant->department ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-participant">ไม่มีผู้เข้าร่วมกิจกรรมนี้</p>
        @endif
    @empty
        <p>ไม่พบข้อมูลกิจกรรม</p>
    @endforelse

    {{-- Page break before section 2 --}}
    <div class="page-break"></div>

    {{-- Section 2: Personnel without any activity --}}
    <div class="section-title">รายชื่อบุคลากรที่ยังไม่ได้เข้าร่วมกิจกรรมใดๆ</div>
    
    @if($personnelWithoutActivity->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">ลำดับ</th>
                    <th style="width: 35%;">ชื่อ-นามสกุล</th>
                    <th style="width: 30%;">ตำแหน่ง</th>
                    <th style="width: 25%;">ฝ่าย/แผนก</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personnelWithoutActivity as $person)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $person->rank }} {{ $person->first_name }} {{ $person->last_name }}</td>
                    <td>{{ $person->position }}</td>
                    <td>{{ $person->department ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #28a745; font-weight: bold;">🎉 บุคลากรทุกคนได้เข้าร่วมกิจกรรมแล้ว</p>
    @endif

    <div style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 15px;">
        <p><strong>สรุป:</strong></p>
        <ul>
            <li>จำนวนกิจกรรมทั้งหมด: {{ $activities->count() }} กิจกรรม</li>
            <li>จำนวนบุคลากรที่ยังไม่ได้เข้าร่วม: {{ $personnelWithoutActivity->count() }} คน</li>
        </ul>
    </div>
</body>
</html>
