<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ReportController extends Controller {
    /**
     * Promotion mail page
     * @return View
     */
    public function getReport(): View {
        return view( 'pages.report.report' );
    }
}
