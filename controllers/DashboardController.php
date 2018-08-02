<?php
/**
 * User: Jeremy
 */

class DashboardController
{
    public function index()
    {
        echo view('dashboard', [
            'newMessages' => 4,
            'newTasks' => 0,
            'newOrders' => 0,
            'newTickets' => 0,
        ]);
    }
}