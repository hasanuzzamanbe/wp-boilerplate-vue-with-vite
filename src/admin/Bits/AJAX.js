const request = function(method, route, data = {}) {
    const url = `${window.PluginClassName.rest.url}/${route}`;

    const headers = {'X-WP-Nonce': window.PluginClassName.rest.nonce};

    if (['PUT', 'PATCH', 'DELETE'].indexOf(method.toUpperCase()) !== -1) {
        headers['X-HTTP-Method-Override'] = method;
        method = 'POST';
    }

    return window.jQuery.ajax({
        url: url,
        type: method,
        data: data,
        headers: headers
    });
}

export default {
    get(route, data = {}) {
        return request('GET', route, data);
    },
    post(route, data = {}) {
        return request('POST', route, data);
    },
    delete(route, data = {}) {
        return request('DELETE', route, data);
    },
    put(route, data = {}) {
        return request('PUT', route, data);
    },
    patch(route, data = {}) {
        return request('PATCH', route, data);
    }
};

jQuery(document).ajaxSuccess((event, xhr, settings) => {
    const nonce = xhr.getResponseHeader('X-WP-Nonce');
    if (nonce) {
        window.PluginClassName.rest.nonce = nonce;
    }
});
