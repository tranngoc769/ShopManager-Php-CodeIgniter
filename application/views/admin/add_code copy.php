<div class="content-wrapper">
  <div class="container-fluid">
    <?php echo $this->session->flashdata("msg"); ?>
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= site_url('admin');?>">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Add Product</li>
    </ol>
    <!-- Example DataTables Card-->
    <?= form_open_multipart('code/add', array('class' => 'form-horizontal', 'id'=>'add-code-form')) ?>
    <div class="card mb-3">
      <div class="card-header">
        Add Code 
      </div>
      <div class="card-body">
        <div class="form-group">
          <?= form_error('code_cash'); ?>
          <label class="control-label">Code Cash</label>
          <div>
            <input  type="number" class="form-control" name="code_cash" value="<?php echo set_value('code_cash'); ?>">
          </div>
        </div>
        <div class="form-group">
          <?= form_error('code_amount'); ?>
          <label class="control-label">Code Amount</label>
          <div>
            <input id="code_amount" type="number" step="1000" class="form-control" name="code_amount" value="<?php echo set_value('code_amount'); ?>">
          </div>
        </div>
        <div class="form-group">
          <input type="submit" value="Add" Class="btn btn-primary form-control">
        </div>
      </div>
    </div>
    <?= form_close(); ?>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
</div>

<script>
  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#product_image')
                .attr('src', e.target.result)
                .width(300)
                .height(300);
        };

        reader.readAsDataURL(input.files[0]);
    }
  }
</script>
<!-- product_id
category_id
seller_id
product_name
price
short_desc
description
add_time
active_flag
cost_app
is_draff
-->
