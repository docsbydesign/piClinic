# coding=utf-8
#
#  Copyright 2020 by Robert B. Watson
#
#  Permission is hereby granted, free of charge, to any person obtaining a copy of
#  this software and associated documentation files (the "Software"), to deal in
#  the Software without restriction, including without limitation the rights to
#  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
#  of the Software, and to permit persons to whom the Software is furnished to do
#  so, subject to the following conditions:
#
#  The above copyright notice and this permission notice shall be included in all
#  copies or substantial portions of the Software.
#
#  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
#  SOFTWARE.
#
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
import re
import csv
import codecs
import argparse

csv_source_file = 'SourceFile'
csv_constant = 'UI_TEXT_CONSTANT'

copyrightText = """ *
 * Copyright 2020 by Robert B. Watson
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  he Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 *  of the Software, and to permit persons to whom the Software is furnished to do
 *  so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
"""

accessTest = """
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
$apiCommonInclude = dirname(__FILE__).'/../api/api_common.php';
if (!file_exists($apiCommonInclude)) {
    // if not over one, try up one more directory and then over.
    $apiCommonInclude = dirname(__FILE__).'/../../api/api_common.php';
    if (!file_exists($apiCommonInclude)) {
        // if not over one, try up one more directory and then over.
        $apiCommonInclude = dirname(__FILE__).'/../../../api/api_common.php';
    }
}
require_once $apiCommonInclude;
exitIfCalledFromBrowser(__FILE__);

"""

how="""Tool to build localized strings for piClinic files in the specified srcpath.

How to create localize strings for the piClinic files

1. Create your localized strings in a .csv file. The piClinic uses /uitext/UIText.csv.
2. Use the localized string constants for any literal text in the .php files.
    a. The string constant names used in a .php file and the .csv file must start with "TEXT_".
    b. The piClinic supports English and Spanish, but additional languages can be added 
        as new columns to the .csv file after these two.
3. When you run this tool, it reads the .php files in the directory specified by
    the srcpath parameter and looks for the presence of TEXT_* string constants.
    a. For each file in the directory that uses at least one of these constants:
        i. The instances of TEXT_* are read from the .php file.
        ii. Each instance is located in the .csv file
        iii. The instance is formatted and written to a string file for that .php file.        
    b. The string files created:
        i. Are locted in the uitext subdirectory of the directory with the .php file.
        ii. Have a file name that is the .php file's name + "Text". (e.g. MyFile.php would
            have as a text file: uitext/MyFileText.php)
4. You can use the --build=all parameter to build all text files, or omit the --build parameter
    to build text files for only those .php files that are newer than the .csv file.

Command line:
python build-uiText.py [-h] [--build {new,all}] --infile INFILE [--srcpath SRCPATH]
"""

cliParser = argparse.ArgumentParser(prog="build-uiText.py", usage=how)
cliParser.add_argument('--build', required=False, choices=["new","all"], default="new", \
    help="What to build: new | all. new updates only changed files and all rebuilds all files.")
cliParser.add_argument('--infile', required=True,  \
    help="The file name of the .CSV input file with the source strings.")
cliParser.add_argument('--srcpath', required=False, default='./', \
    help="The directory with the .php files that use translated strings. If blank or omitted, the current directory is used." )


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
            if 'file_strings' in file_info:
                for uiTextItem in file_info['file_strings']:
                    outFile.write ("\tif (!defined('{}')) {{ define('{}','{}',false); }}\n".format(uiTextItem['UI_TEXT_CONSTANT'], uiTextItem['UI_TEXT_CONSTANT'], uiTextItem[langField]))
                outFile.write ("{}\n".format('}'))
            # else no strings to write
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

    #    print('infile has: {} rows and {} columns'.format(len(csv_rows), len(csv_fields)))
    #    print('Languages in file: ', langs)

    # create a list of the php files to scan
    if (platform.system() == 'Windows'):
        path_char = '\\'
    else:
        path_char = '/'

    files = [f for f in os.listdir(arg_codedir) if os.path.isfile(arg_codedir + path_char + f)]
    # print('outpath: "{}" has {} files'.format((arg_codedir + path_char), len(files)))
    
    php_files = []

    for file in files:
        # print('reviewing {}...'.format(file))
        # check the last 8 characters of the name
        if (file[-4:] not in ['.php', '.svg' ]) :
        #    print('  {} is not a supported file type.')
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

    print('{} files to update.'.format(len(php_files)))
    # get display strings used by each file
    for php_file in php_files:
        with open(php_file['source'], "r") as source_file:
            # print ("reading: " + php_file['source'])
            text_vars = [];
            for line in source_file:
                matches = re.findall ('TEXT_[0-9A-Z_]+', line, )
                for match in matches:
                    text_vars.append(match)

        php_file['strings'] = list(set(text_vars))
        php_file['strings'].sort()

    return createTextFiles(php_files, csv_rows, langs)


def main ():
    args = cliParser.parse_args()
    # assign default values
    arg_build = args.build	    # default: build all files
    arg_csvfile = args.infile   # required parameter: the file with the localized strings
    arg_codedir = args.srcpath  # folder with the .php source files to scan; the default is current folder

    # create the string files
    filesCreated = createFiles (arg_build, arg_csvfile, arg_codedir)

    return ("{} UI text files created.".format(filesCreated))

if __name__ == '__main__':
    main ()
