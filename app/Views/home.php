<div class="main-container">
    <div class="xs-pd-20-10 pd-ltr-20">

        <div class="title pb-20">
            <h2 class="h3 mb-0"><?php echo $churchname; ?> Overview</h2>
        </div>

        <div class="row pb-10">
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark"><?php echo $branches; ?></div>
                            <div class="font-14 text-secondary weight-500">All Church Locations/Branches</div>
                        </div>
                        <div class="widget-icon" style="height: 125px;">
                            <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-house-1"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark"><?php echo $members; ?></div>
                            <div class="font-14 text-secondary weight-500">Total Members</div>
                        </div>
                        <div class="widget-icon" style="height: 125px;">
                            <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-user1"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $groups; ?></div>
              <div class="font-14 text-secondary weight-500">Total Groups</div>
            </div>
            <div class="widget-icon">
              <div class="icon"><i class="icon-copy dw dw-group"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donations; ?></div>
              <div class="font-14 text-secondary weight-500">Total Donations</div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#09cc06"><i class="icon-copy fa fa-money" aria-hidden="true"></i></div>
            </div>
          </div>
        </div>
      </div> -->
        </div>


        <!--
    <div class="row pb-10">
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donationsthisweek . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500">Donations this week</div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-money-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donationsthismonth . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500">Donations this month</div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-money-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $donationsthisyear . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500">Donations this year</div>
            </div>
            <div class="widget-icon">
              <div class="icon"><i class="icon-copy dw dw-money-1"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
        <div class="card-box height-100-p widget-style3">
          <div class="d-flex flex-wrap">
            <div class="widget-data">
              <div class="weight-700 font-24 text-dark"><?php echo $alldonations . $currencycode; ?></div>
              <div class="font-14 text-secondary weight-500">Total Donations</div>
            </div>
            <div class="widget-icon">
              <div class="icon" data-color="#09cc06"><i class="icon-copy fa fa-money" aria-hidden="true"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-box pb-10">
      <div class="h5 pd-20 mb-0">Recent Donations</div>
      <table class="data-table table nowrap">
        <thead>
          <tr>
            <th>#</th>
            <th>Reason</th>
            <th>Email</th>
            <th>Name</th>
            <th>reference</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1;
        foreach ($recentdonations as $res) { ?>
            <tr>
              <td><?php echo $count; ?></td>
              <td><?php echo $res->reason; ?></td>
              <td><?php echo $res->email; ?></td>
              <td><?php echo $res->name; ?></td>
              <td><?php echo $res->reference; ?></td>
              <td><?php echo $res->amount; ?></td>
              <td><?php echo $res->method; ?></td>
              <td><?php echo $res->date; ?></td>
            </tr>
          <?php $count++;
        } ?>
        </tbody>
      </table>
    </div> -->

        <!-- <div class="footer-wrap pd-20 mb-20 card-box">
          Envision Church <a href="https://sales.envisionapps.net" target="_blank">Envision Apps</a>
        </di -->
    </div>
</div>
