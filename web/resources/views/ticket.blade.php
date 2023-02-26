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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    ssdfsfdsd
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>

</script>
