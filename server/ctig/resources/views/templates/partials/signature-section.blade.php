<table style="width:100%; border-collapse: collapse; text-align:center;">

    <tr>

        <td class="no-border-td" style="width:25%; border-bottom:1px solid black; padding-bottom:2px;">
            {{ $date }}
        </td>

        <td class="no-border-td" style="width:2%;"></td>


        <td class="no-border-td" style="width:56%; border-bottom:1px solid black; padding-bottom:2px;">
            {{ $fio ?? '' }}
        </td>

        <td class="no-border-td" style="width:2%;"></td>


        <td class="no-border-td" style="width:15%; border-bottom:1px solid black;"></td>
    </tr>


    <tr>
        <td class="no-border-td" style="font-size:10px; padding-top:2px;">
            дата (число, месяц, год)
        </td>

        <td class="no-border-td"></td>

        <td class="no-border-td">
            расшифровка (ФИО полностью)
        </td>

        <td class="no-border-td"></td>

        <td class="no-border-td">
            подпись
        </td>
    </tr>
</table>