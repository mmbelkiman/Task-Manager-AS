/* Read a page's GET URL variables and return them as an associative array.
 * ex: var first = getUrlVars(); <- to get all variables (without values)
 * ex: var first = getUrlVars()["Name"]; <- to get value of Name 
 */

function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}