 <style type="text/css">
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
    {
        padding: 5px 8px !important;
        vertical-align: middle;
    } 

 </style>

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="index.html">Marcela Housing Program</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>
  </nav>
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link samp_id" href="">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span >Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Marcela Homes</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown"> 
          <a class="dropdown-item unaudited_summary_mhhp" href="javascript:void(0)" data-toggle="modal" data-target="#modal-content" data-backdrop="static" data-keyboard="false"><small>MH-Unaudited Summary</small></a>
          <a class="dropdown-item " href="<?php echo base_url()?>balance_entry/bal_entry_mh_r" data-keyboard="false"><small>MH-Balance Entry</small></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Habitat</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item unaudited_summary_habitat" href="javascript:void(0)" data-toggle="modal" data-target="#modal-content" data-backdrop="static" data-keyboard="false"><small>HB-Unaudited Summary</small></a>
          <a class="dropdown-item " href="<?php echo base_url()?>balance_entry/bal_entry_hb_r" data-keyboard="false"><small>HB-Balance Entry</small></a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tables.html">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span></a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->

          <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo base_url().'accounting/home'?>">MHHP</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url().'accounting/habitat'?>">Habitat</a>
        </li>
      </ul>
      <br>

        <!-- Icon Cards-->
        
        <!-- Area Chart Example-->
        <!-- <div class="chart_mh"> -->
          
        <!-- </div> -->

       
        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
           Marcela Homes</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead style="font-size:14px">
                    <th>Option</th>
                    <th>No.</th>
                    <th>Employee</th>
                    <th>Beginning Balance</th>
                    <th>Balance</th>
                </thead>
                <tbody>
                <?php foreach($getHomePartners as $value)
                { 
                  echo '<tr style="font-size:15px">
                    <td>
                      <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog fa-fw"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
                        <a class="dropdown-item action" id='.$value['mhhp_serial_code'].' href="javascript:void(0)" data-toggle="modal" data-target="#modal-content" data-backdrop="static" data-keyboard="false">Payment History</a>
                        <a class="dropdown-item wkinpay" id='.$value['mhhp_serial_code'].' href="javascript:void(0)">Walk In Payment</a>
                      </div></td>
                    <td>'.$value['mhhp_serial_code'].'</td>
                    <td><a href="../../../../hrms/employee/employee_details.php?com='.$value['emp_id'].'">'.$value['name'].'</a></td>
                    <td style="text-align: right;">'.number_format($value['mhhp_strt_bal'],2).'</td>
                    <td style="text-align: right;">'.number_format($value['mhhp_current_bal'],2).'</td>
                  </tr>';
                } 
                ?>
                </tbody>
              </table>
            </div>
          </div>
        
        </div>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © EBM 2019</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Modal-->
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal_content">
    <div class="modal-dialog" role="document">
    
    </div>
  </div>
  </div>


  