#!/bin/bash
#help
# constants
TEMPDIR="/var/local/piclinic/downloads/"
# usage text
usage()
{
    echo "usage: piClinicDownload  [-h] -f file -t [db|log|system] -p mysqlpassword ]"
}
# check for command line parameters
if ["$1" == ""] 
then 
	usage
	exit 1
fi
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
        -h | --help )   usage
                        exit
                        ;;
        * )             usage
                        exit 1
    esac
    shift
done
# check the download type
if [ "$TYPE_PARAM" != "" ]
then
    case $TYPE_PARAM in
        log )
                        TEMP_LOG_FILE=$TEMPDIR"piclinic_tmplog.tar"
						TEMP_DB_FILE=""
						OUTFILE="$TEMPDIR$FILENAME"
                        ;;
        db  )
                        TEMP_LOG_FILE=""
						TEMP_DB_FILE=$TEMPDIR"piclinic_tmpdb.tar"
						OUTFILE="$TEMPDIR$FILENAME"
                        ;;
        system )
                        TEMP_LOG_FILE=$TEMPDIR"piclinic_tmplog.tar"
						TEMP_DB_FILE=$TEMPDIR"piclinic_tmpdb.tar"
						OUTFILE="$TEMPDIR$FILENAME"
                        ;;
        * )             usage
                        exit 1
    esac
fi
# show status
echo "*> showing results"
echo "FILENAME:      $FILENAME"
echo "TEMP_LOG_FILE: $TEMP_LOG_FILE"
echo "TEMP_DB_FILE:  $TEMP_DB_FILE"
echo "OUTFILE:       $OUTFILE"
echo "PASSWORD: 	 $PASSWORD"

# archive logs if requested
if [ "$TEMP_LOG_FILE" != "" ]
then
	echo "saving logs"
	set -x #echo on
	# archive the server log
	tar -cvf $OUTFILE /var/log/apache2
	# add the piclinic logs to the server log archive, if it exists, otherwise create a new archive for it
	if [ ! -f $OUTFILE ]
	then
		tar -cvf $OUTFILE /var/log/piclinic
	else
		tar -rvf $OUTFILE /var/log/piclinic
	fi
	ls -l $TEMPDIR
fi
#archive db if requested
if [ "$TEMP_DB_FILE" != "" ]
then
	echo "saving db"
	set -x #echo on
	mysqldump -u CTS-user -p $PASSWORD piclinic > $TEMP_DB_FILE 
	# archive the dump if it worked/
	if [ $? -eq 0 ]
	then
		# add it to the log archive, if it exists, otherwise create a new archive
		if [ ! -f $OUTFILE ]
		then
			tar -rvf $OUTFILE $TEMP_DB_FILE 
		else
			tar -cvf $OUTFILE $TEMP_DB_FILE 
		fi
	else
		echo "MYSQL dump command failed."
	fi		
fi
# zip the results
if [ -f $OUTFILE ]
then
	gzip $OUTFILE
else
	exit 1 #archive not successful
fi
# that's all
echo "piClinic archive complete"
exit 0
