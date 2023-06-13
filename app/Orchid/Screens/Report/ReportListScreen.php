<?php

namespace App\Orchid\Screens\Report;

use App\Models\Maintenance;
use App\Orchid\Layouts\Examples\ChartLineExample;
use PDF;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ReportListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = auth()->user();
        $mantenimientosPendientes = Maintenance::where('status', 'Pendiente')->where('user_id', $user->id)->count();
        $mantenimientosFinalizados = Maintenance::where('status', 'Finalizado')->where('user_id', $user->id)->count();
        $PCMeses = [];
        $ImpresoraMeses = [];
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $i = 0;
        foreach ($meses as $mes) {
            $i++;
            $PCMeses[] = Maintenance::where('cpu_id', '!=', null)
                ->where('user_id', $user->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();

            $ImpresoraMeses[] = Maintenance::where('printer_id', '!=', null)
                ->where('user_id', $user->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();
        }
        return [
            'charts'  => [
                [
                    'name'   => 'PC',
                    'values' => $PCMeses,
                    'labels' => $meses,
                ],
                [
                    'name'   => 'Impresora',
                    'values' => $ImpresoraMeses,
                    'labels' => $meses,
                ],
            ],
            'metrics' => [
                'realizados' => number_format(Maintenance::where('user_id', $user->id)->count()),
                'pendientes' => number_format($mantenimientosPendientes),
                'finalizados' => number_format($mantenimientosFinalizados),
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Reporte';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.report',
            'systems.admin'
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Generar reporte')
                ->modal('report')
                ->method('generateReport')
                ->icon('cloud-download')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('report', Layout::rows([
                Input::make('report.name')
                    ->title('Nombre del encargado')
                    ->readonly()
                    ->placeholder(auth()->user()->name),
                DateTimer::make('report.dateStart', 'Fecha de inicio')
                    ->title('Fecha de inicio')
                    ->format('Y-m-d')
                    ->placeholder('Fecha de inicio'),
                DateTimer::make('report.dateEnd')
                    ->title('Fecha de fin')
                    ->format('Y-m-d')
                    ->placeholder('Fecha de fin'),
            ]))->title('Generar reporte')
                ->rawClick()
                ->applyButton('Generar reporte'),

            Layout::metrics([
                'Mantenimientos Realizados'    => 'metrics.realizados',
                'Mantenimientos Pendientes'    => 'metrics.pendientes',
                'Mantenimientos Finalizados'   => 'metrics.finalizados',
            ]),

            Layout::columns([
                ChartLineExample::make('charts', 'Line de tiempo de mantenimientos realizados')
                    ->description('Mantenimientos realizados por mes a los equipos de la Municipalidad de Tela en el año ' . date('Y'))
                ])
        ];
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report.dateStart' => 'required|date',
            'report.dateEnd' => 'required|date|after_or_equal:report.dateStart',
        ],[
            'report.dateStart.required' => 'La fecha de inicio es requerida',
            'report.dateStart.date' => 'La fecha de inicio debe ser una fecha válida',
            'report.dateEnd.required' => 'La fecha de fin es requerida',
            'report.dateEnd.date' => 'La fecha de fin debe ser una fecha válida',
            'report.dateEnd.after_or_equal' => 'La fecha de fin debe ser mayor o igual a la fecha de inicio',
        ]);
        // Obtener los datos del formulario
        $dateStart = $request->input('report.dateStart');
        $dateEnd = $request->input('report.dateEnd');

        // Obtener los mantenimientos hechos dentro del rango de fechas especificado
        $user = auth()->user();
        $mantenimientos = Maintenance::whereBetween('created_at', [$dateStart, $dateEnd])
                        ->where('user_id', $user->id)
                        ->where('status', 'Finalizado')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Generar el reporte en formato PDF
        $pdf = PDF::loadView('reporteMantenimientos', compact('mantenimientos', 'user'));

        // Retornar el reporte en formato PDF


        return $pdf->stream('reporte-mantenimientos.pdf');
        //return view('reporteMantenimientos', compact('mantenimientos'));

    }
}


