// print to php shell_exec
if(typeof process !== 'undefined'){
    console.log(getFilterCacheKey(process.argv[2]));
}

function getFilterCacheKey(requestUri) {
    let requestCache = '', tmp = {};

    let arr = requestUri.replace(/%5B\d*%5D/g, '').split('&').map(function (name) {
        return name.split('=');
    });

    for (let i = 0; i < arr.length; i++) {
        if (arr[i][0] === 'period' && arr[i][1] !== 'null') {
            tmp['y'] = arr[i][1];
        }
        if (arr[i][0] === 'participant_categories') {
            tmp['pcat'] = tmp['pcat'] ? tmp['pcat'].concat(arr[i][1]) : arr[i][1];
        }
        if (arr[i][0] === 'severities') {
            tmp['sev'] = tmp['sev'] ? tmp['sev'].concat(arr[i][1]) : arr[i][1];
        }
        if (arr[i][0] === 'accident_categories') {
            tmp['acat'] = tmp['acat'] ? tmp['acat'].concat(arr[i][1]) : arr[i][1];
        }
    }

    for (const key in tmp) {
        requestCache += key + tmp[key];
    }

    if (requestCache === '') {
        requestCache = 'all';
    }

    return requestCache;
}
