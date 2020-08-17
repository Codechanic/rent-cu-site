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
window.addEventListener('load', function () {
  // var url = "http://localhost/met/genesis.xml";
  // var url = "http://www.insmet.cu/asp/genesis.asp?TB0=RSSFEED";
  AjaxClient.get(insmetUrl, undefined)
    .then(function (response) {
      var xml = response;
      if (xml !== null && xml !== '') {
        var list = xml.getElementsByTagName('item');
        var forecast = [];
        for (let node of list) {
          let obj = {};
          let children = node.children;
          for (let child of children) {
            let tagName = child.tagName.toLowerCase();
            var nodeDiv = document.createElement('div');
            if (tagName === 'description') {
              var str = child.childNodes[0].data;
              str = str.replace('T máxima (°C)', 'máxima');
              str = str.replace('T minima (°C)', 'mínima');
              nodeDiv.innerHTML = str;
              obj[tagName] = nodeDiv.innerHTML;
            } else {
              obj[tagName] = child.innerHTML;
            }
          }

          forecast.push(obj);
        }

        forecast.pop();
        var handler = function () {
          var item = forecast.shift();
          var container = document.getElementById('forecast-container');
          container.innerHTML = "";
          var textNode = document.createTextNode(item.title);
          var spanNode = document.createElement('div');
          spanNode.classList.add('span-title');
          spanNode.appendChild(textNode);
          var divNode = document.createElement('div');
          divNode.innerHTML = item.description;
          container.appendChild(spanNode);
          container.appendChild(divNode);
          forecast.push(item);
        }
        handler();
        var interval = setInterval(handler, 5000);
      }

    })
})
