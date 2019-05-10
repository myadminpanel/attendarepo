  
 <section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-body">
        <div class="col-md-6">
          <h4><i class="fa fa-list"></i> &nbsp; Teacher UI Attendace Log)</h4>
        </div>
        <div class="col-md-6 text-right">
         <?php if($this->rbac->check_operation_permission('add')): ?>
          <a href="<?= base_url('teacher/users/add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Add New User</a>
         <?php endif; ?>
        </div>
        
      </div>
    </div>
  </div> 

   <div class="box">
    <div class="box-header">
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
      <table id="na_datatable" class="table table-bordered table-striped" width="100%">
        <thead>
        <tr>
          <th>#ID</th>
          <th>Time In</th>
          <th>Time Tardy</th>
          <th>Student ID</th>
          <th>Grade</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Present</th>
          <th>Unexcused Tardy</th>
          <th>Absent</th>
          <th>Appointment</th>
          <th>Emergency</th>
          <th>Other</th>
          <th>Swipe Type</th>

 
        </tr>
        </thead>
      </table>
    </div>
    <!-- /.box-body -->
  <!-- /.box -->
</section>  

<!-- Modal -->
<div id="confirm-delete" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete</h4>
      </div>
      <div class="modal-body">
        <p>As you sure you want to delete.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-danger btn-ok">Delete</a>
      </div>
    </div>

  </div>
</div>


  <!-- DataTables -->
  <script src="<?= base_url() ?>public/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>public/plugins/datatables/dataTables.bootstrap.min.js"></script>
  <script>
  //---------------------------------------------------
  var table = $('#na_datatable').DataTable( {
      "processing": true,
      "serverSide": true,
      "ajax": "<?=base_url('teacher/users/datatable_json')?>",
      "order": [[4,'desc']],
      "columnDefs": [
        { "targets": 0, "name": "id", 'searchable':true, 'orderable':true},
        { "targets": 1, "name": "created_at", 'searchable':false, 'orderable':false},
        { "targets": 2, "name": "created_at", 'searchable':false, 'orderable':false},
        { "targets": 3, "name": "username", 'searchable':true, 'orderable':true},
        { "targets": 4, "name": "email", 'searchable':true, 'orderable':true},
        { "targets": 5, "name": "mobile_no", 'searchable':true, 'orderable':true},
        { "targets": 6, "name": "is_active", 'searchable':true, 'orderable':true},
        { "targets": 7, "name": "is_active", 'searchable':true, 'orderable':true},
        { "targets": 8, "name": "is_active", 'searchable':true, 'orderable':true},
        { "targets": 9, "name": "is_active", 'searchable':true, 'orderable':true},
        { "targets": 11, "name": "is_active", 'searchable':true, 'orderable':true},
        { "targets": 12, "name": "is_active", 'searchable':true, 'orderable':true},
        { "targets": 13, "name": "username", 'searchable':true, 'orderable':true},

      ]
    });
  </script>
  
  <script type="text/javascript">
      $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
  </script>

  <script type="text/javascript">
  $("body").on("change",".tgl_checkbox",function(){
    console.log('checked');
    $.post('<?=base_url("teacher/users/change_status")?>',
    {
      '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
      id : $(this).data('id'),
      status : $(this).is(':checked') == true?1:0
    },
    function(data){
      $.notify("Status Changed Successfully", "success");
    });
  });
  </script>
  

