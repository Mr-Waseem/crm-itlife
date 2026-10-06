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
    @if (Session::has('flash_message'))
        Toast.fire({
            icon: 'success',
            title: "{{ Session::get('flash_message') }}"
        });
    @endif
    @if (Session::has('error_message'))
        Toast.fire({
            icon: 'error',
            title: "{{ Session::get('error_message') }}"
        })
    @endif
    @if (Session::has('access_granted'))
        Swal.fire({
            icon: 'error',
            title: 'Sorry...',
            text: "{{ Session::get('access_granted') }}"
        })
    @endif
</script>
