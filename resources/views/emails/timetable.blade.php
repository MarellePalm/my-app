<x-mail::message>
# Tunniplaan

Periood: {{ $startDate->translatedFormat('d.m.Y') }} - {{ $endDate->translatedFormat('d.m.Y') }}

@foreach ($timetableEvents as $dayName => $events)
## {{ ucfirst($dayName) }}

@foreach ($events as $event)
**{{ $event['timeStart'] }} - {{ $event['timeEnd'] }}**  
{{ $event['nameEt'] }}  
Ruum: {{ data_get($event, 'rooms.0.roomCode', '-') }}

@endforeach

@endforeach

</x-mail::message>