<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class d_whos_online {
    var $code = 'd_whos_online';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function d_whos_online() {
      $this->title = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_TITLE;
      $this->description = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_DESCRIPTION;

      if ( defined('MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_STATUS') ) {
        $this->sort_order = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_SORT_ORDER;
        $this->enabled = (MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_STATUS == 'True');
      }
    }

    function getOutput() {
      $days = array();
	  $period = MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_PERIOD;
      for($i = 0; $i < $period; $i++) {
        $days[date('Y-m-d', strtotime('-'. $i .' days'))] = 0;
      }
	  $now = new DateTime;
	  $times = explode(':', $now->format('H:i:s'));
	  $tod = $times[0]*3600+$times[1]*60+$times[2];
	  $orders_query = tep_db_query("SELECT floor((time_last_click-$tod)/86400) as last_time, count(*) as count FROM `whos_online` group by last_time");
      // $orders_query = tep_db_query("select date_format(customers_info_date_of_last_logon, '%Y-%m-%d') as dateday, count(*) as total from " . TABLE_CUSTOMERS_INFO . " where date_sub(curdate(), interval " . $period ." day) <= customers_info_date_of_last_logon group by dateday");
      //  if (tep_not_null($orders['dateday'])) $days[$orders['dateday']] = $orders['total'];
      while ($orders = tep_db_fetch_array($orders_query)) {
		$utime = 86400 * $orders['last_time'] + $tod;
		$dateday = date('Y-m-d', $utime);
        $days[$dateday] = $orders['count'];
      }

      $days = array_reverse($days, true);

      $js_array = '';
      foreach ($days as $date => $total) {
        $js_array .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4))*1000) . ', ' . $total . '],';
      }

      if (!empty($js_array)) {
        $js_array = substr($js_array, 0, -1);
      }

      $chart_label = tep_output_string(MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_CHART_LINK);
      $chart_label_link = tep_href_link(FILENAME_CUSTOMERS);

      $output = <<<EOD
<div id="d_total_customers" style="width: 100%; height: 150px;"></div>
<script type="text/javascript">
$(function () {
  var plot30 = [$js_array];
  $.plot($('#d_total_customers'), [ {
    label: '$chart_label',
    data: plot30,
    lines: { show: true, fill: true },
    points: { show: true },
    color: '#FF9966'
  }], {
    xaxis: {
      ticks: 4,
      mode: 'time'
    },
    yaxis: {
      ticks: 3,
      min: 0
    },
    grid: {
      backgroundColor: { colors: ['#fff', '#eee'] },
      hoverable: true
    },
    legend: {
      labelFormatter: function(label, series) {
        return '<a href="$chart_label_link">' + label + '</a>';
      }
    }
  });
});

function showTooltip(x, y, contents) {
  $('<div id="tooltip">' + contents + '</div>').css( {
    position: 'absolute',
    display: 'none',
    top: y + 5,
    left: x + 5,
    border: '1px solid #fdd',
    padding: '2px',
    backgroundColor: '#fee',
    opacity: 0.80
  }).appendTo('body').fadeIn(200);
}

var monthNames = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

var previousPoint = null;
$('#d_total_customers').bind('plothover', function (event, pos, item) {
  if (item) {
    if (previousPoint != item.datapoint) {
      previousPoint = item.datapoint;

      $('#tooltip').remove();
      var x = item.datapoint[0],
          y = item.datapoint[1],
          xdate = new Date(x);

      showTooltip(item.pageX, item.pageY, y + ' for ' + monthNames[xdate.getMonth()] + '-' + xdate.getDate());
    }
  } else {
    $('#tooltip').remove();
    previousPoint = null;
  }
});
</script>
EOD;

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_STATUS');
    }

    function install() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Whos Online Module', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_STATUS', 'True', 'Do you want to show the Whos Online History chart on the dashboard?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . $multi_stores_config . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Display Period (90 days max)', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_PERIOD', '30', 'Number of days to display.', '6', '2', now())");
    }

    function remove() {
	  GLOBAL $multi_stores_config ;
      tep_db_query("delete from " . $multi_stores_config . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_STATUS', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_SORT_ORDER', 'MODULE_ADMIN_DASHBOARD_WHOS_ONLINE_PERIOD');
    }
  }
?>
