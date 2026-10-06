<!DOCTYPE html>
<html>
<head>
    <title>SALE INVOICE</title>
    <style>
        @page {
            margin: 18px 22px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000000;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .invoice-wrap {
            width: 100%;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
            background: #ffffff;
        }

        .logo-cell {
            width: 100px;
        }

        .logo-cell img {
            height: 70px;
            width: auto;
        }

        .company-cell {
            text-align: center;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
            color: #000000;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 11px;
            color: #000000;
            margin: 0;
        }

        .doc-badge {
            width: 120px;
            text-align: right;
        }

        .doc-badge-inner {
            display: inline-block;
            border: 1.5px solid #000000;
            color: #000000;
            background: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .divider {
            border: none;
            border-top: 2px solid #000000;
            margin: 8px 0 12px 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background: #ffffff;
        }

        .meta-table td {
            border: 1px solid #000000;
            padding: 7px 10px;
            font-size: 12px;
            background: #ffffff;
            color: #000000;
            vertical-align: top;
        }

        .meta-table td.meta-narrow {
            width: 38%;
        }

        .meta-table td.meta-wide {
            width: 62%;
        }

        .meta-label {
            color: #000000;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: block;
            margin-bottom: 2px;
        }

        .meta-value {
            font-weight: bold;
            color: #000000;
            white-space: normal;
            word-wrap: break-word;
        }

        .remarks {
            margin: 0 0 10px 0;
            padding: 6px 10px;
            border: 1px solid #000000;
            background: #ffffff;
            color: #000000;
            font-size: 11px;
        }

        #designed {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        #designed thead tr th {
            background: #ffffff;
            color: #000000;
            border: 1px solid #000000;
            padding: 7px 5px;
            font-size: 11px;
            text-align: center;
            font-weight: bold;
        }

        #designed tbody tr td {
            border: 1px solid #000000;
            padding: 6px 5px;
            font-size: 11px;
            text-align: center;
            vertical-align: middle;
            background: #ffffff;
            color: #000000;
        }

        #designed tfoot tr th {
            border: 1px solid #000000;
            background: #ffffff;
            color: #000000;
            padding: 7px 5px;
            font-size: 11px;
            text-align: center;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .summary-wrap {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 14px;
        }

        .summary-wrap td {
            border: none;
            vertical-align: top;
            padding: 0;
            background: #ffffff;
        }

        .amount-words {
            font-size: 11px;
            text-transform: capitalize;
            padding: 8px 10px;
            border: 1px solid #000000;
            background: #ffffff;
            color: #000000;
            width: 58%;
        }

        .totals-box {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-box th,
        .totals-box td {
            border: 1px solid #000000;
            padding: 6px 8px;
            font-size: 11px;
            background: #ffffff;
            color: #000000;
        }

        .totals-box th {
            text-align: left;
            font-weight: bold;
            width: 60%;
        }

        .totals-box td {
            text-align: right;
            font-weight: bold;
        }

        .totals-box tr.grand th,
        .totals-box tr.grand td {
            background: #ffffff;
            color: #000000;
            border: 1px solid #000000;
            font-size: 12px;
            font-weight: bold;
        }

        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
        }

        .sign-table td {
            border: none;
            width: 33.33%;
            text-align: center;
            font-size: 11px;
            padding-top: 18px;
            vertical-align: top;
            background: #ffffff;
            color: #000000;
        }

        .sign-line {
            border-top: 1px solid #000000;
            margin: 0 12px 6px 12px;
            padding-top: 6px;
        }

        .print-meta {
            margin-top: 14px;
            font-size: 10px;
            color: #000000;
        }

        .disclaimer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #000000;
            border-top: 1px dashed #000000;
            padding-top: 8px;
        }
    </style>
</head>
<body>
<?php
    $grandtotal = 0;
    $totalRecQty = 0;
    $totalrate = 0;
    $totalsaleQty = 0;
    $lineDiscountTotal = 0;
    $grossTotal = 0;
    $logoSrc = null;
    $hasLineDiscount = collect($salevoucherDetails)->sum(function ($row) {
        return (float) ($row->discount ?? 0);
    }) > 0;

    if ($warehouse && $warehouse->logo) {
        $absoluteLogo = base_path('upload/warehouses/' . $warehouse->logo);
        if (is_file($absoluteLogo)) {
            $imageInfo = @getimagesize($absoluteLogo);
            $binary = null;

            // Re-encode via GD so DomPDF always gets a compatible JPEG
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
                $mime = ($imageInfo['mime'] ?? null) ?: (function_exists('mime_content_type') ? mime_content_type($absoluteLogo) : 'image/png');
                $logoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($absoluteLogo));
            }
        }
    }
?>

<div class="invoice-wrap">
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <?php if($logoSrc): ?>
                    <img src="<?php echo e($logoSrc); ?>" alt="Logo">
                <?php endif; ?>
            </td>
            <td class="company-cell">
                <?php if(SettingsFacade::data()->UnRegisteredTitle != 0): ?>
                    <p class="company-name"><?php echo e(SettingsFacade::data()->title); ?></p>
                <?php endif; ?>
                <?php if($warehouseTitle->warehouse_titles == 1): ?>
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
                <div class="doc-badge-inner">Sale Invoice</div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    <table class="meta-table">
        <tr>
            <td class="meta-narrow">
                <span class="meta-label">Voucher No</span>
                <span class="meta-value"><?php echo e($salevoucherDetails[0]->salepurchase->voucher_no); ?></span>
            </td>
            <td class="meta-wide">
                <span class="meta-label">Voucher Date</span>
                <span class="meta-value"><?php echo e(date('d/m/Y', strtotime($salevoucherDetails[0]->salepurchase->date))); ?></span>
            </td>
        </tr>
        <tr>
            <td class="meta-narrow">
                <span class="meta-label">Party Name</span>
                <span class="meta-value"><?php echo e($salevoucherDetails[0]->party->party_name); ?></span>
            </td>
            <td class="meta-wide">
                <span class="meta-label">Party Address</span>
                <span class="meta-value"><?php echo e($salevoucherDetails[0]->party->address ?: '—'); ?></span>
            </td>
        </tr>
    </table>

    <?php if($salevoucherDetails[0]->salepurchase->remarks): ?>
        <div class="remarks"><b>Remarks:</b> <?php echo e($salevoucherDetails[0]->salepurchase->remarks); ?></div>
    <?php endif; ?>

    <table id="designed">
        <thead>
            <tr>
                <th style="width: 8%;">S.No</th>
                <th style="width: <?php echo e($hasLineDiscount ? '40%' : '48%'); ?>;" class="text-left">Description</th>
                <th style="width: 12%;">Qty</th>
                <th style="width: 12%;">Rate</th>
                <?php if($hasLineDiscount): ?>
                    <th style="width: 12%;">Discount</th>
                <?php endif; ?>
                <th style="width: 14%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $salevoucherDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $lineDisc = (float) ($value->discount ?? 0);
                    $lineGross = ((float) $value->sale_qty * (float) $value->rate);
                ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td class="text-left">
                        <?php if($value->product): ?>
                            <?php echo e($value->product->product_name); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?php echo e(number_format($value->sale_qty, 2)); ?></td>
                    <td class="text-right"><?php echo e(number_format($value->rate, 2)); ?></td>
                    <?php if($hasLineDiscount): ?>
                        <td class="text-right"><?php echo e(number_format($lineDisc, 2)); ?></td>
                    <?php endif; ?>
                    <td class="text-right"><?php echo e(number_format($value->total, 2)); ?></td>
                </tr>
                <?php
                    $totalRecQty += $value->qty;
                    $totalrate += $value->rate;
                    $totalsaleQty += $value->sale_qty;
                    $lineDiscountTotal += $lineDisc;
                    $grossTotal += $lineGross;
                    $grandtotal += $value->total;
                ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right"><?php echo e(number_format($totalsaleQty, 2)); ?></th>
                <th></th>
                <?php if($hasLineDiscount): ?>
                    <th class="text-right"><?php echo e(number_format($lineDiscountTotal, 2)); ?></th>
                <?php endif; ?>
                <th class="text-right"><?php echo e(number_format($grandtotal, 2)); ?></th>
            </tr>
        </tfoot>
    </table>

    <?php echo $__env->make('include.numberconvert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php
        $extraCharges = (float) ($salevoucherDetails[0]->salepurchase->extra_charges ?? 0);
        $extraDiscount = (float) ($salevoucherDetails[0]->salepurchase->extra_discount ?? 0);
        $combinedDiscount = $lineDiscountTotal + $extraDiscount;
        $netAmount = $grossTotal - $combinedDiscount + $extraCharges;
        $showDiscountSummary = $combinedDiscount > 0;
        $showCharges = $extraCharges > 0;
        $showBreakdown = $showDiscountSummary || $showCharges;
    ?>

    <table class="summary-wrap">
        <tr>
            <td>
                <div class="amount-words">
                    <b>Amount in Words:</b><br>
                    <?php echo e(SettingsFacade::data()->currency); ?>: <?php echo e(convertNumber($netAmount)); ?> Only/--
                </div>
            </td>
            <td>
                <table class="totals-box">
                    <?php if($showBreakdown): ?>
                        <tr>
                            <th>Total Amount</th>
                            <td><?php echo e(number_format($grossTotal, 2)); ?></td>
                        </tr>
                        <?php if($showCharges): ?>
                            <tr>
                                <th>Extra Charges</th>
                                <td><?php echo e(number_format($extraCharges, 2)); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if($showDiscountSummary): ?>
                            <tr>
                                <th>Discount</th>
                                <td><?php echo e(number_format($combinedDiscount, 2)); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr class="grand">
                            <th>Net Amount</th>
                            <td><?php echo e(number_format($netAmount, 2)); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr class="grand">
                            <th>Total Amount</th>
                            <td><?php echo e(number_format($netAmount, 2)); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

    <table class="sign-table">
        <tr>
            <td>
                <div class="sign-line">
                    Generated by<br>
                    <b><?php echo e($salevoucherDetails[0]->salepurchase->user->name); ?></b>
                </div>
            </td>
            <td>
                <div class="sign-line">Checked by</div>
            </td>
            <td>
                <div class="sign-line">Approved by</div>
            </td>
        </tr>
    </table>

    <div class="print-meta">
        Print Date: <?php echo e(date('d/m/Y')); ?> &nbsp;|&nbsp; Time: <?php echo e(date('h:i:s A')); ?>

    </div>

    <?php if($warehouseTitle->system_generated_invoice == 1): ?>
        <div class="disclaimer">
            This is a system-generated invoice and does not require a signature or stamp.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php /**PATH D:\Axis-Coding\xampp-8.2.12\htdocs\it_life_work\it-lifee\resources\resources\views/sales/direct/invoice.blade.php ENDPATH**/ ?>