#!/bin/bash
#help
# usage text
usage()
{
    echo "usage: piClinicDownload  [-h] [-f file -t [db|log|system]]"
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
                        DUMP_LOG=1
						DUMP_DB=0
                        ;;
        db  )
                        DUMP_LOG=0
						DUMP_DB=1
                        ;;
        system )
                        DUMP_LOG=1
						DUMP_DB=1
                        ;;
        * )             usage
                        exit 1
    esac
fi
# show status
echo "*> showing results"
echo "FILENAME: $FILENAME"
echo "TYPE_LOG: $DUMP_LOG"
echo "TYPE_DB:  $DUMP_DB"
# constants
TEMPDIR="/var/local/piclinic/downloads/"
TEMPFILENAME=$(date +"%Y_%m_%d-%H_%M_%S.tar")
TEMPFILE=$TEMPDIR"piclinic_tmplog.tar"
OUTFILE="$TEMPDIR$FILENAME"
echo "TEMPFILE: $TEMPFILE"
echo "OUTFILE: $OUTFILE"
# zip logs if requested
if [ "$DUMP_LOG" = "1" ]
then
	echo "saving logs"
	set -x #echo on
	tar -cvf $OUTFILE /var/log/apache2
	tar -rvf $OUTFILE /var/log/piclinic
	ls -l $TEMPDIR
	gzip -v $OUTFILE
	ls -l $TEMPDIR
fi
#zip db if requested
if [ "$DUMP_DB" = "1" ]
then
	echo "saving db"
fi
# that's all
echo "saving files complete"
exit 0
