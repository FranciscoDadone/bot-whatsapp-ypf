<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Números') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <button type="button" data-toggle="modal" data-target="#OpenPopUpNew" id="buttonNew" class="focus:outline-none text-white text-sm py-2 px-4 rounded-sm bg-blue-400 hover:bg-blue-500 hover:shadow-lg hover:no-underline" style="border: 1px solid #3b82f6; background-color: #3b82f6;">
                        Nuevo
                    </button>
                    <livewire:numeros-table-view />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal New -->
<div id="OpenPopUpNew" class="modal fade p-0">
    <div class="modal-dialog modal-login" style="max-width: 720px;">
        <div class="modal-content">
            <div class="rounded-lg shadow-lg">
                <div class="bg-white mb-2 mt-6">
                    <form action="{{ route('configuracion.numeros.new') }}" method="POST">
                    @csrf
                        <div class="bg-white">
                            <div class="mt-4 text-center mx-4">
                                <h4 class="uppercase">Nuevo Número</h4>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="form-group m-0">
                                <x-input-label for="nombre" :value="__('Nombre')" />
                                <x-text-input id="nombre" class="mt-1" type="text" name="nombre" />
                            </div>
                            <div class="text-center">
                                <x-input-label for="apellido" :value="__('Apellido')" />
                                <x-text-input id="apellido" class="mt-1" type="text" name="apellido" />
                            </div>
                            <div class="text-center">
                                <x-input-label for="estacion" :value="__('Estación')" />
                                <x-text-input id="estacion" class="mt-1" type="text" name="estacion" />
                            </div>
                            <div class="text-center">
                                <x-input-label for="numero" :value="__('Número')" />
                                <x-text-input id="numero" class="mt-1" type="text" name="numero" required />
                            </div>
                            <div class="text-center">
                                <x-input-label for="descripcion" :value="__('Descripción')" />
                                <textarea id="descripcion" name="descripcion" rows="4" cols="60"></textarea>
                            </div>
                        </div>
                        <div class="px-4 pt-3 pb-2 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" data-dismiss="modal" aria-label="Close" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                            <button type="submit" id="botonSubmit_nuevo" style="background-color: #48bb78;" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-green-500 text-base font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm hover:no-underline">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
<script>
    function deleteNum(id) {
                $.ajax({
                    type: "POST",
                    url: `/configuracion/numeros/delete/${id}`,
                    success:function(datos){
                        window.location.href = '/configuracion/numeros';
                    },
                    error: function(err) {
                        console.log(err)
                    }
                })
            }
</script>
