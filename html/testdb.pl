#!/usr/bin/perl


use DBI;
#use DateTime::Format::SQLite;

my $dbfile = "./test.db";      # your database file
my $table = 'S_Values';
my @rows  = qw(Unit ID Name Type Address);


my $dbh = DBI->connect(          # connect to your database, create if needed
    "dbi:SQLite:dbname=$dbfile", # DSN: dbi, driver, database file
    "",                          # no user
    "",                          # no password
    { RaiseError => 1 },         # complain if something goes wrong
) or die $DBI::errstr;


#print "Time: ", time(), "\n";


$res = $dbh->selectall_arrayref("select * from S_Values where Date_Time>='2018-12-30 14:57';");
foreach my $row (@$res) {
  #($id, $address) = @$row;
  # print("ID: $id - Adress: $address\n");
  print "id: ${$row}[0] Time: ${$row}[1] Value: ${$row}[2]\n";
}
$dbh->disconnect();
