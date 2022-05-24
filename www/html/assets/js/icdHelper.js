"use strict";
/*
 *
 * Copyright (c) 2019 by Robert B. Watson
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
 *
 */
// simple AJAX query class found at https://stackoverflow.com/questions/247483/http-get-request-in-javascript
var HttpClient = function() {
    this.get = function(aUrl, timeout, token, aCallback, eCallback) {
        var anHttpRequest = new XMLHttpRequest();
        anHttpRequest.onreadystatechange = function() {
            if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
                aCallback(anHttpRequest.responseText);
            if (anHttpRequest.readyState == 4 && anHttpRequest.status != 200)
                eCallback(anHttpRequest.statusText);
        }

        anHttpRequest.open( "GET", aUrl, true );
        anHttpRequest.timeout = timeout;
        if (token != null) {
            anHttpRequest.setRequestHeader('X-piClinic-token', token);
        }
        anHttpRequest.onTimeout = eCallback('timeout');
        anHttpRequest.send( null );
    }
}

function inputKeyUpEventHandler (event, inputElem, dataListId, token, lang) {
    // searches diagnosis database for entries that contain
    //  inputId.value and populates dataListId entries found
    var minSearchLen = 2; // number of characters in input required to search
    // maxSearchReturn impacts DB look up time. Adjust as necessary
    var maxSearchReturn = 20; // the most elements returned in a call
    //
    //	Don't look anything up unless the key is a digit, a letter, or the backspace
    if (!(((event.keyCode >= 48) && (event.keyCode <= 57)) || // it's a digit key
        (event.keyCode == 8) || // it's the backspace key
        ((event.keyCode >= 65) && (event.keyCode <= 90)))) { return false;}  // it's a letter key
    //
    var dataListObj = document.getElementById (dataListId);
    if ((dataListObj === undefined) || (dataListObj === null)) { return false; } // no list element found
    if ((inputElem === undefined) || (inputElem === null)) { return false; } // no input element found
    if (inputElem.value.length <= minSearchLen) {
        dataListObj.innerHTML = ''; // clear the list
        return false;
    } // not enough to search
    //
    if (lang == null) {lang = 'en';} //default to English
    // the the qs= search query can be used to speed up searches by searching from the start of the text.
    var searchPath = '/api/icd.php?language=' + lang + '&sort=t&limit=' + maxSearchReturn.toString() + '&q='; // generic search query
    //
    // See if we need to go to the DB to get the matching strings
    var getStrings = false;
    if (inputElem.value.length < inputElem.getAttribute('data-last-search').length) {
        // get the matching strings if the new string is smaller than the last one
        //  because the new string could match more entries than are currently stored
        getStrings = true;
    } else if (inputElem.getAttribute('data-last-search').length > inputElem.getAttribute('data-last-search').length) {
        // this is longer, so get new strings if the last one returned
        //  a full buffer, which means there might be more in the database.
        //  OR if the beginning part of the input value changed
        // if not, let the autofill feature narrow the list down automatically
        if ((inputElem.getAttribute('data-last-list-size') >= maxSearchReturn) ||
            (inputElem.value.substring(0,inputElem.getAttribute('data-last-search').length) != inputElem.getAttribute('data-last-search')))	{
            getStrings = true;
        }
    } else {
        // if the same length, see if the strings are different
        if (inputElem.value != inputElem.getAttribute('data-last-search')) {
            getStrings = true;
        }
    }
    if (getStrings) {
        // show loading text
        var fetchTimeEnd = performance.now();
        var fetchTimeReturn = fetchTimeEnd;
        var fetchTimeLoop = fetchTimeEnd;
        var fetchTimeLoad = fetchTimeEnd;
        var fetchTimeStart = fetchTimeEnd;
        inputElem.style.width = '70%';
        var loadingId = inputElem.id + 'Loading';
        var loadingText = document.getElementById(loadingId);
        loadingText.style.display = 'inline';
        //
        // get some autofill suggestions
        searchPath += inputElem.value;
        var descQuery = new HttpClient();
        descQuery.get (searchPath, 500, token,
            function (response) {
                var responseObj = JSON.parse(response);
                if (responseObj.count < 1) { return false;} // nothing to do, no data
                fetchTimeReturn = performance.now();
                // dataListObj.innerHTML = ''; // clear the list
                var icdMatch = null;
                // save the value of the last search for next time.
                inputElem.setAttribute('data-last-search', inputElem.value);
                inputElem.setAttribute('data-last-list-size', responseObj.count);
                if ((inputElem.value.length >= 3) && (inputElem.value.length<=5)) {
                    // trim down the left most string to the
                    //  size of an ICD code and remove any punctuation
                    icdMatch = inputElem.value.replace('.','').substring(0,5);
                }
                var optionList = [];
                if (responseObj.count > 1) {
                    for (var d = 0; d < responseObj.count; d++ ) {
                        var thisOption = responseObj.data[d];
                        // append options
                        optionList.push ('<option data-code-index="' +
                            thisOption.icd10index +
                            '" value="' +
                            thisOption.shortDescription + ' [' + thisOption.icd10code + ']'+
                            '" />');
                    }
                } else {
                    // an ICD-10 code match will only return 1 element so test it here
                    optionList.push ( '<option data-code-index="' +
                        responseObj.data.icd10index +
                        '" value="' +
                        responseObj.data.shortDescription + ' [' + responseObj.data.icd10code + ']'+
                        '" />');
                }
                fetchTimeLoop = performance.now();
                dataListObj.innerHTML = optionList.join('');
                fetchTimeEnd = performance.now();
                console.log('**Fetch: success. ' + responseObj.count + ' objects in ' + (fetchTimeReturn - fetchTimeStart).toFixed(4) + '/' + (fetchTimeLoop - fetchTimeReturn).toFixed(4) + '/' + (fetchTimeEnd - fetchTimeLoop).toFixed(4) + ' mS');
                return false;
            },
            function (status) {
                // reset control state
                inputElem.style.width = '';
                loadingText.style.display = 'none';
                fetchTimeEnd = performance.now();
                console.log('**Fetch: error.  (' + status + '): '  + (fetchTimeEnd - fetchTimeStart) + ' mS');
            }
        );
    } else {
        // console.log('**Fetch: skipped. ' + inputElem.getAttribute('data-last-list-size') + ' objects found in last search');
    }
    return false;
}

function setCodeValue(TextObj, DataListId, CodeFieldId, token, lang) {
    // look up the string to find the matching ICD-10 code
    //  first look in the autofill buffer and, if no match is found,
    //	write the string to look up
    var maxSearchReturn = 30; // the most elements returned in a call
    //
    // get the diagnosis string to lookup
    // first trim off any code strings
    var stringToMatch = TextObj.value.trim();
    // save the trimmed text value
    TextObj.value = stringToMatch;
    // get the data list to scan
    var dataListObj = document.getElementById (DataListId);
    var optionsList = null;
    if ((dataListObj === undefined) || (dataListObj == null)) {
        alert ('List field not found.');
        return (false);
    } else {
        optionsList = dataListObj.childNodes;
    }
    // get the field to update with the ICD-10 code
    var objForCode = document.getElementById (CodeFieldId);
    if ((objForCode === undefined) || (objForCode == null)){
        alert ('Code field not found.');
        return (false);
    }
    // search the values in the list
    var codeString = stringToMatch;
    var codeFoundInList = false;
    for (var i = 0; i < optionsList.length; i++) {
        var option = optionsList[i];
        if (option.value == stringToMatch) {
            codeString = option.getAttribute('data-code-index');
            codeFoundInList = true;
            break;
        }
    }
    if (!codeFoundInList) {
        // look up the description in the DB
        var firstBracket = stringToMatch.indexOf('[');
        var lastBracket = stringToMatch.indexOf(']');
        var stringStart = 0;
        var stringLength = stringToMatch.length;
        var searchParam = '&q='; // assume a generic search unless we find a code
        // if the string starts with a '@' don't try to parse out a code
        if (((stringLength > 0) && (stringToMatch[0] != '@')) && ((firstBracket >= 0) && (lastBracket >=0))) {
            // the string appears to have a code, so parse out the code
            stringStart = firstBracket + 1;
            stringLength = lastBracket - firstBracket - 1;
            searchParam = '&ce=';
        }
        // trim down the string to match
        stringToMatch = stringToMatch.substr(stringStart, stringLength).trim();
        var searchPath = '/api/icd.php?language=' + lang + '&sort=t&limit=' +
            maxSearchReturn.toString() +
            searchParam + stringToMatch; // search query
        var descQuery = new HttpClient();
        descQuery.get (searchPath, 1000, token,
            function (response) {
                var responseObj = JSON.parse(response);
                if (responseObj.count < 1) {
                    // no data returned (i.e. no match found) so
                    // save the description string (without any code) as the description
                    objForCode.value = stringToMatch;
                    return false;
                }
                var codeList = [];
                if (responseObj.count > 1) {
                    // a single match found, to make sure it's good complete
                    codeList = responseObj.data;
                } else {
                    // an ICD-10 code match will only return 1 element
                    codeList[0] = responseObj.data;
                }
                for (var d = 0; d < codeList.length; d++ ) {
                    // find a match in the returned data
                    if (searchParam == '&ce=') {
                        // compare ICD10 code
                        if (codeList[d].icd10code == stringToMatch) {
                            objForCode.value = codeList[d].icd10index;
                            return false;
                        }
                    } else {
                        //  compare the description
                        if (codeList[d].shortDescription == stringToMatch) {
                            objForCode.value = codeList[d].icd10index;
                            return false;
                        }
                    }
                }
                // if here, no match was found so return the stripped description string
                objForCode.value = stringToMatch;
                return (false);
            },
            function (status) {
                // an error occurred
                // so return the stripped description string
                objForCode.value = stringToMatch;
                return (false);
            }
        );
        return (false);
    } else {
        // save the code string for the description
        objForCode.value = codeString;
        return (false);
    }
    alert ('fell out of the loop');
    return (false);
}
