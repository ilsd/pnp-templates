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

    
    $ds_name[1] = "$servicedesc";
    $opt[1]  = "--units-exponent=0 --lower=0 --vertical-label \"$UNIT[1]\" --title \"'$ds_name[1]'\" --watermark=http://netapp-monitoring.info" ;
    $def[1]  = rrd::def("var1", $RRDFILE[1], $DS[1], "AVERAGE") ;
    $def[1] .= rrd::area( "var1", "#AAB559",  $ds_name[1] );
    $def[1] .= rrd::line1( "var1", "#0459b3" );
    $def[1] .= rrd::gprint("var1", array("LAST", "MAX", "AVERAGE"), "%6.2lf $UNIT[1]") ;
     
    if($WARN[1] != ""){
        if($UNIT[1] == "%%"){ $UNIT[1] = "%"; };
        $def[1] .= rrd::hrule($WARN[1], "#ffff00", "Warning  ".$WARN[1].$UNIT[1]."\\n");
    }
    if($CRIT[1] != ""){
        if($UNIT[1] == "%%"){ $UNIT[1] = "%"; };
        $def[1] .= rrd::hrule($CRIT[1], "#ff0000", "Critical  ".$CRIT[1].$UNIT[1]."\\n");
    }
     
    ?>
