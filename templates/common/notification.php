<?php
$msg = $this->session->flashdata('message');
if (isset($msg) != "") {
?>
    <script>
        $(document).ready(function($msg) {
            var data = <?php echo json_encode($msg); ?>;
            //window.alert(data)
            $.notify({
                // options
                title: data['type'] == 'success' ? 'Success' : data['type'] == 'warning' ? 'Warning' : 'Delete',
                message: data['message'],
                icon: data['type'] == 'success' ? 'fas fa-check-circle' : data['type'] == 'warning' ? 'fas fa-exclamation-circle' : 'fas fa-trash',
                // url: 'https://github.com/mouse0270/bootstrap-notify',
                target: '_blank'
            }, {
                // settings
                element: 'body',
                //position: null,
                type: data['type'] == 'success' ? 'success' : data['type'] == 'warning' ? 'warning' : 'danger',
                allow_dismiss: true,
                //newest_on_top: false,
                showProgressbar: false,
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: 90,
                spacing: 10,
                z_index: 1031,
                delay: 300,
                timer: 700,
                url_target: '_blank',
                mouse_over: null,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutRight'
                },
                onShow: null,
                onShown: null,
                onClose: null,
                onClosed: null,
                icon_type: 'class',
            });

        });
    </script>
<?php
}
?>