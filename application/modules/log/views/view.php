<?php
defined('BASEPATH') or exit('No direct script access allowed');
$logs = json_decode($logs);
$isadmin = json_decode($isadmin);
// echo $subheading ; exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">
                        <?= $heading ?>
                    </h2>
                    <h5 class="text-white op-7 mb-2">
                        <?php echo $subheading ?>
                        <input type="hidden" id="log_name" value="<?php echo $subheading; ?>">
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2 text-right">
                                <a href="<?= base_url('log/entry') ?>" class="btn btn-round btn-icon btn-success"><i
                                        class="fa fa-plus" style="margin-top:12px"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Log Type</th>
                                        <th>Ref No</th>
                                        <th>Sender Name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Contact Person</th>
                                        <th>Contact No</th>
                                        <th>Contact Email</th>
                                        <th>Description</th>
                                        <th>Document</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                        <?php
                                        if ($isadmin) {
                                            echo '<th class="">Action</th>';
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($logs) > 0) {
                                        $i = 1;
                                        foreach ($logs as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . date('d/m/Y',strtotime($row->date)) . '</td>';
                                            echo '<td>' . $row->type . '</td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->contact_person . '</td>';
                                            echo '<td>' . $row->contact_no . '</td>';
                                            echo '<td>' . $row->contact_email . '</td>';
                                            echo '<td class="truncate_text">' . $row->remarks . '</td>';
                                            
                                            $document = isset($row->document) && strlen($row->document) > 0 ? '<div class="avatar"><img src="' . base_url($row->document) . '" class="avatar-img rounded-circle"></div>' : '';
                                            echo '<td>' . $document . '</td>';
                                            echo '<td>' . $row->reason . '</td>';
                                            if ($row->status == 0)
                                                echo '<td><p class="badge btn-warning" style="margin:0px; width: 70px" >Pending</p> </td>';
                                            if ($row->status == 1)
                                            echo '<td><p class="badge btn-success" style="margin:0px; width: 70px">Solve</p></td>';
                                            if ($row->status == -1)
                                                echo '<td><p class="badge btn-danger" style="margin:0px; width: 70px">Rejected</p></td>';
                                            if ($isadmin) {
                                                if($row->status == 0){
                                                    echo '<td class="not-export"><p style="margin:0px; width: 75px">
                                                    <button type="button" title="View" class="button btn btn-sm btn-success" onclick="_logview('.$row->ref_no.')">View</button></p></td>';
                                                }
                                                else {
                                                    echo '<td class="not-export">&nbsp;</td>';
                                                }
                                            
                                            }
                                            echo '</tr>';
                                            $i++;
                                            // echo ('<input type="hidden" id="log_name" value="'. $row->status == 0 . '">');

                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'modal.php'; ?>
</div>

<script>
    $(document).ready(function () {
    var log_name = $('#log_name').val();
    // console.log(log_name);
        var currentdate = new Date();
        $('#tbl').DataTable({
            dom: 'lBfrtip',
            processing: true,
            scrollY: '450px',
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            responsive: true,
            stateSave: true,
            colReorder: true,
            fixedColumns: {
                left: 3,
                right: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'logs_report_' + log_name +'_' + $.now(),
                    title: log_name +'_'+ String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
                            + currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'logs_report_' + log_name,
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function (win) {
                        $(win.document.body)
                                .find('h1').css('text-align', 'center')
                                .css('font-size', '10pt')
                                .prepend(
                                        '<img src="' + baseURL + '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
                                        );
                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit')
                                .css('margin', '50px auto');
                    }
                }
            ]
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });

    
    function _logview(ref_no) {
        window.location.href = "<?php echo site_url('log/log_info/'); ?>?ref_no=" + ref_no;
    }


</script>