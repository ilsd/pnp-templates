    <?php
    #
    # Copyright (c) 2006-2010 Joerg Linge (http://www.pnp4nagios.org)
    # Plugin: check_icmp [Multigraph]
    #
    # RTA
    #
    $ds_name[1] = "$servicedesc";
    $opt[1]  = "-I --units-exponent=0 -Y --lower=0 --vertical-label \"Errors $UNIT[1]\" --title \"'$ds_name[1]'\" --watermark=http://netapp-monitoring.info";
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
