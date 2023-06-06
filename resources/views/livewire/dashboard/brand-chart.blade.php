<div class="col-12">
   <div class="col-12">
      <div class="col-md-12">
          <div class="row">
              <div class="col-md-12">
                  <div class="col-md-12">
{{--                      {!! $brandsales->container() !!}--}}
                     <canvas id="ordersGraph"></canvas>
                     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                     <script>
                        var ctx = document.getElementById('ordersGraph').getContext('2d');
                        var ordersGraph = new Chart(ctx, {
                           type: 'line',
                           data: {
                              labels: <?php echo json_encode($dates); ?>,
                              datasets: [
                                 {
                                    label: 'Pre-Orders Delivered',
                                    data: <?php echo json_encode($quantities); ?>,
                                    borderColor: 'green',
                                    backgroundColor: 'rgba(0, 128, 0, 0.2)',
                                 },
                              ],
                           },
                           options: {
                              scales: {
                                 y: {
                                    beginAtZero: true,
                                 },
                              },
                           },
                        });
                     </script>

                  </div>
              </div>
          </div>
      </div>
   </div>
</div>
{{--{!! $brandsales->script() !!}--}}
