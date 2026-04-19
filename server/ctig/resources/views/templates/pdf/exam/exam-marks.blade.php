<table class="table border center" style="small">
    <thead>
        <tr class="border">
            <th class="border">
                ФИО
            </th>
            <th class="border">
                Паспортные данные
            </th>
            @for($i = 1; $i <= $exam->type->tasks_count; $i++)
                <th class="border">
                    {{ $i }}
                </th>
            @endfor
        </tr>
       
    </thead>
    <tbody>
    
    @foreach ($exam->foreignNationals as $f)
        <td class="border">{{ $f->full_name }}</td>
        <td class="border">{{ $f->full_passport }}</td>
        @if ($f->attempts->isNotEmpty())
            @foreach ($f->attempts as $a)
                @foreach ($a->answers as $answer)
                    <td class="border">{{ $answer->mark}}</td>
                @endforeach
            @endforeach
        @else
            @for($i = 1; $i <= $exam->type->tasks_count; $i++)
                <td class="border">
                   {{ '-' }}
                </td>
            @endfor
        @endif
        
    @endforeach
    </tbody>
</table>
