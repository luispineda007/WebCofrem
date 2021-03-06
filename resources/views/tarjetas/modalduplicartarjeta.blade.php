<div id="modalduplicartarjetas">
    {{Form::open(['route'=>['tarjetas.modalduplicar',$tarjeta->id], 'class'=>'form-horizontal', 'id'=>'duplicartarjeta'])}}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Duplicar tarjeta {{$tarjeta->numero_tarjeta}}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">

                <div class="form-group">
                    <label for="estado">Numero de la nueva tarjeta</label>
                    {{Form::text('numero_tarjeta',null,['class'=>'form-control', 'id'=>'numero_tarjeta', "data-parsley-type"=>"number","maxlength"=>"6", "required"])}}
                </div>
                <div class="form-group">
                    <label for="estado">Motivo</label>
                    {{Form::select('motivo',$motivos,null,['class'=>'form-control'])}}
                </div>
                <div class="form-group">
                    <label for="estado">Nota</label>
                    {{Form::textarea('nota',null,['class'=>'form-control', "rows"=>"2"])}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-custom waves-effect waves-light">Guardar</button>
    </div>
    {{Form::close()}}
</div>

<script>
    $(function () {

        $('#numero_tarjeta').autocomplete({
            serviceUrl: '{{route("tarjetas.duplicado.autocomplete")}}',
            lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
                var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                return re.test(suggestion.value);
            },
            onSelect: function (suggestion) {
                //console.log('You selected: ' + suggestion.value + ', ' + suggestion.data);
                $("#btnCrearTRegalo").removeClass("btn-custom").addClass("btn-success");
                $("#btnCrearTRegalo").html("Asociar Servicio Regalo");
            },
            onHint: function (hint) {
                //$('#producto-x').val(hint);
            },
            onInvalidateSelection: function () {
                $("#btnCrearTRegalo").removeClass("btn-success").addClass("btn-custom");
                $("#btnCrearTRegalo").html("Crear Tarjeta");
            }
        });


        $("#duplicartarjeta").parsley();
        $("#duplicartarjeta").submit(function (e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'POST',
                dataType: 'json',
                beforeSend: function () {
                    cargando();
                },
                success: function (result) {
                    if (result.estado) {
                        swal(
                            {
                                title: 'Bien!!',
                                text: result.mensaje,
                                type: 'success',
                                confirmButtonColor: '#4fa7f3'
                            }
                        );
                        modalBs.modal('hide');
                    } else if (result.estado == false) {
                        swal(
                            'Error!!',
                            result.mensaje,
                            'error'
                        );
                    } else {
                        html = '';
                        for (i = 0; i < result.length; i++) {
                            html += result[i] + '\n\r';
                        }
                        swal(
                            'Error!!',
                            html,
                            'error'
                        )
                    }
                    table.ajax.reload();
                },
                error: function (xhr, status) {
                    var message = "Error de ejecución: " + xhr.status + " " + xhr.statusText;
                    swal(
                        'Error!!',
                        message,
                        'error'
                    )
                },
                // código a ejecutar sin importar si la petición falló o no
                complete: function (xhr, status) {
                    fincarga();
                }
            });
        });

    });


</script>