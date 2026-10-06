<!DOCTYPE html>
<html>
<head>
    <title>QUOTATION</title>
    <style>
        @page { margin: 18px 22px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000000;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .header-table td { vertical-align: middle; border: none; padding: 0; background: #ffffff; }
        .logo-cell { width: 100px; }
        .logo-cell img { height: 70px; width: auto; }
        .company-cell { text-align: center; }
        .company-name {
            font-size: 20px; font-weight: bold; margin: 0 0 4px 0;
            color: #000000; text-transform: uppercase;
        }
        .company-address { font-size: 11px; color: #000000; margin: 0; }
        .doc-badge { width: 120px; text-align: right; }
        .doc-badge-inner {
            display: inline-block; border: 1.5px solid #000000; color: #000000;
            background: #ffffff; font-size: 11px; font-weight: bold;
            padding: 6px 10px; text-transform: uppercase;
        }
        .divider { border: none; border-top: 2px solid #000000; margin: 8px 0 12px 0; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; background: #ffffff; }
        .meta-table td {
            border: 1px solid #000000; padding: 7px 10px; font-size: 12px;
            width: 50%; background: #ffffff; color: #000000;
        }
        .meta-label {
            color: #000000; font-size: 10px; text-transform: uppercase;
            display: block; margin-bottom: 2px;
        }
        .meta-value { font-weight: bold; color: #000000; }
        .section-title {
            font-size: 13px; font-weight: bold; margin: 14px 0 6px;
            border-bottom: 1px solid #000000; padding-bottom: 3px;
        }
        .features-list { margin: 0 0 10px 18px; padding: 0; }
        .features-list li { margin-bottom: 3px; }
        .info-line { margin: 4px 0; font-size: 12px; }
        #designed { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        #designed thead tr th {
            background: #ffffff; color: #000000; border: 1px solid #000000;
            padding: 7px 5px; font-size: 11px; text-align: center; font-weight: bold;
        }
        #designed tbody tr td {
            border: 1px solid #000000; padding: 6px 5px; font-size: 11px;
            text-align: center; vertical-align: middle; background: #ffffff; color: #000000;
        }
        .text-left { text-align: left !important; }
        .sign-table { width: 100%; border-collapse: collapse; margin-top: 28px; }
        .sign-table td {
            border: none; width: 33.33%; text-align: center; font-size: 11px;
            padding-top: 18px; vertical-align: top; background: #ffffff; color: #000000;
        }
        .sign-line { border-top: 1px solid #000000; margin: 0 12px 6px 12px; padding-top: 6px; }
        .print-meta { margin-top: 14px; font-size: 10px; color: #000000; }
        .disclaimer {
            margin-top: 10px; text-align: center; font-size: 10px; color: #000000;
            border-top: 1px dashed #000000; padding-top: 8px;
        }
    </style>
</head>
<body>
<?php
    $logoSrc = null;
    if ($warehouse && $warehouse->logo) {
        $absoluteLogo = base_path('upload/warehouses/' . $warehouse->logo);
        if (is_file($absoluteLogo)) {
            $imageInfo = @getimagesize($absoluteLogo);
            $binary = null;
            if ($imageInfo && function_exists('imagecreatefromstring')) {
                $raw = file_get_contents($absoluteLogo);
                $gd = @imagecreatefromstring($raw);
                if ($gd !== false) {
                    $w = imagesx($gd);
                    $h = imagesy($gd);
                    $maxH = 140;
                    if ($h > $maxH) {
                        $ratio = $maxH / $h;
                        $nw = (int) max(1, round($w * $ratio));
                        $nh = $maxH;
                        $resized = imagecreatetruecolor($nw, $nh);
                        $white = imagecolorallocate($resized, 255, 255, 255);
                        imagefill($resized, 0, 0, $white);
                        imagecopyresampled($resized, $gd, 0, 0, 0, 0, $nw, $nh, $w, $h);
                        imagedestroy($gd);
                        $gd = $resized;
                    }
                    ob_start();
                    imagejpeg($gd, null, 85);
                    $binary = ob_get_clean();
                    imagedestroy($gd);
                }
            }
            if ($binary) {
                $logoSrc = 'data:image/jpeg;base64,' . base64_encode($binary);
            } else {
                $mime = ($imageInfo['mime'] ?? null) ?: 'image/png';
                $logoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($absoluteLogo));
            }
        }
    }

    $featureLines = [];
    if (!empty($quotation->features)) {
        $featureLines = preg_split("/\r\n|\n|\r/", $quotation->features);
        $featureLines = array_values(array_filter(array_map('trim', $featureLines)));
    }
?>

<table class="header-table">
    <tr>
        <td class="logo-cell">
            <?php if($logoSrc): ?>
                <img src="<?php echo e($logoSrc); ?>" alt="Logo">
            <?php endif; ?>
        </td>
        <td class="company-cell">
            <?php if($warehouse): ?>
                <p class="company-name"><?php echo e($warehouse->name); ?></p>
                <p class="company-address"><?php echo e($warehouse->address); ?></p>
                <?php if(!empty($warehouse->phone)): ?>
                    <p class="company-address">Phone: <?php echo e($warehouse->phone); ?>

                        <?php if(!empty($warehouse->email)): ?> | <?php echo e($warehouse->email); ?><?php endif; ?>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <td class="doc-badge">
            <div class="doc-badge-inner">Quotation</div>
        </td>
    </tr>
</table>

<hr class="divider">

<table class="meta-table">
    <tr>
        <td>
            <span class="meta-label">Quotation No</span>
            <span class="meta-value"><?php echo e($quotation->voucher_no); ?></span>
        </td>
        <td>
            <span class="meta-label">Date</span>
            <span class="meta-value"><?php echo e(date('d/m/Y', strtotime($quotation->date))); ?></span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="meta-label">Valid To</span>
            <span class="meta-value"><?php echo e($quotation->valid_to ? date('d/m/Y', strtotime($quotation->valid_to)) : '—'); ?></span>
        </td>
        <td>
            <span class="meta-label">Party / Company</span>
            <span class="meta-value"><?php echo e(optional($quotation->party)->party_name); ?></span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="meta-label">Address</span>
            <span class="meta-value"><?php echo e(optional($quotation->party)->address ?: '—'); ?></span>
        </td>
        <td>
            <span class="meta-label">City</span>
            <span class="meta-value"><?php echo e(optional($quotation->party)->city ?: '—'); ?></span>
        </td>
    </tr>
    <tr>
        <td>
            <span class="meta-label">Atten</span>
            <span class="meta-value"><?php echo e($quotation->atten ?: '—'); ?></span>
        </td>
        <td>
            <span class="meta-label">Subject</span>
            <span class="meta-value"><?php echo e($quotation->subject ?: '—'); ?></span>
        </td>
    </tr>
</table>

<?php if(count($featureLines)): ?>
    <div class="section-title">Application Features</div>
    <ul class="features-list">
        <?php $__currentLoopData = $featureLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($line); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>

<div class="info-line"><b>Deadline (Working Days):</b> <?php echo e($quotation->deadline_days !== null ? $quotation->deadline_days : '—'); ?></div>
<div class="info-line"><b>Warranty (Months):</b> <?php echo e($quotation->warranty_months !== null ? $quotation->warranty_months : '—'); ?></div>

<?php if($quotation->quotation_details && $quotation->quotation_details->count()): ?>
    <div class="section-title">Products</div>
    <table id="designed">
        <thead>
            <tr>
                <th style="width:30%;" class="text-left">Product Name</th>
                <th class="text-left">Description</th>
                <th style="width:120px;">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $quotation->quotation_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-left"><?php echo e($row->product_name ?: '—'); ?></td>
                    <td class="text-left"><?php echo e($row->description ?: '—'); ?></td>
                    <td><?php echo e($row->line_date ? date('d/m/Y', strtotime($row->line_date)) : '—'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>

<?php if($quotation->milestones && $quotation->milestones->count()): ?>
    <div class="section-title">Module Milestones</div>
    <table id="designed">
        <thead>
            <tr>
                <th class="text-left">Module</th>
                <th style="width:120px;">Payment %</th>
                <th style="width:160px;">Timeframe</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $quotation->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-left"><?php echo e($m->module_name); ?></td>
                    <td><?php echo e($m->payment_percent); ?>%</td>
                    <td><?php echo e($m->timeframe_days); ?> Working Days</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>

<table class="sign-table">
    <tr>
        <td>
            <div class="sign-line">
                Generated by<br>
                <b><?php echo e(optional($quotation->creator)->name ?? '—'); ?></b>
            </div>
        </td>
        <td><div class="sign-line">Checked by</div></td>
        <td><div class="sign-line">Approved by</div></td>
    </tr>
</table>

<div class="print-meta">
    Print Date: <?php echo e(date('d/m/Y')); ?> &nbsp;|&nbsp; Time: <?php echo e(date('h:i:s A')); ?>

</div>

<?php if(isset($warehouseTitle) && $warehouseTitle && $warehouseTitle->system_generated_invoice == 1): ?>
    <div class="disclaimer">
        This is a system-generated quotation and does not require a signature or stamp.
    </div>
<?php endif; ?>
</body>
</html>
<?php /**PATH D:\Axis-Coding\xampp-8.2.12\htdocs\it_life_work\it-lifee\resources\resources\views/quotation/invoice.blade.php ENDPATH**/ ?>