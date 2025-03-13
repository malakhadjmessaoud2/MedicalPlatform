@if (session('success'))
    <div id="notification-success" class="notification success  right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg z-50" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="notification-error" class="notification error right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg z-50" role="alert">
        {{ session('error') }}
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les notifications success
    const successNotification = document.getElementById('notification-success');
    if (successNotification) {
        setTimeout(function() {
            successNotification.style.opacity = '0';
            successNotification.style.transition = 'opacity 0.5s ease';
            setTimeout(() => successNotification.remove(), 500);
        }, 3000);
    }

    // Gérer les notifications error
    const errorNotification = document.getElementById('notification-error');
    if (errorNotification) {
        setTimeout(function() {
            errorNotification.style.opacity = '0';
            errorNotification.style.transition = 'opacity 0.5s ease';
            setTimeout(() => errorNotification.remove(), 500);
        }, 3000);
    }
});
</script>


