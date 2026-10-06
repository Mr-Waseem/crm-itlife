<ul class="navbar-nav">
    <li class="nav-item active">
        <a class="nav-link" href="{{ URL::to('/home') }}"><span class="active-item-here"></span><i
                class="fa fa-dashboard mr-5"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Defination
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item d-none" style="padding-top: 15px;"><a class="nav-link"
                    href="{{ URL::to('/departments') }}">Departments</a></li>
            <li class="nav-item dropdown" style="padding-top: 15px;"><a class="nav-link dropdown-item dropdown-toggle"
                    href="#">Account
                    Groups &emsp;</a>
                <ul class="dropdown-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ URL::to('/account-group') }}">Account Group 1</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ URL::to('/account-group2') }}">Account Group 2</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ URL::to('/account-group3') }}">Account Group 3</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Product
                    Information &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('products') }}">Product Information</a>
                    </li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('product-group') }}">Product Group</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Account &
                    Party Profile &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('parties') }}">Chart of Account</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('customers') }}">Customer Profile</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('supplier') }}">Supplier Profile</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('purchasers') }}">Purchasers Profile</a>
                    </li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('trade-group') }}">Trade Group</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Opening
                    &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('opening-stock') }}">Opening Stock</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('opening-balance') }}">Opening Balance</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span>System</span>
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item" style="padding-top: 15px;"><a class="nav-link" href="{{ URL::to('/users') }}">User
                    Information</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('user-rights') }}">User
                    Rights</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span>Gate Pass</span>
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item" style="padding-top: 15px;"><a class="nav-link"
                    href="{{ URL::to('inward-gatepass') }}">Inward Gate Pass &emsp;</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('purchaser-stock') }}">Purchaser Stock &emsp;</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('stock-transfer') }}">Stock Transfer (Production)
                    &emsp;</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('grn') }}">GRN&emsp;</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('purhcaser-report') }}">Purchaser
                    Report&emsp;</a>
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Edit
        </a>

        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item" style="padding-top: 15px;"><a class="nav-link"
                    href="{{ URL::to('request-generate') }}">Request Generate</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('banks') }}">Bank Information</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('cities') }}">City Information</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('warehouses') }}">Godown</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('recipe-creation') }}">Recipe Creation</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="{{ URL::to('production') }}">Production</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Financial
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item dropdown" style="padding-top: 15px;"><a
                    class="nav-link dropdown-item dropdown-toggle" href="javascript:void(0);">Vouchers &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('cash-receipts') }}">Cash Receipt
                            Voucher</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('cash-payments') }}">Cash Payment
                            Voucher</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('bank-receipts') }}">Bank Receipt
                            Voucher</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('bank-payments') }}">Bank Payment
                            Voucher</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('journal-voucher') }}">Journal Voucher</a>
                    </li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('pending-voucher') }}">Pending
                            Vouchers</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Reports
                    &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('financial-reports/activity') }}">Account
                            Activity</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Profit / (Loss) Report</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Balance Sheet Report</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Trial Balance Report</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Cash Book Report</a></li>
                    <li class="d-none"><a class="dropdown-item nav-link" href="#">User Log</a></li>
                    <li class="d-none"><a class="dropdown-item nav-link" href="#">Daily Log</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Sales
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item dropdown" style="padding-top: 15px;"><a
                    class="nav-link dropdown-item dropdown-toggle" href="#">Vouchers &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('sales-order') }}">Sale Order</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('delivery-challan') }}">Delivery
                            Challan</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('sales-voucher') }}">Sales Invoice</a>
                    </li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('sales-return') }}">Sales Return</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Customer
                    Reports &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="#">Listing</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('customer-reports/ledger') }}">Ledger</a>
                    </li>
                    <li><a class="dropdown-item nav-link"
                            href="{{ URL::to('customer-reports/balance') }}">Balance</a>
                    </li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('customer-reports/aging') }}">Aging
                            (Single)</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Aging (All)</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Sales
                    Reports &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('sales-voucher/report') }}">Sales</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('sales-return/report') }}">Sales
                            Return</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Purchase
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item dropdown" style="padding-top: 15px;"><a
                    class="nav-link dropdown-item dropdown-toggle" href="#">Vouchers &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('purchase') }}">Purchase Voucher</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('purchase-return') }}">Purchase Return</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Supplier
                    Reports &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="#">Balance</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Ledger</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Aging (Single)</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Purchase
                    Reports &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="#">Purchase Summary Report</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('purchase/report') }}">Purchase</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Purchase Return</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            Inventory
        </a>
        <ul class="dropdown-menu multilevel scale-up-left">
            <li class="nav-item dropdown" style="padding-top: 15px;"><a
                    class="nav-link dropdown-item dropdown-toggle" href="#">Voucher &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="#">Stock Received</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Stock Issue</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Stock Transfer</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown"><a class="nav-link dropdown-item dropdown-toggle" href="#">Reports
                    &emsp;</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item nav-link" href="#">Product Amount</a></li>
                    <li><a class="dropdown-item nav-link" href="#">Product Balance</a></li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('inventory/ledger') }}">Product Ledger</a>
                    </li>
                    <li><a class="dropdown-item nav-link" href="{{ URL::to('inventory/stock-report') }}">Stock
                            Report</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>
