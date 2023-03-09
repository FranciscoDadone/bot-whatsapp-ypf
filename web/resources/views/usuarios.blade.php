<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Usuarios') }}
        </h2>
    </x-slot>

    @if ( Session::get('success'))
		<div class="alert alert-success">
			{{ Session::get('success') }}
		</div>
	@endif
	@if ( Session::get('error'))
		<div class="alert alert-danger">
		    {{ Session::get('error') }}
		</div>
	@endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <button type="button" data-toggle="modal" data-target="#OpenPopUpNew" id="buttonNew" class="focus:outline-none text-white text-sm py-2 px-4 rounded-sm bg-blue-400 hover:bg-blue-500 hover:shadow-lg hover:no-underline" style="border: 1px solid #3b82f6; background-color: #3b82f6;">
                        Nuevo
                    </button>
                    <livewire:usuarios-table-view />
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
                            <h4 class="uppercase" id="title-delete">¿Seguro quiere eliminar a ?</h4>
                        </div>
                    </div>
                    <div class="px-4 pt-3 pb-2 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" data-dismiss="modal" aria-label="Close" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                        <button onclick="deleteUser()" class="btn btn-danger" id="btn-delete">
                            Eliminar
                        </button>
                    </div>
                </div>
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
                    <form action="{{ route('usuarios.new') }}" method="POST">
                    @csrf
                        <div class="bg-white">
                            <div class="mt-4 text-center mx-4">
                                <h4 class="uppercase">Nuevo Usuario</h4>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="form-group m-0">
                                <x-input-label for="nombre" :value="__('Nombre')" />
                                <input id="nombre" class="mt-1" type="text" name="nombre" required>
                            </div>
                            <div class="text-center">
                                <x-input-label for="apellido" :value="__('Apellido')" />
                                <input id="apellido" class="mt-1" type="text" name="apellido" required>
                            </div>
                            <div class="text-center">
                                <x-input-label for="email" :value="__('Email')" />
                                <input id="email" class="mt-1" type="text" name="email">
                            </div>
                            <div class="text-center">
                                <x-input-label for="numero" :value="__('Número tel.')" />
                                <input id="numero" class="mt-1" type="text" name="numero" required>
                            </div>
                            <div class="text-center">
                                <x-input-label for="password" :value="__('Contraseña')" />
                                <input id="password" class="mt-1" type="password" name="password" required>
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
    let idDelete = 0;
    function deleteUser() {
        $.ajax({
            type: "POST",
            url: `/usuarios/delete/${idDelete}`,
            success:function(datos){
                window.location.href = '/usuarios';
            },
            error: function(err) {
                console.log(err)
            }
        })
    }

    function cargarDatosDelete(id, name) {
        $('#title-delete').text(`¿Seguro quiere eliminar a ${name}?`);
        idDelete = id;
    }
</script>
