
<?php $__env->startSection('head'); ?>
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255,255,255,0.85);
            --glass-border: rgba(255,255,255,0.3);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 8px 30px rgba(0,0,0,0.08);
            --shadow-lg: 0 15px 50px rgba(0,0,0,0.12);
            --shadow-glow-green: 0 8px 32px rgba(40,167,69,0.18);
            --shadow-glow-red: 0 8px 32px rgba(220,53,69,0.18);
            --shadow-glow-blue: 0 8px 32px rgba(0,123,255,0.18);
            --shadow-glow-purple: 0 8px 32px rgba(111,66,193,0.18);
            --radius: 16px;
            --radius-lg: 20px;
        }

        .content-wrapper { background: linear-gradient(135deg, #f0f2f5 0%, #e8ecf1 50%, #f5f7fa 100%) !important; }

        /* ===== Welcome Banner ===== */
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: var(--radius-lg);
            padding: 28px 35px;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: 0 10px 40px rgba(102,126,234,0.3);
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulseOrb 4s ease-in-out infinite;
        }
        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulseOrb 5s ease-in-out infinite reverse;
        }
        .welcome-banner h1 { font-family: 'Inter', sans-serif; font-size: 22px; font-weight: 700; margin: 0; position: relative; z-index: 1; }
        .welcome-banner .welcome-sub { font-size: 13px; opacity: 0.85; margin-top: 4px; position: relative; z-index: 1; }

        @keyframes pulseOrb {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.7; }
        }

        /* ===== Animated Cards ===== */
        .dash-card {
            border: none;
            border-radius: var(--radius);
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-md);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            overflow: hidden;
            position: relative;
            opacity: 0;
            transform: translateY(30px);
            animation: cardSlideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
        .dash-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(102,126,234,0.5), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }
        .dash-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }
        .dash-card:hover::before { opacity: 1; }

        .dash-card .card-body { padding: 22px; position: relative; z-index: 1; }

        .dash-card .card-icon {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            position: relative;
            flex-shrink: 0;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .dash-card:hover .card-icon {
            transform: rotateY(180deg) scale(1.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .dash-card .card-icon i { transition: transform 0.4s; backface-visibility: hidden; }
        .dash-card:hover .card-icon i { transform: rotateY(-180deg); }

        .dash-card .card-value {
            font-family: 'Inter', sans-serif;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        .dash-card .card-label {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            color: #8898aa;
            margin-top: 4px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .dash-card .card-sub {
            font-size: 11px;
            color: #adb5bd;
            margin-top: 2px;
            font-weight: 400;
        }

        /* Stagger animation delays */
        .row .col-xl-3:nth-child(1) .dash-card, .row .col-xl-2:nth-child(1) .dash-card, .row .col-xl-6:nth-child(1) .dash-card { animation-delay: 0.05s; }
        .row .col-xl-3:nth-child(2) .dash-card, .row .col-xl-2:nth-child(2) .dash-card, .row .col-xl-6:nth-child(2) .dash-card { animation-delay: 0.12s; }
        .row .col-xl-3:nth-child(3) .dash-card, .row .col-xl-2:nth-child(3) .dash-card { animation-delay: 0.19s; }
        .row .col-xl-3:nth-child(4) .dash-card, .row .col-xl-2:nth-child(4) .dash-card { animation-delay: 0.26s; }
        .row .col-xl-2:nth-child(5) .dash-card { animation-delay: 0.33s; }
        .row .col-xl-2:nth-child(6) .dash-card { animation-delay: 0.40s; }

        @keyframes cardSlideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card color accents */
        .dash-card.card-green { border-left: 4px solid #28a745; }
        .dash-card.card-green:hover { box-shadow: var(--shadow-glow-green); }
        .dash-card.card-red { border-left: 4px solid #dc3545; }
        .dash-card.card-red:hover { box-shadow: var(--shadow-glow-red); }
        .dash-card.card-blue { border-left: 4px solid #007bff; }
        .dash-card.card-blue:hover { box-shadow: var(--shadow-glow-blue); }
        .dash-card.card-purple { border-left: 4px solid #6f42c1; }
        .dash-card.card-purple:hover { box-shadow: var(--shadow-glow-purple); }
        .dash-card.card-orange { border-left: 4px solid #fd7e14; }
        .dash-card.card-orange:hover { box-shadow: 0 8px 32px rgba(253,126,20,0.18); }
        .dash-card.card-cyan { border-left: 4px solid #17a2b8; }
        .dash-card.card-cyan:hover { box-shadow: 0 8px 32px rgba(23,162,184,0.18); }

        /* ===== Section Titles ===== */
        .section-title {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 20px;
            border-left: 5px solid;
            margin-bottom: 22px;
            margin-top: 8px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 0 var(--radius) var(--radius) 0;
            box-shadow: var(--shadow-sm);
            letter-spacing: 0.3px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fadeSlideRight 0.5s ease forwards;
        }
        .section-title::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 120px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6));
            pointer-events: none;
        }

        @keyframes fadeSlideRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* ===== Chart Boxes ===== */
        .box {
            border: none !important;
            border-radius: var(--radius) !important;
            background: var(--glass-bg) !important;
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-md) !important;
            overflow: hidden;
            transition: all 0.35s ease;
            opacity: 0;
            animation: chartFadeIn 0.7s ease forwards;
            animation-delay: 0.2s;
        }
        .box:hover {
            box-shadow: var(--shadow-lg) !important;
            transform: translateY(-4px);
        }
        .box .box-header {
            background: linear-gradient(135deg, rgba(248,249,250,0.9) 0%, rgba(255,255,255,0.95) 100%) !important;
            border-bottom: 1px solid rgba(0,0,0,0.04) !important;
            padding: 16px 20px !important;
        }
        .box .box-header .box-title {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .box .box-body { padding: 20px !important; }

        @keyframes chartFadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ===== Stats Panel ===== */
        .stat-item {
            padding: 14px 16px;
            margin: 6px 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
            border: 1px solid rgba(0,0,0,0.04);
            border-bottom: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            border-radius: 3px;
            transition: width 0.3s;
        }
        .stat-item:nth-child(1)::before { background: #28a745; }
        .stat-item:nth-child(2)::before { background: #007bff; }
        .stat-item:nth-child(3)::before { background: #ffc107; }
        .stat-item:nth-child(4)::before { background: #17a2b8; }
        .stat-item:hover { transform: translateX(6px); background: #fff; box-shadow: var(--shadow-sm); }
        .stat-item:hover::before { width: 5px; }
        .stat-item:last-child { border-bottom: none; }
        .stat-item .stat-label {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            color: #8898aa;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }
        .stat-item .stat-value {
            font-family: 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        /* ===== Number Counter Animation ===== */
        .count-up {
            display: inline-block;
        }

        /* ===== Shimmer effect on card values ===== */
        .shimmer-text {
            background: linear-gradient(90deg, currentColor 40%, rgba(255,255,255,0.5) 50%, currentColor 60%);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ===== Floating Particles Background ===== */
        .particles-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            animation: floatParticle linear infinite;
        }
        @keyframes floatParticle {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(720deg); }
        }

        /* ===== Chart Boxes (Premium Glassmorphism) ===== */
        .chart-box {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            margin-bottom: 25px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .chart-box.chart-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .chart-box:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }
        .chart-box.chart-visible:hover {
            transform: translateY(-4px);
        }
        .chart-box-header {
            padding: 18px 24px 12px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .chart-box-header h4 {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #32325d;
            margin: 0;
            letter-spacing: 0.2px;
        }
        .chart-box-header h4 i {
            margin-right: 8px;
            font-size: 15px;
        }
        .chart-box-body {
            padding: 20px 24px 24px;
        }

        /* ===== Receivable/Payable Special ===== */
        .big-stat-card {
            border-radius: var(--radius);
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-md);
            padding: 24px 28px;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: cardSlideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            animation-delay: 0.15s;
        }
        .big-stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            transition: all 0.4s;
        }
        .big-stat-card.receivable-card { border-left: 5px solid #28a745; }
        .big-stat-card.receivable-card::after { background: radial-gradient(circle, rgba(40,167,69,0.08) 0%, transparent 70%); }
        .big-stat-card.receivable-card:hover { box-shadow: var(--shadow-glow-green); }
        .big-stat-card.payable-card { border-left: 5px solid #dc3545; }
        .big-stat-card.payable-card::after { background: radial-gradient(circle, rgba(220,53,69,0.08) 0%, transparent 70%); }
        .big-stat-card.payable-card:hover { box-shadow: var(--shadow-glow-red); }
        .big-stat-card:hover { transform: translateY(-6px) scale(1.01); }
        .big-stat-card .big-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; flex-shrink: 0;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        .big-stat-card .big-value {
            font-family: 'Inter', sans-serif;
            font-size: 26px; font-weight: 800; letter-spacing: -0.5px;
        }
        .big-stat-card .big-label {
            font-family: 'Inter', sans-serif;
            font-size: 12px; color: #8898aa; font-weight: 500;
            text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* ===== Responsive Fixes ===== */
        @media (max-width: 768px) {
            .dash-card .card-value { font-size: 22px; }
            .welcome-banner { padding: 20px; }
            .welcome-banner h1 { font-size: 18px; }
        }

        /* ===== Pulse dot for live indicator ===== */
        .live-dot {
            width: 8px; height: 8px; background: #28a745;
            border-radius: 50%; display: inline-block;
            margin-right: 6px; position: relative;
        }
        .live-dot::after {
            content: '';
            position: absolute;
            top: -3px; left: -3px;
            width: 14px; height: 14px;
            border-radius: 50%;
            background: rgba(40,167,69,0.3);
            animation: livePulse 2s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0; }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="content-wrapper">
        
        <div class="particles-bg" id="particlesBg"></div>

        <section class="content" style="position:relative; z-index:1; padding-top: 25px;">

            
            <div class="welcome-banner">
                <h1><i class="fa fa-rocket"></i> &nbsp;Welcome back, <?php echo e(Auth::User()->name); ?>!</h1>
                <div class="welcome-sub"><span class="live-dot"></span> Live Dashboard &mdash; <?php echo e($user_department->name); ?> Department &mdash; <?php echo e(now()->format('l, d M Y')); ?></div>
            </div>

            
            
            
            <div class="section-title" style="border-color: #28a745; color: #28a745;">
                <i class="fa fa-shopping-cart"></i> Sales Overview
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-green">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="fa fa-calendar-day"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value text-success count-up" data-target="<?php echo e($dailySalesAmount ?? 0); ?>"><?php echo e(number_format($dailySalesAmount ?? 0)); ?></div>
                                <div class="card-label">Today's Sales</div>
                                <div class="card-sub"><?php echo e($dailySalesInvoices ?? 0); ?> Invoices</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-cyan">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #17a2b8, #20c997);">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value text-info count-up" data-target="<?php echo e($monthlySalesAmount ?? 0); ?>"><?php echo e(number_format($monthlySalesAmount ?? 0)); ?></div>
                                <div class="card-label">Monthly Sales</div>
                                <div class="card-sub"><?php echo e($monthlySalesInvoices ?? 0); ?> Invoices</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-blue">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #007bff, #6610f2);">
                                <i class="fa fa-chart-line"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value text-primary count-up" data-target="<?php echo e($totalSalesAmount ?? 0); ?>"><?php echo e(number_format($totalSalesAmount ?? 0)); ?></div>
                                <div class="card-label">Total Sales</div>
                                <div class="card-sub"><?php echo e($totalSalesInvoices ?? 0); ?> All-time Invoices</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-orange">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                <i class="fa fa-money-bill-wave"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value" style="color:#e67e22;"><?php echo e(number_format($monthlyPurchaseAmount ?? 0)); ?></div>
                                <div class="card-label">Monthly Purchases</div>
                                <div class="card-sub">Total: <?php echo e(number_format($totalPurchaseAmount ?? 0)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            
            
            <div class="section-title" style="border-color: #dc3545; color: #dc3545;">
                <i class="fa fa-file-invoice-dollar"></i> Expenses Overview
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-red">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #dc3545, #e74c3c);">
                                <i class="fa fa-calendar-day"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value text-danger count-up" data-target="<?php echo e($dailyExpenses ?? 0); ?>"><?php echo e(number_format($dailyExpenses ?? 0)); ?></div>
                                <div class="card-label">Today's Expenses</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-red">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value text-danger count-up" data-target="<?php echo e($monthlyExpenses ?? 0); ?>"><?php echo e(number_format($monthlyExpenses ?? 0)); ?></div>
                                <div class="card-label">Monthly Expenses</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card card-red">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #c0392b, #922B21);">
                                <i class="fa fa-receipt"></i>
                            </div>
                            <div class="ml-15">
                                <div class="card-value text-danger count-up" data-target="<?php echo e($totalExpenses ?? 0); ?>"><?php echo e(number_format($totalExpenses ?? 0)); ?></div>
                                <div class="card-label">Total Expenses</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3">
                    <div class="dash-card" style="border-left: 4px solid #6c757d;">
                        <div class="card-body d-flex align-items-center">
                            <div class="card-icon" style="background: linear-gradient(135deg, #6c757d, #495057);">
                                <i class="fa fa-percentage"></i>
                            </div>
                            <div class="ml-15">
                                <?php $expenseRatio = $totalSalesAmount > 0 ? round(($totalExpenses / $totalSalesAmount) * 100, 1) : 0; ?>
                                <div class="card-value text-secondary"><?php echo e($expenseRatio); ?>%</div>
                                <div class="card-label">Expense-to-Sales Ratio</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            
            
            <div class="section-title" style="border-color: #6f42c1; color: #6f42c1;">
                <i class="fa fa-users"></i> Parties & Business Info
            </div>
            <div class="row">
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="dash-card card-purple">
                        <div class="card-body text-center">
                            <div class="card-icon mx-auto mb-2" style="background: linear-gradient(135deg, #6f42c1, #6610f2);">
                                <i class="fa fa-address-book"></i>
                            </div>
                            <div class="card-value count-up" data-target="<?php echo e($totalParties ?? 0); ?>"><?php echo e($totalParties ?? 0); ?></div>
                            <div class="card-label">Total Parties</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="dash-card card-green">
                        <div class="card-body text-center">
                            <div class="card-icon mx-auto mb-2" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="fa fa-user-check"></i>
                            </div>
                            <div class="card-value text-success count-up" data-target="<?php echo e($activeParties ?? 0); ?>"><?php echo e($activeParties ?? 0); ?></div>
                            <div class="card-label">Active Parties</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="dash-card card-cyan">
                        <div class="card-body text-center">
                            <div class="card-icon mx-auto mb-2" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                                <i class="fa fa-user-tie"></i>
                            </div>
                            <div class="card-value text-info count-up" data-target="<?php echo e($totalCustomers ?? 0); ?>"><?php echo e($totalCustomers ?? 0); ?></div>
                            <div class="card-label">Customers</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="dash-card card-orange">
                        <div class="card-body text-center">
                            <div class="card-icon mx-auto mb-2" style="background: linear-gradient(135deg, #fd7e14, #e67e22);">
                                <i class="fa fa-truck"></i>
                            </div>
                            <div class="card-value count-up" style="color:#e67e22;" data-target="<?php echo e($totalSuppliers ?? 0); ?>"><?php echo e($totalSuppliers ?? 0); ?></div>
                            <div class="card-label">Suppliers</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="dash-card card-blue">
                        <div class="card-body text-center">
                            <div class="card-icon mx-auto mb-2" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                                <i class="fa fa-boxes"></i>
                            </div>
                            <div class="card-value text-primary count-up" data-target="<?php echo e($totalProducts ?? 0); ?>"><?php echo e($totalProducts ?? 0); ?></div>
                            <div class="card-label">Products</div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <?php $netProfit = ($monthlySalesAmount ?? 0) - ($monthlyExpenses ?? 0) - ($monthlyPurchaseAmount ?? 0); ?>
                    <div class="dash-card <?php echo e($netProfit >= 0 ? 'card-green' : 'card-red'); ?>">
                        <div class="card-body text-center">
                            <div class="card-icon mx-auto mb-2" style="background: linear-gradient(135deg, <?php echo e($netProfit >= 0 ? '#28a745, #155724' : '#dc3545, #922B21'); ?>);">
                                <i class="fa fa-balance-scale"></i>
                            </div>
                            <div class="card-value <?php echo e($netProfit >= 0 ? 'text-success' : 'text-danger'); ?>"><?php echo e(number_format($netProfit)); ?></div>
                            <div class="card-label">Monthly Net</div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row">
                <div class="col-xl-6 col-md-6 col-12 mb-3">
                    <div class="big-stat-card receivable-card">
                        <div class="d-flex align-items-center">
                            <div class="big-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="fa fa-hand-holding-usd"></i>
                            </div>
                            <div class="ml-15">
                                <div class="big-value text-success count-up" data-target="<?php echo e($totalReceivable ?? 0); ?>"><?php echo e(number_format($totalReceivable ?? 0)); ?></div>
                                <div class="big-label">Total Receivable (from Customers)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-12 mb-3">
                    <div class="big-stat-card payable-card">
                        <div class="d-flex align-items-center">
                            <div class="big-icon" style="background: linear-gradient(135deg, #dc3545, #c0392b);">
                                <i class="fa fa-file-invoice-dollar"></i>
                            </div>
                            <div class="ml-15">
                                <div class="big-value text-danger count-up" data-target="<?php echo e($totalPayable ?? 0); ?>"><?php echo e(number_format($totalPayable ?? 0)); ?></div>
                                <div class="big-label">Total Payable (to Suppliers)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            
            

            
            <div class="section-title" style="border-color: #28a745; color: #28a745;">
                <i class="fa fa-chart-line"></i> Sales Charts
            </div>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-chart-area text-success"></i> Sales Trend (Last 12 Months)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="salesMonthChart" style="height:320px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-calculator text-success"></i> Sales Statistics (12 Months)</h4>
                        </div>
                        <div class="chart-box-body">
                            <?php
                                $salesCol = collect($salesMonthValues ?? []);
                                $salesAvg = $salesCol->count() > 0 ? $salesCol->sum() / $salesCol->count() : 0;
                                $salesMax = $salesCol->max() ?? 0;
                                $salesMin = $salesCol->filter()->min() ?? 0;
                                $salesTotal12 = $salesCol->sum();
                            ?>
                            <div class="stat-item" style="border-left-color: #28a745;">
                                <div class="stat-label">Average Monthly Sales</div>
                                <div class="stat-value text-success"><?php echo e(number_format($salesAvg)); ?></div>
                            </div>
                            <div class="stat-item" style="border-left-color: #007bff;">
                                <div class="stat-label">Highest Month</div>
                                <div class="stat-value text-primary"><?php echo e(number_format($salesMax)); ?></div>
                            </div>
                            <div class="stat-item" style="border-left-color: #ffc107;">
                                <div class="stat-label">Lowest Month (Non-zero)</div>
                                <div class="stat-value text-warning"><?php echo e(number_format($salesMin)); ?></div>
                            </div>
                            <div class="stat-item" style="border-left-color: #17a2b8;">
                                <div class="stat-label">Total (12 Months)</div>
                                <div class="stat-value text-info"><?php echo e(number_format($salesTotal12)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row">
                <div class="col-12">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-calendar text-primary"></i> Daily Sales (Last 30 Days)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="salesDailyChart" style="height:280px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-user-plus text-info"></i> New Customers / Parties (Last 12 Months)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="customersMonthChart" style="height:300px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-exchange-alt text-warning"></i> Sales vs Purchases (Last 12 Months)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="salesVsPurchaseChart" style="height:300px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="section-title" style="border-color: #dc3545; color: #dc3545;">
                <i class="fa fa-chart-pie"></i> Expenses Charts
            </div>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-chart-area text-danger"></i> Expenses Trend (Last 12 Months)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="expensesMonthChart" style="height:320px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-calculator text-danger"></i> Expenses Statistics (12 Months)</h4>
                        </div>
                        <div class="chart-box-body">
                            <?php
                                $expCol = collect($expensesMonthValues ?? []);
                                $expAvg = $expCol->count() > 0 ? $expCol->sum() / $expCol->count() : 0;
                                $expMax = $expCol->max() ?? 0;
                                $expMin = $expCol->filter()->min() ?? 0;
                                $expTotal12 = $expCol->sum();
                            ?>
                            <div class="stat-item" style="border-left-color: #dc3545;">
                                <div class="stat-label">Average Monthly Expense</div>
                                <div class="stat-value text-danger"><?php echo e(number_format($expAvg)); ?></div>
                            </div>
                            <div class="stat-item" style="border-left-color: #dc3545;">
                                <div class="stat-label">Highest Month</div>
                                <div class="stat-value text-danger"><?php echo e(number_format($expMax)); ?></div>
                            </div>
                            <div class="stat-item" style="border-left-color: #ffc107;">
                                <div class="stat-label">Lowest Month (Non-zero)</div>
                                <div class="stat-value text-warning"><?php echo e(number_format($expMin)); ?></div>
                            </div>
                            <div class="stat-item" style="border-left-color: #dc3545;">
                                <div class="stat-label">Total (12 Months)</div>
                                <div class="stat-value text-danger"><?php echo e(number_format($expTotal12)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="row">
                <div class="col-12">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-calendar text-danger"></i> Daily Expenses (Last 30 Days)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="expensesDailyChart" style="height:280px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="section-title" style="border-color: #007bff; color: #007bff;">
                <i class="fa fa-chart-bar"></i> Comparisons & Top Performers
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-balance-scale text-info"></i> Sales vs Expenses (Monthly)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="salesVsExpensesChart" style="height:300px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-trophy text-warning"></i> Top 5 Products (This Month)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="topProductsChart" style="height:300px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-users text-success"></i> Top 5 Customers (This Month)</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="topCustomersChart" style="height:300px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><i class="fa fa-pie-chart text-info"></i> Monthly Financial Overview</h4>
                        </div>
                        <div class="chart-box-body">
                            <canvas id="financialPieChart" style="height:300px; width:100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php if(Session::has('access_granted')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Sorry...',
                text: "<?php echo e(Session::get('access_granted')); ?>"
            })
        </script>
    <?php endif; ?>

    
    <script type="application/json" id="data-sales-month-labels"><?php echo json_encode($salesMonthLabels ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-sales-month-values"><?php echo json_encode($salesMonthValues ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-sales-daily-labels"><?php echo json_encode($salesDailyLabels ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-sales-daily-values"><?php echo json_encode($salesDailyValues ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-purchase-month-values"><?php echo json_encode($purchaseMonthValues ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-customers-month-labels"><?php echo json_encode($customersMonthLabels ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-customers-month-values"><?php echo json_encode($customersMonthValues ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-expenses-month-labels"><?php echo json_encode($expensesMonthLabels ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-expenses-month-values"><?php echo json_encode($expensesMonthValues ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-expenses-daily-labels"><?php echo json_encode($expensesDailyLabels ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-expenses-daily-values"><?php echo json_encode($expensesDailyValues ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-top-product-names"><?php echo json_encode($topProductNames ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-top-product-amounts"><?php echo json_encode($topProductAmounts ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-top-customer-names"><?php echo json_encode($topCustomerNames ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-top-customer-amounts"><?php echo json_encode($topCustomerAmounts ?? [], 15, 512) ?></script>
    <script type="application/json" id="data-monthly-sales"><?php echo e($monthlySalesAmount ?? 0); ?></script>
    <script type="application/json" id="data-monthly-purchase"><?php echo e($monthlyPurchaseAmount ?? 0); ?></script>
    <script type="application/json" id="data-monthly-expenses"><?php echo e($monthlyExpenses ?? 0); ?></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== PARTICLE GENERATOR =====
        (function(){
            var container = document.getElementById('particlesBg');
            if(!container) return;
            var colors = ['#28a745','#007bff','#6f42c1','#17a2b8','#dc3545','#fd7e14'];
            for(var i=0; i<25; i++){
                var p = document.createElement('div');
                p.className = 'particle';
                var size = Math.random()*6+3;
                p.style.width = size+'px';
                p.style.height = size+'px';
                p.style.left = Math.random()*100+'%';
                p.style.background = colors[Math.floor(Math.random()*colors.length)];
                p.style.animationDuration = (Math.random()*20+15)+'s';
                p.style.animationDelay = (Math.random()*15)+'s';
                container.appendChild(p);
            }
        })();

        // ===== COUNT-UP ANIMATION =====
        var countEls = document.querySelectorAll('.count-up');
        var observer = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if(entry.isIntersecting){
                    var el = entry.target;
                    var target = parseInt(el.getAttribute('data-target')) || 0;
                    if(target === 0) return;
                    var duration = 1800;
                    var start = 0;
                    var startTime = null;
                    function step(ts){
                        if(!startTime) startTime = ts;
                        var progress = Math.min((ts - startTime)/duration, 1);
                        var eased = 1 - Math.pow(1 - progress, 3);
                        var current = Math.floor(eased * target);
                        el.textContent = new Intl.NumberFormat().format(current);
                        if(progress < 1) requestAnimationFrame(step);
                    }
                    requestAnimationFrame(step);
                    observer.unobserve(el);
                }
            });
        }, {threshold: 0.3});
        countEls.forEach(function(el){ observer.observe(el); });

        // ===== CHART.JS ENHANCED =====
        function readJson(id, fallback) {
            try {
                var el = document.getElementById(id);
                if (!el) return fallback;
                return JSON.parse(el.textContent || JSON.stringify(fallback));
            } catch(e) { return fallback; }
        }

        function money(v) {
            return new Intl.NumberFormat(undefined, {minimumFractionDigits:0, maximumFractionDigits:0}).format(Number(v||0));
        }

        function gradient(canvas, c1, c2) {
            if (!canvas) return c1;
            var g = canvas.getContext('2d').createLinearGradient(0,0,0,canvas.height||400);
            g.addColorStop(0,c1); g.addColorStop(1,c2);
            return g;
        }

        // Premium tooltip style
        var tooltipStyle = {
            backgroundColor:'rgba(15,23,42,0.92)',
            titleColor:'#f8fafc',
            bodyColor:'#e2e8f0',
            borderColor:'rgba(255,255,255,0.1)',
            borderWidth:1,
            padding:14,
            cornerRadius:10,
            titleFont:{size:13,weight:'600',family:'Inter'},
            bodyFont:{size:12,family:'Inter'},
            displayColors:true,
            boxPadding:6,
            caretSize:8
        };

        var lineOpts = function(){ return {
            responsive:true, maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            animation:{duration:1200,easing:'easeOutQuart'},
            plugins:{
                legend:{display:true,position:'top',labels:{usePointStyle:true,pointStyle:'circle',padding:20,font:{family:'Inter',size:12,weight:'500'}}},
                tooltip:Object.assign({},tooltipStyle,{callbacks:{label:function(c){return ' '+c.dataset.label+': '+money(c.raw);}}})
            },
            scales:{
                x:{grid:{color:'rgba(0,0,0,0.04)',borderDash:[4,4]},ticks:{font:{family:'Inter',size:11}}},
                y:{grid:{color:'rgba(0,0,0,0.04)',borderDash:[4,4]},ticks:{callback:function(v){return money(v);},font:{family:'Inter',size:11}}}
            }
        }};

        var barOpts = function(){ return {
            responsive:true, maintainAspectRatio:false,
            animation:{duration:1200, easing:'easeOutQuart'},
            plugins:{
                legend:{display:true,labels:{usePointStyle:true,pointStyle:'circle',padding:20,font:{family:'Inter',size:12,weight:'500'}}},
                tooltip:Object.assign({},tooltipStyle,{callbacks:{label:function(c){return ' '+c.dataset.label+': '+money(c.raw);}}})
            },
            scales:{
                x:{grid:{display:false},ticks:{font:{family:'Inter',size:11}}},
                y:{grid:{color:'rgba(0,0,0,0.04)',borderDash:[4,4]},ticks:{callback:function(v){return money(v);},font:{family:'Inter',size:11}}}
            }
        }};

        // Data
        var salesML = readJson('data-sales-month-labels',[]);
        var salesMV = readJson('data-sales-month-values',[]);
        var salesDL = readJson('data-sales-daily-labels',[]);
        var salesDV = readJson('data-sales-daily-values',[]);
        var purchMV = readJson('data-purchase-month-values',[]);
        var custML = readJson('data-customers-month-labels',[]);
        var custMV = readJson('data-customers-month-values',[]);
        var expML = readJson('data-expenses-month-labels',[]);
        var expMV = readJson('data-expenses-month-values',[]);
        var expDL = readJson('data-expenses-daily-labels',[]);
        var expDV = readJson('data-expenses-daily-values',[]);
        var topPN = readJson('data-top-product-names',[]);
        var topPA = readJson('data-top-product-amounts',[]);
        var topCN = readJson('data-top-customer-names',[]);
        var topCA = readJson('data-top-customer-amounts',[]);
        var mSales = readJson('data-monthly-sales',0);
        var mPurch = readJson('data-monthly-purchase',0);
        var mExp = readJson('data-monthly-expenses',0);

        // 1. Sales Trend (12 months)
        var c1 = document.getElementById('salesMonthChart');
        if(c1){ new Chart(c1.getContext('2d'),{type:'line',data:{labels:salesML,datasets:[{label:'Sales Amount',data:salesMV,borderColor:'rgba(40,167,69,1)',backgroundColor:gradient(c1,'rgba(40,167,69,0.35)','rgba(40,167,69,0.02)'),fill:true,tension:0.4,pointRadius:5,pointHoverRadius:9,pointBackgroundColor:'#fff',pointBorderColor:'rgba(40,167,69,1)',pointBorderWidth:2.5,pointHoverBorderWidth:3,borderWidth:3}]},options:lineOpts()}); }

        // 2. Daily Sales (30 days)
        var c2 = document.getElementById('salesDailyChart');
        if(c2){ new Chart(c2.getContext('2d'),{type:'bar',data:{labels:salesDL,datasets:[{label:'Daily Sales',data:salesDV,backgroundColor:gradient(c2,'rgba(0,123,255,0.8)','rgba(0,123,255,0.4)'),borderColor:'rgba(0,123,255,1)',borderWidth:0,borderRadius:8,hoverBackgroundColor:'rgba(0,123,255,0.95)',borderSkipped:false}]},options:barOpts()}); }

        // 3. Customers Trend
        var c3 = document.getElementById('customersMonthChart');
        if(c3){ new Chart(c3.getContext('2d'),{type:'bar',data:{labels:custML,datasets:[{label:'New Customers',data:custMV,backgroundColor:gradient(c3,'rgba(23,162,184,0.85)','rgba(23,162,184,0.5)'),borderColor:'rgba(23,162,184,1)',borderWidth:0,borderRadius:8,hoverBackgroundColor:'rgba(23,162,184,1)',borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,animation:{duration:1200,easing:'easeOutQuart'},plugins:{legend:{display:true,labels:{usePointStyle:true,pointStyle:'circle',padding:20,font:{family:'Inter',size:12}}},tooltip:tooltipStyle},scales:{x:{grid:{display:false},ticks:{font:{family:'Inter',size:11}}},y:{grid:{color:'rgba(0,0,0,0.04)'},ticks:{stepSize:1,font:{family:'Inter',size:11}}}}}}); }

        // 4. Sales vs Purchases (12 months)
        var c4 = document.getElementById('salesVsPurchaseChart');
        if(c4){ new Chart(c4.getContext('2d'),{type:'line',data:{labels:salesML,datasets:[{label:'Sales',data:salesMV,borderColor:'rgba(40,167,69,1)',backgroundColor:gradient(c4,'rgba(40,167,69,0.25)','rgba(40,167,69,0.01)'),fill:true,tension:0.4,borderWidth:2.5,pointRadius:4,pointHoverRadius:7,pointBackgroundColor:'#fff',pointBorderColor:'rgba(40,167,69,1)',pointBorderWidth:2},{label:'Purchases',data:purchMV,borderColor:'rgba(255,193,7,1)',backgroundColor:gradient(c4,'rgba(255,193,7,0.25)','rgba(255,193,7,0.01)'),fill:true,tension:0.4,borderWidth:2.5,pointRadius:4,pointHoverRadius:7,pointBackgroundColor:'#fff',pointBorderColor:'rgba(255,193,7,1)',pointBorderWidth:2}]},options:lineOpts()}); }

        // 5. Expenses Trend (12 months)
        var c5 = document.getElementById('expensesMonthChart');
        if(c5){ new Chart(c5.getContext('2d'),{type:'line',data:{labels:expML,datasets:[{label:'Monthly Expenses',data:expMV,borderColor:'rgba(220,53,69,1)',backgroundColor:gradient(c5,'rgba(220,53,69,0.3)','rgba(220,53,69,0.02)'),fill:true,tension:0.4,pointRadius:5,pointBackgroundColor:'#fff',pointBorderColor:'rgba(220,53,69,1)',pointBorderWidth:2.5,pointHoverRadius:9,pointHoverBorderWidth:3,borderWidth:3}]},options:lineOpts()}); }

        // 6. Daily Expenses (30 days)
        var c6 = document.getElementById('expensesDailyChart');
        if(c6){ new Chart(c6.getContext('2d'),{type:'bar',data:{labels:expDL,datasets:[{label:'Daily Expenses',data:expDV,backgroundColor:gradient(c6,'rgba(220,53,69,0.8)','rgba(220,53,69,0.45)'),borderColor:'rgba(220,53,69,1)',borderWidth:0,borderRadius:8,hoverBackgroundColor:'rgba(220,53,69,0.95)',borderSkipped:false}]},options:barOpts()}); }

        // 7. Sales vs Expenses (monthly comparison)
        var c7 = document.getElementById('salesVsExpensesChart');
        if(c7){ new Chart(c7.getContext('2d'),{type:'bar',data:{labels:salesML,datasets:[{label:'Sales',data:salesMV,backgroundColor:'rgba(40,167,69,0.8)',borderColor:'rgba(40,167,69,1)',borderWidth:0,borderRadius:6,borderSkipped:false},{label:'Expenses',data:expMV,backgroundColor:'rgba(220,53,69,0.8)',borderColor:'rgba(220,53,69,1)',borderWidth:0,borderRadius:6,borderSkipped:false}]},options:barOpts()}); }

        // 8. Top 5 Products
        var c8 = document.getElementById('topProductsChart');
        if(c8 && topPN.length > 0){
            var prodColors = ['rgba(0,123,255,0.85)','rgba(40,167,69,0.85)','rgba(255,193,7,0.85)','rgba(23,162,184,0.85)','rgba(111,66,193,0.85)'];
            new Chart(c8.getContext('2d'),{type:'bar',data:{labels:topPN,datasets:[{label:'Sales Amount',data:topPA,backgroundColor:prodColors,borderWidth:0,borderRadius:8,borderSkipped:false}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,animation:{duration:1200,easing:'easeOutQuart'},plugins:{legend:{display:false},tooltip:Object.assign({},tooltipStyle,{callbacks:{label:function(c){return ' '+money(c.raw);}}})},scales:{x:{ticks:{callback:function(v){return money(v);},font:{family:'Inter',size:11}},grid:{color:'rgba(0,0,0,0.04)'}},y:{grid:{display:false},ticks:{font:{family:'Inter',size:11,weight:'500'}}}}}});
        } else if(c8){ c8.parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="fa fa-box-open fa-2x mb-2" style="opacity:0.4"></i><p>No sales data for this month</p></div>'; }

        // 9. Top 5 Customers
        var c9 = document.getElementById('topCustomersChart');
        if(c9 && topCN.length > 0){
            var custColors = ['rgba(40,167,69,0.85)','rgba(0,123,255,0.85)','rgba(253,126,20,0.85)','rgba(220,53,69,0.85)','rgba(23,162,184,0.85)'];
            new Chart(c9.getContext('2d'),{type:'bar',data:{labels:topCN,datasets:[{label:'Sales Amount',data:topCA,backgroundColor:custColors,borderWidth:0,borderRadius:8,borderSkipped:false}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,animation:{duration:1200,easing:'easeOutQuart'},plugins:{legend:{display:false},tooltip:Object.assign({},tooltipStyle,{callbacks:{label:function(c){return ' '+money(c.raw);}}})},scales:{x:{ticks:{callback:function(v){return money(v);},font:{family:'Inter',size:11}},grid:{color:'rgba(0,0,0,0.04)'}},y:{grid:{display:false},ticks:{font:{family:'Inter',size:11,weight:'500'}}}}}});
        } else if(c9){ c9.parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="fa fa-users fa-2x mb-2" style="opacity:0.4"></i><p>No customer data for this month</p></div>'; }

        // 10. Financial Pie - Doughnut
        var c10 = document.getElementById('financialPieChart');
        if(c10){
            var pieData = [Number(mSales)||0, Number(mPurch)||0, Number(mExp)||0];
            var hasData = pieData.some(function(v){return v > 0;});
            if(hasData){
                new Chart(c10.getContext('2d'),{type:'doughnut',data:{labels:['Sales','Purchases','Expenses'],datasets:[{data:pieData,backgroundColor:['rgba(40,167,69,0.85)','rgba(255,193,7,0.85)','rgba(220,53,69,0.85)'],borderColor:['#fff','#fff','#fff'],borderWidth:3,hoverBorderColor:['rgba(40,167,69,1)','rgba(255,193,7,1)','rgba(220,53,69,1)'],hoverOffset:12}]},options:{responsive:true,maintainAspectRatio:false,cutout:'60%',animation:{animateRotate:true,duration:1500,easing:'easeOutQuart'},plugins:{legend:{display:true,position:'bottom',labels:{usePointStyle:true,pointStyle:'circle',padding:20,font:{family:'Inter',size:12,weight:'500'}}},tooltip:Object.assign({},tooltipStyle,{callbacks:{label:function(c){var t=c.dataset.data.reduce(function(a,b){return a+b;},0);var p=t>0?((c.raw/t)*100).toFixed(1):0;return ' '+c.label+': '+money(c.raw)+' ('+p+'%)';}}})}}});
            } else {
                c10.parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="fa fa-chart-pie fa-2x mb-2" style="opacity:0.4"></i><p>No financial data for this month</p></div>';
            }
        }

        // ===== Scroll-reveal for chart boxes =====
        var chartBoxes = document.querySelectorAll('.chart-box');
        var chartObserver = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if(entry.isIntersecting){
                    entry.target.classList.add('chart-visible');
                    chartObserver.unobserve(entry.target);
                }
            });
        }, {threshold:0.15});
        chartBoxes.forEach(function(box){ chartObserver.observe(box); });
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Axis-Coding\xampp-8.2.12\htdocs\it_life_work\it-lifee\resources\resources\views/home.blade.php ENDPATH**/ ?>