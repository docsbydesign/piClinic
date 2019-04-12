#!/bin/bash
#
# constants
TEMPDIR="/var/local/piclinic/downloads/"
TEMP_DBFILE="piclinic_db.sql"
VERBOSE="0"
#
# usage text
usage()
{
    echo "usage: piClinicDownload  [-h] -f file -t [db|log|system] -p mysqlpassword ]"
}
#
# check for command line parameters
if [ "$1" = "" ] 
then 
	usage
	exit 1
fi
#
# read command line parameters
while [ "$1" != "" ]; do
    case $1 in
        -f | --file )	shift
                        FILENAME=$1
                        ;;
        -p | --password )   shift
						PASSWORD=$1
                        ;;
        -t | --type )   shift
						TYPE_PARAM=$1
                        ;;
        -v | --verbose ) VERBOSE="1"
                        ;;
        -h | --help )   usage
                        exit
                        ;;
        * )             usage
                        exit 1
    esac
    shift
done
#
# check the download type
if [ "$TYPE_PARAM" != "" ]
then
    case $TYPE_PARAM in
        log )
                        TEMP_ARCHIVE=$TEMPDIR"piclinic_archive.tar"
						TEMP_DB_PATH=""
						OUTFILE="$TEMPDIR$FILENAME"
                        ;;
        db  )
                        TEMP_ARCHIVE=""
						TEMP_DB_PATH="$TEMPDIR$TEMP_DBFILE"
						OUTFILE="$TEMPDIR$FILENAME"
                        ;;
        system )
                        TEMP_ARCHIVE=$TEMPDIR"piclinic_archive.tar"
						TEMP_DB_PATH="$TEMPDIR$TEMP_DBFILE"
						OUTFILE="$TEMPDIR$FILENAME"
                        ;;
        * )             usage
                        exit 1
    esac
fi
#
# show status
if [ "$VERBOSE" = "1" ]
then
	echo "*> Script Variables:"
	echo "FILENAME:      $FILENAME"
	echo "TEMP_ARCHIVE:  $TEMP_ARCHIVE"
	echo "TEMP_DB_PATH:  $TEMP_DB_PATH"
	echo "OUTFILE:       $OUTFILE"
	echo "PASSWORD: 	 $PASSWORD"
	echo "VERBOSE:		 $VERBOSE"
	set -x
fi
#
# make sure we're creating a new archive
if [ -f $OUTFILE ] 
then
	rm $OUTFILE
fi
#
# archive logs if requested
if [ "$TEMP_ARCHIVE" != "" ]
then
	if [ "$VERBOSE" = "1" ]
	then
		echo "Saving logs"
		set -x #echo on
	fi
	# archive the server log
	tar -cvf $TEMP_ARCHIVE -C /var log/apache2
	# add the piclinic logs to the server log archive, if it exists, otherwise create a new archive for it
	if [ ! -f $TEMP_ARCHIVE ]
	then
		tar -cvf $TEMP_ARCHIVE -C /var log/piclinic
	else
		tar -rvf $TEMP_ARCHIVE -C /var log/piclinic
	fi
	if [ "$VERBOSE" = "1" ]
	then
		ls -l $TEMPDIR
	fi
fi
#
#archive db if requested
if [ "$TEMP_DB_PATH" != "" ]
then
	if [ "$VERBOSE" = "1" ]
	then
		echo "saving db"
		set -x #echo on
	fi
	mysqldump -uCTS-user -p$PASSWORD piclinic > $TEMP_DB_PATH 
	# archive the dump if it worked/
	if [ $? -eq 0 ]
	then
		# add it to the log archive, if it exists, otherwise create a new archive
		if [ ! -f $TEMP_ARCHIVE ]
		then
			tar -cvf $TEMP_ARCHIVE -C $TEMPDIR $TEMP_DBFILE
		else
			tar -rvf $TEMP_ARCHIVE -C $TEMPDIR $TEMP_DBFILE
		fi
	else
		echo "MYSQL dump command failed."
	fi
	if [ "$VERBOSE" = "1" ]
	then
		ls -l $TEMPDIR
	fi
fi
#
# zip the results
if [ -f $TEMP_ARCHIVE ]
then
	gzip -cf $TEMP_ARCHIVE > $OUTFILE
	if [ "$VERBOSE" = "1" ]
	then
		ls -l $TEMPDIR
	fi
else
	exit 1 #archive not successful
fi
#
# that's all
if [ "$VERBOSE" = "1" ]
then
	echo "piClinic archive complete"
	set +x
fi
exit 0
