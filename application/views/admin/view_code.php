<div class="content-wrapper">
  <div class="container-fluid">
    <?php echo $this->session->flashdata("msg"); ?>
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?= site_url('admin');?>">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Codes Listing</li>
    </ol>
    <!-- View Codes DataTables Card-->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-shopping-bag"></i> Codes List </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th width="2%">ID</th>
                <th width="20%">Code</th>
                <th width="10%">Cash</th>
                <th width="20%">Date Create</th>
                <th width="20%">Date Use</th>
                <th width="10%">User</th>
                <th width="10%">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $count = 1; foreach($code_list->result() as $pl) : ?>
                  <tr>
                    <td><?= $count++; ?></td>
                    <td><?= $pl->code ?></td>
                    <td><?= $pl->cash ?></td>
                    <td><?php $date = new DateTime($pl->date_created); echo date_format($date,"Y/m/d H:i:s"); ?></td>
                    <td><?=$pl->date_used?></td>
                    <td><?= $pl->used_by ?></td>
                    <td>
                        <a href='#'>
                          <?php if($pl->is_used == 1): ?>
                            <button class="btn btn-success" style="width:100%" >Used</button>
                          <?php else: ?>
                            <button class="btn btn-success" style="width:100%" >Unused</button>
                          <?php endif; ?>
                        </a>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- /.container-fluid-->
  <!-- /.content-wrapper-->
</div>
