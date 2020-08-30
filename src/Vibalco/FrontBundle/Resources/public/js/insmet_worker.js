/**
 * Update Query String
 * @param uri
 * @param key
 * @param value
 * @returns {void | string|string|*}
 */
function updateQueryStringParameter(uri, key, value) {
  let re = new RegExp("([?&])" + key + "=.*?(&|#|$)", "i")
  if (value === undefined) {
    if (uri.match(re)) {
      return uri.replace(re, '$1$2')
    } else {
      return uri;
    }
  } else {
    if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
      let hash = ''
      if (uri.indexOf('#') !== -1) {
        hash = uri.replace(/.*#/, '#')
        uri = uri.replace(/#.*/, '')
      }
      let separator = uri.indexOf('?') !== -1 ? "&" : "?"
      return uri + separator + key + "=" + value + hash
    }
  }
}

var AjaxClient = {
  /**
   * Get Call
   * @param url
   * @param params
   * @returns {Promise<any>}
   */
  get(url, params) {
    return new Promise(((resolve, reject) => {
      let xhr = new XMLHttpRequest();
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            resolve(xhr.responseXML)
          } else {
            reject(xhr.responseText);
          }
        }
      }
      let uri = url
      if (params !== undefined) {
        for (const p in params) {
          uri = updateQueryStringParameter(uri, p, params[p])
        }
      }
      xhr.open('GET', uri)
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
      xhr.send()
    }));
  },
  /**
   *
   * @param url
   * @param params
   * @returns {Promise<any>}
   */
  post(url, params) {
    return new Promise(((resolve, reject) => {
      let xhr = new XMLHttpRequest()
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            resolve(xhr.responseXML)
          } else {
            reject(xhr.responseText)
          }
        }
      }
      xhr.open('POST', url)
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
      if (params !== undefined) {
        xhr.send(params)
      } else {
        xhr.send()
      }

    }))
  }
}

onmessage = function (event) {
  if (event.data) {
    AjaxClient.get(event.data, undefined)
      .then(function (response) {
        postMessage(response);
      })
      .catch(function () {
        postMessage(false);
      })
  }
}