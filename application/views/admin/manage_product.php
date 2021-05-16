  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?= site_url('admin');?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Order Listing</li>
      </ol>
      <!-- Ordered Cart Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-shopping-cart"></i> Order Listing</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Product Name</th>
                  <th>Price</th>
                  <th>Time</th>
                  <th>Category</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Detail</th>
                </tr>
              </thead>
              <tbody>
                <?php $count = 1; foreach($products as $product): ?>
                <?php if ($product->active_flag!="0"): ?>
                    <tr align="center">
                      <td><?= $count++;?></td>
                      <th><?= $product->product_name;?></th>
                      <th><?= $product->price;?></th>
                      <th><?= $product->add_time;?></th>
                      <th><?= $product->category_name;?></th>
                      <th><?php if($product->cost_app == "1"): ?>Paid<?php else: ?>Free<?php endif; ?></th>
                      <th><?php if($product->active_flag == "1"): ?>Runing<?php elseif($product->active_flag == "2"): ?>
                        <?php elseif($product->active_flag == "3"): ?>Unpublish<?php elseif($product->active_flag == "4"): ?>Denied<?php endif; ?>
                      </th>
                        <th>
                          <a href='<?= site_url('/Shop/product/'.$product->product_id)?>'>Detail</a>
                        </th>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->

    <script>
    $(document).ready(function() {
        $('table.display').DataTable();
    } );
    </script>
 