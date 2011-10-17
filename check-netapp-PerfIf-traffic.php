<?php
# +------------------------------------------------------------------+
# |             ____ _               _        __  __ _  __           |
# |            / ___| |__   ___  ___| | __   |  \/  | |/ /           |
# |           | |   | '_ \ / _ \/ __| |/ /   | |\/| | ' /            |
# |           | |___| | | |  __/ (__|   <    | |  | | . \            |
# |            \____|_| |_|\___|\___|_|\_\___|_|  |_|_|\_\           |
# |                                                                  |
# | Copyright Mathias Kettner 2010             mk@mathias-kettner.de |
# +------------------------------------------------------------------+
#
# This file is part of Check_MK.
# The official homepage is at http://mathias-kettner.de/check_mk.
#
# check_mk is free software;  you can redistribute it and/or modify it
# under the  terms of the  GNU General Public License  as published by
# the Free Software Foundation in version 2.  check_mk is  distributed
# in the hope that it will be useful, but WITHOUT ANY WARRANTY;  with-
# out even the implied warranty of  MERCHANTABILITY  or  FITNESS FOR A
# PARTICULAR PURPOSE. See the  GNU General Public License for more de-
# ails.  You should have  received  a copy of the  GNU  General Public
# License along with GNU Make; see the file  COPYING.  If  not,  write
# to the Free Software Foundation, Inc., 51 Franklin St,  Fifth Floor,
# Boston, MA 02110-1301 USA.

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
