@extends('layouts.admin')

@section('adminContent')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            Administrator Dashboard
                        </div>
                        <div class="col-md-2">
                            <b>Total Participants:</b>&nbsp;{{$participants->count()}}
                        </div>
                        <div class="col-md-2 text-right">
                            {!! Form::open(['route' => ['admin.export.users'], 'method' => 'POST']) !!}
                            <button type="submit" class="btn btn-success"
                                    title="Download user information into an excel spreadsheet">Download
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => ['admin.store'], 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                </div>
                            </div>
                            <br>
                            <table class="table table-bordered">
                                <tr class="fixed">
                                    <th class="fixed-header">User</th>
                                    <th class="fixed-header">Date</th>
                                    <th class="fixed-header">Computer</th>
                                    <th class="fixed-header">Researcher</th>
                                    <th class="fixed-header">Overtime</th>
                                    <th class="fixed-header">Credit Granted</th>
                                    <th class="fixed-header">Comments</th>
                                </tr>
                                @foreach($participants as $participant)
                                    <input type="hidden" value="{{$participant->id}}" name="id[]">
                                    <tr>
                                        <td>
                                            {{$participant->username}}
                                            <br><br>
                                            @if($participant->complete)
                                                <span class="oi oi-circle-check text-success"
                                                      title="User has completed the study"></span>
                                            @else
                                                <span class="oi oi-circle-x text-danger"
                                                      title="User has completed {{round($participant->progress, 2)}}% of the study"></span>
                                            @endif
                                        </td>
                                        <td>
                                            {{Carbon\Carbon::parse($participant->created_at)->format('M d, Y')}}
                                            <br>
                                            {{Carbon\Carbon::parse($participant->created_at)->format('h:i A')}}
                                        </td>
                                        <td>
                                            {!! Form::select('computer[' . $participant->id . ']', ['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'], $participant->computer, ['placeholder' => '', 'class' => 'form-control', 'style' => 'width: 5em;', 'title' => 'Computer']) !!}
                                        </td>
                                        <td>{!! Form::text('researcher_initials[' . $participant->id . ']', $participant->researcher_initials, ['placeholder' => 'Initials', 'class' => 'form-control', 'style' => 'width: 5em;', 'title' => 'Researcher Initials']) !!}</td>
                                        <td>{!! Form::select('overtime[' . $participant->id . ']', [true => 'TRUE', false => 'FALSE'], $participant->overtime, ['placeholder' => '', 'class' => 'form-control', 'title' => 'Overtime']) !!}</td>
                                        <td>{!! Form::select('credit_granted[' . $participant->id . ']', [true => 'TRUE', false => 'FALSE'], $participant->credit_granted, ['placeholder' => '', 'class' => 'form-control', 'title' => 'Credit Given']) !!}</td>
                                        <td>{!! Form::textArea('comments[' . $participant->id . ']', $participant->comments, ['placeholder' => 'Comments...', 'class' => 'form-control', 'rows' => '3', 'style' => 'width: 20em;']) !!}</td>
                                    </tr>
                                @endforeach
                            </table>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection