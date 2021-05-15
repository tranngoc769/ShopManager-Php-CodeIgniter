  <!-- Navigation-->

  <div class="content-wrapper">
    <div class="container-fluid">
          <?php echo $this->session->flashdata("msg"); ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <a href="<?= site_url('user/dashboard')?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Upgrade</li>
      </ol>
      <div class="card-body">
        <?=form_open(site_url('user/upgradedev'));?>
          <div class="form-group">
            <label for="dispname">Status</label>
            <input disabled readonly class="form-control" type="text" aria-describedby="emailHelp"  value="<?= $text; ?>">
          </div>
          <button <?php if($status) {echo "disabled"; }?> class="btn btn-primary btn-block" type="submit" ><?php if($status) {echo "Requested"; } else{echo "Request Upgrade"; }?></button>
        <?=form_close();?>

      </div>

    </div><!-- /.container-fluid-->  
  </div><!-- /.content-wrapper-->
