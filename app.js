/**
 * Transform PHP (c) 2016 Carlos Torchia
 * This program is licensed under the GNU GENERAL PUBLIC LICENSE.
 * NO WARRANTY.
 */

function updateNewUrl()
{
    var url = document.getElementById('urlField').value;
    var regex = document.getElementById('regexField').value;
    var replace = document.getElementById('replaceField').value;

    var transformation = {
        regex: regex,
        replace: replace
    };
    var pageUrl = location.href;
    var encodedTransformation = encodeURIComponent(btoa(JSON.stringify(transformation)));
    var encodedUrl = encodeURIComponent(btoa(url));
    var newUrl = pageUrl + '?' +
        'transformation=' + encodedTransformation +
        '&url=' + encodedUrl;

    var newUrlElement = document.getElementById('newUrl');
    newUrlElement.innerHTML = newUrl;
}
