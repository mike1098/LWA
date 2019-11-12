#!/usr/bin/perl
# Test if we get the outside temperature from the LWA203 without prior negotiation phase.

use Device::SerialPort qw( :PARAM :STAT 0.07 );
use DBI;
use strict;

########################### Global Variables ######################################################################
my $STALL_DEFAULT=60; # how many seconds wait till the next attempt
my $timeout=3;
my $tty1="/dev/ttyUSB0" ;
my $dbfile = "HouseKeeper.db";      # your database file
my $debug = 1;

##############################Initialize RS232 Port #########################################################

my $port=Device::SerialPort->new("/dev/ttyUSB0",0) || die "Can't open $tty1: $!\n";

$port->baudrate(2400); 
$port->parity("even");
$port->databits(8);
$port->stopbits(2);
$port->handshake("none");
$port->alias("LWA");

$port->read_char_time(0);     # don't wait for each character
$port->read_const_time(1000); # 1 second per unfulfilled "read" call
$port->is_xon_char(0x11);     # VSTART (stty start=.)
$port->is_xoff_char(0x13);    # VSTOP
$port->is_stty_eof(0x1a);     # VEOF
$port->dtr_active(0);              # sends outputs direct to hardware
$port->rts_active(1);             # return status of ioctl call

#Purge RX and TX
$port->purge_all;

$port->user_msg(1);       # built-in instead of warn/die above
$port->error_msg(1);      # translate error bitmasks and carp

$port->write_settings || undef $port;

#########################################Open Database #####################################
my $dbh = DBI->connect(          # connect to your database, create if needed
    "dbi:SQLite:dbname=$dbfile", # DSN: dbi, driver, database file
    "",                          # no user
    "",                          # no password
    { RaiseError => 1 },         # complain if something goes wrong
) or die $DBI::errstr;

########################### Some debug Variables ###############################################

my $chars=0;
my $buffer="";
my $initialReq = pack('H*','0D000D01000B000000000026');
my $initialRepl = pack('H*','555555555555555555550352');
my $outTempReq = pack('H*','0D000301000C00000000001D');
my $InBytes;my $OutBytes;my $ErrorFlags;my $count_out;my $BlockingFlags;


while ($timeout>0)
  {
  ###################################### Write initial request ###################################################
  print "Write initial request\n" if $debug;
  $count_out = $port->write($initialReq);    
  warn " write failed\n" unless ($count_out);
  warn " write incomplete sent:",$count_out,"\n" if ( $count_out != length($initialReq) );
	if ($debug) {
	  ($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
	  print " Wrote: ", $count_out, " Characters ", $OutBytes, " Bytes ", "Error: ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
	  print " wrote: ", unpack('H*',$initialReq), "\n";
	  print "Read first 12 char\n";
	};
	###################################### Read from port #########################################################
	my ($count,$saw)=$port->read(12); # will read _up to 12 chars
	if ($debug) {
	  ($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
	  print " Read: ", $count, " Characters ", $InBytes, " Bytes ", "Error: ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
	  print " Got: ", unpack('H*',$saw), "\n";
	};
	##################################### If we get the right answer read the second reply #########################################
	if (unpack('H*',$saw) eq unpack('H*',$initialReq)) {
		print "Read second 12 char\n" if $debug;		
		my ($count,$saw)=$port->read(12); # will read _up to_ 24 chars
		if ($debug) {
		  ($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
		  print " Read: ", $count, " Characters ", $InBytes, " Bytes ", "Error: ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
		  print " Got: ", unpack('H*',$saw), "\n";
		};
		############################################# If the second reply match we are in the game ###############################
		if (unpack('H*',$saw) eq unpack('H*',$initialRepl)) {
			while ($timeout>0) {
			  $port->purge_all;
			  ################################################ Get Sensors from DB ######################################
			  my $res = $dbh->selectall_arrayref("SELECT ID, Address, Name FROM sensors;");
			  ################################################ Request Values from LWZ ##################################				
			  foreach my $row (@$res) {
  			    my ($id, $address) = @$row;
				#check of address has valid values		
    			    my $Req = pack('H*',${$row}[1]);	
				  $count_out = $port->write($Req);
				  warn " write failed\n" unless ($count_out);
				  warn " write incomplete sent:",$count_out,"\n" if ( $count_out != length($Req) );
					if ($debug) {
					  ($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
					  print " Wrote: ", $count_out, " Characters ", $OutBytes, " Bytes ", "Error: ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
					  print " Read first 12 char\n" if $debug;
					  };
					my ($count,$saw)=$port->read(12); # will read _up to 12 chars
					if ($debug) {
					  ($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
					  print " Read: ", $count, " Characters ", $InBytes, " Bytes ", "Error: ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
					};
				  if (unpack('H*',$saw) eq unpack('H*',$Req)) {
				     print "Read second 12 char and convert to values\n" if $debug;
				     my ($count,$saw)=$port->read(12); # will read _up to 12 chars
				     if ($debug) {
					  ($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
					  print " Read: ", $count, " Characters ", $InBytes, " Bytes ", "Error: ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
					};
				     eval {
				     my ($first,$second) = unpack ('x6s>s>',$saw);# || warn print "could not unpack response\n";
				     
				     print "First Value: $first, second value: $second\n" if $debug;
				     my $value;
				     if ($first > 0) {$value = $first} elsif ($second > 0) {$value = $second} else {$value=0};
				     print "Insert $value for sensor ${$row}[2]\n" if $debug;
				     $dbh->do("INSERT INTO 'S_Values' (Sensor_ID, S_Value) VALUES (${$row}[0],$value);") or warn $DBI::errstr;
				     };				     
				     $dbh->commit();
				  } else {
					print "Got not what I expected\n";
				  };
				
				};
				sleep $STALL_DEFAULT;
				###########################################################################################################

			}
		}
		else {
		     sleep $STALL_DEFAULT;
		     $timeout--;
		}	
	}
print "$timeout No response from LWZ  \n";
sleep $STALL_DEFAULT;
$timeout--;
}

$port->close || warn "close failed";
print "Exit because of no connection to LWZ";
undef $port;
$dbh->disconnect();
