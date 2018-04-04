<?php

namespace App\Exports;

use App\Emotion;
use App\Record;
use App\Style;
use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DataExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{
    use Exportable;

    private $fileName = 'data.xlsx';
    public $sheet = [];

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            BeforeSheet::class => function(BeforeSheet $event) {
                $event->getSheet()->fromArray($this->sheet, null, 'data.xlsx', true);
            },
        ];
    }

    public function map($user): array
    {
        $styles = Style::all();
        $emotions = Emotion::all();
        $this->sheet = [
            $user->username,
        ];
        foreach($styles as $style) {
            foreach($emotions as $emotion) {
                array_push($this->sheet, $user->computeScore($style->name, $emotion->name));
            }
        }
        return $this->sheet;
    }

    public function headings(): array
    {
        return [
            'User',
            'Abbr_Pos',
            'Abbr_Neu',
            'Abbr_Neg',
            'Gramm_Pos',
            'Gramm_Neu',
            'Gramm_Neg',
            'Emoji_Pos',
            'Emoji_Neu',
            'Emoji_Neg',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function collection()
    {
        return User::all();
    }
}
