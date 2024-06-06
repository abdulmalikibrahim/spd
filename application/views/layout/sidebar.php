<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon rotate-n-0">
                  <img src="<?= base_url("assets/img/logo_adm.png") ?>" alt="" width="30%"> SPD ADM KAP
            </div>
      </a>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
            <a class="nav-link" href="<?= base_url(); ?>">
                  <i class="fas fa-fw fa-tachometer-alt"></i>
                  <span>Dashboard</span>
            </a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading"> Interface </div>
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSPD" aria-expanded="true" aria-controls="collapseSPD">
                  <i class="fas fa-fw fa-wrench"></i>
                  <span>Service Part</span>
            </a>
            <div id="collapseSPD" class="collapse <?php if($this->p1 == "spd_all" || $this->p1 == "spd_remain" || $this->p1 == "spd_close"){ echo "show"; } ?>" aria-labelledby="headingSPD" data-parent="#accordionSidebar">
                  <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php if($this->p1 == "spd_all"){ echo "active"; } ?>" href="<?= base_url("spd_all"); ?>"><i class="fas fa-bars text-info pr-2" style="width:22px;"></i>All Data</a>
                        <a class="collapse-item <?php if($this->p1 == "spd_remain"){ echo "active"; } ?>" href="<?= base_url("spd_remain"); ?>"><i class="fas fa-bell text-danger pr-2" style="width:22px;"></i>Remain</a>
                        <a class="collapse-item <?php if($this->p1 == "spd_close"){ echo "active"; } ?>" href="<?= base_url("spd_close"); ?>"><i class="fas fa-check-circle text-success pr-2" style="width:22px;"></i>Close</a>
                  </div>
            </div>
      </li>
      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProcessing" aria-expanded="true" aria-controls="collapseProcessing">
                  <i class="fas fa-fw fa-qrcode"></i>
                  <span>Processing</span>
            </a>
            <div id="collapseProcessing" class="collapse <?php if($this->p1 == "scan_process" || $this->p1 == "scan_out" || $this->p1 == "cancel_process" || $this->p1 == "failed_scan_out"){ echo "show"; } ?>" aria-labelledby="headingProcessing" data-parent="#accordionSidebar">
                  <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item <?php if($this->p1 == "scan_process"){ echo "active"; } ?>" href="<?= base_url("scan_process") ?>"><i class="fas fa-qrcode text-info pr-2" style="width:22px;"></i>Scan Process</a>
                        <a class="collapse-item <?php if($this->p1 == "cancel_process"){ echo "active"; } ?>" href="<?= base_url("cancel_process") ?>"><i class="fas fa-times-circle pr-2 text-danger" style="width:22px;"></i>Cancel Process</a>
                        <a class="collapse-item <?php if($this->p1 == "scan_out"){ echo "active"; } ?>" href="<?= base_url("scan_out") ?>"><i class="fas fa-arrow-right-from-bracket text-info text-success pr-2" style="width:22px;"></i>Scan Out</a>
                        <a class="collapse-item <?php if($this->p1 == "failed_scan_out"){ echo "active"; } ?>" href="<?= base_url("failed_scan_out") ?>"><i class="fas fa-circle-exclamation text-danger pr-2" style="width:22px;"></i>Fail Scan Out</a>
                  </div>
            </div>
      </li>
      <!-- Nav Item - Tables -->
      <li class="nav-item <?php if($this->p1 == "planning_spd"){ echo "active"; } ?>">
            <a class="nav-link" href="<?= base_url("planning_spd"); ?>">
                  <i class="fas fa-fw fa-table"></i>
                  <span>Planning SPD</span>
            </a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading"> Addons </div>
      <!-- Nav Item - Charts -->
      <li class="nav-item">
		<?php
		$parsedUrl = parse_url(base_url());
		?>
            <a class="nav-link" id="btn-syncronize" onclick="sycnronize(this)" href="javascript:void(0)" data-url="<?= $parsedUrl["scheme"]."://".$parsedUrl["host"].":3000/api/get_data_spd"; ?>">
                  <i class="fas fa-fw fa-sync"></i>
                  <span>Syncronize</span>
            </a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
</ul>
