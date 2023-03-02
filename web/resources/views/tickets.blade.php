<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <livewire:tickets-table-view />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="OpenPopUpDelete" class="modal fade p-0">
        <div class="modal-dialog modal-login" style="max-width: 720px;">
            <div class="modal-content">
                <div class="rounded-lg shadow-lg">
                    <div class="bg-white mb-2 mt-6">
                        <div class="bg-white">
                            <div class="mt-4 text-center mx-4">
                                <h4 class="uppercase" id="title-delete">¿Seguro quiere eliminar el ticket ?</h4>
                            </div>
                        </div>
                        <div class="px-4 pt-3 pb-2 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" data-dismiss="modal" aria-label="Close" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                            <button onclick="deleteTicket()" class="btn btn-danger" id="btn-delete">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    let idDelete = 0;
    function deleteTicket() {
        $.ajax({
            type: "POST",
            url: `/tickets/delete/${idDelete}`,
            success: function(datos){
                window.location.href = '/tickets';
            },
            error: function(err) {
                console.log(err)
            }
        })
    }

    function cargarDatosDelete(id) {
        $('#title-delete').text(`¿Seguro quiere eliminar el ticket #${id}?`);
        idDelete = id;
    }

    function verTicket(id) {
        window.location.href = `/ticket/ver/${id}`;
    }
</script>
