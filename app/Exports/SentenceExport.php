<?php

namespace App\Exports;

use App\Sentence;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SentenceExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    public $sheet = [];
    public $users;
    public $records;
    public $sentences;

    public function __construct()
    {
        $this->users = Cache::get('users');
        $this->records = Cache::get('records');
        $this->sentences = Cache::get('sentences');
    }

    public function map($sentence): array
    {
        $users = $this->users;
        $records = $this->records->whereIn('user_id', $users->pluck('id'));

        $sentenceRecords = $records->where('sentence_id', $sentence->id)->pluck('correct')->toArray();
        $sentenceRecords = array_map(function ($item) {
            return $item ? 1 : 0;
        }, $sentenceRecords);

        if (!empty($sentenceRecords)) {
            $average = array_sum($sentenceRecords) / count($sentenceRecords);
        } else {
            $average = NULL;
        }

        $this->sheet = [
            $sentence->text,
            $average,
            $sentence->value,
            $sentence->style->name,
            $sentence->emotion->name,
            $sentence->emotion_id,
        ];

        foreach($users as $user) {
            $record = $records->where('user_id', $user->id)->where('sentence_id', $sentence->id)->first();

            if ($record) {
                $value = $record->answer;
            }
            else {
                $value = "NA"; // Question not yet answered
            }
            array_push($this->sheet, $value);
        }
        return $this->sheet;
    }

    public function headings(): array
    {
        $users = $this->users;

        $a = ['Sentence', 'Avg_Score', 'Sent_Value', 'Style', 'Emotion', 'Correct_Emotion'];
        foreach ($users as $user) {
            array_push($a, $user->username. '_answer');
        }

        return $a;
    }

    public function collection()
    {
        return $this->sentences;
    }
}
