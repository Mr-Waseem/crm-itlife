<?php
    use App\Models\MenuRights;
    use App\Models\RightsLevel1;

    $rightLevels = RightsLevel1::with([
        'right_level2' => function ($query) {
            $query->with(['right_level3' => function($query){
                $query->Orderby('code', 'asc');
            }]);
        },
    ])
        ->orderBy('code')
        ->get();

?>
<ul class="navbar-nav">
    <li class="nav-item active">
        <a class="nav-link" href="<?php echo e(URL::to('/home')); ?>"><span class="active-item-here"></span><i
                class="fa fa-dashboard mr-5"></i>
            <span>DASHBOARD</span></a>
    </li>
    
    <?php $__currentLoopData = $rightLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $right1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $MenuRights = MenuRights::where('user_id', Auth::User()->id)
                ->whereType('Level 1')
                ->where('level_id', $right1->id)
                ->count();
            $i = 0;
        ?>
        <?php if($MenuRights > 0): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="javascript:void(0)" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"> <?php echo e($right1->title); ?>

                </a>
                <ul class="dropdown-menu multilevel scale-up-left">
                    <?php $__currentLoopData = $right1->right_level2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $right2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $MenuRights2 = MenuRights::where('user_id', Auth::User()->id)
                                ->whereType('Level 2')
                                ->where('level_id', $right2->id)
                                ->count();
                        ?>
                        <?php if($MenuRights2 > 0): ?>
                            <?php if(count($right2->right_level3) > 0): ?>
                                <li class="nav-item dropdown"
                                    <?php if($i == 0): ?> style="padding-top: 15px;" <?php endif; ?>
                                    <?php $i++; ?>>
                                    <a class="nav-link dropdown-item dropdown-toggle" href="#">
                                        <?php echo e($right2->title); ?> &emsp;</a>

                                    <ul class="dropdown-menu"  style="width: 250px;">
                                        <?php $__currentLoopData = $right2->right_level3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $right3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $MenuRights3 = MenuRights::where('user_id', Auth::User()->id)
                                                    ->whereType('Level 3')
                                                    ->where('level_id', $right3->id)
                                                    ->count();
                                            ?>
                                            <?php if($MenuRights3 > 0): ?>
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="<?php echo e(URL::to($right3->url)); ?>"><?php echo e($right3->title); ?>&emsp;</a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="nav-item" <?php if($i == 0): ?> style="padding-top: 15px;" <?php endif; ?>
                                    <?php $i++; ?>>
                                    <a class="nav-link" href="<?php echo e(URL::to($right2->url)); ?>"><?php echo e($right2->title); ?>

                                        &emsp;</a>
                                </li>
                            <?php endif; ?>
                            
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </li>
            
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php /**PATH D:\Axis-Coding\xampp-8.2.12\htdocs\it_life_work\it-lifee\resources\resources\views/navbar/almajeed.blade.php ENDPATH**/ ?>