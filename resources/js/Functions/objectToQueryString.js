export function objectToQueryString(obj, prefix) {
    var str = [],
        p;
    for (p in obj) {
        if (obj.hasOwnProperty(p)) {
            var k = prefix ? prefix + "[" + p + "]" : p,
                v = obj[p];
            str.push((v !== null && typeof v === "object") ?
                objectToQueryString(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
    }

    str = str.filter(n => n);

    return str.join("&");
}
