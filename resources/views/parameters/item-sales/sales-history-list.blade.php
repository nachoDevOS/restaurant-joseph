<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th style="width:5px">N&deg;</th>
            <th style="text-align: center">ID Venta</th>
            <th style="text-align: center">Cliente</th>
            <th style="text-align: center">Fecha</th>
            <th style="text-align: center">Cantidad</th>
            <th style="text-align: center">Precio</th>
            <th style="text-align: center">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @php
            $num = ($saleDetails->currentPage() - 1) * $saleDetails->perPage() + 1;
        @endphp
        @forelse ($saleDetails as $detail)
            <tr>
                <td>{{ $num++ }}</td>
                <td style="text-align: center">
                    <a href="{{ route('sales.show', ['sale' => $detail->sale->id]) }}" target="_blank">
                        {{ $detail->sale->id }}
                    </a>
                </td>
                <td>{{ optional($detail->sale->person)->last_name }} {{ optional($detail->sale->person)->first_name ?? 'Sin Cliente' }}</td>
                <td style="text-align: center">{{ date('d/m/Y H:i:s', strtotime($detail->sale->created_at)) }}</td>
                <td style="text-align: right">{{ $detail->quantity }}</td>
                <td style="text-align: right">{{ number_format($detail->price, 2, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($detail->quantity * $detail->price, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <h5 class="text-center" style="margin-top: 50px">
                        <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.8">
                        <br><br>
                        No se han realizado ventas de este producto.
                    </h5>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="col-md-12">
    <div class="col-md-6" style="overflow-x:auto">
        @if (count($saleDetails) > 0)
            <p class="text-muted">Mostrando del {{ $saleDetails->firstItem() }} al {{ $saleDetails->lastItem() }} de
                {{ $saleDetails->total() }} registros.</p>
        @endif
    </div>
    <div class="col-md-6" style="overflow-x:auto">
        <nav class="text-right">
            {{ $saleDetails->links() }}
        </nav>
    </div>
</div>

<script>
    var page_sales_history = "{{ request('page') }}";
    $(document).ready(function() {
        $('.page-link').click(function(e) {
            e.preventDefault();
            let link = $(this).attr('href');
            if (link) {
                page_sales_history = link.split('=')[1];
                list_sales_history(page_sales_history);
            }
        });
    });
</script>