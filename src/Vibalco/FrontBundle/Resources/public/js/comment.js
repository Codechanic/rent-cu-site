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
            resolve(xhr.responseText)
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
            resolve(xhr.responseText)
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
  //load comments
  var homeStay = document.querySelector('#comment-form');
  var homeStayId = homeStay.dataset.home;
  AjaxClient
    .get(commentUrl, {homeStayId})
    .then(function(response){

    })
    .catch(function (response) {
      console.log(response)
    })

  var sendButton = document.getElementById('send-button');
  sendButton.addEventListener('click', function (ev) {
    var valid = true;
    var mailRegex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var name = document.getElementById('comment-name');
    var email = document.getElementById('comment-email');
    var text = document.getElementById('comment-text');
    //todo validate data
    if(text.value.length < 5) {
      valid = false;
      text.classList.add('has-error');
    }
    if(name.value.length < 5) {
      valid = false;
      name.classList.add('has-error');
    }
    if(!mailRegex.test(email.value)) {
      valid = false;
      email.classList.add('has-error');
    }

    if( valid === true){
          text.classList.remove('has-error');
          email.classList.remove('has-error');
          name.classList.remove('has-error');
      var formData = new FormData();
      formData.append('name', name.value);
      formData.append('email', email.value);
      formData.append('text', text.value);
      formData.append('homeStayId', homeStayId);
      AjaxClient
        .post(commentUrlPost,formData)
        .then(function (response) {
           text.value = '';
           email.value = '';
           name.value = '';
        })
        .catch(function (response) {
        })
    }
  })
}, true)