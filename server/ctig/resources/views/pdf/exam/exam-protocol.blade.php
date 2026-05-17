@extends('pdf.layouts.base')
@section('title')
    Протокол
@endsection
@push('style')
    
    .section {
        margin-top: 20px;
    }

    .violation-section-content{
        margin-left: 20px;
    }

    .violation-section-title{
        margin-top: 5px;
        margin-bottom: 5px;
    }
@endpush
@section('content')
<div>

    <div class="title text-center bold">ПРОТОКОЛ</div>

    <div class="text-center mt-10 mb-20">
        проведения экзамена для иностранных граждан по русскому языку как иностранному,<br>
        истории России и основам законодательства Российской Федерации<br>
        на уровне, соответствующем цели получения<br>
        <span class="bold">{{ $exam->type->certificate_name }}</span>
    </div>

    <div class="section">
        <span class="bold">Учреждение:</span> {{ $center->name }}
    </div>

    <table class="section">
        <tr>
            <td class="label">Дата проведения экзамена:</td>
            <td class="value">
                <span>
                    {{ $exam->begin_time_local->format('d.m.Y') }}
                </span>
            </td>
        </tr>

        <tr>
            <td>Место проведения (адрес):</td>
            <td>{{ $exam->address_name }}</td>
        </tr>

        <tr>
            <td>Начало экзамена:</td>
            <td>
                <span">
                    {{ $beginTimeReal?->format('H:i') ?? '-'}}
                </span>
            </td>
        </tr>

        <tr>
            <td>Окончание экзамена:</td>
            <td>
                <div">
                    {{ $endTimeReal?->format('H:i') ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section">

        <span class="bold">
            Нарушения / отсутствие нарушений:
        </span>

        <div>
        @if ($exam->protocol_comment)
        <div class="underline violation-section-title">
            Общий комментарий:
        </div>
            <div style="margin-left:20px;">
{!!  nl2br(e($exam->protocol_comment)) !!}
            </div>
        @endif
            <div>
                @if($bannedAttempts->isNotEmpty())
                <div class="underline violation-section-title">
                    Аннулированния:
                </div>
                <div class="violation-section-content">
                    @foreach ( $bannedAttempts as $attempt )
                        <div>
                            Cдающий {{ $attempt->foreignNational->full_name_short }}
                            (паспорт: {{ $attempt->foreignNational->full_passport }}) был снят с экзамена в {{ $attempt->banned_at->format('H:i') }} по причине: 
                            "{{ $attempt->ban_reason }}";
                        </div> 
                    @endforeach
                </div>
                @endif
            </div>

            <div>
                @if($attemptWithViolations->isNotEmpty())
                    <div class="underline violation-section-title">
                        Нарушения:
                    </div>
                    @foreach ( $attemptWithViolations as $attempt )
                        <div class="mt-10 violation-section-content">
                            <div >
                                За сдающим {{ $attempt->foreignNational->full_name_short }}
                                (паспорт: {{ $attempt->foreignNational->full_passport }})
                                зафиксированы нарушения:
                            </div>

                            <ol style="margin-top: 0px;">
                                @foreach ($attempt->violations as $violation)
                                    <li>
                                        {{ $violation->comment }} ( {{ $violation->updated_at->format('H:i') }} );
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div>{{ !$exam->protocol_comment && $bannedAttempts->isEmpty() ?  'Нарушения не установлены' : '' }}</div>
    </div>
    
    

    <div class="section text-center">
        <div class="bold mb-10">
            ПРОТОКОЛ СОСТАВЛЕН
        </div>
        <div >
            @include('pdf.components.date-inline', ['date' => \Carbon\Carbon::now()])
        </div>
    </div>
    <div class="mt-10">
        <div class="mb-10">
            Лица, принимающие экзамен:
        </div>
        
        <div class="mb-20">
            @foreach($exam->examiners as $examiner)
                @include('pdf.components.signature-section', [
                    'date' =>  \Carbon\Carbon::now()->format('d.m.Y'), 
                    'fio' => $examiner->full_name, 
                ])
            @endforeach
        </div>
    </div>
</div>
@endsection