{{--
    ======================================================================
    Partial: Documentos pendientes de un deudor en formato accordion.

    Responsabilidad única (SRP):
        Renderizar los documentos pendientes de UN solo cliente como un
        desplegable tipo accordion de Bootstrap, cuyo cuerpo contiene una
        tabla con las columnas: ID Orden | Fecha | Monto.

    Dependencias (inyectadas desde la vista padre, sin consultas extra):
        $documentos : \Illuminate\Support\Collection de CxcDocumentoModel
                      con saldo pendiente (saldoDocto > 0)
        $clienteId  : int  ID del cliente (para generar ids únicos en el DOM)

    Nota de compatibilidad:
        El CSS cargado es de Bootstrap 4 (AdminLTE 3), mientras que el JS
        es Bootstrap 5.3.3 (CDN). Por eso se usa markup con clases de BS4
        y atributos de datos `data-bs-*` (BS5).

    Nota de arquitectura:
        Bootstrap debe cargarse UNA sola vez (lo hace adminlte::master).
        NO se debe volver a incluir partials/scripts aquí ni en la vista,
        porque dos instancias del Collapse de BS5 se "pelean" y el
        accordion jamás se cierra.
    ======================================================================
--}}
@if($documentos->count() > 0)
    @php
        $accordionId = 'accordion-deudor-' . $clienteId;
        $collapseId  = 'collapse-documentos-' . $clienteId;
        $buttonId    = $collapseId . '-btn';
        $totalDocs   = $documentos->count();
    @endphp

    <div class="accordion" id="{{ $accordionId }}">
        <button id="{{ $buttonId }}"
                class="btn btn-block btn-outline-danger btn-sm text-left d-flex justify-content-between align-items-center px-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#{{ $collapseId }}"
                aria-expanded="false"
                aria-controls="{{ $collapseId }}">
            <span>
                <i class="fas fa-file-invoice mr-1"></i>
                {{ $totalDocs }} {{ $totalDocs === 1 ? 'documento pendiente' : 'documentos pendientes' }}
            </span>
            <span class="d-flex align-items-center">
                <span class="badge badge-danger mr-1">{{ $totalDocs }}</span>
                <i class="fas fa-chevron-down accordion-chevron"></i>
            </span>
        </button>

        <div id="{{ $collapseId }}"
             class="collapse"
             data-bs-parent="#{{ $accordionId }}"
             aria-labelledby="{{ $buttonId }}">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered bg-white mb-0 mt-1">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">ID Orden</th>
                            <th scope="col">Fecha</th>
                            <th scope="col" class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documentos as $doc)
                            <tr>
                                <td>#{{ $doc->Nro_docto }}</td>
                                <td>{{ \Carbon\Carbon::parse($doc->fechaDocto)->format('d/m/Y') }}</td>
                                <td class="text-right font-weight-bold">Q. {{ number_format($doc->saldoDocto, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <span class="text-muted">Sin documentos pendientes</span>
@endif

