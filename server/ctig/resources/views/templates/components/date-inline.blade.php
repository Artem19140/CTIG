<div>
    <span style="border-bottom:1px solid black;"> 
       « {{ $date->format('d') }} »
    </span> 
    
    <span style="border-bottom:1px solid black; padding-left:20px; padding-right:20px;"> 
        {{ $date->format('m') }} 
    </span> 
    {{ $date->format(' Y г.') }}
</div>