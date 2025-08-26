<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;

class DashboardController extends Controller
{
    public function index()
    {
        $trabajos = Trabajo::all();

        $cards = [
            [
                'icon' => 'fas fa-clock',
                'color' => 'warning',
                'value' => $trabajos->where('estado', 'pendiente')->count(),
                'label' => 'Pendientes'
            ],
            [
                'icon' => 'fas fa-check-circle',
                'color' => 'success',
                'value' => $trabajos->where('estado', 'completado')->count(),
                'label' => 'Completados'
            ],
            [
                'icon' => 'fas fa-times-circle',
                'color' => 'danger',
                'value' => $trabajos->where('estado', 'cancelado')->count(),
                'label' => 'Cancelados'
            ],
            [
                'icon' => 'fas fa-spinner',
                'color' => 'info',
                'value' => $trabajos->where('estado', 'En_progreso')->count(),
                'label' => 'En Progreso'
            ],
        ];

        // 👇 Aquí mandás $cards a la vista
        return view('dashboard.index', compact('cards'));
    }
}
