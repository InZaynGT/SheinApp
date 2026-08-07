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
        es Bootstrap 5.3.3 (CDN). Por eso se usa markup de `.card` (BS4)
        con atributos de datos `data-bs-*` (BS5).
    ======================================================================
--}}
@if($documentos->count() > 0)
    @php
        $accordionId = 'accordion-deudor-' . $clienteId;
        $collapseId  = 'collapse-documentos-' . $clienteId;
        $headingId   = 'heading-documentos-' . $clienteId;
        $totalDocs   = $documentos->count();
    @endphp

    <div class="accordion" id="{{ $accordionId }}">
        <div class="card card-outline card-danger mb-0">
            <div class="card-header p-0" id="{{ $headingId }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left text-danger d-flex justify-content-between align-items-center"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#{{ $collapseId }}"
                            aria-expanded="false"
                            aria-controls="{{ $collapseId }}">
                        <span>
                            <i class="fas fa-file-invoice mr-1"></i>
                            {{ $totalDocs }} {{ $totalDocs === 1 ? 'documento pendiente' : 'documentos pendientes' }}
                        </span>
                        <i class="fas fa-chevron-down accordion-chevron"></i>
                    </button>
                </h2>
            </div>

            <div id="{{ $collapseId }}"
                 class="collapse"
                 data-bs-parent="#{{ $accordionId }}"
                 aria-labelledby="{{ $headingId }}">
                <div class="card-body p-0">
                    <table class="table table-sm table-striped table-hover mb-0">
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
    </div>
@else
    <span class="text-muted">Sin documentos pendientes</span>
@endif
