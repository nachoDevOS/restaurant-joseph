<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #{{ $sale->ticket }}</title>

    <style>
        body {
            font-family: 'Courier New', monospace; /* Fuente mejor para impresoras térmicas */
            font-size: 12px;
            width: 76mm; /* Ancho exacto para papel de 76mm */
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .ticket {
            width: 100%;
            max-width: 76mm;
            padding: 0 2mm; /* Pequeño margen interno */
        }
        .header {
            text-align: center;
            margin-bottom: 3px;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
        }
        .title-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 3px;
        }
        .restaurant-info {
            font-size: 9px;
            margin-bottom: 3px;
        }
        .ticket-info {
            display: flex;
            justify-content: left;
            margin-bottom: 3px;
            border-bottom: 1px dashed #000;
            padding-bottom: 2px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed; /* Mejor control del ancho de columnas */
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 1px 0;
            font-size: 11px;
        }
        .items-table td {
            padding: 1px 0;
            vertical-align: top;
            word-wrap: break-word;
        }
        .items-table .quantity {
            text-align: center;
            width: 15%;
        }
        .items-table .description {
            width: 60%;
            padding-right: 2px;
        }
        .items-table .price {
            text-align: right;
            width: 25%;
        }
        .total {
            text-align: right;
            font-weight: 900;
            font-size: 12px;
            margin-top: 8px;
            border-top: 1px dashed #000;
            padding-top: 3px;
        }
        .footer {
            text-align: center;
            margin-top: 8px;
            font-size: 9px;
            border-top: 1px dashed #000;
            padding-top: 8px;
        }
        .client-info {
            margin-bottom: 3px;
            border-bottom: 1px dashed #000;
            padding-bottom: 2px;
            font-size: 10px;
        }
        
        /* Estilos para impresión */
        @media print {
            .hide-print, .btn-print {
                display: none;
            }
            body {
                margin: 0;
                width: 76mm;
            }
            html, body {
                height: auto;
                overflow: hidden;
            }
            .ticket {
                padding: 0;
            }
        }
        
        /* Estilos para pantalla */
        @media screen {
            body {
                width: auto;
                max-width: 76mm;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print">
        <button class="btn-print" onclick="window.close()">Cancelar (Esc)</button>
        <button class="btn-print" onclick="window.print()">Imprimir (Enter)</button>
    </div>
    
    <div class="ticket">
        <div class="header">
            <div class="title-name" style="margin-top: 5px;">TICKET #{{$sale->ticket}}</div>
            <div class="title-name" style="text-transform: uppercase;">{{$sale->typeSale}}</div>
        </div>
        
        <!-- Información del cliente y cajero -->
        <div class="client-info">
            <b>Fecha:</b> {{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}}<br>
        </div>
        
        <!-- Detalles de los productos -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="quantity">CANT</th>
                    <th class="description">DESCRIPCIÓN</th>
                    <th class="price">PRECIO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total=0;
                @endphp
                @foreach ($sale->saleDetails as $item)
                    <tr>
                        <td class="quantity">{{ (float)$item->quantity == (int)$item->quantity? (int)$item->quantity:(float)$item->quantity }}</td>
                        <td class="description">{{$item->itemSale->name}}</td>
                        <td class="price">{{ number_format($item->amount, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $total+=$item->amount;
                    @endphp
                @endforeach
            </tbody>
        </table>
        
        <!-- Total y método de pago -->
        <div class="total">
            TOTAL: {{ number_format($total, 2, ',', '.') }}
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            ¡Gracias por su preferencia!<br>
            soluciondigital.dev
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Imprimir automáticamente al cargar la página
            setTimeout(function() {
                window.print();
            }, 500); // Pequeño retraso para asegurar que todo esté cargado
        });
        
        // Control de teclado para impresión y cierre
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                case 'p':
                case 'P':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                    break;
                default:
                    break;
            }
        });

        // Cerrar la ventana después de imprimir (o si se cancela la impresión)
        window.onafterprint = function() {
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>
</body>
</html>