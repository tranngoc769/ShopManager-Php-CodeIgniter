  <div class="content-wrapper">
    <div class="container-fluid">
      <?php echo $this->session->flashdata("success"); ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
      <!-- Icon Cards-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-envelope"></i> Request List </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th width="10%">No</th>
                  <th width="25%">Username</th>
                  <th width="25%">Full Name</th>
                  <th width="25%">E-Mail</th>
                  <th> Accept</th>
                  <th> Reject</th>
                </tr>
              </thead>
              <tbody>
                <?php $count = 1; foreach($all_request as $request) : ?>
                    <tr>
                      <td><?= $count++; ?></td>
                      <td  style='font-weight:bold;'><?= $request->username ?></td>
                      <td><?= $request->first_name." ".$request->last_name ?></td>
                      <td><?= $request->email ?></td>
                      <td>
                        <a href="<?= site_url('/admin/upgrade/'.$request->user_id)?>">
                          <button class="btn btn-primary">Upgrade</button>
                        </a>
                      </td>
                      <td>
                        <a href="<?= site_url('/admin/reject/'.$request->user_id)?>">
                          <button class="btn btn-warning">Reject</button>
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

