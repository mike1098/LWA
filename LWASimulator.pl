#!/usr/bin/perl


use Device::SerialPort qw( :PARAM :STAT 0.07 );
#use strict;
my $tty1="/dev/ttyUSB0";

my $port=Device::SerialPort->new("/dev/ttyUSB0",0) || die "Can't open /dev/ttyUSB0: $!\n";

my $STALL_DEFAULT=6; # how many seconds to wait for new input
 
my $timeout=120;
 
$port->read_char_time(0);     # don't wait for each character
$port->read_const_time(1000); # 1 second per unfulfilled "read" call

# Set speed of RS232 Port
$port->baudrate(9600);
$port->parity("even");
$port->databits(8);
$port->stopbits(2);
$port->handshake("none");
$port->alias("LWA");

$port->is_xon_char(0x11);     # VSTART (stty start=.)
$port->is_xoff_char(0x13);    # VSTOP
#$port->is_stty_intr($num_char);    # VINTR
#$port->is_stty_quit($num_char);    # VQUIT
$port->is_stty_eof(0x1a);     # VEOF
#$port->is_stty_eol($num_char);     # VEOL
#$port->is_stty_erase($num_char);   # VERASE
#$port->is_stty_kill($num_char);    # VKILL
#$port->is_stty_susp($num_char);    # VSUSP

$port->user_msg(ON);       # built-in instead of warn/die above
$port->error_msg(ON);      # translate error bitmasks and carp

#$port->parity_enable(F);   # faults during input
$port->debug(0);

$port->write_settings || undef $port;

$port->save("ttyconfig.cfg")
       || warn "Can't save $Configuration_File_Name: $!\n";

#Purge RX and TX
$port->purge_all;

#Write something to the port
#my $output_string=0x123456789;

#$count_out = $port->write($output_string);
 # warn "write failed\n"         unless ($count_out);
  #warn "write incomplete\n"     if ( $count_out != length($output_string) );



 my $chars=0;
 my $buffer="";

my $answer = pack('H*','0D000D01000B000000000026');
my $answer2 = pack('H*','555555555555555555550352');

 while ($timeout>0) {
        my ($count,$saw)=$port->read(255); # will read _up to_ 255 chars
        if ($count > 0) {

		#my $request = unpack('H*',$saw);
		#print "Before if statement read: ", $request, " Write: ",$answer, "\n";
		
		
		if (unpack('H*',$saw) eq unpack('H*',$answer)) {
			$port->purge_all; 
			print "write: ", (unpack('H*',$answer)), "\n";
			$count_out = $port->write($answer);
			warn "write failed\n"         unless ($count_out);
			warn "write incomplete sent:",$count_out,"\n" if ( $count_out != length($answer) );
			#;
			($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
			print "Wrote: ", $count_out, " Characters ", $OutBytes, " Bytes ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
			#sleep 1;
			print "write: ", (unpack('H*',$answer2)), "\n";		        
			$count_out = $port->write($answer2);
			warn "write failed\n"         unless ($count_out);
			warn "write incomplete sent:",$count_out,"\n" if ( $count_out != length($answer2) );
			($BlockingFlags, $InBytes, $OutBytes, $ErrorFlags) = $port->status;
			print "Wrote: ", $count_out, " Characters ", $OutBytes, " Bytes ", $ErrorFlags, " WriteDrain: ",$port->write_drain,"\n";
		}; 
 		#print $saw,"\n";
		#print "Hexadecimal number: ", uc(sprintf("%x\n", $saw)), "\n";
                # Check here to see if what we want is in the $buffer
                # say "last" if we find it
        }
        else {
                $timeout--;
        }
 }

 if ($timeout==0) {
        die "Waited $STALL_DEFAULT seconds and never saw what I wanted\n";
 } 

$port->close || warn "close failed";
undef $port;
