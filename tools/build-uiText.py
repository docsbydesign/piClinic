# coding=utf-8
#
#	script to create UI Text string files to include with PHP script files
#		
#		Command line format:
#	
#			create-uiText.py <source-csv> <dest-path> 
#
#			<source-csv> the CSV that contains the string constants and contents
#			<dest-path> = Where to write the files created (the file names are in the .csv)
#
import sys
import platform
import os.path
import datetime
import re
import csv
import codecs
import json

csv_source_file = 'SourceFile'
csv_constant = 'UI_TEXT_CONSTANT'

copyrightText = """
 *
 *	Copyright (c) 2019, Robert B. Watson
 *
 *	This file is part of the piClinic Console.
 *
 *  piClinic Console is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  piClinic Console is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with piClinic Console software at https://github.com/docsbydesign/piClinic/blob/master/LICENSE.
 *	If not, see <http://www.gnu.org/licenses/>.
"""

accessTest = """
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once dirname(__FILE__).'/../api/api_common.php';
exitIfCalledFromBrowser(__FILE__);

"""

def createStringsInFiles(file_info):
    # open output file
    with codecs.open(file_info['text'],'w','utf-8') as outFile:
        # write file header
        outFile.write("<?php\n/*\n")
        outFile.write(copyrightText)
        outFile.write(" *\n */\n")
        outFile.write(accessTest)
        #
        lang_idx = 0
        for language in file_info['langs']:
            # deal with special case for UI_TEXT_CONSTANT
            langField = language
            if (langField == 'UITEST_LANGUAGE'):
                langField = 'UI_TEXT_CONSTANT'
            #write language header
            outFile.write ("// Strings for {}\nif ($pageLanguage == {}) {}\n".format(language,language,'{'))
            for uiTextItem in file_info['file_strings']:
                outFile.write ("\tif (!defined('{}')) {{ define('{}','{}',false); }}\n".format(uiTextItem['UI_TEXT_CONSTANT'], uiTextItem['UI_TEXT_CONSTANT'], uiTextItem[langField]))
            outFile.write ("{}\n".format('}'))
        outFile.write ("//EOF\n")

    return 1

def getLangStrings (csv_rows, string):
    for row in csv_rows:
        if row['UI_TEXT_CONSTANT'] == string:
            return row
    return None

def createTextFiles (file_list, csv_rows, langs):

    sortedCsvRows = sorted(csv_rows, key=lambda k: k[csv_constant])

    file_count = 0
    for file_info in file_list:
        print ('Creating: ', file_info['text'])
        file_strings = []
        if file_info['strings']:
            for string in file_info['strings']:
                langStrings = getLangStrings(sortedCsvRows, string)
                if langStrings:
                    file_strings.append(langStrings)
                    file_info['file_strings'] = file_strings
                else:
                    print ("****String not defined for symbol: " + string)
            file_info['langs'] = langs
            file_count += createStringsInFiles(file_info)

#	print(json.dumps(file_list, indent=4))
#	print(json.dumps(csv_rows, indent=4))

    return
#	return file_count


def createFiles (arg_build, arg_csvfile, arg_codedir):
    # test the CSV file
    file_count = 0
    if not os.path.isfile(arg_csvfile):
        print (arg_csvfile, ' was not found.')
        return file_count
    else:
        # get the csv file date and then open the file
        csv_file_date = os.path.getmtime(arg_csvfile)
        # open the CSV file
        with codecs.open(arg_csvfile, 'r', 'utf-8') as csv_file:
            csv_read = csv.DictReader(csv_file, )

            # convert csv_read to dict object
            csv_rows = []
            for row in csv_read:
                csv_rows.append(row)

            # get columns found
            csv_fields = csv_rows[0].keys()
            # read the rest of the columns to make a list of the output languages
            langs = ['UITEST_LANGUAGE']  # all files have at least this language
            for key in csv_fields:
                if (key != csv_constant):
                    # it must be a language key so add it to the list
                    langs.append(key)

            # and make sure they are all there
            if not (csv_constant in csv_fields):
                print('CSV file is missing required column(s)')

        print('Languages in file: ', langs)

    # create a list of the php files to scan
    if (platform.system() == 'Windows'):
        path_char = '\\'
    else:
        path_char = '/'

    files = [f for f in os.listdir(arg_codedir) if os.path.isfile(arg_codedir + path_char + f)]

    php_files = []
    for file in files:
        # check the last 8 characters of the name
        if file[-4:] != '.php':
            continue
        else:
            # make sure this isn't a text string file
            if file[-8:] != 'Text.php':
                # create a new file name entry the is the base file name
                newFile = {}
                newFile['source'] = arg_codedir + path_char + file
                newFile['text'] = arg_codedir + path_char + 'uitext' + path_char + file[:-4]+'Text.php'
                if arg_build == 'new':
                    # if new file doesn't exist, it needs to
                    if os.path.isfile(newFile['text']):
                        # See if the text text file is newer than the source code file
                        if os.path.getmtime(newFile['source']) < os.path.getmtime(newFile['text']):
                            # See if the text file is newer than the csv, if so, don't build a new one
                            if os.path.getmtime(newFile['text']) > csv_file_date:
                                print ('Skipping ' + newFile['source'] + ' because the text file is newer than the source files.')
                                continue
                # else need to create the text file
                php_files.append(newFile)

    # get display strings used by each file
    for php_file in php_files:
        with open(php_file['source'], "r") as source_file:
            text_vars = [];
            for line in source_file:
                matches = re.findall ('TEXT_[0-9A-Z_]+', line, )
                for match in matches:
                    text_vars.append(match)

        php_file['strings'] = list(set(text_vars))
        php_file['strings'].sort()

    return createTextFiles(php_files, csv_rows, langs)


def main (argv):
    # assign default values
    arg_build = 'all'		# default: build all files
    arg_csvfile = None		# required parameter: the file with the localized strings
    arg_codedir = './'		# folder with the .php source files to scan; the default is current folder

    # read command line args and assign parameter value
    # argv[0] = the script file name
    # argv[1] = what to build: all | new
    # argv[2] = the csvfile (required)
    # argv[3] = dest directory (optional)

    if len(argv) >= 3:
        if argv[1] in ['all','new']:
            arg_build = argv[1]
        # read the csv file name
        arg_csvfile = argv[2]
    else:
        print ("""			build-uiText.py <source-csv> <dest-path> 
            [all|new]
            <source-csv> the CSV that contains the string constants and contents
            <code-path> = The folder with the source code that has the.php files using the strings
""")
    if len(argv) >= 4:
        arg_codedir = argv[3]

    # ignore any other parameters

    # create the string files
    filesCreated = createFiles (arg_build, arg_csvfile, arg_codedir)

    return ("{} UI text files created.".format(filesCreated))

if __name__ == '__main__':
    main (sys.argv)