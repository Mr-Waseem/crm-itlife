<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Party;
use App\Models\PartyProject;
use App\Models\EmployeeHistory;
use App\Models\SalePurchase;
use App\Models\SalePurchaseDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
   
    public function index()
    {
        $user_department = Warehouse::find(Auth::User()->warehouse_id);
        $companyId = Auth::user()->warehouse_id;
        $shopId = Auth::user()->shop_id;
        // dd($shopId, $companyId);

        // All sale/purchase type variants used across the app
        $saleTypes = ['SALE', 'DIRECT SALES', 'SALESTAX INVOICE', 'DIRECT SALESTAX INVOICE'];
        $purchaseTypes = ['PURCHASE', 'DIRECT PURCHASE', 'PURCHASETAX', 'DIRECT PURCHASETAX VOUCHER'];

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $salesMonthsStart = Carbon::now()->subMonths(11)->startOfMonth();
        $salesDaysStart = Carbon::now()->subDays(29)->startOfDay();

        // =====================
        // PARTIES / CUSTOMERS
        // =====================
        $totalParties = Party::count();
        $activeParties = Party::where('status', 1)->count();
        $totalCustomers = Party::where('account_type', 'CUSTOMER')->count();
        $totalSuppliers = Party::where('account_type', 'SUPPLIER')->count();
        $totalProducts = Product::count();

        // =====================
        // SALES (from sale_purchases / sale_purchase_details)
        // =====================
        // Sales invoice counts
        $totalSalesInvoices = SalePurchase::whereIn('type', $saleTypes)->count();
        $monthlySalesInvoices = SalePurchase::whereIn('type', $saleTypes)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])->count();
        $dailySalesInvoices = SalePurchase::whereIn('type', $saleTypes)
            ->whereDate('date', $today->toDateString())->count();

        // Sales amounts
        $totalSalesAmount = (float) SalePurchaseDetail::whereIn('type', $saleTypes)->sum('total');
        $monthlySalesAmount = (float) SalePurchaseDetail::whereIn('type', $saleTypes)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])->sum('total');
        $dailySalesAmount = (float) SalePurchaseDetail::whereIn('type', $saleTypes)
            ->whereDate('date', $today->toDateString())->sum('total');

        // =====================
        // PURCHASES
        // =====================
        $totalPurchaseAmount = (float) SalePurchaseDetail::whereIn('type', $purchaseTypes)->sum('total');
        $monthlyPurchaseAmount = (float) SalePurchaseDetail::whereIn('type', $purchaseTypes)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])->sum('total');
        $dailyPurchaseAmount = (float) SalePurchaseDetail::whereIn('type', $purchaseTypes)
            ->whereDate('date', $today->toDateString())->sum('total');

        // =====================
        // SALES TREND (last 12 months)
        // =====================
        $salesTrendRaw = SalePurchaseDetail::selectRaw("DATE_FORMAT(date, '%Y-%m') as ym, SUM(total) as total")
            ->whereIn('type', $saleTypes)
            ->where('date', '>=', $salesMonthsStart->toDateString())
            ->groupBy('ym')->orderBy('ym')->get()->pluck('total', 'ym')->toArray();

        $salesMonthLabels = [];
        $salesMonthValues = [];
        for ($i = 0; $i < 12; $i++) {
            $mKey = Carbon::now()->subMonths(11 - $i)->format('Y-m');
            $salesMonthLabels[] = Carbon::now()->subMonths(11 - $i)->format('M Y');
            $salesMonthValues[] = isset($salesTrendRaw[$mKey]) ? (float) $salesTrendRaw[$mKey] : 0;
        }

        // =====================
        // DAILY SALES (last 30 days)
        // =====================
        $dailySalesRaw = SalePurchaseDetail::selectRaw("DATE_FORMAT(date, '%Y-%m-%d') as d, SUM(total) as total")
            ->whereIn('type', $saleTypes)
            ->where('date', '>=', $salesDaysStart->toDateString())
            ->groupBy('d')->orderBy('d')->get()->pluck('total', 'd')->toArray();

        $salesDailyLabels = [];
        $salesDailyValues = [];
        for ($i = 0; $i < 30; $i++) {
            $dKey = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $salesDailyLabels[] = Carbon::now()->subDays(29 - $i)->format('d M');
            $salesDailyValues[] = isset($dailySalesRaw[$dKey]) ? (float) $dailySalesRaw[$dKey] : 0;
        }

        // =====================
        // PURCHASES TREND (last 12 months)
        // =====================
        $purchaseTrendRaw = SalePurchaseDetail::selectRaw("DATE_FORMAT(date, '%Y-%m') as ym, SUM(total) as total")
            ->whereIn('type', $purchaseTypes)
            ->where('date', '>=', $salesMonthsStart->toDateString())
            ->groupBy('ym')->orderBy('ym')->get()->pluck('total', 'ym')->toArray();

        $purchaseMonthValues = [];
        for ($i = 0; $i < 12; $i++) {
            $mKey = Carbon::now()->subMonths(11 - $i)->format('Y-m');
            $purchaseMonthValues[] = isset($purchaseTrendRaw[$mKey]) ? (float) $purchaseTrendRaw[$mKey] : 0;
        }

        // =====================
        // CUSTOMERS TREND (new per month - last 12 months)
        // =====================
        $customersTrendRaw = Party::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
            ->where('account_type', 'CUSTOMER')
            ->where('created_at', '>=', $salesMonthsStart)
            ->groupBy('ym')->orderBy('ym')->get()->pluck('total', 'ym')->toArray();

        $customersMonthLabels = [];
        $customersMonthValues = [];
        for ($i = 0; $i < 12; $i++) {
            $mKey = Carbon::now()->subMonths(11 - $i)->format('Y-m');
            $customersMonthLabels[] = Carbon::now()->subMonths(11 - $i)->format('M Y');
            $customersMonthValues[] = isset($customersTrendRaw[$mKey]) ? (int) $customersTrendRaw[$mKey] : 0;
        }

        // =====================
        // EXPENSES (parties with account_group_id 6 or 7 → general_vouchers)
        // =====================
        $expensePartyIds = Party::whereIn('account_group_id', [6, 7])->pluck('id')->toArray();

        $expenseBaseQuery = DB::table('general_vouchers')
            ->whereIn('account_head_id', $expensePartyIds);

        $totalExpenses = (float) (clone $expenseBaseQuery)->sum('debit');
        $monthlyExpenses = (float) (clone $expenseBaseQuery)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])->sum('debit');
        $dailyExpenses = (float) (clone $expenseBaseQuery)
            ->whereDate('date', $today->toDateString())->sum('debit');

        // Monthly expenses trend (last 12 months)
        $expensesMonthlyRaw = DB::table('general_vouchers')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as ym, SUM(debit) as total")
            ->whereIn('account_head_id', $expensePartyIds)
            ->where('date', '>=', $salesMonthsStart->toDateString())
            ->groupBy('ym')->orderBy('ym')->get()->pluck('total', 'ym')->toArray();

        $expensesMonthLabels = [];
        $expensesMonthValues = [];
        for ($i = 0; $i < 12; $i++) {
            $mKey = Carbon::now()->subMonths(11 - $i)->format('Y-m');
            $expensesMonthLabels[] = Carbon::now()->subMonths(11 - $i)->format('M Y');
            $expensesMonthValues[] = isset($expensesMonthlyRaw[$mKey]) ? (float) $expensesMonthlyRaw[$mKey] : 0;
        }

        // Daily expenses trend (last 30 days)
        $expensesDailyRaw = DB::table('general_vouchers')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-%d') as d, SUM(debit) as total")
            ->whereIn('account_head_id', $expensePartyIds)
            ->where('date', '>=', $salesDaysStart->toDateString())
            ->groupBy('d')->orderBy('d')->get()->pluck('total', 'd')->toArray();

        $expensesDailyLabels = [];
        $expensesDailyValues = [];
        for ($i = 0; $i < 30; $i++) {
            $dKey = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $expensesDailyLabels[] = Carbon::now()->subDays(29 - $i)->format('d M');
            $expensesDailyValues[] = isset($expensesDailyRaw[$dKey]) ? (float) $expensesDailyRaw[$dKey] : 0;
        }

        // =====================
        // TOP 5 SELLING PRODUCTS (current month)
        // =====================
        $topProducts = SalePurchaseDetail::select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->whereIn('type', $saleTypes)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->groupBy('product_id')->orderByDesc('total_amount')->limit(5)->get();

        $topProductNames = [];
        $topProductAmounts = [];
        foreach ($topProducts as $tp) {
            $product = Product::find($tp->product_id);
            $topProductNames[] = $product ? $product->product_name : 'Unknown';
            $topProductAmounts[] = (float) $tp->total_amount;
        }

        // =====================
        // TOP 5 CUSTOMERS BY SALES (current month)
        // =====================
        $topCustomers = SalePurchaseDetail::select('party_id', DB::raw('SUM(total) as total_amount'))
            ->whereIn('type', $saleTypes)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->groupBy('party_id')->orderByDesc('total_amount')->limit(5)->get();

        $topCustomerNames = [];
        $topCustomerAmounts = [];
        foreach ($topCustomers as $tc) {
            $party = Party::find($tc->party_id);
            $topCustomerNames[] = $party ? $party->party_name : 'Unknown';
            $topCustomerAmounts[] = (float) $tc->total_amount;
        }

        // =====================
        // RECEIVABLES & PAYABLES (from general_vouchers)
        // =====================
        $customerPartyIds = Party::where('account_type', 'CUSTOMER')->pluck('id')->toArray();
        $supplierPartyIds = Party::where('account_type', 'SUPPLIER')->pluck('id')->toArray();

        $totalReceivable = 0;
        $totalPayable = 0;
        if (!empty($customerPartyIds)) {
            $custDebit = (float) DB::table('general_vouchers')->whereIn('account_head_id', $customerPartyIds)->sum('debit');
            $custCredit = (float) DB::table('general_vouchers')->whereIn('account_head_id', $customerPartyIds)->sum('credit');
            $totalReceivable = $custDebit - $custCredit;
        }
        if (!empty($supplierPartyIds)) {
            $suppDebit = (float) DB::table('general_vouchers')->whereIn('account_head_id', $supplierPartyIds)->sum('debit');
            $suppCredit = (float) DB::table('general_vouchers')->whereIn('account_head_id', $supplierPartyIds)->sum('credit');
            $totalPayable = $suppCredit - $suppDebit;
        }

        return view('home', compact(
            'user_department',
            'totalParties',
            'activeParties',
            'totalCustomers',
            'totalSuppliers',
            'totalProducts',
            'totalSalesInvoices',
            'monthlySalesInvoices',
            'dailySalesInvoices',
            'totalSalesAmount',
            'monthlySalesAmount',
            'dailySalesAmount',
            'totalPurchaseAmount',
            'monthlyPurchaseAmount',
            'dailyPurchaseAmount',
            'salesMonthLabels',
            'salesMonthValues',
            'salesDailyLabels',
            'salesDailyValues',
            'purchaseMonthValues',
            'customersMonthLabels',
            'customersMonthValues',
            'totalExpenses',
            'monthlyExpenses',
            'dailyExpenses',
            'expensesMonthLabels',
            'expensesMonthValues',
            'expensesDailyLabels',
            'expensesDailyValues',
            'topProductNames',
            'topProductAmounts',
            'topCustomerNames',
            'topCustomerAmounts',
            'totalReceivable',
            'totalPayable'
        ));
    }


    public function indexxx(Request $request)
    {
        $codes = 1;
        $code = Vouchers::where('v_type', 'Bank Payment')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
            ->pluck('name', 'id');
        }
        // $banks = Party::where('account_type', 'BANK')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Bank Account Name', '');
        $banks = Party::where('account_group_id3','2')->pluck('party_name', 'id')
        ->prepend('Select Bank Account Name', '');
        // $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     // ->where('account_type', 'BANK')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');

        //     $Accounts = Party::
        // // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        // select(DB::raw(
        //     'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
        //     CONCAT(`code`, "-", `party_name`) AS `party_name`'
        //     ))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');


            $Accounts = Party::
            select(DB::raw(
                'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
                CONCAT(`code`, "-", `party_name`, "-", `ContactPerson`) AS `party_name`'
                ))
                ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->where('account_type', "!=", 'AGENT')
                ->OrderBy('party_name', 'asc')
                ->pluck('party_name', 'id')
                ->prepend('Select Party/Account', '');

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

        return view('bank-payments.index', compact('codes', 'Accounts', 'banks', 'warehouse', 'Accountsbelow'));
    }

    public function edit($jvmawb)
    {
        //  return $jvmawb;
        $data = Vouchers::whereId($jvmawb)->first();
        $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
            ->pluck('name', 'id');
        }
        // $banks = Party::where('account_type', 'BANK')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Bank Account Name', '');
        $banks = Party::where('account_group_id3','2')->pluck('party_name', 'id')
        ->prepend('Select Bank Account Name', '');
        // $Accounts = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     // ->where('account_type', 'BANK')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');

        //     $Accounts = Party::
        // // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
        // select(DB::raw(
        //     'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
        //     CONCAT(`code`, "-", `party_name`) AS `party_name`'
        //     ))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party/Account', '');


            $Accounts = Party::
            select(DB::raw(
                'CONCAT(`id`, "_", `party_name`, "_", `code`) AS `id`,
                CONCAT(`code`, "-", `party_name`, "-", `ContactPerson`) AS `party_name`'
                ))
                ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->where('account_type', "!=", 'AGENT')
                ->OrderBy('party_name', 'asc')
                ->pluck('party_name', 'id')
                ->prepend('Select Party/Account', '');
                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

        return view('bank-payments.index', compact('data', 'Accounts', 'banks', 'warehouse', 'Accountsbelow'));
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
        $code = Vouchers::where('v_type', 'Bank Payment')
        ->where('warehouse_id', $request->warehouseID)
        ->OrderBy('id', 'desc')->first();
        if ($code) {
            $codes = (int)$code->voucher_no + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'voucher_no' => 'required',
            'account_id' => 'required'
        ], [
            'voucher_date.required' => 'The Voucher Date field is required.',
            'voucher_no.required' => 'The Voucher No field is required.',
            'account_id.required' => 'The Bank Account Name field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        if (!isset($request->narration)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if (!isset($request->amount)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }



        if ($request->update_voucher_no != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'BANK PAYMENT VOUCHER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $data = $request->all();
            // $data['account_id'] = explode('_', $request->account_id)[0];
            // return $data['account_id'] = $request->account_id;
            $data['warehouse_id'] = $request->warehouse_id;


            $voucherData = Vouchers::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
                ->where('v_type', 'Bank Payment')
                ->first();

            $voucherData = Vouchers::find($voucherData->id);
            $voucherData->update($data);

            GeneralVoucher::where('voucher_no', $request->update_voucher_no)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('v_type', 'Bank Payment')->delete();
            // LedgerDetailWise::where('voucher_no', $request->update_voucher_no)->where('v_type', 'Bank Payment')->delete();

            $totalDebit = 0;
            $count = count($request->amount);
            for ($i = 0; $i < $count; $i++) {
                // Top account | Debit account | Bank receive account

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $voucherData['account_id'];
                $generalVoucher->other_head_id = $request->party_id[$i];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->credit = $request->amount[$i];
                $generalVoucher->save();

                // Below account | Credit account | Bank paid account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $request->party_id[$i];
                $generalVoucher->other_head_id = $voucherData['account_id'];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->debit = $request->amount[$i];
                $generalVoucher->save();

                // $totalDebit += $request->amount[$i];
            }

            // $voucherData->total_debit = $totalDebit;
            // $voucherData->save();
            Session::flash('flash_message', 'Voucher Updated Successfully!');
            return redirect('bank-payments');
            return redirect()->back()->with('flash_message', 'Voucher Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'BANK PAYMENT VOUCHER')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            
            $codes = 1;
            $code = Vouchers::where('v_type', 'Bank Payment')
            ->where('warehouse_id', $request->warehouse_id)
            ->OrderBy('id', 'desc')->first();
            if ($code) {
                $codes = (int)$code->voucher_no + 1;
            }

            $data=$request->all();
            $data['voucher_no']=$codes;
            $data['warehouse_id'] = $request->warehouse_id;
            $voucherData = Vouchers::create($data);

            $totalDebit = 0;
            $count = count($request->amount);
            for ($i = 0; $i < $count; $i++) {
                // Top account | Debit account | Bank receive account

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $voucherData['account_id'];
                $generalVoucher->other_head_id = $request->party_id[$i];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->credit = $request->amount[$i];
                $generalVoucher->save();

                // Below account | Credit account | Bank paid account
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $voucherData['id'];
                $generalVoucher->account_head_id = $request->party_id[$i];
                $generalVoucher->other_head_id = $voucherData['account_id'];
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->date = $voucherData['voucher_date'];
                $generalVoucher->voucher_no = $voucherData['voucher_no'];
                $generalVoucher->cheque_no = $request->cheque_no[$i];
                $generalVoucher->cheque_date = $request->cheque_date[$i];
                $generalVoucher->v_type = $request->v_type;
                $generalVoucher->narration = $request->narration[$i];
                $generalVoucher->bank_id = $voucherData['account_id'];
                $generalVoucher->debit = $request->amount[$i];
                $generalVoucher->save();


                // $totalDebit += $request->amount[$i];
            }

            // $voucherData->total_debit = $totalDebit;
            // $voucherData->save();

            return redirect()->back()->with('flash_message', 'Voucher Added Successfully!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $edit = Vouchers::where('voucher_no', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Bank Payment')
            ->first();

        if ($edit) {
            $edit = GeneralVoucher::with('parties')
                ->where('v_type', 'Bank Payment')
                ->where('debit', '!=', 0)
                ->where('voucher_no', $request->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $edit, 'Accountsbelow' => $Accountsbelow]);

            // return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $BankReceipt = Vouchers::where('voucher_no', '>', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Bank Payment')
            ->min('voucher_no');

        if ($BankReceipt) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Bank Payment')
                ->where('debit', '!=', 0)
                ->where('voucher_no', $BankReceipt)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $data, 'Accountsbelow' => $Accountsbelow]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
         $BankReceipt = Vouchers::where('voucher_no', '<', $request->voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
            ->where('v_type', 'Bank Payment')
            ->max('voucher_no');

        if ($BankReceipt) {
            $data = GeneralVoucher::with('parties')
                ->where('v_type', 'Bank Payment')
                ->where('voucher_no', $BankReceipt)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->where('debit', '!=', 0)
                ->get();

                $Accountsbelow = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                // ->pluck('party_name', 'id')
                ->get(['id', 'party_name', 'code']);

            return Response::json(['data' => $data, 'Accountsbelow' => $Accountsbelow]);

            // return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucher = Vouchers::where('voucher_no', $request->delete_voucher_no)
        ->where('warehouse_id', '=', $request->delete_warehouseID)
        ->where('v_type', 'Bank Payment')->first();
        if ($voucher) {
            // Vouchers::findOrFail($voucher->id)->delete();

            Vouchers::where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('voucher_no', $request->delete_voucher_no)->where('v_type', 'Bank Payment')->delete();
            GeneralVoucher::where('voucher_id', $voucher->id)
            ->where('warehouse_id', '=', $request->delete_warehouseID)
            ->where('v_type', 'Bank Payment')->delete();
            // LedgerDetailWise::where('voucher_id', $voucher->id)->where('voucher_type', 'Bank Payment')->delete();
            Session::flash('flash_message', 'Voucher Deleted Successfully!');
            return redirect('bank-payments');
            return redirect()->back()->with('flash_message', 'Bank Receipt Voucher has been Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }   public function ProPrint(){
        $data = Product::Orderby('id', 'asc')->delete();
        return "Done";
    }public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/bank-payment');


        $voucher_no = $request->voucher_no;
        $vouchers = Vouchers::where('voucher_no', $voucher_no)
        ->where('warehouse_id', '=', $request->warehouseID)
        ->where('v_type', 'Bank Payment')->first();
        if($vouchers){
        // $generalVoucher = GeneralVoucher::with(['voucher' => function ($query) {
        //     $query->with('parties:id,party_name,address');
        // }])
        //     ->with('parties:id,party_name,code')
        //     ->where('v_type', 'Bank Payment')
        //     ->where('voucher_no', $voucher_no)
        //     ->where('warehouse_id', '=', $request->warehouseID)
        //     ->where('debit', '!=', 0)
        //     ->get();

            $generalVoucher = Vouchers::with(['voucher_details' => function($query){
                $query->where('debit', '!=', 0);
                $query->where('v_type', '=', "Bank Payment");
            }])
                ->with('parties:id,code,party_name', 'warehouse:id,name', 'billers:id,name')
                ->where('v_type', 'Bank Payment')
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('id', $vouchers->id)
                // ->where('voucher_no', $vouchers->voucher_no)
                ->where('warehouse_id', '=', $request->warehouseID)
                ->get();
        $pdf = PDF::loadView('bank-payments.invoice', compact('generalVoucher'));
        $fileName =  'Bank-Payment-Voucher' . $voucher_no . '.pdf';
        $pdf->save(base_path('upload/bank-payment/' . $fileName));
        return $fileName;
    }else{
        return false;
    }
    }   public function Listings_pro(){
        // $data = Product::Orderby('id', 'asc')->delete();
        $wh = Warehouse::all();
        $sum = 0;
        foreach($wh as $ones){
            $sum = $sum + 1;
            $ones->id = $ones->id.$sum;
            $ones->save();
        }
        return "Done";
    }


}