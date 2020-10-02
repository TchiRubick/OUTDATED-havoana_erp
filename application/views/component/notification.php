<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000" id="notificationSuccess" style="position: absolute; bottom: 0; right: 0; min-width: 50px; z-index: 999;">
    <div class="toast-header">
        <strong class="mr-auto" id="titreNotification" style="color:green;">Notification</strong>
        <small>just now</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body" id="notificationMessageSuccess"></div>
</div>

<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000" id="notificationError" style="position: absolute; bottom: 0; right: 0; min-width: 50px; z-index: 999;">
    <div class="toast-header">
        <strong class="mr-auto" id="titreNotification" style="color:red;">Error notification</strong>
        <small>just now</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body" id="notificationMessageError"></div>
</div>

<script>
    $('#notificationError').toast('hide')
    $('#notificationSuccess').toast('hide')
</script>
