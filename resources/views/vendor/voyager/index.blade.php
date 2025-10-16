@extends('voyager::master')

@section('page_header')
    @php
        $meses = [
            '',
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
        ];
    @endphp
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="overflow: visible;">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>Hola, {{ Auth::user()->name }}</h2>
                                <p class="text-muted">Resumen de rendimiento -
                                    {{ date('d') . ' de ' . $meses[intval(date('m'))] . ' ' . date('Y') }}</p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" id="filter-button">
                                        <i class="voyager-refresh"></i> Todo
                                    </button>
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" id="filter-menu">
                                        <li><a href="#" data-range="Todo">Todo</a></li>
                                        <li><a href="#" data-range="Desayuno">Desayuno</a></li>
                                        <li><a href="#" data-range="Almuerzo">Almuerzo</a></li>
                                        <li><a href="#" data-range="Cena">Cena</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="page-content container-fluid">
        @include('voyager::alerts')
        @include('voyager::dimmers')
        @php
            $sales = $global_index['sales'];
            // dump($sales);

            $amountDaytotal = $global_index['sales']
                ->where('deleted_at', null)
                ->filter(function ($sale) {
                    return $sale->created_at->format('Y-m-d') === date('Y-m-d');
                })
                ->sum('amount');

            $saleDaytotal = $global_index['sales']
                ->where('deleted_at', null)
                ->filter(function ($sale) {
                    return $sale->created_at->format('Y-m-d') === date('Y-m-d');
                })
                ->count();

            $customer = $global_index['people']->count();

            $monthInteractive = $global_index['monthInteractive'];
            // $monthInteractive = $global_index['monthInteractive'];
        @endphp
        <!-- KPI Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-dollar"></i>
                        </div>
                        <h3 class="kpi-value">Bs. {{ number_format($amountDaytotal, 2, ',', '.') }}</h3>
                        <p class="kpi-label">Ventas Total del Día</p>
                        {{-- <div class="kpi-trend trend-up">
                            <i class="voyager-up"></i> 12.5%
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-bag"></i>
                        </div>
                        <h3 class="kpi-value">{{ $saleDaytotal }}</h3>
                        <p class="kpi-label">Pedidos del Día</p>
                        {{-- <div class="kpi-trend trend-up">
                            <i class="voyager-up"></i> 5.2%
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-bar-chart"></i>
                        </div>
                        <h3 class="kpi-value">Bs.
                            {{ $saleDaytotal > 0 ? number_format($amountDaytotal / $saleDaytotal, 2, ',', '.') : '0,00' }}</h3>
                        <p class="kpi-label">Ticket Promedio</p>
                        {{-- <div class="kpi-trend trend-up">
                            <i class="voyager-up"></i> 8.7%
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-bordered dashboard-kpi">
                    <div class="panel-body text-center">
                        <div class="kpi-icon">
                            <i class="voyager-person"></i>
                        </div>
                        <h3 class="kpi-value">{{ $customer }}</h3>
                        <p class="kpi-label">Clientes</p>
                        {{-- <div class="kpi-trend trend-down">
                            <i class="voyager-down"></i> 3.1%
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>




        @if ($globalFuntion_cashier)
            @if ($globalFuntion_cashier->status == 'abierta' || $globalFuntion_cashier->status == 'apertura pendiente')

                @if ($globalFuntion_cashier->status == 'abierta')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body">
                              

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2 id="h2"><i class="fa-solid fa-wallet"></i>
                                                {{ $globalFuntion_cashier->title }}</h2>
                                        </div>
                                        @if ($globalFuntion_cashier->status == 'abierta')
                                            <div class="col-md-6 text-right">
                                                <a href="#" data-toggle="modal" data-target="#modal-create-expense"
                                                    title="Agregar Gastos" class="btn btn-success">Gastos <i
                                                        class="fa-solid fa-money-bill-transfer"></i></a>
                                                {{-- <a  href="#" data-toggle="modal" data-target="#modal_transfer_moneyCashier" title="Transferir Dinero" class="btn btn-success">Traspaso <i class="fa-solid fa-money-bill-transfer"></i></a> --}}

                                                <a href="{{ route('cashiers.close', ['cashier' => $globalFuntion_cashier->id]) }}"
                                                    class="btn btn-danger">Cerrar <i class="voyager-lock"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6" style="margin-top: 50px">
                                            <table width="100%" cellpadding="20">
                                                <tr>
                                                    <td><small>Dinero Asignado a Caja</small></td>
                                                    <td class="text-right"><h4>{{ number_format($globalFuntion_cashierMoney['cashierIn'], 2, ',', '.') }} <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td><small>Dinero disponible en Caja</small></td>
                                                    <td class="text-right"><h4>{{ number_format($globalFuntion_cashierMoney['amountCashier'], 2, ',', '.') }} <small>Bs.</small></h4></td>
                                                </tr>
                                            </table>
                                            <hr>
                                            <table width="100%" cellpadding="20">
                                                <tr>
                                                    <td><small>Ventas Total de Caja</small></td>
                                                    <td class="text-right"><h4>{{ number_format($globalFuntion_cashierMoney['paymentEfectivo']+$globalFuntion_cashierMoney['paymentQr'], 2, ',', '.') }} <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td><small>Ventas "Efectivo"</small></td>
                                                    <td class="text-right"><h4>{{ number_format($globalFuntion_cashierMoney['paymentEfectivo'], 2, ',', '.') }} <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td><small>Ventas "Qr"</small></td>
                                                    <td class="text-right"><h4>{{ number_format($globalFuntion_cashierMoney['paymentQr'], 2, ',', '.') }} <small>Bs.</small></h4></td>
                                                </tr>
                                                <tr>
                                                    <td><small>Gastos Realizados</small></td>
                                                    <td class="text-right"><h4>{{ number_format($globalFuntion_cashierMoney['cashierOut'], 2, ',', '.') }} <small>Bs.</small></h4></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-3 col-md-offset-2" >
                                            <canvas id="myChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('partials.modal-registerExpense')


                @else
                    <div class="row" id="rowCashierOpen">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h2 id="h2"><i class="fa-solid fa-wallet"></i>
                                                {{ $globalFuntion_cashier->title }}</h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6" style="margin-top: 50px">
                                            <table class="table table-hover" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Corte</th>
                                                        <th>Cantidad</th>
                                                        <th>Sub Total</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $cash = [
                                                        '200',
                                                        '100',
                                                        '50',
                                                        '20',
                                                        '10',
                                                        '5',
                                                        '2',
                                                        '1',
                                                        '0.5',
                                                        '0.2',
                                                        '0.1',
                                                    ];
                                                    $total = 0;
                                                @endphp
                                                <tbody>
                                                    @foreach ($cash as $item)
                                                        <tr>
                                                            <td>
                                                                <h4 style="margin: 0px"><img
                                                                        src=" {{ url('images/cash/' . $item . '.jpg') }} "
                                                                        alt="{{ $item }} Bs." width="70px">
                                                                    {{ $item }} Bs. </h4>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $details = null;
                                                                    if ($globalFuntion_cashier->vault_detail) {
                                                                        $details = $globalFuntion_cashier->vault_detail->cash
                                                                            ->where('cash_value', $item)
                                                                            ->first();
                                                                    }
                                                                @endphp
                                                                {{ $details ? $details->quantity : 0 }}
                                                            </td>
                                                            <td>
                                                                {{ $details ? number_format($details->quantity * $item, 2, ',', '.') : 0 }}
                                                                <input type="hidden" name="cash_value[]"
                                                                    value="{{ $item }}">
                                                                <input type="hidden" name="quantity[]"
                                                                    value="{{ $details ? $details->quantity : 0 }}">
                                                            </td>
                                                            @php
                                                                if ($details) {
                                                                    $total += $details->quantity * $item;
                                                                }
                                                            @endphp
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <br>
                                            <div class="alert alert-info">
                                                <strong>Información:</strong>
                                                <p>Si la cantidad de de cortes de billetes coincide con la cantidad
                                                    entregada por parte del administrador(a) de vóbeda, acepta la apertura
                                                    de caja, caso contrario puedes rechazar la apertura.</p>
                                            </div>
                                            <br>
                                            <h2 id="h3" class="text-right">Total en caja: Bs.
                                                {{ number_format($total, 2, ',', '.') }} </h2>
                                            <br>
                                            <div class="text-right">
                                                <button type="button" data-toggle="modal"
                                                    data-target="#refuse_cashier-modal" class="btn btn-danger">Rechazar 
                                                    apertura <i class="voyager-x"></i></button>
                                                <button type="button" data-toggle="modal" data-target="#open_cashier-modal"
                                                    class="btn btn-success">Aceptar apertura <i
                                                        class="voyager-key"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aceptar apertura de caja --}}
                    <form action="{{ route('cashiers.change.status', ['cashier' => $globalFuntion_cashier->id]) }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="status" value="abierta">
                        <div class="modal fade" tabindex="-1" id="open_cashier-modal" role="dialog">
                            <div class="modal-dialog modal-success">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span
                                                aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="fa-solid fa-wallet"></i> Aceptar apertura de caja
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted"></p>
                                        <small>Esta a punto de aceptar que posee todos los cortes de billetes descritos en
                                            la lista, ¿Desea continuar?</small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Si, aceptar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Rechazar apertura de caja --}}
                    <form action="{{ route('cashiers.change.status', ['cashier' => $globalFuntion_cashier->id]) }}"
                        method="post">
                        @csrf
                        <input type="hidden" name="status" value="cerrada">
                        <div class="modal modal-danger fade" tabindex="-1" id="refuse_cashier-modal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><i class="fa-solid fa-wallet"></i> Rechazar apertura de
                                            caja</h4>
                                    </div>
                                    <div class="modal-body">
                                        <small>Esta a punto de rechazar la apertura de caja, ¿Desea continuar?</small>
                                        <p class="text-muted"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Si, rechazar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body text-center">
                                <h2>Tienes una caja esperando por confimación de cierre</h2>
                                <a href="#" style="margin: 0px" data-toggle="modal"
                                    data-target="#cashier-revert-modal" class="btn btn-success"><i
                                        class="voyager-key"></i> Reabrir caja</a>
                                <a href="{{ route('cashiers.print', $globalFuntion_cashier->id) }}" style="margin: 0px"
                                    class="btn btn-danger" target="_blank"><i class="fa fa-print"></i> Imprimir</a>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('cashiers.close.revert', ['cashier' => $globalFuntion_cashier->id]) }}" method="post">
                    @csrf
                    <div class="modal fade" tabindex="-1" id="cashier-revert-modal" role="dialog">
                        <div class="modal-dialog modal-success">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span
                                            aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"><i class="voyager-key"></i> Reabrir Caja</h4>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted">Si reabre la caja deberá realizar el arqueo nuevamente, ¿Desea
                                        continuar?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-success">Si, reabrir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <h1 class="text-center">No tienes caja abierta</h1>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        

        <div class="row">
            <!-- Gráfico de ventas por día de la semana -->
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ventas por Día de la Semana</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="ventasDiasChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de productos más vendidos -->
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">5 Productos Más Vendidos del Día</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="topProductosChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de ventas mensuales -->
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ventas Mensuales</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="ventasMensualesChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de comparación año actual vs año anterior -->
            {{-- <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Comparación Anual</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="comparacionAnualChart" height="250"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@stop

@section('css')
    <style>
        .dashboard-kpi {
            transition: all 0.3s ease;
        }

        .dashboard-kpi:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .kpi-icon {
            font-size: 24px;
            color: #22A7F0;
            margin-bottom: 10px;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .kpi-label {
            color: #6c757d;
            margin-bottom: 5px;
        }

        .kpi-trend {
            font-size: 12px;
            font-weight: bold;
        }

        .trend-up {
            color: #2ecc71;
        }

        .trend-down {
            color: #e74c3c;
        }

        .panel-heading .btn-group {
            margin-top: -5px;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
    </style>
@stop

@section('javascript')
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @if ($globalFuntion_cashier)
        @if ($globalFuntion_cashier->status == 'abierta')
            <script>
                $(document).ready(function() {
                    const data = {
                        labels: [
                            'Ventas en Efectivo',
                            'Ventas en Qr',
                            'Gastos',
                        ],
                        datasets: [{
                            label: 'Bs.',
                            data: [
                                "{{ $globalFuntion_cashierMoney['paymentEfectivo'] }}", // Ventas Efectivo
                                "{{ $globalFuntion_cashierMoney['paymentQr'] }}", // Ventas QR
                                "{{ $globalFuntion_cashierMoney['cashierOut'] }}", // Gastos
                            ],
                            backgroundColor: [
                                'rgb(60, 179, 113)',
                                'rgb(70, 130, 180)',
                                'rgb(229, 57, 53)'
                            ],
                            hoverOffset: 4
                        }]
                    };
                    const config = {
                        type: 'pie',
                        data: data,
                    };
                    var myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );

                    $('.btn-agregar-gasto').click(function() {
                        let cashier_id = $(this).data('cashier_id');
                        $('#form-agregar-gasto input[name="cashier_id"]').val(cashier_id);
                    });
                });
            </script>
        @endif
    @endif

    <script>
        $(document).ready(function() {
            // 1. Declarar las variables de los gráficos aquí para que sean accesibles en todo el script
            let ventasMensualesChart, topProductosChart, ventasDiasChart;


            // --- Preparación de datos iniciales para los gráficos ---
            const monthData = @json($monthInteractive);
            const ventasMensualesData = {
                labels: monthData.map(item => item.month.substring(0, 3) + '-' + item.year),
                datasets: [{
                    label: 'Ventas',
                    data: monthData.map(item => item.amount),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',

                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            };

            // Datos para el gráfico de productos más vendidos
            const productTop5Day = @json($global_index['productTop5Day']);

            const topProductosData = {
                labels: productTop5Day.map(item => item.name),
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: productTop5Day.map(item => item.total_quantity),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            // Datos para el gráfico de ventas por día de la semana
            $weekDays = @json($global_index['weekDays']);
            const ventasDiasData = {
                labels: $weekDays.map(item => item.name + ' (' + item.dateInverso + ')'),

                datasets: [{
                    label: 'Ventas promedio',
                    data: $weekDays.map(item => item.amount),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            };

            const comparacionAnualData = {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                        label: '2022',
                        data: [100000, 150000, 130000, 160000, 190000, 210000, 230000, 200000, 220000,
                            240000, 260000, 280000
                        ],
                        borderColor: 'rgba(201, 203, 207, 1)',
                        backgroundColor: 'rgba(201, 203, 207, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: '2023',
                        data: [120000, 190000, 150000, 180000, 210000, 230000, 250000, 220000, 240000,
                            260000, 280000, 300000
                        ],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            };

            // Configuración común para los gráficos
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            };

            const pieChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            };

            // Lógica para el filtro del dashboard
            $('#filter-menu a').on('click', function(e) {
                e.preventDefault();
                var range = $(this).data('range');
                $('#filter-button').html('<i class="voyager-refresh"></i> ' + range);
                
                // Muestra un loader mientras se cargan los datos
                $('#voyager-loader').fadeIn();

                // Petición AJAX para obtener los nuevos datos
                $.ajax({
                    url: '{{ url('admin/dashboard-data') }}/' + range,
                    type: 'GET',
                    success: function(data) {
                        updateDashboard(data);
                        $('#voyager-loader').fadeOut();
                    },
                    error: function(error) {
                        console.error("Error al cargar los datos:", error);
                        toastr.error('No se pudieron actualizar los datos del dashboard.');
                        $('#voyager-loader').fadeOut();
                    }
                });
            });

            // Función para actualizar todos los componentes del dashboard
            function updateDashboard(data) {
                // --- Actualizar KPIs ---
                const today = new Date().toISOString().slice(0, 10);
                
                let amountDaytotal = 0;
                let saleDaytotal = 0;

                data.sales.forEach(sale => {
                    if (sale.deleted_at === null && sale.created_at >= today) {
                        amountDaytotal += parseFloat(sale.amount);
                        saleDaytotal++;
                    }
                });

                let ticketPromedio = saleDaytotal > 0 ? (amountDaytotal / saleDaytotal) : 0;

                // Formatear números a 2 decimales con coma
                const formatNumber = (num) => num.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                $('.dashboard-kpi').eq(0).find('.kpi-value').text('Bs. ' + formatNumber(amountDaytotal));
                $('.dashboard-kpi').eq(1).find('.kpi-value').text(saleDaytotal);
                $('.dashboard-kpi').eq(2).find('.kpi-value').text('Bs. ' + formatNumber(ticketPromedio));
                $('.dashboard-kpi').eq(3).find('.kpi-value').text(data.people.length);

                // --- Actualizar Gráficos ---

                // Gráfico de Ventas Mensuales
                ventasMensualesChart.data.datasets[0].data = data.monthInteractive.map(item => item.amount);
                ventasMensualesChart.update();

                // Gráfico de Top 5 Productos
                topProductosChart.data.labels = data.productTop5Day.map(item => item.name);
                topProductosChart.data.datasets[0].data = data.productTop5Day.map(item => item.total_quantity);
                topProductosChart.update();

                // Gráfico de Ventas por Día de la Semana
                ventasDiasChart.data.datasets[0].data = data.weekDays.map(item => item.amount);
                ventasDiasChart.update();

                toastr.success('Dashboard actualizado para: ' + $('#filter-button').text().trim());
            }

            // 2. Inicializar los gráficos UNA SOLA VEZ, asignándolos a las variables declaradas arriba
            ventasMensualesChart = new Chart(document.getElementById('ventasMensualesChart'), {
                type: 'bar',
                data: ventasMensualesData,
                options: chartOptions
            });

            topProductosChart = new Chart(document.getElementById('topProductosChart'), {
                type: 'pie',
                data: topProductosData,
                options: pieChartOptions
            });

            ventasDiasChart = new Chart(document.getElementById('ventasDiasChart'), {
                type: 'line',
                data: ventasDiasData,
                options: chartOptions
            });

        });
    </script>
@stop
