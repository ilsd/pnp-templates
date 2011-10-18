<?php
#
# Copyright (c) 2011 Ingo LANTSCHNER (http://netapp-monitoring.info)

# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# Template for check_disk
#
# RRDtool Options

foreach ($this->DS as $KEY=>$VAL) {
# set initial values
	$fmt = '%7.3lf';
	$pct = '';
	$upper = "";
	$maximum = "";
	$divis = 1;
	$return = '\n';
	$unit = "B";
	$label = $unit;
	if ($VAL['UNIT'] != "") {
		$unit = $VAL['UNIT'];
		$label = $unit;
		if ($VAL['UNIT'] == "%%") {
			$label = '%';
			$fmt = '%5.1lf';
			$pct = '%';
		}
	}
	if ($VAL['MAX'] != "") {
		# adjust value and unit, details in .../helpers/pnp.php
		$max = pnp::adjust_unit( $VAL['MAX'].$unit,1024,$fmt );
		$upper = "-u $max[1] ";
		$maximum = "of $max[1] $max[2]$pct used";
		$label = $max[2];
		$divis = $max[3];
		$return = '';
	}
	$ds_name[$KEY] = str_replace("_","/",$VAL['NAME']);
	# set graph labels
	$opt[$KEY]     = "-I --vertical-label $label -l 0 $upper --title \"Usage of '$ds_name[$KEY]'\" --watermark=http://netapp-monitoring.info";
	# Graph Definitions
	$def[$KEY]     = rrd::def( "var1", $VAL['RRDFILE'], $VAL['DS'], "AVERAGE" ); 
	# "normalize" graph values
	$def[$KEY]    .= rrd::cdef( "v_n","var1,$divis,/");
	$def[$KEY]    .= rrd::area( "v_n", "#AAB559",  $ds_name[$KEY] );
	$def[$KEY]    .= rrd::line1( "v_n", "#0459b3" );
	# show values in legend
	$def[$KEY]    .= rrd::gprint( "v_n", "LAST", "$fmt $label$pct $maximum ");
	$def[$KEY]    .= rrd::gprint( "v_n", "AVERAGE", "$fmt $label$pct avg used $return");
	# create max line and legend
	if ($VAL['MAX'] != "") {
		$def[$KEY] .= rrd::gprint( "v_n", "MAX", "$fmt $label$pct max used \\n" );
	}
	# create warning line and legend
	if ($VAL['WARN'] != "") {
		$warn = pnp::adjust_unit( $VAL['WARN'].$unit,1024,$fmt );
		$def[$KEY] .= rrd::hrule( $warn[1], "#ffff00", "Warning  on $warn[0] \\n" );
	}
	# create critical line and legend
	if ($VAL['CRIT'] != "") {
		$crit = pnp::adjust_unit( $VAL['CRIT'].$unit,1024,$fmt );
		$def[$KEY] .= rrd::hrule( $crit[1], "#ff0000", "Critical on $crit[0]\\n" );
	}
}
?>
