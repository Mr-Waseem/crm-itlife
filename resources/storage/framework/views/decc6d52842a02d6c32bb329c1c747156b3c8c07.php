<script>
    // Toastr
    const Toast = Swal.mixin({
        toast: true,
        // bgColor: 'white',
        // color: 'black',
        position: 'top-right',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    <?php if(Session::has('flash_message')): ?>
        Toast.fire({
            icon: 'success',
            title: "<?php echo e(Session::get('flash_message')); ?>"
        });
    <?php endif; ?>
    <?php if(Session::has('error_message')): ?>
        Toast.fire({
            icon: 'error',
            title: "<?php echo e(Session::get('error_message')); ?>"
        })
    <?php endif; ?>
    <?php if(Session::has('access_granted')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Sorry...',
            text: "<?php echo e(Session::get('access_granted')); ?>"
        })
    <?php endif; ?>
</script>
<?php /**PATH D:\Axis-Coding\xampp-8.2.12\htdocs\it_life_work\it-lifee\resources\resources\views/include/toast-messages.blade.php ENDPATH**/ ?>