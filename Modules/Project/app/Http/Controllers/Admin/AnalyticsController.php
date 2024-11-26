<?php

namespace Modules\Project\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ContractStatus;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Contract;
use Modules\Project\Models\Project;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $total_clients = Account::whereType(AccountType::CLIENT->value)->count();
        $total_freelancers = Account::whereType(AccountType::FREELANCER->value)->count();
        $total_projects = Project::count();
        $total_contracts = Contract::count();
        $total_completed_transactions = Contract::whereStatus(ContractStatus::COMPLETED->value)->sum('total_amount');
        $total_active_projects = Project::whereStatus(ProjectStatus::ACTIVE->value)->count();
        $total_active_contracts = Contract::whereStatus(ContractStatus::ACTIVE->value)->count();
        $total_completed_contracts = Contract::whereStatus(ContractStatus::COMPLETED->value)->count();

        return response()->json([
            'total_clients' => $total_clients,
            'total_freelancers' => $total_freelancers,
            'total_projects' => $total_projects,
            'total_contracts' => $total_contracts,
            'total_completed_transactions' => $total_completed_transactions,
            'total_active_projects' => $total_active_projects,
            'total_active_contracts' => $total_active_contracts,
            'total_completed_contracts' => $total_completed_contracts,
        ]);
    }
}
