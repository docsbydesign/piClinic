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
 *	Copyright (c) 2018, Robert B. Watson
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
 *  along with piClinic Console software at https://github.com/MercerU-TCO/CTS/blob/master/LICENSE. 
 *	If not, see <http://www.gnu.org/licenses/>.
"""

accessTest = """
// check to make sure this file wasn't called directly
//  it must be called from a script that supports access checking
require_once 'api_common.php';
exitIfCalledFromBrowser(__FILE__);

"""

def createStringsInFiles(arg_destdir, csvFilePath, csvRows, files, langs):
	# for each file in the list
	fileCount = 0
	for file in files:
		outFilePath = arg_destdir + file + '.php'
		print ('Creating: ', outFilePath)
		# open output file
		with codecs.open(outFilePath,'w','utf-8') as outFile:
			# write file header
			outFile.write("<?php\n/*\n")
			# outFile.write(" * File generated: {}\n".format( datetime.datetime.now().strftime('%c') ))
			outFile.write(" *\n * Source file: {}".format(csvFilePath))
			outFile.write(copyrightText)
			outFile.write(" *\n */\n")
			outFile.write(accessTest)
			# 
			for language in langs:
				# deal with special case for UI_TEXT_CONSTANT
				langField = language
				if (langField == 'UITEST_LANGUAGE'):
					langField = 'UI_TEXT_CONSTANT'
				#write language header
				outFile.write ("// Strings for {}\nif ($pageLanguage == {}) {}\n".format(language,language,'{'))
				for uiTextItem in csvRows:
					# only write the rows that apply to the current output file
					if (uiTextItem[csv_source_file] == file):
						outFile.write ("\tdefine('{}','{}',false);\n".format(uiTextItem['UI_TEXT_CONSTANT'], uiTextItem[langField]))
				outFile.write ("{}\n".format('}'))
			outFile.write ("?>\n")
		fileCount = fileCount + 1
	
	return fileCount

def createTextFile (file_list, csv_rows):

		# read the rest of the columns to make a list of the output languages
		langs = ['UITEST_LANGUAGE']  # all files have at least this language
		for key in csvFields:
			if ((key != csv_source_file) and
					(key != csv_constant)):
				# it must be a language key so add it to the list
				langs.append(key)

		langs.sort()
		print('Languages in file: ', langs)

		files = []
		for row in csv_rows:
			files.append(row[csv_source_file])
		files = set(files)

		# sort CSV by UI_TEXT_CONSTANT before creating files
		sortedCsvRows = sorted(csv_rows, key=lambda k: k[csv_constant])

		fileCount = createStringsInFiles(arg_codedir, os.path.abspath(arg_csvfile), sortedCsvRows, files, langs)

		return fileCount

def createFiles (arg_csvfile, arg_codedir):
	# test the CSV file
	file_count = 0
	if not os.path.isfile(arg_csvfile):
		print (arg_csvfile, ' was not found.')
		return file_count
	else:
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

			# and make sure they are all there
			if not ((csv_source_file in csv_fields) and (csv_constant in csv_fields)):
				print('CSV file is missing required column(s)')


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
				# if new file doesn't exist, it needs to
				if os.path.isfile(newFile['text']):
					# See if the text file is newer than the csv, if so, don't build a new one
					if os.path.getmtime(newFile['text']) > csv_file_date:
						# See if the text text file is newer than the source code file
						if os.path.getmtime(newFile['text']) > os.path.getmtime(newFile['source']):
							continue
				# else need to create the text file
				php_files.append(newFile)

	# get display strings in each file to
	for php_file in php_files:
		with open(php_file['source'], "r") as source_file:
			text_vars = [];
			for line in source_file:
				matches = re.findall ('TEXT_[A-Z_]+', line, )
				for match in matches:
					text_vars.append(match)

		php_file['strings'] = list(set(text_vars))
		php_file['strings'].sort()

	print (json.dumps (php_files, sort_keys=True, indent=4))

	return # createTextFiles(php_files, csv_rows)


def main (argv):
	# assign default values
	arg_codedir = './'		# folder with the .php source files to scan; the default is current folder
	arg_csvfile = None		# required parameter: the file with the localized strings
	
	# read command line args and assign parameter value
	# argv[0] = the script file name
	# argv[1] = the csvfile (required)
	# argv[2] = dest directory (optional)
	if len(argv) >= 2:
		# read the prefix string
		arg_csvfile = argv[1]
	else:
		print ("""			build-uiText.py <source-csv> <dest-path> 

			<source-csv> the CSV that contains the string constants and contents
			<dest-path> = Where to write the created .php include files with the strings
""")
	if len(argv) >= 3:
		arg_codedir = argv[2]

	# ignore any other parameters
		
	# create the string files
	filesCreated = createFiles (arg_csvfile, arg_codedir)
	
	return ("{} UI text files created.".format(filesCreated))

if __name__ == '__main__':
	main (sys.argv)