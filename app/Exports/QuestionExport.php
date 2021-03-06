<?php

namespace App\Exports;

use App\Demographic;
use App\Record;
use App\Sentence;
use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class QuestionExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison
{
    use Exportable;

    public $sheet = [];
    public $demographics;
    public $sentences;
    public $records;
    public $users;

    public function __construct()
    {
        $this->sentences = Sentence::with('emotion', 'style')->get();
        $this->records = Record::with('sentence')->get();
        $this->users = User::where('admin', false)->with('demographics')->get();
        $this->demographics = Demographic::all();
    }

    public function map($user): array
    {
        $sentences = $this->sentences;
        $records =  $this->records;

        $this->sheet = [
            $user->username,
        ];

        /**
         * Each Sentence Answer an Correctness
         */
        foreach($sentences as $sentence) {
            $record = $records->where('user_id', $user->id)->where('sentence_id', $sentence->id)->first();

            if ($record) {
                if ($record->answer == 0) {
                    $userScore = 9; // Signals that the question timed out
                } else {
                    // Gives value of 1 if correct and 0 if incorrect
                    $userScore = $record->answer == $sentence->emotion_id ? 1 : 0;

                    // Gives their actual answer
                    $answer = $record->answer;
                }
            }
            else {
                $userScore = "NA"; // Question not yet answered
                $answer = "NA";
            }
            array_push($this->sheet, $userScore);
            array_push($this->sheet, $answer);
        }

        /**
         * Demographic Info
         */
        foreach($user->demographics as $demographic) {
            array_push($this->sheet, $demographic->value);
        }

        return $this->sheet;
    }

    public function headings(): array
    {
        $sentences = $this->sentences;

        $a = ['User'];
        foreach($sentences as $index => $sentence) {
            array_push($a, 'Q' . ($index + 1) . '_"' . $sentence->text . '"_CORRECT');
            array_push($a, 'Q' . ($index + 1) . '_"' . $sentence->text . '"_ANSWER');
        }

        foreach($this->demographics as $demographic) {
            array_push($a, $demographic->name);
        }

        return $a;
    }

    public function collection()
    {
        return $this->users;
    }
}
