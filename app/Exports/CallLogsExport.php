<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CallLogsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    protected $callLogs;

    public function __construct($callLogs)
    {
        $this->callLogs = $callLogs;
    }

    public function collection()
    {
        return $this->callLogs;
    }

    public function headings(): array
    {
        return [
            'Job ID',
            'Job Card',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Fault Description',
            'ZIMRA Reference',
            'Date Booked',
            'Date Resolved',
            'Time Start',
            'Time Finish',
            'Job Type',
            'Status',
            'Billed Hours',
            'Amount Charged',
            'Assigned Technician',
            'Approved By',
            'Engineer Comments',
            'Created At'
        ];
    }

    public function map($callLog): array
    {
        return [
            $callLog->id,
            $callLog->job_card ?? 'N/A',
            $callLog->customer_name,
            $callLog->customer_email ?? 'N/A',
            $callLog->customer_phone ?? 'N/A',
            $callLog->fault_description,
            $callLog->zimra_ref ?? 'N/A',
            $callLog->date_booked ? $callLog->date_booked->format('Y-m-d') : 'N/A',
            $callLog->date_resolved ? $callLog->date_resolved->format('Y-m-d') : 'N/A',
            $callLog->time_start ? $callLog->time_start->format('H:i') : 'N/A',
            $callLog->time_finish ? $callLog->time_finish->format('H:i') : 'N/A',
            ucfirst($callLog->type ?? 'N/A'),
            ucfirst($callLog->status ?? 'N/A'),
            $callLog->billed_hours ?? 0,
            $callLog->amount_charged ?? 0,
            $callLog->assignedTo->name ?? 'Unassigned',
            $callLog->approver->name ?? 'N/A',
            $callLog->engineer_comments ?? 'N/A',
            $callLog->created_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E8']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10, 'B' => 15, 'C' => 20, 'D' => 25, 'E' => 15,
            'F' => 30, 'G' => 15, 'H' => 12, 'I' => 12, 'J' => 10,
            'K' => 10, 'L' => 12, 'M' => 12, 'N' => 12, 'O' => 15,
            'P' => 20, 'Q' => 20, 'R' => 30, 'S' => 18
        ];
    }
}
