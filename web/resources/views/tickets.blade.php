<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:tickets-table-view />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function deleteTicket(id) {
        $.ajax({
            type: "POST",
            url: `/tickets/delete/${id}`,
            success: function(datos){
                window.location.href = '/tickets';
            },
            error: function(err) {
                console.log(err)
            }
        })
    }

    function verTicket(id) {

    }
</script>
