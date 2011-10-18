<?php

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

$bitBandwidth = $MAX[1] * 8;
$warn = $WARN[1];
$crit = $CRIT[1];

$megabyte = 1024.0 * 1024.0;

$bandwidth = $bitBandwidth;
$mByteBandwidth = $MAX[1] / $megabyte;
$mByteWarn      = $WARN[1] / $megabyte;
$mByteCrit      = $CRIT[1] / $megabyte;

$bwuom = ' ';
$base = 1000;
if($bandwidth > $base * $base * $base) {
	$warn /= $base * $base * $base;
	$crit /= $base * $base * $base;
	$bandwidth /= $base * $base * $base;
	$bwuom = 'G';
} elseif ($bandwidth > $base * $base) {
	$warn /= $base * $base;
	$crit /= $base * $base;
	$bandwidth /= $base * $base;
	$bwuom = 'M';
} elseif ($bandwidth > $base) {
	$warn /= $base;
	$crit /= $base;
	$bandwidth /= $base;
	$bwuom = 'K';
}

if ($mByteBandwidth < 10)
   $range = $mByteBandwidth;
else
   $range = 10.0;


$ds_name[1] = "$servicedesc";
$opt[1] = "-I --vertical-label \"MByte/sec\" -l -$range -u $range -X0 -b 1024 --title \"$servicedesc\" --watermark=http://netapp-monitoring.info";
$def[1] =  "HRULE:-$mByteBandwidth#0459b3: ";
   if ($warn)
      $def[1] .= "HRULE:$mByteWarn#ffff00:\"Warning\:                " . sprintf("%6.1f", $warn) . " ".$bwuom."B/s\\n\" ".
                 "HRULE:-$mByteWarn#ffff00: ";
   if ($crit)
      $def[1] .= "HRULE:$mByteCrit#ff0000:\"Critical\:               " . sprintf("%6.1f", $crit) . " ".$bwuom."B/s\\n\" ".
                 "HRULE:-$mByteCrit#ff0000: ";

  $def[1] .= "DEF:inbytes=$RRDFILE[1]:$DS[1]:MAX ".
  "CDEF:inmb=inbytes,1048576,/ ".
  "AREA:inmb#AAB559:\"Traffic        \" ".
  "GPRINT:inbytes:LAST:\"%6.1lf %sB/s last\" ".
  "GPRINT:inbytes:AVERAGE:\"%6.1lf %sB/s avg\" ".
  "GPRINT:inbytes:MAX:\"%6.1lf %sB/s max\\n\" ";
  $def[1] .= rrd::line1( "inmb", "#0459b3" );
?>
