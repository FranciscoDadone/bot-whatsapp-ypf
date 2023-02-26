<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <div style='padding-left: 1em; padding-right: 1em; font-size: 1rem; display: inline; background-color: {{ $color_ticket }}; text-align: center; border-radius: 1em; padding-top: 0.5em; padding-bottom: 0.4em;'>{{ $ticket->status }}</div>
            Ticket #{{$ticket->id}}
        </h2>
        <h2 style="margin-top: 0.4em; margin-left: 0.4em; color: gray;" class="font-semibold text-sm text-gray-800 dark:text-gray-200 leading-tight">
            {{ $ticket->from()->value('name') }} {{ $ticket->from()->value('surname') }} ( {{ $ticket->from()->value('number') }} )
        </h2>
    </x-slot>

    <div style="display: flex; height: 100%;">
        <div style="width: 20%;" class="py-2 px-2 my-2 mx-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            adsa
        </div>
        <div class="py-2 bg-gray-400" style="width: 80%; height: 100%">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="height: 100%">
                <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight py-3">
                    Mensajes
                </h1>
                @foreach ($ticket->messages() as $message)
                <div class="my-1 p-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    {{ $message->message }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
<script>

</script>
