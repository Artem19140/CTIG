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
    
    @foreach ($markTable['rows'] as $row)
    <tr>
        <td class="border">{{ $row['fullName'] }}</td>
        <td class="border">{{ $row['fullPassport'] }}</td>
        @if ($row['answers'])
            @foreach ( $row['answers'] as $answer)
                <td class="border">{{ $answer->mark }}</td>
            @endforeach                
        @endif
       @if ($row['answers'] === null)
            @for($i = 1; $i <= $exam->type->tasks_count; $i++)
                <td class="border">
                </td>
            @endfor
       @endif
    @endforeach
    </tbody>
</table>
