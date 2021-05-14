  <!-- Navigation-->

  <div class="content-wrapper">
    <div class="container-fluid">
          <?php echo $this->session->flashdata("msg"); ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <a href="<?= site_url('user/dashboard')?>">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Charge Cash</li>
      </ol>
      <div class="card-body">
        <?=form_open(site_url('user/charge_cash'));?>
          <div class="form-group">
            <label for="dispname">Current Cash</label>
            <input disabled readonly class="form-control"  type="text" aria-describedby="emailHelp"  value="<?= $userData->cash; ?>">
          </div>
          <div class="form-group">
            <label for="dispname">Cash Code</label>
            <input class="form-control" name="cash_code" type="text" placeholder="Code" required>
          </div>
          <button class="btn btn-primary btn-block" type="submit" >Charge</button>
        <?=form_close();?>

      </div>

    </div><!-- /.container-fluid-->  
  </div><!-- /.content-wrapper-->
