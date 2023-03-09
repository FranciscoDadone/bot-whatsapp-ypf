<x-app-layout>
<style>
    @media (max-width: 800px) {
        #datos {
            display: none;
        }

        #mensajes {
            width: 100%;
        }
    }

    @media (min-width: 800px) {
        #datos {
            display: block;
            width: 20%
        }

        #mensajes {
            width: 80%;
        }
    }

    .float-container {
        border: 3px solid #fff;
        padding: 20px;
    }

    .float-child {
        width: 50%;
        padding: 20px;
        border: 2px solid red;
    }
</style>
    <x-slot name="header" class="" style="display: table; width:100%">
        <div style="text-align: left; width: 100%; display: table-cell;" class="">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if ($ticket->status != 'CARGANDO')
                <select style='padding-left: 0.6em; padding-right: 1em; font-size: 1rem; display: inline; background-color: {{ $color_ticket }}; text-align: center; border-radius: 1em; padding-top: 0.5em; padding-bottom: 0.4em;' class="form-select" id="select-status">
                    <option @if($ticket->status == 'ABIERTO') selected @endif style="background-color: #47ed73;" value="ABIERTO">Abierto</option>
                    <option @if($ticket->status == 'EN_PROCESO') selected @endif style="background-color: #fcf453;" value="EN_PROCESO">En proceso</option>
                    <option @if($ticket->status == 'CERRADO') selected @endif style="background-color: #b8b8b8;" value="CERRADO">Cerrado</option>
                </select>
                @else
                <h2 style='margin-right: 0.3em; padding-left: 1em; padding-right: 0.6em; font-size: 1rem; display: inline; background-color: {{ $color_ticket }}; text-align: center; border-radius: 1em; padding-top: 0.5em; padding-bottom: 0.4em;'>
                    Cargando
                </h2>
                @endif
                Ticket #{{$ticket->id}}
            </h2>
            <h2 style="margin-top: 0.4em; margin-left: 0.4em; color: gray;" class="font-semibold text-sm text-gray-800  leading-tight">
                {{ $ticket->from()->value('name') }} {{ $ticket->from()->value('surname') }} ( {{ $ticket->from()->value('number') }} )
            </h2>
        </div>
        <div style="display: table-cell; text-align:right;">
        @if (hasPermission(1))
            <button type="button" data-toggle="modal" data-target="#OpenPopUpAssign" class="focus:outline-none text-white text-sm py-2 px-4 rounded-sm bg-blue-400 hover:bg-blue-500 hover:shadow-lg hover:no-underline" style="border: 1px solid #3b82f6; background-color: #3b82f6; display:flex;">
                <i class="fa fa-forward"></i><span style="padding-left: 0.5em;"> Asignar</span>
            </button>
        @endif
        </div>
    </x-slot>

    <div style="display: flex; height: 100%;">
        <div id="datos" class="py-2 px-2 my-2 mx-2 bg-white  overflow-hidden shadow-sm sm:rounded-lg">
            <img src="{{ $ticket->from()->value('profile_pic') }}" style="border-radius: 10em;" />
            <br>
            <hr>
            <br>
            <p class="text-sm">Creación: {{ date_format(date_create($ticket->created_at), 'd/m/Y H:i:s') }}</p>
            <p class="text-sm">Ult. actualización: {{ date_format(date_create($ticket->updated_at), 'd/m/Y H:i:s') }}</p>
        </div>
        <div class="py-2 bg-gray-400" id="mensajes" style="height: 100%; border-radius: 1em;">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="height: 100%">
                <h1 class="font-semibold text-xl text-gray-800  leading-tight py-3">
                    Mensajes
                </h1>
                @foreach ($ticket->messages() as $message)
                <div class="my-1 p-6 text-gray-900  bg-white  overflow-hidden shadow-sm sm:rounded-lg">
                    @if (str_contains($message->message, 'media/'))
                        @if (str_contains($message->message, '.png'))
                        <img src="{{ asset($message->message) }}" style="max-height: 80vh;" />
                        @elseif(str_contains($message->message, '.mp4'))
                        <video src="{{ asset($message->message) }}" type="video/mp4" controls />
                        @else
                        <?php
                        $filename = explode('/', $message->message)[count(explode('/', $message->message)) - 1];
                        ?>
                        <a style="background-color: #91ffde; border-radius: 1em; padding: 0.5em;" href="{{ asset($message->message) }}" download="{{ $filename }}">
                            <i class="fa fa-download"></i>
                            {{ $filename }}
                        </a>
                        @endif
                    @else
                    {{ $message->message }}
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Assign -->
    <div id="OpenPopUpAssign" class="modal fade p-0">
        <div class="modal-dialog modal-login" style="max-width: 720px;">
            <div class="modal-content">
                <div class="rounded-lg shadow-lg">
                    <div class="bg-white mb-2 mt-6">
                        <form action="{{ route('ticket.assign') }}" method="POST">
                        @csrf
                            <div class="bg-white">
                                <div class="mt-4 text-center mx-4">
                                    <h4 class="uppercase">Asignar ticket a usuario</h4>
                                </div>
                            </div>
                            <div class="text-center">
                                <br>
                                <hr>
                                <br>
                                <div class="form-group m-0">
                                    <input type="number" name="ticket_id" value="{{ $ticket->id }}" hidden />
                                    <select id="select2-usuario" name="user" class="form-control" style="width: 70%;">
                                        <option value="">Seleccionar...</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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
    $(document).ready(function() {
        $('#select2-usuario').select2();
    });

    $('#select-status').on('change', (ev) => {
        const status = ev.target.value;

        let color;
        switch (status) {
            case 'ABIERTO':
                color = '#47ed73';
                break;
            case 'EN_PROCESO':
                color = '#fcf453';
                break;
            case 'CERRADO':
                color = '#b8b8b8';
                break;
        }
        $('#select-status').css('background-color', color);
        $.ajax({
            type: "POST",
            url: `/ticket/{{ $ticket->id }}/status/${status}`,
            success: function(){},
            error: function(err) {
                console.log(err)
            }
        })
    });
</script>
