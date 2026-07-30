  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <script src="{{asset('script/tailwind.js')}}"></script> {{-- Version 3.4.16 --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

    <script src="{{asset('script/sweetalert.js')}}"></script>
<script>
    // Force reload when navigating via back/forward cache
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // Extra safety: check auth on every load
    document.addEventListener('DOMContentLoaded', function () {
        fetch('/api/check-auth', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => {
            if (res.status === 401) {
                window.location.href = '/login';
            }
        })
        .catch(() => {
            window.location.href = '/login';
        });
    });
</script>



{{-- <script src="{{asset('script/block.js')}}"></script> --}}

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#3b82f6',
              secondary: '#f97316'
            },
            borderRadius: {
              'none': '0px',
              'sm': '4px',
              DEFAULT: '8px',
              'md': '12px',
              'lg': '16px',
              'xl': '20px',
              '2xl': '24px',
              '3xl': '32px',
              'full': '9999px',
              'button': '8px'
            }
          }
        }
      }
    </script>
    <style>
      :where([class^="ri-"])::before {
        content: "\f3c2";
      }
    </style>
    <script src="{{asset('script/token.js')}}"></script>
  </head>
