<?php

namespace App\Http\Controllers;

use App\Imports\TagihanImport;
use App\Models\Jenjang;
use App\Models\Tagihan;
use App\Models\Ujian;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('admin.dashboard.index', $this->data);
    }
}
